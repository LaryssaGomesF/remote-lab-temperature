const express = require('express');
const { spawn } = require('child_process');
const path = require('path');
const fs = require('fs');

const app = express();
const PORT = 6009;

// Diretório para armazenar os arquivos HLS
const hlsDirectory = path.join(__dirname, 'hls');
if (!fs.existsSync(hlsDirectory)) {
  fs.mkdirSync(hlsDirectory);
}

// Caminho do stream RTSP (substitua pelo URL da sua câmera)
const rtspUrl = 'rtsp://<USER>:<PASSWORD>@<IP>/cam/realmonitor?channel=1&subtype=0';

// Inicia o FFmpeg para converter RTSP em HLS
const startFFmpeg = () => {
  console.log('Iniciando FFmpeg...');
  const ffmpeg = spawn('ffmpeg', [
    '-i', rtspUrl,           // Entrada RTSP
    '-c:v', 'copy',          // Copia o codec de vídeo
    '-hls_time', '2',        // Duração dos segmentos (em segundos)
    '-hls_list_size', '5',   // Quantos segmentos manter na lista
    '-hls_flags', 'delete_segments', // Remove segmentos antigos
    '-f', 'hls',             // Formato de saída
    path.join(hlsDirectory, 'stream.m3u8') // Arquivo de saída HLS
  ]);

  ffmpeg.stdout.on('data', (data) => console.log(`FFmpeg: ${data}`));
  ffmpeg.stderr.on('data', (data) => console.error(`FFmpeg Error: ${data}`));
  ffmpeg.on('close', (code) => console.log(`FFmpeg finalizado com código: ${code}`));

  return ffmpeg;
};

// Inicia o processo do FFmpeg
const ffmpegProcess = startFFmpeg();

// Serve os arquivos HLS
app.use('/hls', express.static(hlsDirectory));

// Página HTML para exibir o stream
app.get('/', (req, res) => {
  res.send(`
    <!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>RTSP to HLS Stream</title>
      <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
    </head>
    <body>
      <h1>RTSP to HLS Stream</h1>
      <video id="video" controls autoplay width="640" height="360"></video>
      <script>
        const video = document.getElementById('video');
        if (Hls.isSupported()) {
          const hls = new Hls();
          hls.loadSource('/hls/stream.m3u8');
          hls.attachMedia(video);
        } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
          video.src = '/hls/stream.m3u8';
        }
      </script>
    </body>
    </html>
  `);
});

// Inicia o servidor
app.listen(PORT, () => {
  console.log(`Servidor rodando em http://localhost:${PORT}`);
});

// Encerrar o FFmpeg ao finalizar o servidor
process.on('SIGINT', () => {
  console.log('Encerrando FFmpeg...');
  ffmpegProcess.kill('SIGINT');
  process.exit();
});

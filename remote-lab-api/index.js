const express = require("express");

const app = express();

app.use(express.json());
app.use(express.urlencoded({extended: true}));

const handlebars = require('express-handlebars');

app.engine('handlebars', handlebars.engine({ defaultLayout: 'main'}));
app.set('view engine', 'handlebars')

const plantRoutes = require("./plant/plantRoutes");
const controlRoutes = require("./control/controlRoutes");
const dashboardRoutes = require("./dashboard/dashboardRoutes");


//Setting ffmpeg
const { spawn } = require('child_process');
const path = require('path');
const fs = require('fs');

const hlsDirectory = path.join(__dirname, 'hls');
if (!fs.existsSync(hlsDirectory)) {
  fs.mkdirSync(hlsDirectory);
}

const rtspUrl = 'rtsp://admin:'+ process.env.CAMERA_PASSWORD +'@' + process.env.CAMERA_IP + '/cam/realmonitor?channel=1&subtype=0';

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

  const ffmpegProcess = startFFmpeg();

// Serve os arquivos HLS
app.use('/hls', express.static(hlsDirectory));



const db = require('./db');
const tables = require('./tables');

tables.init(db);

app.use('/plant', plantRoutes);
app.use('/control', controlRoutes);
app.use('/dashboard', dashboardRoutes);
app.use('/imagens', express.static(path.join(__dirname, 'imagens')));

app.use((req, res, next) => {
    const error = new Error('Not Found');
    error.status = 404;
    next(error);
})

app.use((error, req, res, next) => {
    res.status(error.status || 500);
    res.json({
        error: {
            message: error.message
        }
    });
});

app.listen(process.env.PORT, function(){
   console.log("Servidor rodando na porta: " + process.env.PORT)
})

process.on('SIGINT', () => {
    console.log('Encerrando FFmpeg...');
    ffmpegProcess.kill('SIGINT');
    process.exit();
  });
  

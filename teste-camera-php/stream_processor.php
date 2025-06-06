<?php

// --- Configurações ---
$rtsp_url = 'rtsp://admin:LabcamBancadaTemp@192.168.1.32:554/cam/realmonitor?channel=1&subtype=0'; // Substitua pela URL do seu stream RTSP
$output_dir = __DIR__ . '/stream_output'; // Diretório para salvar os arquivos HLS (deve ter permissão de escrita pelo servidor web)
$ffmpeg_path = 'ffmpeg'; // Caminho para o executável do FFmpeg. Se não estiver no PATH, use o caminho completo (ex: 'C:\ffmpeg\bin\ffmpeg.exe' ou '/usr/bin/ffmpeg')

// --- Validação e Preparação ---

// Valida a URL RTSP (exemplo básico, pode precisar de validação mais robusta)
if (filter_var($rtsp_url, FILTER_VALIDATE_URL) === FALSE || strpos($rtsp_url, 'rtsp://') !== 0) {
    die('URL RTSP inválida.');
}

// Cria o diretório de saída se não existir
if (!is_dir($output_dir)) {
    if (!mkdir($output_dir, 0755, true)) {
        die('Falha ao criar o diretório de saída. Verifique as permissões.');
    }
}

// Verifica se o diretório tem permissão de escrita
if (!is_writable($output_dir)) {
    die('O diretório de saída não tem permissão de escrita para o servidor web (PHP).');
}

// Nome base para os arquivos de saída
$output_playlist = $output_dir . '/stream.m3u8';
$output_segment_pattern = rtrim($output_dir, '/\\') . '/segment_%03d.ts';


// --- Comando FFmpeg para converter RTSP para HLS ---

// Opções comuns:
// -i '$rtsp_url': Input (URL RTSP). Aspas são importantes se a URL tiver caracteres especiais.
// -fflags nobuffer: Reduz a latência inicial.
// -rtsp_transport tcp: Força o uso de TCP para RTSP (pode ser mais estável que UDP em algumas redes).
// -c:v copy: Copia o codec de vídeo sem re-encodar (mais rápido, menor uso de CPU). Remova se precisar re-encodar.
// -c:a aac: Codifica o áudio para AAC (amplamente compatível).
// -b:a 128k: Bitrate do áudio.
// -hls_time 4: Duração de cada segmento de vídeo em segundos.
// -hls_list_size 5: Número máximo de segmentos na playlist M3U8.
// -hls_flags delete_segments: Remove segmentos antigos quando novos são criados.
// -hls_segment_filename '$output_segment_pattern': Padrão para nomear os arquivos de segmento (.ts).
// '$output_playlist': Caminho para o arquivo de playlist M3U8.


$command = sprintf(
    'start "" /B %s -i %s -fflags nobuffer -rtsp_transport tcp -c:v copy -c:a aac -b:a 128k -hls_time 4 -hls_list_size 5 -hls_flags delete_segments -hls_segment_filename %s %s',
    escapeshellarg($ffmpeg_path),
    escapeshellarg($rtsp_url),
    escapeshellarg($output_segment_pattern),
    escapeshellarg($output_playlist)
);



// --- Execução do Comando (Versão Final para Windows/XAMPP) ---

// Garante caminhos com barras invertidas para Windows
$output_dir_win = str_replace('/', '\\', $output_dir); // Garante barras invertidas
$output_playlist_win = $output_dir_win . '\\stream.m3u8';
$output_segment_pattern_win = $output_dir_win . '\\segment_%03d.ts';

// Comando FFmpeg usando start /B para rodar em background no Windows
// Aspas duplas são importantes para caminhos com espaços (embora não tenha aqui)
$command = sprintf(
    'start "FFmpeg Stream" /B "%s" -i "%s" -fflags nobuffer -rtsp_transport tcp -c:v copy -c:a aac -b:a 128k -hls_time 4 -hls_list_size 5 -hls_flags delete_segments -hls_segment_filename "%s" "%s"
',
    $ffmpeg_path, // Ex: 'C:\ffmpeg\bin\ffmpeg.exe' ou 'ffmpeg' se no PATH
    $rtsp_url,
    $output_segment_pattern_win,
    $output_playlist_win
);

// Verifica se o processo FFmpeg já está rodando (verificação básica para Windows)
// Nota: pgrep não existe no Windows. Usaremos tasklist.
// Isso é menos preciso que pgrep, pode pegar outros ffmpeg se rodando.
$check_process_command = 'tasklist /FI "IMAGENAME eq ffmpeg.exe" /FI "WINDOWTITLE eq FFmpeg Stream*" /NH';
exec($check_process_command, $process_output, $return_var);

// $return_var é 0 se encontrou, 1 se não. Verifica se a saída contém ffmpeg.exe
$process_running = false;
if ($return_var === 0 && !empty($process_output)) {
    foreach ($process_output as $line) {
        if (stripos($line, 'ffmpeg.exe') !== false) {
            $process_running = true;
            echo "Processo FFmpeg para este stream parece já estar em execução.<br>";
            break;
        }
    }
}

if (!$process_running) {
    echo "Iniciando processo FFmpeg em segundo plano...<br>";
    echo "Comando: " . htmlspecialchars($command) . "<br>";

    // Executa o comando FFmpeg em segundo plano usando pclose(popen(...))
    // Esta é uma forma mais recomendada para iniciar processos em background no Windows via PHP
    pclose(popen($command, 'r'));

    // Pequena pausa para dar tempo ao FFmpeg iniciar e criar a playlist
    sleep(8); // Aumentado para 8 segundos

    if (file_exists($output_playlist_win)) {
        echo "Processo FFmpeg iniciado. Playlist HLS deve estar disponível em: " . htmlspecialchars($output_playlist_win) . "<br>";
    } else {
        echo "Falha ao iniciar o processo FFmpeg ou criar a playlist após a espera. Verifique as permissões da pasta '{$output_dir_win}' para o usuário do Apache/PHP e se o comando FFmpeg funciona manualmente.<br>";
    }
}

echo "Verificação concluída.";

?>

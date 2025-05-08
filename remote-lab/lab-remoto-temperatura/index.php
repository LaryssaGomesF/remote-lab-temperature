<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Remove query string da URI
$requestUri = strtok($_SERVER['REQUEST_URI'], '?');
$basePath = '/lab-remoto-temperatura';
$route = '/' . ltrim(str_replace($basePath, '', $requestUri), '/');

// Normaliza sem barra final
$route = rtrim($route, '/');

// Roteamento principal
switch (true) {
    // Rota raiz (index.html)
    case $route === '':
        include __DIR__ . '/public/index.html';
        break;

    // Rotas /plant
    case strpos($route, '/plant') === 0:
        require __DIR__ . '/plantRoutes.php'; // Movido para a raiz
        break;

    // Rotas /control
    case strpos($route, '/control') === 0:
        require __DIR__ . '/controlRoutes.php'; // Movido para a raiz
        break;

    // Arquivos estáticos (CSS, JS, imagens)
    case preg_match('/\.(html|css|js|png|jpg)$/', $route):
        $filePath = __DIR__ . '/public' . $route;
        if (file_exists($filePath)) {
            readfile($filePath);
        } else {
            http_response_code(404);
            echo "Arquivo não encontrado.";
        }
        break;

    // Rota não encontrada
    default:
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(["error" => "Rota não encontrada"]);
        break;
}
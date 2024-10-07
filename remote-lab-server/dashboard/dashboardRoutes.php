<?php
// routes/dashboardRoutes.php

// Roteamento básico para a página de dashboard
$requestMethod = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($uri === '/dashboard' && $requestMethod === 'GET') {
    // Renderiza a página de dashboard
    require_once '../views/dashboard.php';
} else {
    http_response_code(404);
    echo 'Página não encontrada';
}

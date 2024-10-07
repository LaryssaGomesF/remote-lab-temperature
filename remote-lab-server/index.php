<?php
// index.php

if (isset($_GET['url'])) {
    $uri = $_GET['url'];

    switch ($uri) {
        case 'labserver/plant':
            require 'plant/plantRoutes.php';
            break;
        case 'labserver/control':
            require 'control/controlRoutes.php';
            break;
        case 'labserver/dashboard':
            require 'dashboard/dashboardRoutes.php';
            break;
        default:
            http_response_code(404);
            echo json_encode(['error' => 'Not Found']);
            break;
    }
} else {
    // Handle requests that don't match any rules
    http_response_code(404);
    echo json_encode(['error' => 'Not Found']);
}

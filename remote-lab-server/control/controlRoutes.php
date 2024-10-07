<?php
// control/controlRoutes.php

require_once 'ControlModel.php';  // Inclui o modelo

// Inicializa a conexão com o banco de dados
require_once '../db/db.php';
$controlModel = new ControlModel($db);

// Roteamento básico
$requestMethod = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Define as rotas

switch ($requestMethod) {
    case 'GET':
        $data = $controlModel->get();
        if ($data) {
            header('Content-Type: application/json');
            echo json_encode($data);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Erro ao buscar dados.']);
        }
        break;

    case 'POST':
        $parameters = json_decode(file_get_contents('php://input'), true);
        $result = $controlModel->post($parameters);
        if ($result) {
            http_response_code(201);
            echo json_encode(['message' => 'Parâmetros atualizados com sucesso.']);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Erro ao atualizar parâmetros.']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['message' => 'Método não permitido.']);
        break;
}


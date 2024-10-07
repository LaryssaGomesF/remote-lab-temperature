<?php
// plant/plantRoutes.php

require_once 'PlantModel.php';  // Inclui o modelo

// Inicializa a conexão com o banco de dados
require_once '../db/db.php';  
$plantModel = new PlantModel($db);

// Roteamento básico
$requestMethod = $_SERVER['REQUEST_METHOD'];

switch ($requestMethod) {
    case 'GET':
        $data = $plantModel->get();
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
        $result = $plantModel->post($parameters);
        if ($result) {
            http_response_code(201);
            header('Content-Type: application/json');
            echo json_encode($result);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Erro ao inserir dados.']);
        }
        break;

    case 'DELETE':
        $result = $plantModel->delete();
        if ($result) {
            http_response_code(200);
            echo json_encode(['message' => 'Dados deletados com sucesso.']);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Erro ao deletar dados.']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['message' => 'Método não permitido.']);
        break;
}

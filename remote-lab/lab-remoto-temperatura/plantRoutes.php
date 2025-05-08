<?php

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
require_once 'PlantModel.php';

$model = new PlantModel();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        echo json_encode($model->get());
        break;
    case 'POST':
        $input = json_decode(file_get_contents('php://input'), true);
        echo json_encode($model->post($input));
        break;
    case 'DELETE':
        echo json_encode($model->delete());
        break;
    default:
        http_response_code(405);
        echo json_encode(["error" => "Método não permitido"]);
        break;
}

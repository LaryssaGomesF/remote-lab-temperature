<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Resposta para preflight OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once __DIR__ . '/PlantModel.php';

$model = new PlantModel();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        echo json_encode($model->get());
        break;

    case 'POST':
        $contentType = $_SERVER["CONTENT_TYPE"] ?? '';

        if (strpos($contentType, 'application/json') !== false) {
            $input = json_decode(file_get_contents('php://input'), true);
        } elseif (strpos($contentType, 'application/x-www-form-urlencoded') !== false) {
            $input = $_POST;
        } else {
            $input = null;
        }

        if (!is_array($input)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Parâmetros inválidos ou não enviados']);
            break;
        }

        $result = $model->post($input);
        echo json_encode($result);
        break;

    case 'DELETE':
        $result = $model->delete();
        echo json_encode($result);
        break;

    default:
        http_response_code(405);
        echo json_encode(['status' => 'error', 'message' => 'Método não permitido']);
        break;
}

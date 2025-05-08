<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

require_once 'ControlModel.php';

$model = new ControlModel();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        echo json_encode($model->get());
        break;
        
    case 'POST':
        $input = json_decode(file_get_contents('php://input'), true);
        
        // Verifica se é uma requisição para atualizar apenas o setpoint
        if (isset($input['s']) && count($input) === 1) {
            echo json_encode($model->postSetpoint($input));
        } else {
            echo json_encode($model->post($input));
        }
        break;
        
    default:
        http_response_code(405);
        echo json_encode(["error" => "Método não permitido"]);
        break;
}
?>
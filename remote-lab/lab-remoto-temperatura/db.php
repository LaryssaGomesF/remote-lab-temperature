<?php
$host = "localhost";
$user = "root";
$pass = "2602";
$dbname = "remote-lab-db";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(["error" => "Erro ao conectar com o banco de dados: " . $conn->connect_error]));
}
?>

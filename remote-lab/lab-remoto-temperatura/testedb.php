<?php
$pdo = new PDO('mysql:host=localhost;dbname=remote-lab-db', 'root', '2602');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
    $stmt = $pdo->query("SELECT 1");
    echo "Conexão com o banco de dados funcionando!";
} catch (PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
}
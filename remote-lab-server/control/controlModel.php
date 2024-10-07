<?php
// control/ControlModel.php

require_once '../db/db.php';  // Inclui a conexão com o banco de dados

class ControlModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Recupera os parâmetros de controle
    public function get() {
        $sql = "SELECT ki, kd, kp, s, m FROM control";
        try {
            $stmt = $this->db->query($sql);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            echo "Erro ao recuperar parâmetros: " . $e->getMessage();
            return false;
        }
    }

    // Insere ou atualiza os parâmetros de controle
    public function post($parameters) {
        $sql = "
            INSERT INTO control (id, ki, kd, kp, s, m) 
            VALUES (1, :ki, :kd, :kp, :s, :m)
            ON DUPLICATE KEY UPDATE 
                ki = VALUES(ki), 
                kd = VALUES(kd), 
                kp = VALUES(kp), 
                s = VALUES(s), 
                m = VALUES(m)";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':ki', $parameters['ki']);
            $stmt->bindParam(':kd', $parameters['kd']);
            $stmt->bindParam(':kp', $parameters['kp']);
            $stmt->bindParam(':s', $parameters['s']);
            $stmt->bindParam(':m', $parameters['m']);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "Erro ao inserir ou atualizar parâmetros: " . $e->getMessage();
            return false;
        }
    }

   
}

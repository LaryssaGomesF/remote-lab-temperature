<?php
// plant/PlantModel.php

require_once '../db/db.php';  // Inclui a conexão com o banco de dados
require_once '../control/ControlModel.php';  // Inclui o ControlModel

class PlantModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Recupera os dados
    public function get() {
        $sql = "SELECT t, tp, e, s FROM plant_data";
        try {
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erro ao recuperar dados: " . $e->getMessage();
            return false;
        }
    }

    // Deleta os dados
    public function delete() {
        $sql = "DELETE FROM plant_data";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "Erro ao deletar dados: " . $e->getMessage();
            return false;
        }
    }

    // Insere os dados
    public function post($parameters) {
      
        $sql = "
        INSERT INTO plant_data (tp, e, t, s) 
        VALUES (:tp, :e, :t, :s)";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':tp', $parameters['tp']);
            $stmt->bindParam(':e', $parameters['e']);
            $stmt->bindParam(':t', $parameters['t']);
            $stmt->bindParam(':s', $parameters['s']);
            $stmt->execute();

            // Após inserir os dados, recupera o estado de controle
            $controlModel = new ControlModel($this->db);
            return $controlModel->get();
        } catch (PDOException $e) {
            echo "Erro ao inserir dados: " . $e->getMessage();
            return false;
        }
    }
}

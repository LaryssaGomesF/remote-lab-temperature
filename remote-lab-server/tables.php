<?php

class Tables {
    private $conexao;

    public function init($conexao) {
        $this->conexao = $conexao;
        $this->createTablePlantData();
        $this->createTableControlConstants();
    }

    // Criação da tabela plant_data
    private function createTablePlantData() {
        $sql = "
            CREATE TABLE IF NOT EXISTS plant_data(
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            t INT,
            tp DOUBLE,
            e DOUBLE,
            s DOUBLE
            );
        ";

        try {
            $this->conexao->exec($sql);
            echo "Tabela plant_data criada com sucesso.\n";
        } catch (PDOException $e) {
            echo "Erro ao criar tabela plant_data: " . $e->getMessage() . "\n";
        }
    }

    // Criação da tabela control com verificação e inserção de registro inicial
    private function createTableControlConstants() {
        $sql = "
            CREATE TABLE IF NOT EXISTS control(
            id INT NOT NULL PRIMARY KEY,
            ki DOUBLE,
            kd DOUBLE,
            kp DOUBLE,
            s DOUBLE,
            m DOUBLE DEFAULT 0.0
            );
        ";

        $checkIfRecordExistsSql = "SELECT * FROM control WHERE id = 1";
        $insertInitialRecordSql = "
            INSERT INTO control (id, ki, kd, kp, m, s) 
            VALUES (1, 0.0, 0.0, 0.0, 0.0, 0.0)
        ";

        try {
            // Cria a tabela control
            $this->conexao->exec($sql);
            echo "Tabela control criada com sucesso.\n";

            // Verifica se o registro inicial já existe
            $stmt = $this->conexao->query($checkIfRecordExistsSql);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($result) === 0) {
                // Insere o registro inicial
                $this->conexao->exec($insertInitialRecordSql);
                echo "Registro inicial inserido com sucesso.\n";
            } else {
                echo "Registro inicial já existe.\n";
            }

        } catch (PDOException $e) {
            echo "Erro ao criar tabela control ou verificar/ inserir registro inicial: " . $e->getMessage() . "\n";
        }
    }
}

// Exemplo de uso:
// require_once 'db.php';
// $tables = new Tables();
// $tables->init($db);

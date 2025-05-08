<?php

class PlantModel {

    // Método para obter dados da tabela plant_data
    public function get() {
        $sql = 'SELECT t, tp, e, s, a FROM plant_data';
        try {
            // Conexão com o banco de dados
            $pdo = new PDO('mysql:host=localhost;dbname=remote-lab-db', 'root', '2602');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Preparar e executar a consulta
            $stmt = $pdo->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } catch (PDOException $e) {
            echo "Erro ao recuperar dados: " . $e->getMessage();
            return [];
        }
    }

    // Método para deletar todos os dados da tabela plant_data
    public function delete() {
        $sql = 'TRUNCATE TABLE plant_data';
        try {
            // Conexão com o banco de dados
            $pdo = new PDO('mysql:host=localhost;dbname=nome_do_banco', 'usuario', 'senha');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Preparar e executar a consulta
            $pdo->exec($sql);
            return ['status' => 'success'];
        } catch (PDOException $e) {
            echo "Erro ao deletar dados: " . $e->getMessage();
            return ['status' => 'error'];
        }
    }

    // Método para inserir dados na tabela plant_data
    public function post($parameters) {
        $sql = 'INSERT INTO plant_data (tp, e, t, s, a) VALUES (?, ?, ?, ?, ?)';

        try {
            // Conexão com o banco de dados
            $pdo = new PDO('mysql:host=localhost;dbname=nome_do_banco', 'usuario', 'senha');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Preparar a consulta
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$parameters['tp'], $parameters['e'], $parameters['t'], $parameters['s'], $parameters['a']]);

            // Retorna dados atualizados (você pode adicionar outra lógica de controle aqui, como chamar outra função)
            return $this->get(); // Chamando o método get para retornar os dados mais recentes
        } catch (PDOException $e) {
            echo "Erro ao inserir ou atualizar parâmetros: " . $e->getMessage();
            return ['status' => 'error'];
        }
    }
}

?>

<?php
require_once __DIR__ . '/ControlModel.php';

class PlantModel {

    // Método para obter dados da tabela plant_data
    public function get() {
        $sql = 'SELECT t, tp, e, s, a FROM plant_data';
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=remote-lab-db', 'root', '2602');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } catch (PDOException $e) {
            return ["error" => "Erro ao recuperar dados: " . $e->getMessage()];
        }
    }

    // Método para deletar todos os dados da tabela plant_data
    public function delete() {
        $sql = 'TRUNCATE TABLE plant_data';
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=remote-lab-db', 'root', '2602');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $pdo->exec($sql);
            return ['status' => 'success'];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    // Método para inserir dados na tabela plant_data
    public function post($parameters) {
        $sql = 'INSERT INTO plant_data (tp, e, t, s, a) VALUES (?, ?, ?, ?, ?)';

        if (!is_array($parameters)) {
            return ['status' => 'error', 'message' => 'Parâmetros inválidos'];
        }

        $requiredKeys = ['tp', 'e', 't', 's', 'a'];
        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $parameters)) {
                return ['status' => 'error', 'message' => "Parâmetro '$key' ausente"];
            }
        }

        try {
            $pdo = new PDO('mysql:host=localhost;dbname=remote-lab-db', 'root', '2602');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare($sql);
            $stmt->execute([$parameters['tp'], $parameters['e'], $parameters['t'], $parameters['s'], $parameters['a']]);

            $controlModel = new ControlModel();
            return $controlModel->get();

        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => "Erro ao inserir dados: " . $e->getMessage()];
        }
    }
}
?>

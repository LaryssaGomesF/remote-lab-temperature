<?php
class ControlModel {
    // Método para obter dados da tabela control
   public function get() {
    $sql = 'SELECT ki, kd, kp, s, m FROM control';
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=remote-lab-db', 'root', '2602');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return [
                'ki' => (float) $result['ki'],
                'kd' => (float) $result['kd'],
                'kp' => (float) $result['kp'],
                's'  => (float) $result['s'],
                'm'  => (int) $result['m']
            ];
        }

        return [];
    } catch (PDOException $e) {
        error_log("Erro ao recuperar dados de controle: " . $e->getMessage());
        return [];
    }
}


    // Método para atualizar parâmetros de controle
    public function post($params) {
        $sql = 'INSERT INTO control (id, ki, kd, kp, s, m) 
                VALUES (1, :ki, :kd, :kp, :s, :m)
                ON DUPLICATE KEY UPDATE 
                ki = VALUES(ki), kd = VALUES(kd), kp = VALUES(kp), 
                s = VALUES(s), m = VALUES(m)';

        try {
            $pdo = new PDO('mysql:host=localhost;dbname=remote-lab-db', 'root', '2602');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':ki' => $params['ki'] ?? 0,
                ':kd' => $params['kd'] ?? 0,
                ':kp' => $params['kp'] ?? 0,
                ':s'  => $params['s'] ?? 0,
                ':m'  => isset($params['m']) ? ($params['m'] ? 1 : 0) : 0
            ]);


            return ['success' => true];
        } catch (PDOException $e) {
            error_log("Erro ao atualizar parâmetros de controle: " . $e->getMessage());
            throw new Exception("Erro ao atualizar parâmetros: " . $e->getMessage());
        }
    }

    // Método para atualizar apenas o setpoint
    public function postSetpoint($params) {
        $sql = 'INSERT INTO control (id, s) 
                VALUES (1, :s)
                ON DUPLICATE KEY UPDATE s = VALUES(s)';

        try {
            $pdo = new PDO('mysql:host=localhost;dbname=remote-lab-db', 'root', '2602');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':s' => $params['s'] ?? 0
            ]);

            return ['success' => true];
        } catch (PDOException $e) {
            error_log("Erro ao atualizar setpoint: " . $e->getMessage());
            throw new Exception("Erro ao atualizar setpoint: " . $e->getMessage());
        }
    }
}
?>
const db = require('../db')

class ControlModel {

    get() {
        const sql = 'SELECT ki,kd,kp,setpoint,mode FROM control';
        return new Promise((resolve, reject) => {
            db.query(sql, {}, (error, resposta) => {
                if (error) {
                    console.log("Error ao recuperar parametros");
                    reject(error);
                }
               
                resolve(resposta[0] || {});
            });
        });
    }

    post(parameters) {
        // Consulta SQL para inserir ou atualizar valores
        const sql = `
                    INSERT INTO control (id, ki, kd, kp,setpoint, mode) 
                    VALUES (1, ?, ?, ? ,?, ?)
                    ON DUPLICATE KEY UPDATE ki = VALUES(ki), kd = VALUES(kd), kp = VALUES(kp), setpoint = VALUES(setpoint),  mode = VALUES(mode);
                    `;

        return new Promise((resolve, reject) => {
            // Executa a consulta com os parâmetros fornecidos
            db.query(sql, [parameters.ki, parameters.kd, parameters.kp,parameters.setpoint, parameters.mode], (error, resposta) => {
                if (error) {
                    console.error("Erro ao inserir ou atualizar parâmetros:", error);
                    reject(error);
                } else {
                    resolve(resposta);
                }
            });
        });
    }

    postSetpoint(parameters) {
        // Consulta SQL para inserir ou atualizar valores
        const sql = `
                    INSERT INTO control (id, setpoint) 
                    VALUES (1, ?, ?, ? , ?)
                    ON DUPLICATE KEY UPDATE  setpoint = VALUES(setpoint);
                    `;

        return new Promise((resolve, reject) => {
            // Executa a consulta com os parâmetros fornecidos
            db.query(sql, [parameters.setpoint], (error, resposta) => {
                if (error) {
                    console.error("Erro ao inserir setpoint", error);
                    reject(error);
                } else {
                    resolve(resposta);
                }
            });
        });
    }
}

module.exports = new ControlModel();
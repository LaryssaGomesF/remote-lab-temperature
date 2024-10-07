const db = require('../db')

class ControlModel {

    get() {
        const sql = 'SELECT ki,kd,kp,s,m FROM control';
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
                    INSERT INTO control (id, ki, kd, kp,s, m) 
                    VALUES (1, ?, ?, ? ,?, ?)
                    ON DUPLICATE KEY UPDATE ki = VALUES(ki), kd = VALUES(kd), kp = VALUES(kp), s = VALUES(s),  m = VALUES(m);
                    `;

        return new Promise((resolve, reject) => {
            // Executa a consulta com os parâmetros fornecidos
            const ki = parameters.ki || 0;
            const kd = parameters.kd || 0;
            const kp = parameters.kp || 0;
            const s = parameters.s || 0;
            const m = parameters.m || 0;
            db.query(sql, [ki, kd, kp,s, m], (error, resposta) => {
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
                    INSERT INTO control (id, s) 
                    VALUES (1, ?, ?, ? , ?)
                    ON DUPLICATE KEY UPDATE  s = VALUES(s);
                    `;

        return new Promise((resolve, reject) => {
            // Executa a consulta com os parâmetros fornecidos
            db.query(sql, [parameters.s], (error, resposta) => {
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
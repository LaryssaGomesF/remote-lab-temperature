const db = require('../db')

class ControlModel {

    get() {
        const sql = 'SELECT * FROM control';
        return new Promise((resolve, reject) => {
            db.query(sql, {}, (error, resposta) => {
                if (error) {
                    console.log("Error ao recuperar parametros");
                    reject(error);
                }
                console.log("Parametros recuperados com sucesso");
                resolve(resposta[0] || {});
            });
        });
    }

    post(parameters) {
        // Consulta SQL para inserir ou atualizar valores
        const sql = `
                    INSERT INTO control (id, KI, KD, KP, MODE) 
                    VALUES (1, ?, ?, ? , ?)
                    ON DUPLICATE KEY UPDATE KI = VALUES(KI), KD = VALUES(KD), KP = VALUES(KP), MODE = VALUES(MODE);
                    `;

        return new Promise((resolve, reject) => {
            // Executa a consulta com os parâmetros fornecidos
            db.query(sql, [parameters.KI, parameters.KD, parameters.KP, parameters.MODE], (error, resposta) => {
                if (error) {
                    console.error("Erro ao inserir ou atualizar parâmetros:", error);
                    reject(error);
                } else {
                    console.log("Parâmetros inseridos ou atualizados com sucesso");
                    resolve(resposta);
                }
            });
        });
    }
}

module.exports = new ControlModel();
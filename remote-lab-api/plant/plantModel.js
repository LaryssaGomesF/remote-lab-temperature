const db = require('../db')

const control = require('../control/controlModel')

class PlantModel {

    get() {
        const sql = 'SELECT time, temperature, erro FROM plant_data';
        return new Promise((resolve, reject) => {
            db.query(sql, {}, (error, resposta) => {
                if (error) {
                    console.log("Error ao recuperar dados");
                    reject(error);
                }
                resolve(resposta);
            });
        });
    }

    delete() {
        const sql = 'DELETE FROM plant_data';
        return new Promise((resolve, reject) => {
            db.query(sql, {}, (error, resposta) => {
                if (error) {
                    console.log("Error ao deletar dados");
                    console.log(error);
                    reject(error);
                }
                resolve(resposta);
            });
        });
    }

    post(parameters) {
        // Consulta SQL para inserir ou atualizar valores
        const sql = `
                    INSERT INTO plant_data (temperature, erro, time) 
                    VALUES ( ?, ?, ?)
                    `;

        return new Promise((resolve, reject) => {
            // Executa a consulta com os parâmetros fornecidos
            db.query(sql, [parameters.temperature, parameters.erro, parameters.time], (error, resposta) => {
                if (error) {
                    console.error("Erro ao inserir ou atualizar parâmetros:", error);
                    reject(error);
                } else {
                    control.get().then(data => {

                        resolve(data);
                    })
                    .catch(err => {
                        console.error("Erro ao recuperar dados após a inserção:", err);
                        reject(err);
                    });
                }
            });
        });
    }
}

module.exports = new PlantModel();
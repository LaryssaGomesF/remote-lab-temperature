class Tables {
    init(conexao) {
        this.conexao = conexao;
        this.createTablePlantData();
        this.createTableControlConstants();
    }


    createTablePlantData() {
        const sql = `
            CREATE TABLE IF NOT EXISTS plant_data(
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            time INT,
            temperature INT,
            erro INT
            );
        `;
        this.conexao.query(sql, (error) => {
            if (error) {
                console.log("Erro create table plant_data");
                console.log(error);
            }
        })
    }

    createTableControlConstants() {
        const sql = `
            CREATE TABLE IF NOT EXISTS control(
            id INT NOT NULL PRIMARY KEY,
            KI INT DEFAULT 1,
            KD INT DEFAULT 1,
            KP INT DEFAULT 1,
            MODE BOOLEAN DEFAULT FALSE 
            );
        `;

        const insertInitialRecordSql = `
        INSERT INTO control (id,KI, KD, KP, MODE) VALUES (1, 1, 1, 1, FALSE)
        ON DUPLICATE KEY UPDATE KI = VALUES(KI), KD = VALUES(KD), KP = VALUES(KP),  id = VALUES(id), MODE = VALUES(MODE);
    `;

        this.conexao.query(sql, (error) => {
            if (error) {
                console.log("Erro create table control");
                console.log(error);
            } else {
                this.conexao.query(insertInitialRecordSql, (error) => {
                    if (error) {
                        console.error('Erro ao inserir registro inicial:', error);
                    } else {
                        console.log('Tabela criada e registro inicial inserido.');
                    }
                });
            }
        })
    }


}

module.exports = new Tables();
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
            t INT,
            tp DOUBLE,
            e DOUBLE,
            s DOUBLE,
            a DOUBLE
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
          ki DOUBLE NOT NULL,
          kd DOUBLE NOT NULL,
          kp DOUBLE NOT NULL,
          s DOUBLE NOT NULL,
          m DOUBLE DEFAULT 0.0
         );
        `;
      
        const checkIfRecordExistsSql = 'SELECT * FROM control WHERE id = 1';
        const insertInitialRecordSql = `
          INSERT INTO control (id, ki, kd, kp, m, s) VALUES (1, 0.0, 0.0, 0.0, 0.0, 0.0)
        `;
      
        this.conexao.query(sql, (error) => {
          if (error) {
            console.log("Erro create table control");
            console.log(error);
          } else {
            this.conexao.query(checkIfRecordExistsSql, (error, results) => {
              if (error) {
                console.error('Erro ao verificar registro inicial:', error);
              } else {
                if (results.length === 0) {
                  this.conexao.query(insertInitialRecordSql, (error) => {
                    if (error) {
                      console.error('Erro ao inserir registro inicial:', error);
                    } else {
                      console.log('Tabela criada e registro inicial inserido.');
                    }
                  });
                } else {
                  console.log('Registro inicial já existe.');
                }
              }
            });
          }
        });
      }


}

module.exports = new Tables();
const db = require("mysql2");

const conexao = db.createConnection({
    host: 'localhost', //IP address 
    user: 'root',
    password: '2602',
    database: 'remote-lab-db' // criar banco de dados no servidor ou depois mudar para automático
})

module.exports = conexao;

const db = require("mysql2");

const conexao = db.createConnection({
    host: 'localhost', //IP address 
    user: 'root',
    password: 'j159j951',
    database: 'remotelabdb' // criar banco de dados no servidor ou depois mudar para automático
})

module.exports = conexao;

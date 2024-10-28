const db = require("mysql2");
require('dotenv').config();

const conexao = db.createConnection({
    host: process.env.host, //IP address 
    user: process.env.user,
    password:  process.env.password,
    database: process.env.database // criar banco de dados no servidor ou depois mudar para automático
})

module.exports = conexao;

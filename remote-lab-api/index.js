const express = require("express");

const app = express();

app.use(express.json());
app.use(express.urlencoded({extended: true}));

const handlebars = require('express-handlebars');

app.engine('handlebars', handlebars.engine({ defaultLayout: 'main'}));
app.set('view engine', 'handlebars')

const plantRoutes = require("./plant/plantRoutes");
const controlRoutes = require("./control/controlRoutes");
const dashboardRoutes = require("./dashboard/dashboardRoutes");
const path = require('path');



const db = require('./db');
const tables = require('./tables');

tables.init(db);

app.use('/plant', plantRoutes);
app.use('/control', controlRoutes);
app.use('/dashboard', dashboardRoutes);
app.use('/imagens', express.static(path.join(__dirname, 'imagens')));

app.use((req, res, next) => {
    const error = new Error('Not Found');
    error.status = 404;
    next(error);
})

app.use((error, req, res, next) => {
    res.status(error.status || 500);
    res.json({
        error: {
            message: error.message
        }
    });
});

app.listen(6008, function(){
    console.log("Servidor rodando na url http://localhost:5000");
})


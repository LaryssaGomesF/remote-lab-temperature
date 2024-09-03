const express = require('express');
const router = express.Router();

const plantModel = require("./plantModel");

router.get('/', (req, res, next) => {
    const result = plantModel.get();
    result.then(registros => res.status(200).json(registros)).catch(error => res.status(400).json(error.message));

});

router.post('/', (req, res, next) => {
    const parameters = req.body;
    const result = plantModel.post(parameters);
    console.log("parameters");
    result.then( registered => res.status(201).json(registered)).catch(error => res.status(400).json(error.message));

});

router.delete('/', (req, res, next) => {
    const result = plantModel.delete();
    result.then( registered => res.status(201).json(registered)).catch(error => res.status(400).json(error.message));
});


module.exports = router;
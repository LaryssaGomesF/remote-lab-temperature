const express = require('express');
const router = express.Router();

const controlModel = require("./controlModel");

router.get('/', (req, res, next) => {
    const result = controlModel.get();
    result.then(registros => res.status(200).json(registros)).catch(error => res.status(400).json(error.message));

    // res.send(result);
});

router.post('/', (req, res, next) => {
    const parameters = req.body;
    const result = controlModel.post(parameters);
    console.log(parameters);
    result.then( registered => res.status(201).json(registered)).catch(error => res.status(400).json(error.message));
});


module.exports = router;
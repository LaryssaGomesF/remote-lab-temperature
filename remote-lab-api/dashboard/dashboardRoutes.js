const express = require('express');
const router = express.Router();
require('dotenv').config();


router.get('/', (req, res, next) => {
     res.render('dashboard', {
          apiUrl: process.env.API_URL, // ou qualquer outra variável que queira passar
      });
});




module.exports = router;
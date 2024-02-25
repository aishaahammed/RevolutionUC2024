const express = require("express");
const path = require('path');

//This is needed for opening php files
var socket = require('socket.io');
var phpExpress = require('php-express')({
    binPath: 'php'
});

const app = express();
const port = process.env.PORT || 8080;


server = app.listen(port, function () {
  console.log(`Example app listening on port ${port}!`);
});

app.engine('php', phpExpress.engine);
app.set('views', '/home/mason/projects/RevolutionUC2024/');

app.all(/.+\.php$/, phpExpress.router);

//Static files
app.use(express.static('public'));
//Socket setup
var io = socket(server);



///ALL THE GET GUYS
// GET method route
app.get('/', (req, res) => {
  res.sendFile(path.join(__dirname, '/entryPage.html'));
})

// GET method route
app.get('/appFrontPage.html', (req, res) => {
res.sendFile(path.join(__dirname, '/appFrontPage.html'));
})

// GET method route
app.get('/entryPage.html', (req, res) => {
  res.sendFile(path.join(__dirname, '/entryPage.html'));
})

// GET method route
app.get('/login.html', (req, res) => {
  res.sendFile(path.join(__dirname, '/login.html'));
})

// GET method route
app.get('/signUp.php', (req, res) => {
  res.sendFile(path.join(__dirname, '/signUp.php'));
})

// GET method route
app.get('/GuestPage.html', (req, res) => {
  res.sendFile(path.join(__dirname, '/GuestPage.html'));
})

// GET method route
app.get('/User_Profile.html', (req, res) => {
  res.sendFile(path.join(__dirname, '/User_Profile.html'));
})

// GET method route
app.get('/tryClothesPage.html', (req, res) => {
  res.sendFile(path.join(__dirname, '/tryClothesPage.html'));
})




const path = require('path');
var express = require('express');
var socket = require('socket.io');
var phpExpress = require('php-express')({
    binPath: 'php'
});

var app = express();

//App setup
var server = app.listen(4000, function() {
    console.log('listening on requests on port 4000');
});


app.engine('php', phpExpress.engine);

app.all(/.+\.php$/, phpExpress.router);
+app.set('views', '/home/mason/projects/RevolutionUC2024/testMysql');
//Static files
app.use(express.static('public'));


//Socket setup
var io = socket(server);

app.get('/', (req, res) => {
  res.sendFile(path.join(__dirname, '/index.html'));
})

app.get('/register.php', (req, res) => {
  res.sendFile(path.join(__dirname, '/register.php'));
})

// GET method route
app.get('/login.php', (req, res) => {
res.sendFile(path.join(__dirname, '/login.php'));
})

// GET method route
app.get('/logout.php', (req, res) => {
  res.sendFile(path.join(__dirname, '/logout.php'));
})

// GET method route
app.get('/reset-password.php', (req, res) => {
  res.sendFile(path.join(__dirname, '/reset-password.php'));
})

// GET method route
app.get('/welcome.php', (req, res) => {
  res.sendFile(path.join(__dirname, '/welcome.php'));
})

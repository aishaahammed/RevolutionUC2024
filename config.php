<?php
$servername = "localhost";
$database = "RevolutionUC";
$username = "root";
$password = "nix4*4KcYK*8qqP";
// Create connection
$db = mysqli_connect($servername, $username, $password, $database);
// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

mysqli_close($db);
?>
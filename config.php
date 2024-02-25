<?php
$servername = "localhost";
$database = "RevolutionUC";
$username = "root";
$password = "nix4*4KcYK*8qqP";
// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

mysqli_close($conn);
?>
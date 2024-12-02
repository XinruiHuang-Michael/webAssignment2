<?php
$servername = "localhost";
$username = "root";
$password = ""; // default password is null
$dbname = "online_ordering"; // database name

// create database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// check if connect successfully
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

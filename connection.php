<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "3379TQEMD9L";

/* Creating a new connection to the database. */
$conn = new mysqli($servername, $username, $password);
/* Setting the character set to utf8. */
$conn->query("SET NAMES utf8");
// $conn->query("SET CHARACTER SET utf8mb4;");

/* Checking if the connection is successful or not. */
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
//echo "Connected successfully";
?>

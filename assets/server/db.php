<?php
$servername = "sql306.infinityfree.com";
$username = "if0_39113609";       // default for XAMPP
$password = "ecaPassword";           // default for XAMPP
$dbname = "if0_39113609_XXX "; // change this to your actual DB name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>



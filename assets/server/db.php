<?php
$servername = "sql306.infinityfree.com ";
$username = "if0_39113609";       
$password = "ecaPassword";           
$dbname = "if0_39113609_eca "; 

// Create connection
$conn = new mysqli('sql306.infinityfree.com', 'if0_39113609', 'ecaPassword', 'if0_39113609_eca');


// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>









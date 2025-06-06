<?php
session_start();
include('assets/server/db.php'); 

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $number = trim($_POST['number']);
    $password = $_POST['password'];
    
    
    if (empty($username) || empty($email) || empty($number) || empty($password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters.";
    } else {
        
        $check = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $check->bind_param("ss", $username, $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "Username or email already exists.";
        } else {
            
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, email, number, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $email,$number, $hashed_password);
            
            if ($stmt->execute()) 
            {
            $_SESSION['user_id'] = $conn->insert_id;
            $_SESSION['username'] = $username;
            header("Location: index.php");
            exit;
            }
            else {
                $error = "Registration failed. Try again.";
            }
        }

        $check->close();
    }

    if (!empty($error)) {
        echo "<p style='color:red;'>$error</p>";
    } elseif (!empty($success)) {
        echo "<p style='color:green;'>$success</p>";
    }
}
?>
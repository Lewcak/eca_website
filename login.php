<?php
session_start();
include('assets/server/db.php'); // Make sure this sets up a MySQLi $conn object

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error = 'Both fields are required.';
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header("Location: index.php");
                exit();
            } else {
                $error = 'Invalid password.';
            }
        } else {
            $error = 'Email not found.';
        }
        
        $stmt->close();
    }
}
?>



<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | HomeOfTheBoards</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
   
</head>

<body>
    <!-- Navigation Bar (Same as your other pages) -->
    <nav class="navbar navbar-expand-lg custom-navbar py-3 fixed-top">
        <div class="container-fluid">
            
            <a href="index.php">
                <img src="assets/imgs/logo.jpg" alt="Logo" class="navbar-logo" style="height: 70px; width: auto;">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse nav-buttons" id="navbarNav">
                <ul class="navbar-nav nav-buttons ms-auto">

                    <li class="nav-item">
                        <a class="nav-link" href="shop.php">Shop</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="support.php">Support</a>
                    </li>

                    

                    <?php if (isset($_SESSION['user_id'])): ?>
                        
                        <li class="nav-item">
                            <a class="nav-link" href="profile.php">
                                <i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['username']) ?>
                            </a>
                        </li>
                        
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="login.php">
                                    <i class="fas fa-user"></i> Login
                                </a>
                            </li>
                        <?php endif; 
                    ?>
                    
                </ul>
            </div>
        </div>

    </nav>

    <!-- Main Login Content -->
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <img src="assets/imgs/logo.jpg" alt="HomeOfTheBoards Logo">
                <h2>Member Login</h2>
                <p class="text-muted">Sign in to your account</p>
            </div>
            
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">

                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                </div>
                
                
                
                <button type="submit" class="btn btn-primary btn-login">Login</button>
                
                <div class="login-links">
                    
                    <p class="text-muted">Don't have an account? <a href="registration.php">Register here</a></p>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="mt-5 py-5">
        <div class="row container">
            
            <div class="col-lg-9 col-md-12 ps-lg-5">
                <div class="row justify-content-start">
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <h4>Phone</h4>
                        <p>123 456 7890</p>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <h4>Address</h4>
                        <p>Somewhere in Cape Town</p>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <h4>Email</h4>
                        <p>HomeOfTheBoards@hotmail.com</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
        crossorigin="anonymous">
    </script>
</body>
</html>
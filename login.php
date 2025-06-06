<?php
session_start();
include('assets/server/db.php'); 

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error = 'Both fields are required.';
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['password'])) {
               
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                
                $_SESSION['role'] = strtolower($user['role']); 
                
               
                $_SESSION['admin'] = intval($user['admin']);



                
                if ($_SESSION['admin'] === 1) {
                    header("Location: admin.php"); 
                } else {
                    header("Location: index.php");
                }
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
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login | HomeOfTheBoards</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="assets/css/style.css" />
</head>

<body>
    <nav class="navbar navbar-expand-lg custom-navbar py-3 fixed-top">
        <div class="container-fluid">
            <a href="index.php">
                <img src="assets/imgs/logo.jpg" alt="Logo" class="navbar-logo" style="height: 70px; width: auto;" />
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
                    <?php if (isset($_SESSION['user_id'])) : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="profile.php">
                                <i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['username']) ?>
                            </a>
                        </li>
                    <?php else : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">
                                <i class="fas fa-user"></i> Login
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="login-container" style="margin-top:120px;">
        <div class="login-card mx-auto" style="max-width: 400px;">
            <div class="login-header text-center mb-4">
                <img src="assets/imgs/logo.jpg" alt="HomeOfTheBoards Logo" class="mb-3" />
                <h2>Member Login</h2>
                <p class="text-muted">Sign in to your account</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" novalidate>
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required
                        value="<?= isset($email) ? htmlspecialchars($email) : '' ?>" />
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required />
                </div>

                <button type="submit" class="btn btn-primary w-100">Login</button>

                <div class="login-links mt-3 text-center">
                    <p class="text-muted">Don't have an account? <a href="registration.php">Register here</a></p>
                </div>
            </form>
        </div>
    </div>

    <footer class="mt-3 py-3">
        <div class="row container mx-auto">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

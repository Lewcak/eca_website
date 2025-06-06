<?php
session_start();
include('assets/server/db.php');

if (!isset($_GET['seller_id']) || !is_numeric($_GET['seller_id'])) {
    header("Location: index.php");
    exit();
}

$sellerId = intval($_GET['seller_id']);


$stmt = $conn->prepare("SELECT username, email, number FROM users WHERE id = ?");
$stmt->bind_param("i", $sellerId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
   
    echo "Seller not found.";
    exit();
}

$seller = $result->fetch_assoc();

$stmt->close();
?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HomeOfTheBoards</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div class="page_wrapper">

    <!-- navigation bar -->
    
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

    <div class="container mt-5">
        <h2>Contact Seller</h2>
        <div class="card p-4" style="max-width: 500px;">
            <h4><?= htmlspecialchars($seller['username']) ?></h4>
            <p><strong>Email:</strong> <a href="mailto:<?= htmlspecialchars($seller['email']) ?>"><?= htmlspecialchars($seller['email']) ?></a></p>
            <p><strong>Phone Number:</strong> <?= htmlspecialchars($seller['number'] ?? 'Not available') ?></p>
            <a href="javascript:history.back()" class="btn btn-secondary mt-3">Go Back</a>
        </div>
    </div>
    </div>
    <footer class="mt-3 py-3">
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

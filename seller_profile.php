<?php
session_start();
require 'assets/server/db.php';

// Check if seller ID is in the query string
if (!isset($_GET['id'])) {
    die("Seller ID not specified.");
}

$sellerId = (int)$_GET['id'];

// Get seller's username (public info only)
$stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
$stmt->bind_param("i", $sellerId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Seller not found.");
}

$seller = $result->fetch_assoc();

// Get seller's listings
$listings = [];
$stmt = $conn->prepare("SELECT id, name, price, image FROM products WHERE seller_id = ?");
$stmt->bind_param("i", $sellerId);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $listings[] = $row;
}
$stmt->close();
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <<title><?= htmlspecialchars($seller['username']) ?>'s Profile | HomeOfTheBoards</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="assets/css/style.css">
</head>





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

<div class="container mt-5 pt-5">
    <div class="profile-header mb-4 d-flex align-items-center">
        <img src="assets/imgs/default-avatar.jpg" alt="" class="profile-avatar me-4">
        <div>
            <h2 class="mb-1"><?= htmlspecialchars($seller['username']) ?></h2>
            <p class="text-muted">Seller on HomeOfTheBoards</p>
        </div>
    </div>

    <!-- Seller Listings -->
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0"><i class="fas fa-chess-board me-2"></i> Listings by <?= htmlspecialchars($seller['username']) ?></h5>
        </div>
        <div class="card-body">
            <div class="row">
                <?php if (empty($listings)): ?>
                    <p class="text-muted">This seller hasn't listed any products yet.</p>
                <?php else: ?>
                    <?php foreach ($listings as $product): ?>
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <img src="assets/imgs/<?= htmlspecialchars($product['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
                                <div class="card-body">
                                    <h6 class="card-title"><?= htmlspecialchars($product['name']) ?></h6>
                                    <p class="card-text">R<?= number_format($product['price'], 2) ?></p>
                                    <a href="product.php?id=<?= $product['id'] ?>" class="btn btn-sm btn-outline-primary">View Product</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<footer class="mt-5 py-3">
    <div class="container text-center">
        <p>&copy; <?= date('Y') ?> HomeOfTheBoards</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

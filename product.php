<?php
session_start();
require 'assets/server/db.php'; 

if (!isset($_GET['id'])) {
    die('Product ID not specified.');
}

$productId = (int)$_GET['id']; 

$stmt = $conn->prepare("
    SELECT products.*, users.username AS seller_name 
    FROM products 
    JOIN users ON products.seller_id = users.id 
    WHERE products.id = ?
");

$stmt->bind_param("i", $productId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die('Product not found.');
}

$product = $result->fetch_assoc();
?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Product Details | HomeOfTheBoards</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
<div class="page_wrapper">
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


    <!-- Single Product Section -->
<section id="product-details" class="py-5" style="margin-top: 100px;">
    <div class="container">
        <div class="row">

            <!-- Product Image -->
            <div class="col-lg-6 mb-4">
                <div class="product-gallery">
                    <div class="main-image-container mb-3">
                        <img src="assets/imgs/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="img-fluid main-image rounded shadow" id="mainImage">

                    </div>
                </div>
            </div>

            <!-- Product Details -->
            <div class="col-lg-6">
                <h2 class="mb-3"><?php echo htmlspecialchars($product['name']); ?></h2>
                
                <h3 class="text-danger mb-3">R<?php echo number_format($product['price'], 2); ?></h3>
                
                <p class="text-muted mb-4">Condition: <?php echo htmlspecialchars($product['condition']); ?></p>

                <p class="text-muted mb-4">
                    Seller: 
                    <a href="seller_profile.php?id=<?= $product['seller_id'] ?>">
                        <?= htmlspecialchars($product['seller_name']) ?>
                    </a>
                </p>

                

                

                <div class="d-flex align-items-center mb-4">
                    <a href="contact_seller.php?seller_id=<?= $product['seller_id'] ?>" class="btn btn-danger ms-3">
                        <i class="fa fa-envelope me-2"></i>Contact Seller
                     </a>
                </div>

                
            </div>
        </div>

        <!-- Product Tabs -->
        <div class="row mt-5">
            <div class="col-12">
                <ul class="nav nav-tabs" id="productTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab">Description</button>
                    </li>
                    
                </ul>
                <div class="tab-content p-3 border border-top-0 rounded-bottom" id="productTabsContent">
                    <div class="tab-pane fade show active" id="description" role="tabpanel">
                        <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</section>
</div>
<footer class="mt-3 py-3">
    <div class="row container">
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
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

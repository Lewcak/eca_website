<?php
session_start();
include('assets/server/db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];
$user = ['username' => 'Guest', 'email' => 'Not available']; 

$listings = [];

$stmt = $conn->prepare("SELECT id, name, price, image, 'condition' FROM products WHERE seller_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $listings[] = $row;
}

$stmt->close();



$stmt = $conn->prepare("SELECT username, email, number FROM users WHERE id = ?");
if ($stmt) {
    $stmt->bind_param("i", $userId);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        
       
        
        if (!isset($_SESSION['username']) && isset($user['username'])) {
            $_SESSION['username'] = $user['username'];
        }
    } else {
        error_log("Query execution failed: " . $stmt->error);
    }
    $stmt->close();
} else {
    error_log("Prepare failed: " . $conn->error);
}



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | HomeOfTheBoards</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
   
    
</head>
<body>
    <div class="page_wrapper">
    <!-- navbar -->
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
    
    <div class="profile-container">
        <!-- Profile Header -->
        <div class="profile-header mb-4">
            <div class="d-flex align-items-center">
                <img src="assets/imgs/default_profile_pic.jpg" alt="" class="profile-avatar me-4">
                <div>
                    <h2 class="mb-1"><?= htmlspecialchars($_SESSION['username'] ?? 'Guest') ?></h2>
                    
                   
                </div>
            </div>
            
        </div>

        <div class="row">
            <!-- Sidebar Navigation -->
            <div class="col-md-3">
                <div class="profile-nav mb-4">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="#"><i class="fas fa-user me-2"></i> My Profile</a>
                        </li>

                        <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] == 1): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="admin.php"><i class="fas fa-cogs me-2"></i> Admin Panel</a>
                            </li>
                        <?php endif; ?>

                        <li class="nav-item">
                            <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <!-- Personal Info Card -->
                <div class="card profile-card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i> Personal Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                
                                
                                <h5 class="mb-1"> Number: <?= htmlspecialchars($user['number'] ?? 'Not available') ?></h5>
                                
                            </div>
                            <div class="col-md-6">
                               <h5 class="mb-1"> Email: <?= htmlspecialchars($user['email'] ?? 'Not available') ?></h5>
                            </div>
                        </div>
                    </div>
                </div>

                

                <!-- Current Listings -->
                <div class="card profile-card">                    
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-chess-board me-2"></i> My Listings</h5>
                    </div>


                    <div class="card-body">
                        <div class="row">
                            <?php if (empty($listings)): ?>
                                <div class="col-12">
                                    <p class="text-muted">You haven't created any products yet.</p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($listings as $product): ?>
                                    <div class="col-md-4 mb-3">
                                        <div class="card h-100">
                                            <img src="assets/imgs/<?= htmlspecialchars($product['image'] ?? 'default_listing.jpg') ?>" 
                                                class="card-img-top" 
                                                alt="<?= htmlspecialchars($product['name']) ?>">
                                            <div class="card-body">
                                                <h6 class="card-title"><?= htmlspecialchars($product['name']) ?></h6>
                                                <p class="card-text">R<?= number_format($product['price'], 2) ?></p>
                                                
                                                <a href="product.php?id=<?= $product['id'] ?>" class="btn btn-sm btn-outline-primary">View</a>
                                                <form action="delete_listing.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this listing?');">
                                                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger mt-2">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                                    
                            

                    </div>
                    
                    <a href="create_listing.php" class="btn btn-primary mt-3">
                    <i class="fas fa-plus me-2"></i>Add New Listing</a>
                </div>

            </div>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    
    
</body>
</html>
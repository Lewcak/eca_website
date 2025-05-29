<?php
session_start();
require 'assets/server/db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$errors = [];
$success = false;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $price = floatval($_POST['price']);
    $description = trim($_POST['description']);
    $condition = $_POST['condition'];
    $imageName = null;

    // Validate inputs
    if (empty($name)) $errors[] = "Product name is required.";
    if ($price <= 0) $errors[] = "Price must be a positive number.";
    if (empty($description)) $errors[] = "Description is required.";
    if (empty($condition)) $errors[] = "Condition is required.";

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'assets/imgs/';
        $imageName = basename($_FILES['image']['name']);
        $targetPath = $uploadDir . $imageName;

        // Move uploaded file
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $errors[] = "Failed to upload image.";
        }
    } else {
        $errors[] = "Product image is required.";
    }

    // Insert into database if valid
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO products (name, price, image, description, `condition`, seller_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sdsssi", $name, $price, $imageName, $description, $condition, $_SESSION['user_id']);
        $stmt->execute();
        $success = true;
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HomeOfTheBoards</title>
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

<div class="container py-5" style="max-width: 700px;">

    <h2 class="mb-4">Create New Listing</h2>

    <?php if ($success): ?>
        <div class="alert alert-success">Product listed successfully!</div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Product Name</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Price (R)</label>
            <input type="number" name="price" step="0.01" class="form-control" value="<?= htmlspecialchars($_POST['price'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Condition</label>
            <select name="condition" class="form-select">
                <option value="">Select condition</option>
                <option value="New" <?= ($_POST['condition'] ?? '') === 'New' ? 'selected' : '' ?>>New</option>
                <option value="Like New" <?= ($_POST['condition'] ?? '') === 'Like New' ? 'selected' : '' ?>>Like New</option>
                <option value="Used" <?= ($_POST['condition'] ?? '') === 'Used' ? 'selected' : '' ?>>Used</option>
                <option value="Worn" <?= ($_POST['condition'] ?? '') === 'Worn' ? 'selected' : '' ?>>Worn</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Product Image</label>
            <input type="file" name="image" accept="image/*" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" rows="4" class="form-control"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Create Listing</button>
        <a href="profile.php" class="btn btn-secondary ms-2">Cancel</a>
    </form>
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

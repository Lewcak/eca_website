<?php
session_start();

if (empty($_SESSION['user_id']) || empty($_SESSION['admin']) || $_SESSION['admin'] != 1) {
    header('Location: login.php');
    exit();
}


include('assets/server/db.php');



$userCountResult = $conn->query("SELECT COUNT(*) AS count FROM users");
$userCount = $userCountResult->fetch_assoc()['count'] ?? 0;


$productCountResult = $conn->query("SELECT COUNT(*) AS count FROM products");
$productCount = $productCountResult->fetch_assoc()['count'] ?? 0;


$users = [];
$userResult = $conn->query("SELECT id, username, email, role FROM users ORDER BY id DESC LIMIT 20");
if ($userResult) {
    while ($row = $userResult->fetch_assoc()) {
        $users[] = $row;
    }
}


$products = [];
$productResult = $conn->query("SELECT id, name, price FROM products ORDER BY id DESC LIMIT 20");
if ($productResult) {
    while ($row = $productResult->fetch_assoc()) {
        $products[] = $row;
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


    <div class="admin">
        <h1>Dashboard</h1>
        <p>Welcome, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>!</p>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card p-3">
                    <h5>Total Users</h5>
                    <p class="fs-3"><?= $userCount ?></p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-3">
                    <h5>Total Products</h5>
                    <p class="fs-3"><?= $productCount ?></p>
                </div>
            </div>
        </div>

        <h3>All Users</h3>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?= $u['id'] ?></td>
                        <td><?= htmlspecialchars($u['username']) ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td><?= htmlspecialchars($u['role']) ?></td>
                        <td>
                            
                            <form method="POST" action="delete_profile.php" style="display:inline;" onsubmit="return confirm('Delete this user?');">
                                <input type="hidden" name="target_user_id" value="<?= $u['id'] ?>">
                                <input type="hidden" name="admin_delete" value="1">
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>

                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if(empty($users)): ?>
                    <tr><td colspan="5" class="text-center">No users found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h3>All Products</h3>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price (R)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $p): ?>
                    <tr>
                        <td><?= $p['id'] ?></td>
                        <td><?= htmlspecialchars($p['name']) ?></td>
                        <td><?= number_format($p['price'], 2) ?></td>
                        <td>
                            
                            <a href="delete_listing.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this product?');">Delete</a>

                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if(empty($products)): ?>
                    <tr><td colspan="4" class="text-center">No products found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

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

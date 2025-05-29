<?php
session_start();


include('assets/server/db.php');


$stmt = $conn->prepare("SELECT * FROM products WHERE seller_id = ?");
$seller_id = 1;
$stmt->bind_param("i", $seller_id);
$stmt->execute();
$products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
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
    <!--Home-->
    <section id="home">
        <div class="">
            <h5>Home Of The Boards</h5>
            <h1>Buy & Sell Used Board Games</h1>
            <h4>Refurbished products available<h4>

            <a href="shop.php">

                <button>View Board Games</button>
            </a>

        </div>
    </section>


    <!--Featured Board Games-->

    <section id="featured" class="my-5 pb-5">
        <div class="container text-center mt-5 py-5">
            <h1>Sold by Home Of The Boards</h1>
            <h4>All Games Condition & Integrity Verified</h4>
            

            <hr>
        </div>

        <div class="row mx-auto container-fluid">
            <?php foreach ($products as $product): ?>
                <div class="product text-center col-lg-3 col-md-4 col-sm-6 mb-4">
                <img src="assets/imgs/<?php echo htmlspecialchars($product['image']); ?>" 
                alt="<?php echo htmlspecialchars($product['name']); ?>" 
                class="img-fluid main-image rounded shadow" id="mainImage">

                <h5 class="p-name"><?php echo htmlspecialchars($product['name']); ?></h5>
                <h4 class="p-price">R<?php echo htmlspecialchars($product['price']); ?></h4>

            <a href="product.php?id=<?php echo $product['id']; ?>" class="btn btn-danger">View Listing</a>
                 </div>
         <?php endforeach; ?>
        </div>
    </section>

    <!--Footer-->
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
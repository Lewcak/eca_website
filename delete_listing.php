<?php
session_start();
include('assets/server/db.php');


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}


if (isset($_SESSION['admin']) && $_SESSION['admin'] == 1 && isset($_GET['id'])) {
    $productId = intval($_GET['id']);

    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $productId);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Product deleted by admin.";
    } else {
        $_SESSION['error'] = "Failed to delete product.";
    }

    $stmt->close();
    header("Location: admin.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $productId = intval($_POST['product_id']);
    $userId = $_SESSION['user_id'];

    $stmt = $conn->prepare("DELETE FROM products WHERE id = ? AND seller_id = ?");
    $stmt->bind_param("ii", $productId, $userId);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Listing deleted successfully.";
    } else {
        $_SESSION['error'] = "Failed to delete listing.";
    }

    $stmt->close();
    header("Location: profile.php");
    exit();
}


header("Location: index.php");
exit();

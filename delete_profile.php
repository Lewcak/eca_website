<?php
session_start();
include('assets/server/db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$isAdmin = isset($_SESSION['admin']) && $_SESSION['admin'] == 1;
$isAdminDelete = isset($_POST['admin_delete']) && $_POST['admin_delete'] == '1';
$targetUserId = $isAdminDelete ? intval($_POST['target_user_id']) : $_SESSION['user_id'];


if (!$isAdminDelete && $targetUserId !== $_SESSION['user_id']) {
    header('Location: profile.php');
    exit();
}


$stmt1 = $conn->prepare("DELETE FROM products WHERE seller_id = ?");
$stmt1->bind_param("i", $targetUserId);
$stmt1->execute();
$stmt1->close();


$stmt2 = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt2->bind_param("i", $targetUserId);
$stmt2->execute();
$stmt2->close();

if ($isAdminDelete) {
    $_SESSION['message'] = "User deleted successfully.";
    header("Location: admin.php");
} else {
    session_unset();
    session_destroy();
    header("Location: goodbye.php"); 
}
exit();
?>

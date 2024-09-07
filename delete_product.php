<?php
include("admin_session.php");

if (!isset($_GET['id'])) {
    header('Location: products.php');
    exit();
}

$productID = $_GET['id'];

$query = "DELETE FROM products WHERE ProductID=$productID";
$conn->query($query);

header('Location: products.php');
exit();
?>

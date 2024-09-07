<?php
include("admin_session.php");

if (!isset($_GET['id'])) {
    header('Location: customers.php');
    exit();
}

$customerID = $_GET['id'];

$query = "DELETE FROM customers WHERE CustomerID=$customerID";
$conn->query($query);

header('Location: customers.php');
exit();
?>

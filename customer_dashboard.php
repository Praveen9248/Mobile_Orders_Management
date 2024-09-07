<?php
session_start();
if ($_SESSION['role'] !== 'Customer') {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Dashboard</title>
</head>
<body>
    <h2>Welcome, Customer!</h2>
    <p>This is the customer dashboard.</p>
</body>
</html>

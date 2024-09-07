<?php
include("admin_session.php");

if (!isset($_GET['id'])) {
    header('Location: users.php');
    exit();
}

$userID = $_GET['id'];

$query = "DELETE FROM users WHERE UserID=$userID";
$conn->query($query);

header('Location: admin_dashboard.php');
exit();
?>

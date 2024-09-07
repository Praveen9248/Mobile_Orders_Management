<?php
include("admin_session.php");

$customerCount = $conn->query("SELECT COUNT(*) AS count FROM customers")->fetch_assoc()['count'];
$productCount = $conn->query("SELECT COUNT(*) AS count FROM products")->fetch_assoc()['count'];
$orderCount = $conn->query("SELECT COUNT(*) AS count FROM orders")->fetch_assoc()['count'];
$totalSales = $conn->query("SELECT SUM(TotalAmount) AS total FROM orders")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reports</title>
    <style>
                /* General styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        .navbar {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            text-align: center;
            position: relative;
        }
        .navbar nav {
            display: inline-block;
            margin: 0 auto;
        }
        .navbar a {
            color: #fff;
            display: inline-block;
            padding: 10px 20px;
            text-decoration: none;
        }
        .navbar a:hover {
            background-color: #444;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        /* Report container styles */
        .report-container {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
            text-align: center;
        }

        .report-container p {
            font-size: 18px;
            margin: 10px 0;
            color: #555;
        }

        /* Highlighted text */
        .report-container p span {
            font-weight: bold;
            color: #007bff;
        }

    </style>
</head>
<body>
    <div class="navbar">
        <nav>
            <a href="admin_dashboard.php">Admin Dashboard</a>
            <a href="customers.php">Customers</a>
            <a href="products.php">Products</a>
            <a href="orders.php">Orders</a>
            <a href="users.php">Users</a>
            <a href="admin_manage_requests.php">Service Repair</a>
            <a href="reports.php">Reports</a>
            <a href="logout.php">Logout</a>
        </nav>
    </div>
    <h1>Reports</h1>
    <div class="report-container">
        <p>Total Customers: <span><?php echo $customerCount; ?></span></p>
        <p>Total Products: <span><?php echo $productCount; ?></span></p>
        <p>Total Orders: <span><?php echo $orderCount; ?></span></p>
        <p>Total Sales: $<span><?php echo $totalSales; ?></span></p>
    </div>
</body>
</html>

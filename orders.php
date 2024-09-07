<?php
include("admin_session.php");

$orders = $conn->query("SELECT * FROM orders");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Orders</title>
    <style>/* General styles */
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

        /* Table styles */
        table {
            width: 100%;
            max-width: 1000px;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        table thead {
            background-color: #007bff;
            color: white;
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            position: sticky;
            top: 0;
            z-index: 1;
        }

        table tbody tr:nth-child(even) {
            background-color: #f4f4f4;
        }

        table tbody tr:hover {
            background-color: #e9e9e9;
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
    <h1>Orders</h1>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer ID</th>
                <th>Total Amount</th>
                <th>Order Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $orders->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['OrderID']; ?></td>
                    <td><?php echo $row['CustomerID']; ?></td>
                    <td><?php echo $row['TotalAmount']; ?></td>
                    <td><?php echo $row['OrderDate']; ?></td>
                    <td><?php echo $row['OrderStatus']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>

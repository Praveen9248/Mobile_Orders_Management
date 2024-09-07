<?php
include("admin_session.php");

$users = $conn->query("SELECT u.UserID, u.Username, u.Email, u.RegistrationDate
FROM users u
JOIN userroleassignments ura ON u.UserID = ura.UserID
JOIN userroles ur ON ura.RoleID = ur.RoleID
WHERE ur.RoleName != 'Admin';
");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
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
        
        .card {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .card-header {
            font-size: 1.5em;
            margin-bottom: 10px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .table th {
            background-color: #f4f4f9;
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
            <a href="admin_manage_requests.php">Service Repair</a>
            <a href="users.php">Users</a>
            <a href="reports.php">Reports</a>
            <a href="logout.php">Logout</a>
        </nav>
    </div>
    <div class="content">
        <div class="card">
            <div class="card-header">
                Overview
            </div>
            <div class="card-body">
                <p>Summary of key metrics...</p>
                <!-- Summary Cards -->
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                Sales Chart
            </div>
            <div class="card-body">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                Users
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $users->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['UserID']; ?></td>
                            <td><?php echo $row['Username']; ?></td>
                            <td><?php echo $row['Email']; ?></td>
                            <td>
                                <a href="edit_user.php?id=<?php echo $row['UserID']; ?>">Edit</a>
                                <a href="delete_user.php?id=<?php echo $row['UserID']; ?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Example of initializing a Chart.js chart
        var ctx = document.getElementById('salesChart').getContext('2d');
        var salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June'],
                datasets: [{
                    label: 'Sales',
                    data: [12, 19, 3, 5, 2, 3],
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>


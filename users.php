<?php
include("admin_session.php");

$users = $conn->query("SELECT u.UserID, u.Username, u.Email, u.RegistrationDate
FROM users u
JOIN userroleassignments ura ON u.UserID = ura.UserID
JOIN userroles ur ON ura.RoleID = ur.RoleID
WHERE ur.RoleName = 'Customer';
");
$tech = $conn->query("SELECT u.UserID, u.Username, u.Email, u.RegistrationDate
FROM users u
JOIN userroleassignments ura ON u.UserID = ura.UserID
JOIN userroles ur ON ura.RoleID = ur.RoleID
WHERE ur.RoleName = 'Technician';
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Users</title>
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
            max-width: 800px;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: white;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        table thead {
            background-color: #007bff;
            color: white;
        }

        table thead th {
            padding: 10px;
            text-align: left;
        }

        table tbody tr {
            border-bottom: 1px solid #ddd;
        }

        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tbody td {
            padding: 10px;
        }

        table tbody td a {
            color: #007bff;
            text-decoration: none;
            margin-right: 10px;
        }

        table tbody td a:hover {
            text-decoration: underline;
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
    <h1>Users</h1>
    <table>
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
    <h1>technicians</h1>
    <table>
        <thead>
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $tech->fetch_assoc()): ?>
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
</body>
</html>

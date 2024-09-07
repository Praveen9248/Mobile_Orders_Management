<?php
include("admin_session.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $email = $_POST['email'];
        $phoneNumber = $_POST['phoneNumber'];
        $address = $_POST['address'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $zipCode = $_POST['zipCode'];
        $registrationDate = $_POST['registrationDate'];

        $query = "INSERT INTO customers (FirstName, LastName, Email, PhoneNumber, Address, City, State, ZipCode, RegistrationDate) VALUES ('$firstName', '$lastName', '$email', '$phoneNumber', '$address', '$city', '$state', '$zipCode', '$registrationDate')";
        $conn->query($query);
    }
}

$customers = $conn->query("SELECT * FROM customers");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customers</title>
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
        .mini_bar{
            height: 40px;
            display:flex;
            justify-content:space-between;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        form {
            margin-top: 20px;
        }
        form input {
            margin-bottom: 10px;
            padding: 10px;
            width: 100%;
            box-sizing: border-box;
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
    <div class="content">
        <div class="mini_bar">
            <h1>Customers</h1>
            <button id="toggleButton">Add Customer</button>
        </div>
        <form method="POST" id="add_customer" style="display:none;">
            <input type="text" name="firstName" placeholder="First Name" required>
            <input type="text" name="lastName" placeholder="Last Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="phoneNumber" placeholder="Phone Number" required>
            <input type="text" name="address" placeholder="Address" required>
            <input type="text" name="city" placeholder="City" required>
            <input type="text" name="state" placeholder="State" required>
            <input type="text" name="zipCode" placeholder="Zip Code" required>
            <input type="date" name="registrationDate" required>
            <button type="submit" name="add">Add Customer</button>
        </form>

        <table id="display_customer">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Address</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Zip Code</th>
                    <th>Registration Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $customers->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['CustomerID']; ?></td>
                        <td><?php echo $row['FirstName']; ?></td>
                        <td><?php echo $row['LastName']; ?></td>
                        <td><?php echo $row['Email']; ?></td>
                        <td><?php echo $row['PhoneNumber']; ?></td>
                        <td><?php echo $row['Address']; ?></td>
                        <td><?php echo $row['City']; ?></td>
                        <td><?php echo $row['State']; ?></td>
                        <td><?php echo $row['ZipCode']; ?></td>
                        <td><?php echo $row['RegistrationDate']; ?></td>
                        <td>
                            <a href="edit_customer.php?id=<?php echo $row['CustomerID']; ?>">Edit</a>
                            <a href="delete_customer.php?id=<?php echo $row['CustomerID']; ?>" onclick="return confirm('Are you sure you want to delete this customer?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <!-- <script>
        document.getElementById('toggleButton').addEventListener('click', function() {
            var form = document.getElementById('add_customer');
            if (form.style.display === 'none' || form.style.display === '') {
                form.style.display = 'block';
            } else {
                form.style.display = 'none';
            }
        });
    </script> -->
</body>
    <script>
        document.getElementById('toggleButton').addEventListener('click', function() {
            var form = document.getElementById('add_customer');
            var table = document.getElementById('display_customer');

            if (form.style.display === 'none') {
                form.style.display = 'block';
                table.style.display = 'none';
                this.textContent = 'View Customers';
            } else {
                form.style.display = 'none';
                table.style.display = 'block';
                this.textContent = 'Add Customer';
            }
        });
    </script>
</html>

<?php
include("admin_session.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $brand = $_POST['Brand'];
        $category = $_POST['Category'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];

        if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
            $imageTmpName = $_FILES['image']['tmp_name'];
            $imageData = file_get_contents($imageTmpName);
    
            // Prepare and execute the SQL statement
            $stmt = $mysqli->prepare("INSERT INTO products (ProductName, Description, Brand, Category, Price, StockQuantity, image_url) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $null = NULL; // Used for BLOB handling
            $stmt->bind_param("sssdsb", $name, $description, $brand, $category, $price, $null);
    
            // Bind the BLOB parameter and execute
            $stmt->send_long_data(6, $imageData); // 6 is the index of the BLOB parameter
            $stmt->execute();
    
            if ($stmt->affected_rows > 0) {
                echo "Product added successfully!";
            } else {
                echo "Error adding product: " . $mysqli->error;
            }
    
            $stmt->close();
        } else {
            echo "No image file uploaded or upload error.";
        }
    }
    // Close the database connection
    $mysqli->close();
}
$products = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
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
            height: 60px;
            display:flex;
            justify-content:space-between;
        }
        /* Button styles */
        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            margin-bottom: 20px;
            cursor: pointer;
            border-radius: 5px;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* Form styles */
        form {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
        }

        form input, form select, form button {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
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

        /* Link styles */
        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
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
    <div class="content">
    <div class="mini_bar">
            <h1>Products</h1>
            <button id="toggleButton">Add Products</button>
    </div>
    <form method="POST" id="add_Product" enctype="multipart/form-data" style="display:none;">
        <input type="text" name="name" placeholder="Product Name" required>
        <input type="text" name="description" placeholder="Description" required>
        <input type="text" name="Brand" placeholder="Brand" required>
        <select name="Category" required>
            <option value="" disabled selected>Select Category</option>
            <option value="Accessories">Accessories</option>
            <option value="Audio Store">Audio Store</option>
            <option value="Laptops">Laptops</option>
            <option value="Tablets">Tablets</option>
            <option value="Mobiles">Mobiles</option>
        </select>
        <input type="number" step="0.01" name="price" placeholder="Price" required>
        <input type="number" name="stock" placeholder="Stock" required>
        <!-- <input type="file" name="image" accept="image/*" required> -->
        <button type="submit" name="add">Add Product</button>
    </form>

    <table id="display_Product" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Name</th>
                <th>Description</th>
                <th>Brand</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $products->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['ProductID']); ?></td>
                    <td>
                        <?php if ($row['image_url']): ?>
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['image_url']); ?>" alt="Product Image" style="width: 100px; height: auto;">
                        <?php else: ?>
                            No Image
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($row['ProductName']); ?></td>
                    <td><?php echo htmlspecialchars($row['Description']); ?></td>
                    <td><?php echo htmlspecialchars($row['Brand']); ?></td>
                    <td><?php echo htmlspecialchars($row['Category']); ?></td>
                    <td><?php echo htmlspecialchars($row['Price']); ?></td>
                    <td><?php echo htmlspecialchars($row['StockQuantity']); ?></td>
                    <td>
                        <a href="edit_product.php?id=<?php echo htmlspecialchars($row['ProductID']); ?>">Edit</a>
                        <a href="delete_product.php?id=<?php echo htmlspecialchars($row['ProductID']); ?>" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    </div>
</body>
<script>
        document.getElementById('toggleButton').addEventListener('click', function() {
            var form = document.getElementById('add_Product');
            var table = document.getElementById('display_Product');

            if (form.style.display === 'none') {
                form.style.display = 'block';
                table.style.display = 'none';
                this.textContent = 'View Products';
            } else {
                form.style.display = 'none';
                table.style.display = 'block';
                this.textContent = 'Add Products';
            }
        });
    </script>
</html>

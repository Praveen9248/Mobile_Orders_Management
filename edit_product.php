<?php
include("admin_session.php");

if (!isset($_GET['id'])) {
    header('Location: products.php');
    exit();
}

$productID = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $brand = $_POST['Brand'];
    $category = $_POST['Category'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $query = "UPDATE products SET ProductName='$name', Description='$description', Brand='$brand', Category='$category', Price='$price', StockQuantity='$stock' WHERE ProductID=$productID";
    if ($conn->query($query) === TRUE) {
        header('Location: products.php');
        exit();
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }
}

$query = "SELECT * FROM products WHERE ProductID=$productID";
$result = $conn->query($query);
$product = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
    <style>/* General styles */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

h1 {
    color: #333;
    margin-bottom: 20px;
}

/* Form styles */
form {
    background-color: white;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 600px;
    display: flex;
    flex-direction: column;
}

form input,
form select {
    margin-bottom: 10px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
}

form select {
    height: 40px;
}

form button {
    padding: 10px 20px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

form button:hover {
    background-color: #0056b3;
}
</style>
</head>
<body>
    <h1>Edit Product</h1>
    <form method="POST">
        <input type="text" name="name" value="<?php echo htmlspecialchars($product['ProductName']); ?>" placeholder="Product Name" required>
        <input type="text" name="description" value="<?php echo htmlspecialchars($product['Description']); ?>" placeholder="Description" required>
        <input type="text" name="Brand" value="<?php echo htmlspecialchars($product['Brand']); ?>" placeholder="Brand" required>
        <select name="Category" required>
            <option value="" disabled>Select Category</option>
            <option value="Accessories" <?php if ($product['Category'] == 'Accessories') echo 'selected'; ?>>Accessories</option>
            <option value="Audio Store" <?php if ($product['Category'] == 'Audio Store') echo 'selected'; ?>>Audio Store</option>
            <option value="Laptops" <?php if ($product['Category'] == 'Laptops') echo 'selected'; ?>>Laptops</option>
            <option value="Tablets" <?php if ($product['Category'] == 'Tablets') echo 'selected'; ?>>Tablets</option>
            <option value="Mobiles" <?php if ($product['Category'] == 'Mobiles') echo 'selected'; ?>>Mobiles</option>
        </select>
        <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($product['Price']); ?>" placeholder="Price" required>
        <input type="number" name="stock" value="<?php echo htmlspecialchars($product['StockQuantity']); ?>" placeholder="Stock" required>
        <button type="submit">Update Product</button>
    </form>
</body>
</html>

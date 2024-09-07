<?php
include("config.php");
include("customer_session.php");
function fetchProductById($id) {
    global $conn;
    $query = $conn->prepare("SELECT * FROM products WHERE ProductID = ?");
    $query->bind_param("i", $id);
    $query->execute();
    $result = $query->get_result();
    return $result->fetch_assoc();
}

$product = null;

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $product = fetchProductById($id);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        h1 {
            text-align: center;
            padding: 20px;
            background-color: #007BFF;
            color: white;
            border-radius: 10px 10px 0 0;
            margin: -20px -20px 20px -20px;
        }
        .product-details img {
            max-width: 100%;
            border-radius: 10px;
            display: block;
            margin: 0 auto 20px;
        }
        .product-details p {
            font-size: 1.2em;
            color: #555;
        }
        .back-link, .add-to-cart {
            display: block;
            text-align: center;
            margin-top: 20px;
        }
    </style>
    
</head>
<body>
    <div class="container">
        <?php if ($product): ?>
            <h1><?php echo htmlspecialchars($product['ProductName']); ?></h1>
            <div class="product-details">
                <img src="<?php echo htmlspecialchars('data:image/jpeg;base64,' . base64_encode($product['image_url'])); ?>" alt="<?php echo htmlspecialchars($product['ProductName']); ?>">
                <p><strong>Price:$</strong> <?php echo htmlspecialchars($product['Price']); ?></p>
                <p><strong>Description:</strong> <?php echo htmlspecialchars($product['Description']); ?></p>
            </div>
            
            <button><a href="add_to_cart.php?ProductID=<?php echo htmlspecialchars($product['ProductID']); ?>" class="order-button">Add to Cart</a></button>
            <button><a href="shipping_payment.php?ProductID=<?php echo htmlspecialchars($product['ProductID']); ?>" class="order-button">Order Now</a></button>
        <?php else: ?>
            <p>Product not found.</p>
        <?php endif; ?>
        <a class="back-link" href="javascript:history.back()">Back to Products</a>
    </div>
</body>
</html>

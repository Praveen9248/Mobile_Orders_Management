<?php
include("config.php");
include("customer_session.php"); 
$user_id = $_SESSION['UserID'];
// Get the product ID from the query string
$product_id = isset($_GET['ProductID']) ? intval($_GET['ProductID']) : 0;

// Fetch product details (optional, if you need to display them)
$query = $conn->prepare("SELECT ProductName, Price FROM products WHERE ProductID = ?");
$query->bind_param("i", $product_id);
$query->execute();
$product_result = $query->get_result();
$product = $product_result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Shipping and Payment</title>
    <style>
        .form-container {
            max-width: 600px;
            margin: auto;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        button {
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Shipping and Payment</h1>
        <form action="process_order.php" method="POST">
            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product_id); ?>">
            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($_SESSION['UserID']); ?>">
            
            <h3>Product Details</h3>
            <p>Product: <?php echo htmlspecialchars($product['ProductName']); ?></p>
            <p>Price: $<?php echo htmlspecialchars(number_format($product['Price'], 2)); ?></p>
            
            <h3>Shipping Information</h3>
            <div class="form-group">
                <label for="shipping_address">Shipping Address:</label>
                <input type="text" id="shipping_address" name="shipping_address" required>
            </div>
            
            <h3>Billing Information</h3>
            <div class="form-group">
                <label for="billing_address">Billing Address:</label>
                <input type="text" id="billing_address" name="billing_address" required>
            </div>
            
            <div class="form-group">
                <label for="payment_method">Payment Method:</label>
                <select id="payment_method" name="payment_method" required>
                    <option value="Credit Card">Credit Card</option>
                    <option value="PayPal">PayPal</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                    <option value="Cash on Delivery">Cash on Delivery</option>
                </select>
            </div>
            
            <button type="submit">Submit Order</button>
        </form>
    </div>
</body>
</html>

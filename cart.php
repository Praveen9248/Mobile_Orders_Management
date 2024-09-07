<?php
include("config.php");
session_start();

// Get the user ID from the session
$user_id = isset($_SESSION['UserID']) ? intval($_SESSION['UserID']) : 0;

if ($user_id > 0) {
    // Retrieve the active cart for the user
    $query = $conn->prepare("SELECT CartID FROM carts WHERE UserID = ? ORDER BY CreatedDate DESC LIMIT 1");
    $query->bind_param("i", $user_id);
    $query->execute();
    $result = $query->get_result();
    $cart = $result->fetch_assoc();

    if ($cart) {
        $cart_id = $cart['CartID'];
        
        // Retrieve items in the cart
        $query = $conn->prepare("SELECT ci.CartItemID, p.ProductName, p.Price, ci.Quantity FROM cartitems ci JOIN products p ON ci.ProductID = p.ProductID WHERE ci.CartID = ?");
        $query->bind_param("i", $cart_id);
        $query->execute();
        $result = $query->get_result();
    } else {
        $result = [];
    }
} else {
    $result = [];
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Cart</title>
    <link rel="stylesheet" href="style_tech.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f8f8f8;
        }
        td img {
            max-width: 80px;
            height: auto;
            border-radius: 4px;
        }
        .total {
            text-align: right;
            font-size: 1.2em;
            margin-top: 20px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }
        .button:hover {
            background-color: #0056b3;
        }
        .empty {
            text-align: center;
            font-size: 1.2em;
            color: #666;
        }
    </style>
</head>
<body>
    <header>
        <h1 style="color:white";>Cart Dashboard</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <div class="container">
        <h1>Your Cart</h1>
        <?php if ($result && $result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    while ($row = $result->fetch_assoc()):
                        $item_total = $row['Price'] * $row['Quantity'];
                        $total += $item_total;
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['ProductName']); ?></td>
                            <td>$<?php echo htmlspecialchars(number_format($row['Price'], 2)); ?></td>
                            <td><?php echo htmlspecialchars($row['Quantity']); ?></td>
                            <td>$<?php echo htmlspecialchars(number_format($item_total, 2)); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <div class="total">
                <strong>Total: $<?php echo htmlspecialchars(number_format($total, 2)); ?></strong>
            </div>
            
        <?php else: ?>
            <p class="empty">Your cart is empty.</p>
        <?php endif; ?>
    </div>
</body>
</html>

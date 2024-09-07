<?php
include("config.php");

if (isset($_GET['order_id'])) {
    $order_id = intval($_GET['order_id']);

    try {
        // Fetch order details
        $query = $conn->prepare("SELECT o.OrderID, o.OrderDate, o.TotalAmount, o.OrderStatus, o.PaymentMethod, o.ShippingAddress, o.BillingAddress, c.FirstName
                                 FROM orders o
                                 JOIN customers c ON o.CustomerID = c.CustomerID
                                 WHERE o.OrderID = ?");
        $query->bind_param("i", $order_id);
        $query->execute();
        $order_result = $query->get_result();
        $order = $order_result->fetch_assoc();

        if ($order) {
            // Fetch order items
            $query = $conn->prepare("SELECT p.ProductName, oi.Quantity, oi.UnitPrice, oi.TotalPrice
                                     FROM orderitems oi
                                     JOIN products p ON oi.ProductID = p.ProductID
                                     WHERE oi.OrderID = ?");
            $query->bind_param("i", $order_id);
            $query->execute();
            $items_result = $query->get_result();

            $items = [];
            while ($item = $items_result->fetch_assoc()) {
                $items[] = $item;
            }
        } else {
            echo "Order not found.";
            exit();
        }
    } catch (Exception $e) {
        // Handle exceptions
        echo "An error occurred: " . $e->getMessage();
        exit();
    }
} else {
    echo "No order ID provided.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            width: 80%;
            margin: 0 auto;
        }
        h1 {
            text-align: center;
        }
        .order-details, .order-items {
            margin-bottom: 20px;
        }
        .order-items table {
            width: 100%;
            border-collapse: collapse;
        }
        .order-items th, .order-items td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .order-items th {
            background-color: #f4f4f4;
        }
        .print-button {
            display: block;
            width: 150px;
            margin: 20px auto;
            padding: 10px;
            text-align: center;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
    </style>
    <script>
        function printReceipt() {
            window.print();
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Order Confirmation</h1>
        
        <div class="order-details">
            <h2>Order Details</h2>
            <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order['OrderID']); ?></p>
            <p><strong>Order Date:</strong> <?php echo htmlspecialchars($order['OrderDate']); ?></p>
            <p><strong>Total Amount:</strong> $<?php echo number_format($order['TotalAmount'], 2); ?></p>
            <p><strong>Order Status:</strong> <?php echo htmlspecialchars($order['OrderStatus']); ?></p>
            <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($order['PaymentMethod']); ?></p>
            <p><strong>Shipping Address:</strong> <?php echo htmlspecialchars($order['ShippingAddress']); ?></p>
            <p><strong>Billing Address:</strong> <?php echo htmlspecialchars($order['BillingAddress']); ?></p>
        </div>

        <div class="order-items">
            <h2>Order Items</h2>
            <table>
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['ProductName']); ?></td>
                            <td><?php echo htmlspecialchars($item['Quantity']); ?></td>
                            <td>$<?php echo number_format($item['UnitPrice'], 2); ?></td>
                            <td>$<?php echo number_format($item['TotalPrice'], 2); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <button class="print-button" onclick="printReceipt()">Print Receipt</button>
        <center><button><a href="index.php">Home</a></button><center>
    </div>
</body>
</html>

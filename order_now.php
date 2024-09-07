<?php
include("config.php");

// Start session to manage user data
session_start();

// Get the user ID from the session
$user_id = isset($_SESSION['UserID']) ? intval($_SESSION['UserID']) : 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    if ($user_id > 0) {
        // Get CustomerID from the users table
        $query = $conn->prepare("SELECT CustomerID FROM customers WHERE UserID = ?");
        $query->bind_param("i", $user_id);
        $query->execute();
        $result = $query->get_result();
        $customer = $result->fetch_assoc();

        if ($customer) {
            $customer_id = $customer['CustomerID'];
            $order_date = date('Y-m-d');
            $total_amount = 0; // Initialize total amount

            // Check if there is an existing order for the user
            $query = $conn->prepare("SELECT OrderID FROM orders WHERE CustomerID = ? AND OrderStatus = 'Pending' ORDER BY OrderDate DESC LIMIT 1");
            $query->bind_param("i", $customer_id);
            $query->execute();
            $result = $query->get_result();
            $order = $result->fetch_assoc();

            if ($order) {
                $order_id = $order['OrderID'];
            } else {
                // Create a new order if none exists
                $query = $conn->prepare("INSERT INTO orders (CustomerID, OrderDate, TotalAmount, OrderStatus, PaymentMethod, ShippingAddress, BillingAddress) VALUES (?, ?, ?, 'Pending', 'Not Paid', 'Not Provided', 'Not Provided')");
                $query->bind_param("iss", $customer_id, $order_date, $total_amount);
                $query->execute();
                $order_id = $conn->insert_id;
            }

            // Check if the product is already in the order
            $query = $conn->prepare("SELECT OrderItemID, Quantity, UnitPrice FROM orderitems WHERE OrderID = ? AND ProductID = ?");
            $query->bind_param("ii", $order_id, $product_id);
            $query->execute();
            $result = $query->get_result();
            $order_item = $result->fetch_assoc();

            if ($order_item) {
                // Update quantity and total price if the product already exists in the order
                $new_quantity = $order_item['Quantity'] + $quantity;
                $unit_price = $order_item['UnitPrice'];
                $total_price = $unit_price * $new_quantity;

                $query = $conn->prepare("UPDATE orderitems SET Quantity = ?, TotalPrice = ? WHERE OrderItemID = ?");
                $query->bind_param("idi", $new_quantity, $total_price, $order_item['OrderItemID']);
                $query->execute();
            } else {
                // Get product details to calculate unit price
                $query = $conn->prepare("SELECT Price FROM products WHERE ProductID = ?");
                $query->bind_param("i", $product_id);
                $query->execute();
                $result = $query->get_result();
                $product = $result->fetch_assoc();

                if ($product) {
                    $unit_price = $product['Price'];
                    $total_price = $unit_price * $quantity;

                    // Add new item to orderitems
                    $query = $conn->prepare("INSERT INTO orderitems (OrderID, ProductID, Quantity, UnitPrice, TotalPrice) VALUES (?, ?, ?, ?, ?)");
                    $query->bind_param("iiidi", $order_id, $product_id, $quantity, $unit_price, $total_price);
                    $query->execute();
                } else {
                    echo "Product not found.";
                    exit();
                }
            }

            // Update the total amount of the order
            $query = $conn->prepare("UPDATE orders SET TotalAmount = (SELECT SUM(TotalPrice) FROM orderitems WHERE OrderID = ?) WHERE OrderID = ?");
            $query->bind_param("ii", $order_id, $order_id);
            $query->execute();

            // Redirect to a confirmation page or another relevant page
            header("Location: order_confirmation.php?order_id=" . $order_id);
            exit();
        } else {
            echo "Customer record not found.";
        }
    } else {
        echo "Please log in to place an order.";
    }
}
?>

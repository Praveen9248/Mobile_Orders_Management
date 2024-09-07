<?php
include("config.php");
include("customer_session.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = intval($_POST['product_id']);
    $user_id = intval($_POST['user_id']);
    $shipping_address = $_POST['shipping_address'];
    $billing_address = $_POST['billing_address']; // Value from dropdown
    $payment_method = $_POST['payment_method'];   // Value from dropdown
    $quantity = 1; // Assuming ordering one unit; adjust as needed

    try {
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

            // Determine order status based on payment method
            $order_status = 'Pending'; // Default status
            if ($payment_method === 'Credit Card') {
                $order_status = 'Completed';
            } elseif ($payment_method === 'PayPal') {
                $order_status = 'Completed'; // Assuming immediate payment
            } elseif ($payment_method === 'Bank Transfer') {
                $order_status = 'Completed';
            }

            // Create a new order
            $query = $conn->prepare("INSERT INTO orders (CustomerID, OrderDate, TotalAmount, OrderStatus, PaymentMethod, ShippingAddress, BillingAddress) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $query->bind_param("issssss", $customer_id, $order_date, $total_amount, $order_status, $payment_method, $shipping_address, $billing_address);
            $query->execute();
            $order_id = $conn->insert_id;

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

                // Update the total amount of the order
                $query = $conn->prepare("UPDATE orders SET TotalAmount = (SELECT SUM(TotalPrice) FROM orderitems WHERE OrderID = ?) WHERE OrderID = ?");
                $query->bind_param("ii", $order_id, $order_id);
                $query->execute();

                // Update the delivery date if status is 'Completed'
                if ($order_status === 'Completed') {
                    $delivery_date = date('Y-m-d', strtotime('+5 days')); // Example: 5 days from today
                    $query = $conn->prepare("UPDATE orders SET DeliveryDate = ? WHERE OrderID = ?");
                    $query->bind_param("si", $delivery_date, $order_id);
                    $query->execute();
                }

                // Redirect to a confirmation page
                header("Location: order_confirmation.php?order_id=" . $order_id);
                exit();
            } else {
                echo "Product not found.";
                exit();
            }
        } else {
            echo "Customer record not found.";
        }
    } catch (Exception $e) {
        // Handle exceptions
        echo "An error occurred: " . $e->getMessage();
    }
}
?>

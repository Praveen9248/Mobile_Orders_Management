<?php
include("config.php");
session_start();

// Get the user ID from the session
$user_id = isset($_SESSION['UserID']) ? intval($_SESSION['UserID']) : 0;

// Handle request
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $product_id = isset($_GET['ProductID']) ? intval($_GET['ProductID']) : 0;
    $quantity = isset($_GET['quantity']) ? intval($_GET['quantity']) : 1; // Default quantity is 1

    if ($user_id > 0 && $product_id > 0 && $quantity > 0) {
        // Create or retrieve cart for the user
        // Check if the user already has an active cart
        $query = $conn->prepare("SELECT CartID FROM carts WHERE UserID = ? ORDER BY CreatedDate DESC LIMIT 1");
        $query->bind_param("i", $user_id);
        $query->execute();
        $result = $query->get_result();
        $cart = $result->fetch_assoc();
        
        if ($cart) {
            $cart_id = $cart['CartID'];
        } else {
            // Create a new cart if no active cart exists
            $query = $conn->prepare("INSERT INTO carts (UserID, CreatedDate) VALUES (?, NOW())");
            $query->bind_param("i", $user_id);
            $query->execute();
            $cart_id = $conn->insert_id;
        }

        // Check if the product is already in the cart
        $query = $conn->prepare("SELECT CartItemID, Quantity FROM cartitems WHERE CartID = ? AND ProductID = ?");
        $query->bind_param("ii", $cart_id, $product_id);
        $query->execute();
        $result = $query->get_result();
        $cart_item = $result->fetch_assoc();

        if ($cart_item) {
            // Update quantity if the product already exists in the cart
            $new_quantity = $cart_item['Quantity'] + $quantity;
            $query = $conn->prepare("UPDATE cartitems SET Quantity = ? WHERE CartItemID = ?");
            $query->bind_param("ii", $new_quantity, $cart_item['CartItemID']);
            $query->execute();
        } else {
            // Add new item to the cart
            $query = $conn->prepare("INSERT INTO cartitems (CartID, ProductID, Quantity) VALUES (?, ?, ?)");
            $query->bind_param("iii", $cart_id, $product_id, $quantity);
            $query->execute();
        }

        // Redirect to cart or a confirmation page
        header("Location: cart.php");
        exit();
    } else {
        echo "Invalid request or you need to log in.";
    }
}
?>

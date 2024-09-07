<?php
    session_start(); 
    include("config.php");
    if (isset($_GET['q'])) {
        $searchTerm = $_GET['q'];
        
        // Sanitize the search term to prevent SQL injection
        $searchTerm = mysqli_real_escape_string($conn, $searchTerm);
        
        // Query the database for products matching the search term
        $sql = "SELECT ProductID FROM products WHERE ProductName LIKE '%$searchTerm%' OR Description LIKE '%$searchTerm%'";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            // Fetch the product ID
            $row = mysqli_fetch_assoc($result);
            $productID = $row['ProductID'];
            
            // Redirect to the product detail page with the product ID
            header("Location: product_details.php?id=$productID");
            exit();
        } else {
            $errorMessage = "No products found.";
        }
    }
?>
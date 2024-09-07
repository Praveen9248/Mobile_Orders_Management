<?php
include("config.php");

function fetchProductsByCategory($category) {
    global $conn; // Ensure you are using the correct database connection variable
    $query = $conn->prepare("SELECT * FROM products WHERE Category = ?");
    $query->bind_param("s", $category);
    $query->execute();
    $result = $query->get_result();
    $products = [];
    
    while ($row = $result->fetch_assoc()) {
        // Convert image blob to base64
        $row['image_url'] = 'data:image/jpeg;base64,' . base64_encode($row['image_url']);
        $products[] = $row;
    }
    
    return $products;
}
?>

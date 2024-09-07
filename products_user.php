<?php
include("config.php");
include("fetch_products.php"); // Include the function definition

$category = '';

if (isset($_GET['category'])) {
    $category = $_GET['category'];
    $products = fetchProductsByCategory($category);
} else {
    $products = [];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        h1 {
            text-align: center;
            padding: 20px;
            background-color: #007BFF;
            color: white;
            margin: 0;
        }
        .products {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 20px;
        }
        .product {
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 10px;
            padding: 15px;
            text-align: center;
            width: calc(100% / 3 - 40px); /* For three columns */
            box-sizing: border-box;
            text-decoration: none;
            color: inherit;
            transition: transform 0.2s;
        }
        .product:hover {
            transform: scale(1.05);
        }
        .product img {
            max-width: 100%;
            border-radius: 10px;
        }
        .product h2 {
            font-size: 1.2em;
            margin: 10px 0;
        }
        .product p {
            font-size: 1em;
            color: #555;
        }
        @media (max-width: 1200px) {
            .product {
                width: calc(100% / 2 - 40px); /* For two columns */
            }
        }
        @media (max-width: 768px) {
            .product {
                width: 100%; /* For one column */
            }
        }
    </style>
</head>
<body>
    <h1>Products in <?php echo htmlspecialchars($category); ?></h1>
    <div class="products">
        <?php foreach ($products as $product): ?>
            <a href="product_details.php?id=<?php echo $product['ProductID']; ?>" class="product">
                <h2><?php echo htmlspecialchars($product['ProductName']); ?></h2>
                <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['ProductName']); ?>">
                <p>Price: $<?php echo htmlspecialchars($product['Price']); ?></p>
                <p>Description: <?php echo htmlspecialchars($product['Description']); ?></p>
            </a>
        <?php endforeach; ?>
    </div>
</body>
</html>

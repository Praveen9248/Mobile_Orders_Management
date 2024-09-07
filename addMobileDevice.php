<?php
include("customer_session.php");
$userID = $_SESSION['UserID'];

if (isset($userID) && !empty($userID)) {
    $sql = "SELECT CustomerID FROM customers WHERE UserID = ?";  
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $stmt->bind_result($customerID);
    $stmt->fetch();
    $stmt->close();
    $conn->close();
} else {
    echo "UserID is not set or invalid.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Mobile Device</title>
    <link rel="stylesheet" href="style_tech.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 20px;
            text-align: center;
        }
        .container {
            display: inline-block; 
            margin: 0 auto; 
        }
        h1 {
            color: #007BFF;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input[type="text"], input[type="number"], input[type="date"], select {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            text-align: center;
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <header>
        <h1 style="color:white";>Service Request Dashboard</h1>
        <nav>
            <a href="fetch_service_requests.php">Home</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <div class="container">
        <h1>Add Mobile Device</h1>
        <form action="add_device.php" method="post">
            <label for="CustomerID">Customer ID:</label>
            <input type="number" id="CustomerID" name="CustomerID" value="<?php echo htmlspecialchars($customerID); ?>" readonly><br><br>

            <label for="Brand">Brand:</label>
            <input type="text" id="Brand" name="Brand"><br><br>

            <label for="Model">Model:</label>
            <input type="text" id="Model" name="Model"><br><br>

            <label for="IMEI">IMEI:</label>
            <input type="text" id="IMEI" name="IMEI"><br><br>

            <label for="PurchaseDate">Purchase Date:</label>
            <input type="date" id="PurchaseDate" name="PurchaseDate"><br><br>

            <label for="WarrantyExpiryDate">Warranty Expiry Date:</label>
            <input type="date" id="WarrantyExpiryDate" name="WarrantyExpiryDate"><br><br>

            <label for="DeviceCondition">Device Condition:</label>
            <select id="DeviceCondition" name="DeviceCondition">
               <option value="New">New</option>
               <option value="Used">Used</option>
               <option value="Refurbished">Refurbished</option>
               <!-- Add more options as needed -->
            </select><br><br>
           <input type="submit" value="Add Device">
       </form>
   </div>
</body>
</html>



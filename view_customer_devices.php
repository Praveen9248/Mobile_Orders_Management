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

    $device_query = "SELECT * FROM mobiledevices WHERE CustomerID = ?";
    $stmt = $conn->prepare($device_query);
    $stmt->bind_param("i", $customerID);
    $stmt->execute();
    $device_result = $stmt->get_result();
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
    <title>Customer Mobile Devices</title>
    <link rel="stylesheet" href="style_tech.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        h1 {
            color: #333;
        }
        table {
            border-collapse: collapse;
            width: 80%;
            margin-top: 20px;
            background-color: #fff;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
        }
        button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
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
    <h1>Customer Mobile Devices</h1>
    <?php
    if ($device_result->num_rows > 0) {
        echo "<table>
                <tr>
                    <th>Device ID</th>
                    <th>Customer ID</th>
                    <th>Brand</th>
                    <th>Model</th>
                    <th>IMEI</th>
                    <th>Purchase Date</th>
                    <th>Warranty Expiry Date</th>
                    <th>Device Condition</th>
                </tr>";
        while ($row = $device_result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row['DeviceID'] . "</td>
                    <td>" . $row['CustomerID'] . "</td>
                    <td>" . $row['Brand'] . "</td>
                    <td>" . $row['Model'] . "</td>
                    <td>" . $row['IMEI'] . "</td>
                    <td>" . $row['PurchaseDate'] . "</td>
                    <td>" . $row['WarrantyExpiryDate'] . "</td>
                    <td>" . $row['DeviceCondition'] . "</td>
                  </tr>";
        }
        echo "</table>";
        echo '<br><a href="addMobileDevice.php">
        <button>Add Mobile Device</button>
        </a>';
    } else {
        echo "No mobile devices found for this customer.";
        echo '<br><a href="addMobileDevice.php">
        <button>Add Mobile Device</button>
        </a>';
    }
    // Close statement and connection
    $stmt->close();
    $conn->close();
    ?>
</body>
</html>

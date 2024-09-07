<?php
include("customer_session.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $deviceID = intval($_POST["DeviceID"]);
    $customerID = intval($_POST["CustomerID"]);
    $brand = htmlspecialchars($_POST["Brand"]);
    $model = htmlspecialchars($_POST["Model"]);
    $imei = htmlspecialchars($_POST["IMEI"]);
    $purchaseDate = htmlspecialchars($_POST["PurchaseDate"]);
    $warrantyExpiryDate = htmlspecialchars($_POST["WarrantyExpiryDate"]);
    $deviceCondition = htmlspecialchars($_POST["DeviceCondition"]);

    $sql = "INSERT INTO mobiledevices (DeviceID, CustomerID, Brand, Model, IMEI, PurchaseDate, WarrantyExpiryDate, DeviceCondition) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iissssss", $deviceID, $customerID, $brand, $model, $imei, $purchaseDate, $warrantyExpiryDate, $deviceCondition);

    if ($stmt->execute()) {
        echo "New device added successfully.";
        header("Location: view_customer_devices.php");
    } else {
        echo "Error: " . $stmt->error;
    }
    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>

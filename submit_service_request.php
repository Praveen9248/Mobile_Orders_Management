<?php
include("customer_session.php"); 

// Get form data
$customer_id = $_POST['customer_id'];
$device_id = $_POST['device_id'];
$service_type = $_POST['service_type'];

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO servicerequests (CustomerID, DeviceID, RequestDate, ServiceType) VALUES (?, ?, NOW(), ?)");
$stmt->bind_param("iis", $customer_id, $device_id, $service_type);

// Execute statement
if ($stmt->execute()) {
    echo "Service request added successfully.";
    header('Location:fetch_service_requests.php');
} else {
    echo "Error: " . $stmt->error;
}

// Close connections
$stmt->close();
$conn->close();
?>

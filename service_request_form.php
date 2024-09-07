<?php
include("customer_session.php"); 

// Check for mobile devices
$device_query = "SELECT DeviceID FROM mobiledevices";
$device_result = $conn->query($device_query);

if ($device_result->num_rows == 0) {
    // Redirect to the add mobile device form if no devices are found
    header("Location: addMobileDevice.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Service Request</title>
    <link rel="stylesheet" href="style_tech.css">

</head>
<body>
    <header>
        <h1>Service Request Dashboard</h1>
        <nav>
            <a href="fetch_service_requests.php">Home</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <center><h1>Add Service Request</h1>
    <form action="submit_service_request.php" method="post">
        <label for="customer_id">Customer ID:</label>
        <select id="customer_id" name="customer_id" required>
            <?php
            // Fetch customers for customer_id dropdown
            $customer_query = "SELECT CustomerID FROM customers";
            $customer_result = $conn->query($customer_query);

            if ($customer_result->num_rows > 0) {
                while ($row = $customer_result->fetch_assoc()) {
                    echo '<option value="' . $row['CustomerID'] . '">' . $row['CustomerID'] . $row['FirstName'] . '</option>';
                }
            } else {
                echo '<option value="">No customers available</option>';
            }
            ?>
        </select><br><br>

        <label for="device_id">Device ID:</label>
        <select id="device_id" name="device_id" required>
            <?php
            // Since the redirection occurs if no devices, this block assumes devices are available
            while ($row = $device_result->fetch_assoc()) {
                echo '<option value="' . $row['DeviceID'] . '">' . $row['DeviceID'] . ' ' . $row['Model'] . '</option>';
            }
            ?>
        </select><br><br>

        <label for="service_type">Service Type:</label>
        <select id="service_type" name="service_type" required>
            <option value="repair">Repair</option>
            <option value="maintenance">Maintenance</option>
        </select><br><br>

        <input type="submit" value="Submit Request">
    </form></center>

    <?php
    // Error handling and closing connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $conn->close();
    ?>
</body>
</html>

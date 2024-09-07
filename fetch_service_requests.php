<?php
include("customer_session.php");
$userID = $_SESSION['UserID'];  // Ensure that the session contains the UserID

if (isset($userID) && !empty($userID)) {
    $sql = "SELECT CustomerID FROM customers WHERE UserID = ?";  
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $stmt->bind_result($customerID);
    $stmt->fetch();
    $stmt->close();

    $service_query = "SELECT * FROM servicerequests WHERE CustomerID = ?";
    $stmt = $conn->prepare($service_query);
    $stmt->bind_param("i", $customerID);
    $stmt->execute();
    $service_result = $stmt->get_result();
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
    <title>Existing Service Requests</title>
    <link rel="stylesheet" href="style_tech.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
            color: #333;
        }
        h1 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #fff;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #007BFF;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #e1e1e1;
        }
        .button-container {
            text-align: center;
            margin-top: 20px;
        }
        button {
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 5px;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <header>
        <h1>Service Request Dashboard</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <h1>Existing Service Requests</h1>
    <?php
    if ($service_result->num_rows > 0) {
        echo "<table>
                <tr>
                    <th>Request ID</th>
                    <th>Device ID</th>
                    <th>Service Type</th>
                    <th>Request Status</th>
                    <th>Request Date</th>
                    <th>Visit Date</th>
                    <th>Estimated Cost</th>
                    <th>Actual Cost</th>
                    <th>Service Notes</th>
                </tr>";
        while ($row = $service_result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row['ServiceRequestID'] . "</td>
                    <td>" . $row['DeviceID'] . "</td>
                    <td>" . $row['ServiceType'] . "</td>
                    <td>" . $row['RequestStatus'] . "</td>
                    <td>" . $row['RequestDate'] . "</td>
                    <td>" . $row['VisitDate'] . "</td>
                    <td>" . $row['EstimatedCost'] . "</td>
                    <td>" . $row['ActualCost'] . "</td>
                    <td>" . $row['ServiceNotes'] . "</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No service requests found for this customer.</p>";
    }
    ?>
    <div class="button-container">
        <a href="service_request_form.php">
            <button>Add Service Request</button>
        </a>
        <a href="addMobileDevice.php">
            <button>Add Mobile Device</button>
        </a>
        <a href="view_customer_devices.php">
            <button>View Mobile Device</button>
        </a>
    </div>
    <?php
    // Close statement and connection
    $stmt->close();
    $conn->close();
    ?>
</body>
</html>


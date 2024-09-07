<?php
include("admin_session.php");

// Handle form submission for pushing service requests to repairs
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['serviceRequestID']) && isset($_POST['technicianID'])) {
    $serviceRequestID = mysqli_real_escape_string($conn, $_POST['serviceRequestID']);
    $technicianID = mysqli_real_escape_string($conn, $_POST['technicianID']);

    // Fetch service request data
    $sql = "SELECT * FROM servicerequests WHERE ServiceRequestID = '$serviceRequestID'";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $serviceRequest = mysqli_fetch_assoc($result);

        // Insert data into repairs table
        $insertSql = "INSERT INTO repairs (
            DeviceID, CustomerID, TechnicianID, RepairDate, 
            ProblemDescription, RepairStatus, EstimatedCost, 
            ActualCost, RepairNotes, ServiceRequestID
        ) VALUES (
            '" . mysqli_real_escape_string($conn, $serviceRequest['DeviceID']) . "',
            '" . mysqli_real_escape_string($conn, $serviceRequest['CustomerID']) . "',
            '$technicianID',
            '" . mysqli_real_escape_string($conn, $serviceRequest['VisitDate']) . "',
            '" . mysqli_real_escape_string($conn, $serviceRequest['ServiceNotes']) . "',
            '" . mysqli_real_escape_string($conn, $serviceRequest['RequestStatus']) . "',
            '" . mysqli_real_escape_string($conn, $serviceRequest['EstimatedCost']) . "',
            '" . mysqli_real_escape_string($conn, $serviceRequest['ActualCost']) . "',
            '" . mysqli_real_escape_string($conn, $serviceRequest['ServiceNotes']) . "',
            '$serviceRequestID'
        )";

        if (mysqli_query($conn, $insertSql)) {
            $message = "Service request successfully pushed to repairs table and assigned to technician.";
        } else {
            $message = "Error: " . $insertSql . "<br>" . mysqli_error($conn);
        }
    } else {
        $message = "No service request found with ID: $serviceRequestID.";
    }
}

// Fetch all unassigned service requests (not in repairs)
$sql = "SELECT sr.* 
        FROM servicerequests sr
        LEFT JOIN repairs r ON sr.ServiceRequestID = r.ServiceRequestID
        WHERE r.ServiceRequestID IS NULL";
$result = mysqli_query($conn, $sql);

// Fetch all technicians
$techSql = "SELECT * FROM technicians";
$techResult = mysqli_query($conn, $techSql);

// Fetch all repairs
$repairsSql = "SELECT * FROM repairs";
$repairsResult = mysqli_query($conn, $repairsSql);

// Store technicians in an array
$technicians = [];
while ($techRow = mysqli_fetch_assoc($techResult)) {
    $technicians[] = $techRow;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Service Requests</title>
    <link rel="stylesheet" href="style_service.css">
    
</head>
<body>
    <div class="navbar">
        <nav>
            <a href="admin_dashboard.php">Admin Dashboard</a>
            <a href="customers.php">Customers</a>
            <a href="products.php">Products</a>
            <a href="orders.php">Orders</a>
            <a href="admin_manage_requests.php">Service Repair</a>
            <a href="users.php">Users</a>
            <a href="reports.php">Reports</a>
            <a href="logout.php">Logout</a>
        </nav>
    </div>
    <h2>Manage Service Requests</h2>
    <?php if (!empty($message)) { echo "<p>$message</p>"; } ?>
    <table border="1">
        <tr>
            <th>Service Request ID</th>
            <th>Customer ID</th>
            <th>Device ID</th>
            <th>Request Date</th>
            <th>Service Type</th>
            <th>Request Status</th>
            <th>Estimated Cost</th>
            <th>Actual Cost</th>
            <th>Service Notes</th>
            <th>Assign Technician</th>
            <th>Actions</th>
        </tr>
        <?php while($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <form method="post" action="">
                <td><?php echo htmlspecialchars($row['ServiceRequestID']); ?></td>
                <td><?php echo htmlspecialchars($row['CustomerID']); ?></td>
                <td><?php echo htmlspecialchars($row['DeviceID']); ?></td>
                <td><?php echo htmlspecialchars($row['RequestDate']); ?></td>
                <td><?php echo htmlspecialchars($row['ServiceType']); ?></td>
                <td><?php echo htmlspecialchars($row['RequestStatus']); ?></td>
                <td><?php echo htmlspecialchars($row['EstimatedCost']); ?></td>
                <td><?php echo htmlspecialchars($row['ActualCost']); ?></td>
                <td><?php echo htmlspecialchars($row['ServiceNotes']); ?></td>
                <td>
                    <select name="technicianID" required>
                        <option value="">Select Technician</option>
                        <?php foreach ($technicians as $tech) { ?>
                            <option value="<?php echo $tech['TechnicianID']; ?>"><?php echo htmlspecialchars($tech['FirstName']); ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td>
                    <input type="hidden" name="serviceRequestID" value="<?php echo htmlspecialchars($row['ServiceRequestID']); ?>">
                    <input type="submit" value="Push to Repair and Assign">
                </td>
            </form>
        </tr>
        <?php } ?>
    </table>
    
    <button>
        <a href="assign_technician.php">Assign Service requests</a>
    </button>

    <h2>Repairs</h2>
    <?php if (mysqli_num_rows($repairsResult) > 0) { ?>
    <table border="1">
        <tr>
            <th>Repair ID</th>
            <th>Device ID</th>
            <th>Customer ID</th>
            <th>Technician ID</th>
            <th>Repair Date</th>
            <th>Problem Description</th>
            <th>Repair Status</th>
            <th>Estimated Cost</th>
            <th>Actual Cost</th>
            <th>Repair Notes</th>
            <th>Service Request ID</th>
        </tr>
        <?php while($repairRow = mysqli_fetch_assoc($repairsResult)) { ?>
        <tr>
            <td><?php echo htmlspecialchars($repairRow['RepairID']); ?></td>
            <td><?php echo htmlspecialchars($repairRow['DeviceID']); ?></td>
            <td><?php echo htmlspecialchars($repairRow['CustomerID']); ?></td>
            <td><?php echo htmlspecialchars($repairRow['TechnicianID']); ?></td>
            <td><?php echo htmlspecialchars($repairRow['RepairDate']); ?></td>
            <td><?php echo htmlspecialchars($repairRow['ProblemDescription']); ?></td>
            <td><?php echo htmlspecialchars($repairRow['RepairStatus']); ?></td>
            <td><?php echo htmlspecialchars($repairRow['EstimatedCost']); ?></td>
            <td><?php echo htmlspecialchars($repairRow['ActualCost']); ?></td>
            <td><?php echo htmlspecialchars($repairRow['RepairNotes']); ?></td>
            <td><?php echo htmlspecialchars($repairRow['ServiceRequestID']); ?></td>
        </tr>
        <?php } ?>
    </table>
    <?php } else { ?>
    <p>No repairs found.</p>
    <?php } ?>
</body>
</html>

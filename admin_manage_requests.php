<?php
include("admin_session.php");

// Handle form submission for updating service requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $serviceRequestID = mysqli_real_escape_string($conn, $_POST['serviceRequestID']);
    $requestStatus = mysqli_real_escape_string($conn, $_POST['requestStatus']);
    $vistdate = mysqli_real_escape_string($conn, $_POST['visitdate']);
    $estimatedCost = mysqli_real_escape_string($conn, $_POST['estimatedCost']);
    $actualCost = mysqli_real_escape_string($conn, $_POST['actualCost']);
    $serviceNotes = mysqli_real_escape_string($conn, $_POST['serviceNotes']);

    // Update service request
    $sql = "UPDATE servicerequests SET 
            RequestStatus = '$requestStatus',
            EstimatedCost = '$estimatedCost',
            VisitDate = '$vistdate',
            ActualCost = '$actualCost', 
            ServiceNotes = '$serviceNotes'
            WHERE ServiceRequestID = '$serviceRequestID'";

    if (mysqli_query($conn, $sql)) {
        $message = "Service request updated successfully.";
    } else {
        $message = "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

// Fetch all service requests
$sql = "SELECT * FROM servicerequests";
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
    <style>
        .navbar {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            text-align: center;
            position: relative;
        }
        .navbar nav {
            display: inline-block;
            margin: 0 auto;
        }
        .navbar a {
            color: #fff;
            display: inline-block;
            padding: 10px 20px;
            text-decoration: none;
        }
        .navbar a:hover {
            background-color: #444;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .table th {
            background-color: #f4f4f9;
        }
    </style>
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
            <th>Visit Date</th>
            <th>Estimated Cost</th>
            <th>Actual Cost</th>
            <th>Service Notes</th>
            <th>Actions</th>
        </tr>
        <?php while($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <form method="post" action="">
                <td><?php echo $row['ServiceRequestID']; ?></td>
                <td><?php echo $row['CustomerID']; ?></td>
                <td><?php echo $row['DeviceID']; ?></td>
                <td><?php echo $row['RequestDate']; ?></td>
                <td><?php echo $row['ServiceType']; ?></td>
                <td>
                    <select name="requestStatus" required>
                        <option value="Pending" <?php if ($row['RequestStatus'] == 'Pending') echo 'selected'; ?>>Pending</option>
                        <option value="In Progress" <?php if ($row['RequestStatus'] == 'In Progress') echo 'selected'; ?>>In Progress</option>
                        <option value="Completed" <?php if ($row['RequestStatus'] == 'Completed') echo 'selected'; ?>>Completed</option>
                        <option value="Canceled" <?php if ($row['RequestStatus'] == 'Canceled') echo 'selected'; ?>>Canceled</option>
                    </select>
                </td>
                <td>
                    <input type="date" name="visitdate" value="<?php echo $row['VisitDate']; ?>" required>
                </td>
                <td>
                    <input type="number" step="0.01" name="estimatedCost" value="<?php echo $row['EstimatedCost']; ?>" required>
                </td>
                <td>
                    <input type="number" step="0.01" name="actualCost" value="<?php echo $row['ActualCost']; ?>" required>
                </td>
                <td>
                    <input type="text" name="serviceNotes" value="<?php echo $row['ServiceNotes']; ?>">
                </td>
                <td>
                    <input type="hidden" name="serviceRequestID" value="<?php echo $row['ServiceRequestID']; ?>">
                    <input type="submit" value="Update">
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
            <td><?php echo $repairRow['RepairID']; ?></td>
            <td><?php echo $repairRow['DeviceID']; ?></td>
            <td><?php echo $repairRow['CustomerID']; ?></td>
            <td><?php echo $repairRow['TechnicianID']; ?></td>
            <td><?php echo $repairRow['RepairDate']; ?></td>
            <td><?php echo $repairRow['ProblemDescription']; ?></td>
            <td><?php echo $repairRow['RepairStatus']; ?></td>
            <td><?php echo $repairRow['EstimatedCost']; ?></td>
            <td><?php echo $repairRow['ActualCost']; ?></td>
            <td><?php echo $repairRow['RepairNotes']; ?></td>
            <td><?php echo $repairRow['ServiceRequestID']; ?></td>
        </tr>
        <?php } ?>
    </table>
    <?php } else { ?>
    <p>No repairs found.</p>
    <?php } ?>
</body>
</html>

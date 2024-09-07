<?php
include("technician_session.php");

// Ensure the user is logged in and has a valid UserID
if (!isset($_SESSION['TechnicianID'])) {
    die("Access denied. You are not logged in.");
}

$userID = $_SESSION['TechnicianID'];

// Fetch TechnicianID based on UserID
$techSql = "SELECT TechnicianID FROM technicians WHERE UserID = '$userID'";
$techResult = mysqli_query($conn, $techSql);

if ($techResult && mysqli_num_rows($techResult) > 0) {
    $techRow = mysqli_fetch_assoc($techResult);
    $technicianID = $techRow['TechnicianID'];
} else {
    die("TechnicianID not found for UserID: $userID.");
}

// Handle form submission for updating repairs
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['repairID'])) {
    $repairID = mysqli_real_escape_string($conn, $_POST['repairID']);
    $repairStatus = mysqli_real_escape_string($conn, $_POST['repairStatus']);
    $problemdescription = mysqli_real_escape_string($conn, $_POST['problemdescription']);
    $repairdate = mysqli_real_escape_string($conn, $_POST['repairdate']);
    $estimatedCost = mysqli_real_escape_string($conn, $_POST['estimatedCost']);
    $actualCost = mysqli_real_escape_string($conn, $_POST['actualCost']);
    $repairNotes = mysqli_real_escape_string($conn, $_POST['repairNotes']);

    // Update repair record
    $updateSql = "UPDATE repairs SET 
                    RepairStatus = '$repairStatus',
                    ProblemDescription = '$problemdescription',
                    EstimatedCost = '$estimatedCost', 
                    RepairDate = '$repairdate',
                    ActualCost = '$actualCost', 
                    RepairNotes = '$repairNotes'
                    WHERE RepairID = '$repairID' AND TechnicianID = '$technicianID'";

    if (mysqli_query($conn, $updateSql)) {
        $message = "Repair record updated successfully.";
    } else {
        $message = "Error: " . $updateSql . "<br>" . mysqli_error($conn);
    }
}

// Fetch all repairs assigned to the technician
$sql = "SELECT * FROM repairs WHERE TechnicianID = '$technicianID'";
$repairsResult = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Technician Interface</title>
    <link rel="stylesheet" href="style_tech.css">
</head>
<body>
    <header>
        <h1>Technician Dashboard</h1>
        <nav>
            <a href="technician_profile.php">Profile</a>
            <a href="update_status.php">Your Repairs</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <h2>Technician Interface</h2>
    <?php if (!empty($message)) { echo "<p>$message</p>"; } ?>
    
    <h3>Your Repairs</h3>
    <?php if (mysqli_num_rows($repairsResult) > 0) { ?>
    <table border="1">
        <tr>
            <th>Repair ID</th>
            <th>Device ID</th>
            <th>Customer ID</th>
            <th>Problem Description</th>
            <th>Repair Status</th>
            <th>Repair Date</th>
            <th>Estimated Cost</th>
            <th>Actual Cost</th>
            <th>Repair Notes</th>
            <th>Actions</th>
        </tr>
        <?php while($repairRow = mysqli_fetch_assoc($repairsResult)) { ?>
        <tr>
            <form method="post" action="">
                <td><?php echo htmlspecialchars($repairRow['RepairID']); ?></td>
                <td><?php echo htmlspecialchars($repairRow['DeviceID']); ?></td>
                <td><?php echo htmlspecialchars($repairRow['CustomerID']); ?></td>
                
                <td>
                <input type="text" name="problemdescription" value="" required>
                </td>
                <td>
                    <select name="repairStatus" required>
                        <option value="Pending" <?php if ($repairRow['RepairStatus'] == 'Pending') echo 'selected'; ?>>Pending</option>
                        <option value="In Progress" <?php if ($repairRow['RepairStatus'] == 'In Progress') echo 'selected'; ?>>In Progress</option>
                        <option value="Completed" <?php if ($repairRow['RepairStatus'] == 'Completed') echo 'selected'; ?>>Completed</option>
                        <option value="Canceled" <?php if ($repairRow['RepairStatus'] == 'Canceled') echo 'selected'; ?>>Canceled</option>
                    </select>
                </td>
                <td>
                    <input type="date" name="repairdate" value="<?php echo htmlspecialchars($repairRow['RepairDate']); ?>" required>
                </td>
                <td>
                    <input type="number" step="0.01" name="estimatedCost" value="<?php echo htmlspecialchars($repairRow['EstimatedCost']); ?>" required>
                </td>
                <td>
                    <input type="number" step="0.01" name="actualCost" value="<?php echo htmlspecialchars($repairRow['ActualCost']); ?>" required>
                </td>
                <td>
                    <input type="text" name="repairNotes" value="<?php echo htmlspecialchars($repairRow['RepairNotes']); ?>">
                </td>
                <td>
                    <input type="hidden" name="repairID" value="<?php echo htmlspecialchars($repairRow['RepairID']); ?>">
                    <input type="submit" value="Update">
                </td>
            </form>
        </tr>
        <?php } ?>
    </table>
    <?php } else { ?>
    <p>No repairs assigned to you.</p>
    <?php } ?>
</body>
</html>

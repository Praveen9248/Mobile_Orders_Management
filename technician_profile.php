<?php
include("technician_session.php"); 
$user_id = $_SESSION['UserID'];

// Fetch technician profile data
$sql = "SELECT * FROM technicians WHERE UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$technician = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <link rel="stylesheet" href="style_tech.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            /* display: flex; */
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .profile-container {
            background: #fff;
            padding: 20px 40px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .profile-container h1 {
            margin-bottom: 20px;
            color: #333;
        }
        .profile-container p {
            margin: 10px 0;
            color: #555;
            text-align: left;
        }
        .profile-container .label {
            font-weight: bold;
            display: inline-block;
            width: 150px;
        }
        .profile-container .value {
            display: inline-block;
            width: calc(100% - 150px);
        }
        .profile-container form {
            margin-top: 20px;
        }
        .profile-container button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .profile-container button:hover {
            background-color: #0056b3;
        }
    </style>
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
    <?php if ($technician): ?>
    <div class="profile-container">
        <h1>Profile</h1>
        <div>
            <p><span class="label">First Name:</span> <span class="value"><?php echo htmlspecialchars($technician['FirstName']); ?></span></p>
            <p><span class="label">Last Name:</span> <span class="value"><?php echo htmlspecialchars($technician['LastName']); ?></span></p>
            <p><span class="label">Email:</span> <span class="value"><?php echo htmlspecialchars($technician['Email']); ?></span></p>
            <p><span class="label">Phone Number:</span> <span class="value"><?php echo htmlspecialchars($technician['PhoneNumber']); ?></span></p>
            <p><span class="label">Skills:</span> <span class="value"><?php echo htmlspecialchars($technician['Skills']); ?></span></p>
            <p><span class="label">Hire Date:</span> <span class="value"><?php echo htmlspecialchars($technician['HireDate']); ?></span></p>
            <form action="edit_tech_profile.php" method="get">
                <button type="submit">Edit Profile</button>
            </form>
        </div>
    </div>
    <?php else: ?>
        <div class="profile-container no-profile">
            <p>No profile found. Please add your profile.</p>
            <form action="add_technician_profile.html" method="post">
                <button type="submit">Add Profile</button>
            </form>
        </div>
    <?php endif; ?>

    <?php
    // Close connection
    $stmt->close();
    $conn->close();
    ?>
</body>
</html>

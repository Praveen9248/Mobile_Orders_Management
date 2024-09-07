<?php
include("technician_session.php"); 
$user_id=$_SESSION['UserID'];

// Fetch customer profile data for editing
$sql = "SELECT * FROM technicians WHERE UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$technician = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process the form submission
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['phoneNumber'];
    $skills = $_POST['Skills'];
    $hiredate = $_POST['HireDate'];
    // Update the customer profile
    $sql = "UPDATE technicians SET FirstName = ?, LastName = ?, Email = ?, PhoneNumber = ?, Skills = ?, HireDate = ? WHERE TechnicianID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $firstName, $lastName, $email, $phoneNumber, $skills, $hiredate, $user_id);
    $stmt->execute();
    
    // Redirect back to the profile page
    header('Location: technician_profile.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            max-width: 600px;
            width: 100%;
        }
        h1 {
            margin-bottom: 20px;
            color: #333;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }
        input[type="text"], input[type="email"] {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Profile</h1>
        <form action="edit_profile_save.php" method="post">
            <label for="firstName">First Name:</label>
            <input type="text" id="firstName" name="firstName" value="<?php echo htmlspecialchars($technician['FirstName']); ?>" required>
            
            <label for="lastName">Last Name:</label>
            <input type="text" id="lastName" name="lastName" value="<?php echo htmlspecialchars($technician['LastName']); ?>" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($technician['Email']); ?>" required>
            
            <label for="phoneNumber">Phone Number:</label>
            <input type="text" id="phoneNumber" name="phoneNumber" value="<?php echo htmlspecialchars($technician['PhoneNumber']); ?>" required>
            
            <label for="Skills">Skills:</label>
            <input type="text" id="Skills" name="Skills" value="<?php echo htmlspecialchars($technician['Skills']); ?>" required>
            
            <label for="city">HireDate:</label>
            <input type="date" id="HireDate" name="HireDate" value="<?php echo htmlspecialchars($technician['HireDate']); ?>" required>
            <br><br>
            <button type="submit">Update Profile</button>
        </form>
    </div>
</body>
</html>

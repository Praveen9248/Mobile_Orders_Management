<?php
include("technician_session.php"); 

// Get the UserID from the session
$user_id = $_SESSION['TechnicianID'];

// Get data from the form
$firstName = $_POST['FirstName'];
$lastName = $_POST['LastName'];
$email = $_POST['Email'];
$phoneNumber = $_POST['PhoneNumber'];
$skills = $_POST['Skills'];
$hireDate = $_POST['HireDate'];

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO technicians (UserID, FirstName, LastName, Email, PhoneNumber, Skills, HireDate) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("issssss", $user_id, $firstName, $lastName, $email, $phoneNumber, $skills, $hireDate);

// Execute the statement
if ($stmt->execute()) {
    echo "New technician added successfully";
    header("Location: technician_profile.php");
} else {
    echo "Error: " . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>

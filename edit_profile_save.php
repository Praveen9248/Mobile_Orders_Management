<?php
include("technician_session.php"); 
$user_id = $_SESSION['UserID'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['firstName'];
    $last_name = $_POST['lastName'];
    $email = $_POST['email'];
    $phone_number = $_POST['phoneNumber'];
    $skills = $_POST['Skills'];
    $hire_date = $_POST['HireDate'];

    $sql_check = "SELECT * FROM technicians WHERE UserID = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("i", $user_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    $technician_exists = $result_check->num_rows > 0;

    if ($technician_exists) {
        // Update existing profile
        $sql = "UPDATE technicians SET FirstName = ?, LastName = ?, Email = ?, PhoneNumber = ?, Skills = ?, HireDate = ? WHERE UserID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi", $first_name, $last_name, $email, $phone_number, $skills, $hire_date, $user_id);
    } else {
        // Insert new profile
        $sql = "INSERT INTO technicians (UserID, FirstName, LastName, Email, PhoneNumber, Skills, HireDate) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issssss", $user_id, $first_name, $last_name, $email, $phone_number, $skills, $hire_date);
    }

    if ($stmt->execute()) {
        echo "Profile saved successfully.";
        header("Location: technician_profile.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close connection
    $stmt->close();
    $conn->close();
}
?>

<?php
include("customer_session.php"); 
$user_id = $_SESSION['UserID'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zip_code = $_POST['zip_code'];
    $registration_date = date('Y-m-d');

    // Check if profile exists
    $sql_check = "SELECT * FROM customers WHERE UserID = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("i", $user_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    $customer_exists = $result_check->num_rows > 0;

    if ($customer_exists) {
        // Update existing profile
        $sql = "UPDATE customers SET FirstName = ?, LastName = ?, Email = ?, PhoneNumber = ?, Address = ?, City = ?, State = ?, ZipCode = ? WHERE UserID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssi", $first_name, $last_name, $email, $phone_number, $address, $city, $state, $zip_code, $user_id);
    } else {
        // Insert new profile
        $sql = "INSERT INTO customers (UserID, FirstName, LastName, Email, PhoneNumber, Address, City, State, ZipCode, RegistrationDate) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isssssssss", $user_id, $first_name, $last_name, $email, $phone_number, $address, $city, $state, $zip_code, $registration_date);
    }

    if ($stmt->execute()) {
        echo "Profile saved successfully.";
        header("Location: profile.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close connection
    $stmt->close();
    $conn->close();
}
?>



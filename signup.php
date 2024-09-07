<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];

    $conn->begin_transaction();

    try {
        // Insert into users table
        $sql = "INSERT INTO users (Username, PasswordHash, Email) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $password, $email);
        $stmt->execute();

        // Get the inserted user's ID
        $userID = $stmt->insert_id;

        // Insert into userroleassignments table
        $sql = "INSERT INTO userroleassignments (UserID, RoleID) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $userID, $role);
        $stmt->execute();

        $conn->commit();
        echo "Registration successful!";
        header("Location: login.html");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }

    $stmt->close();
    $conn->close();
}
?>

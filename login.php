<?php

include 'config.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Sanitize and validate input data
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit();
    }

    // Prepare the SQL statement
    $sql = "SELECT u.UserID, u.PasswordHash, r.RoleName 
            FROM users u 
            JOIN userroleassignments ur ON u.UserID = ur.UserID
            JOIN userroles r ON ur.RoleID = r.RoleID
            WHERE u.Email = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error preparing the statement: " . $conn->error);
    }

    // Bind the parameters and execute the statement
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($UserID, $passwordHash, $role);
        $stmt->fetch();

        if (password_verify($password, $passwordHash)) {
            $_SESSION['UserID'] = $UserID;
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $role;

            if ($role == "Technician") {
                $_SESSION['TechnicianID'] = $UserID;
                header("Location: technician_dashboard.php");
                exit();
            } elseif ($role == "Admin") {
                $_SESSION['AdminID'] = $UserID;
                header("Location: admin_dashboard.php");
                exit();
            } else {
                $_SESSION['CustomerID'] = $UserID;
                header("Location: index.php");
                exit();
            }
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with that email.";
    }

    $stmt->close();
    $conn->close();
}
?>

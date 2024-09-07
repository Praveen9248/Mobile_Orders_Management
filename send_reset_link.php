<?php
include 'config.php';
require 'vendor/autoload.php'; // Include this if you're using a library like PHPMailer

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Check if email exists in the database
    $sql = "SELECT UserID FROM users WHERE Email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        echo "No user found with that email.";
        $stmt->close();
        $conn->close();
        exit();
    }

    // Generate a unique reset token
    $token = bin2hex(random_bytes(16));
    $expires = date("U") + 3600; // Token expires in 1 hour

    // Store the token and expiration date in the database
    $stmt->bind_result($userID);
    $stmt->fetch();
    $stmt->close();

    $sql = "INSERT INTO password_resets (UserID, ResetToken, ExpiryDate) VALUES (?, ?, ?) 
            ON DUPLICATE KEY UPDATE ResetToken = VALUES(ResetToken), ExpiryDate = VALUES(ExpiryDate)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $userID, $token, date("Y-m-d H:i:s", $expires));
    $stmt->execute();
    $stmt->close();

    // Prepare the reset email
    $resetLink = "http://yourdomain.com/reset_password.php?token=" . $token;

    // Use PHPMailer or another mail library to send the email
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'smtp.example.com'; // Your SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = 'your_email@example.com'; // Your SMTP username
    $mail->Password = 'your_email_password'; // Your SMTP password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('no-reply@example.com', 'Your Website');
    $mail->addAddress($email);
    $mail->Subject = 'Password Reset Request';
    $mail->Body = "Click the following link to reset your password: $resetLink\n\nThis link will expire in 1 hour.";
    $mail->isHTML(true);

    if ($mail->send()) {
        echo "A password reset link has been sent to your email.";
    } else {
        echo "Failed to send reset email. Please try again later.";
    }

    $conn->close();
}
?>

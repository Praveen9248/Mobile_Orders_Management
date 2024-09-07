<?php
include("admin_session.php");

if (!isset($_GET['id'])) {
    header('Location: users.php');
    exit();
}

$userID = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];

    $query = "UPDATE users SET Username='$username', Email='$email' WHERE UserID=$userID";
    $conn->query($query);

    header('Location: users.php');
    exit();
}

$query = "SELECT * FROM users WHERE UserID=$userID";
$result = $conn->query($query);
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <style>
        /* General styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        /* Form styles */
        form {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            display: flex;
            flex-direction: column;
        }

        form input {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        form button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        form button:hover {
            background-color: #0056b3;
        }

    </style>
</head>
<body>
    <h1>Edit User</h1>
    <form method="POST">
        <input type="text" name="username" value="<?php echo $user['Username']; ?>" placeholder="Username" required>
        <input type="email" name="email" value="<?php echo $user['Email']; ?>" placeholder="Email" required>
        <button type="submit">Update User</button>
    </form>
</body>
</html>

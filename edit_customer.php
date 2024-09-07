<?php
include("admin_session.php");

if (!isset($_GET['id'])) {
    header('Location: customers.php');
    exit();
}

$customerID = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['phoneNumber'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zipCode = $_POST['zipCode'];
    $registrationDate = $_POST['registrationDate'];

    $query = "UPDATE customers SET FirstName='$firstName', LastName='$lastName', Email='$email', PhoneNumber='$phoneNumber', Address='$address', City='$city', State='$state', ZipCode='$zipCode', RegistrationDate='$registrationDate' WHERE CustomerID=$customerID";
    $conn->query($query);

    header('Location: customers.php');
    exit();
}

$query = "SELECT * FROM customers WHERE CustomerID=$customerID";
$result = $conn->query($query);
$customer = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Customer</title>
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
    <h1>Edit Customer</h1>
    <form method="POST">
        <input type="text" name="firstName" value="<?php echo $customer['FirstName']; ?>" placeholder="First Name" required>
        <input type="text" name="lastName" value="<?php echo $customer['LastName']; ?>" placeholder="Last Name" required>
        <input type="email" name="email" value="<?php echo $customer['Email']; ?>" placeholder="Email" required>
        <input type="text" name="phoneNumber" value="<?php echo $customer['PhoneNumber']; ?>" placeholder="Phone Number" required>
        <input type="text" name="address" value="<?php echo $customer['Address']; ?>" placeholder="Address" required>
        <input type="text" name="city" value="<?php echo $customer['City']; ?>" placeholder="City" required>
        <input type="text" name="state" value="<?php echo $customer['State']; ?>" placeholder="State" required>
        <input type="text" name="zipCode" value="<?php echo $customer['ZipCode']; ?>" placeholder="Zip Code" required>
        <input type="date" name="registrationDate" value="<?php echo $customer['RegistrationDate']; ?>" required>
        <button type="submit">Update Customer</button>
    </form>
</body>
</html>

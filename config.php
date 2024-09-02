<?php
session_start();
$host = 'localhost';
$dbname = 'user_managementdb';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

if (isset($_POST['submit'])) {
    // Retrieve form data
    $phone_number = $_POST['phone_number'];

    $full_name = $_POST['full_name'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate form data
    if (empty($phone_number)) {
        echo "Phone number is required";
        return;
    } elseif (!preg_match('/^[0-9]{10}$/', $phone_number)) {
        echo "Phone number should be a valid 10-digit phone number";
        return;
    }
    if (empty($full_name)) {
        echo "Full name is required";
        return;
    }
    if (empty($password)) {
        echo "Password is required";
        return;
    }
    if (empty($confirm_password)) {
        echo "Confirm password is required";
        return;
    } elseif ($password !== $confirm_password) {
        echo "Passwords do not match";
        return;
    }

    // If form data is valid, process the form
    $verification_code = rand(1000, 9999);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Perform necessary actions, such as saving data to a database
    $stmt = $conn->prepare("INSERT INTO register_users (full_name, phone_number, password, verification_code, created) VALUES (:full_name, :phone_number, :password, :verification_code, NOW())");
    $stmt->bindParam(':full_name', $full_name);
    $stmt->bindParam(':phone_number', $phone_number);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->bindParam(':verification_code', $verification_code);
    $stmt->execute();

    $_SESSION["full_name"] = $full_name;
    $_SESSION["phone_number"] = $phone_number;

    // Redirect user to the verification page
    header("Location: verify-register.php");
    exit;
} elseif (isset($_POST['verify'])) {
    $verification_code = $_POST['verification_code'];
    $phone_number = $_SESSION["phone_number"];

    $stmt = $conn->prepare("SELECT ID FROM register_users WHERE phone_number = :phone_number AND verification_code = :verification_code LIMIT 1");
    $stmt->bindParam(':verification_code', $verification_code);
    $stmt->bindParam(':phone_number', $phone_number);
    $stmt->execute();

    if ($stmt->rowCount() === 0) {
        $_SESSION["message"] = "Wrong verification code";
        header("Location: verify-register.php");
        exit;
    } else {
        header("Location: home.html");
        exit;
    }
}

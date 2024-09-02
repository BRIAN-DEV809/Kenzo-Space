<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once "db_connection.php";

    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password', 'client')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION["username"] = $username;
        $_SESSION["role"] = "client";
        header("Location: client_dashboard.php");
        exit();
    } else {
        header("Location: signup.html?error=1");
        exit();
    }
} else {
    header("Location: signup.html");
    exit();
}
?>

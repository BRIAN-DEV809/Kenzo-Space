<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "usermanagement_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $officeSuitNo = $_POST['officeSuitNo'];
    $name = $_POST['name'];

    // Prepare and bind SQL statement
    $stmt = $conn->prepare("INSERT INTO bookingz (office_suit_no, name) VALUES (?, ?)");
    $stmt->bind_param("ss", $officeSuitNo, $name);

    // Execute SQL statement
    if ($stmt->execute() === TRUE) {
        echo "Booking successful!";
    } else {
        echo "Error: " . $conn->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}

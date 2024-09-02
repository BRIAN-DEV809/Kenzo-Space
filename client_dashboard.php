<?php
session_start();

if (!isset($_SESSION["username"]) || $_SESSION["role"] !== "client") {
    header("Location: index.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="dashboard-container">
        <h2>Welcome, <?php echo $_SESSION["username"]; ?> (Client)!</h2>
        <a href="rooms.php">Rooms</a>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>

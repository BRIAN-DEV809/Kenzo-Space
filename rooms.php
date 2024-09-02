<?php
session_start();

if (!isset($_SESSION["username"]) || $_SESSION["role"] !== "client") {
    header("Location: index.html");
    exit();
}

include_once "db_connection.php";

// Fetch available office spaces
$sql = "SELECT * FROM office_spaces";
$result = $conn->query($sql);

// Handle booking
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["book"])) {
    $office_space_id = $_POST["office_space_id"];
    $client_id = $_SESSION["user_id"]; // Assuming user_id is available in the session

    // Check if the office space is available
    $check_availability = "SELECT * FROM bookings WHERE office_space_id='$office_space_id'";
    $result_availability = $conn->query($check_availability);

    if ($result_availability->num_rows == 0) {
        // Book the office space
        $book_sql = "INSERT INTO bookings (client_id, office_space_id, booked_date) VALUES ('$client_id', '$office_space_id', CURDATE())";
        if ($conn->query($book_sql) === TRUE) {
            echo "<script>alert('Office space booked successfully.');</script>";
        } else {
            echo "Error: " . $book_sql . "<br>" . $conn->error;
        }
    } else {
        echo "<script>alert('This office space is already booked. Please choose another.');</script>";
    }
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
        <h3>Available Office Spaces</h3>
        <div class="office-spaces">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='office-space'>";
                    echo "<h4>" . $row["title"] . "</h4>";
                    echo "<p>Description: " . $row["description"] . "</p>";
                    echo "<p>Price: $" . $row["price"] . "</p>";
                    echo "<img src='uploads/" . $row["image"] . "' alt='" . $row["title"] . "'><br>";
                    echo "<form method='post' action=''>";
                    echo "<input type='hidden' name='office_space_id' value='" . $row["id"] . "'>";
                    echo "<input type='submit' name='book' value='Book'>";
                    echo "</form>";
                    echo "</div>";
                }
            } else {
                echo "<p>No office spaces available.</p>";
            }
            ?>
        </div>
        <p><a href="logout.php">Logout</a></p>
    </div>
</body>
</html>

<?php
$conn->close();
?>

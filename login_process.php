<?php
session_start();
$host = "localhost"; // Database host
$dbUsername = "zyfplieb_Web"; // Database username
$dbPassword = "GK4tQ7Z86E7NqYxuv25W"; // Database password
$dbName = "zyfplieb_Web"; // Database name

// Create connection
$conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch user from database
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $storedPassword);
    $stmt->fetch();

    // Compare plain text passwords
    if ($stmt->num_rows == 1 && $password === $storedPassword) {
        $_SESSION['user_id'] = $id;
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Invalid username or password.";
    }

    $stmt->close();
    $conn->close();
}
?>
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Ensure 'id' is received via GET request
if (isset($_GET['id'])) {
    $key_id = $_GET['id'];

    // Database credentials
    $host = "localhost";
    $dbUsername = "zyfplieb_Web";
    $dbPassword = "GK4tQ7Z86E7NqYxuv25W";
    $dbName = "zyfplieb_Web";

    // Connect to database
    $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Secure deletion query using key ID instead of key_value
    $stmt = $conn->prepare("DELETE FROM `keys` WHERE id = ?");
    $stmt->bind_param("i", $key_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Key deleted successfully!";
    } else {
        $_SESSION['error'] = "Failed to delete key.";
    }

    // Close connection
    $stmt->close();
    $conn->close();
}

// Redirect to dashboard with a success or error message
header("Location: dashboard.php");
exit();

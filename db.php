<?php
// Database connection credentials
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
?>

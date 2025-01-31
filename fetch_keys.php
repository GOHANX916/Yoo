<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

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

// Fetch keys from the `keys` table (use backticks around the table name)
$sql = "SELECT key_value, expiry_date, device_limit FROM `keys`";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['key_value']}</td>
                <td>{$row['expiry_date']}</td>
                <td>{$row['device_limit']}</td>
                <td><a href='delete_key.php?key={$row['key_value']}'>Delete</a></td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='4'>No keys generated yet.</td></tr>";
}

$conn->close();
?>
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $days_to_expire = $_POST['expiry_days'];
    $device_limit = $_POST['device_limit'];
    $custom_key = trim($_POST['custom_key']);

    // Validate expiry days
    if ($days_to_expire <= 0) {
        $error = "Expiry days must be a positive number.";
    }

    // Validate device limit
    if (!in_array($device_limit, [1, 99])) {
        $error = "Invalid device limit.";
    }

    if (!isset($error)) {
        // Calculate expiry date based on days
        $expiry_date = date('Y-m-d', strtotime("+$days_to_expire days"));

        // Generate custom key if provided, otherwise generate a random key
        $key_value = empty($custom_key) ? bin2hex(random_bytes(16)) : $custom_key;

        // Database connection
        include 'db.php';
        $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("INSERT INTO `keys` (key_value, expiry_date, device_limit) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $key_value, $expiry_date, $device_limit);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Key generated successfully!";
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Failed to generate key. Please try again.";
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Key</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 350px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #2c3e50;
        }

        label {
            display: block;
            margin: 8px 0 4px;
            color: #2c3e50;
        }

        input, select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 16px;
            color: #333;
        }

        /* Expiry Days field (half width) */
        #expiry_days {
            width: 48%;
            display: inline-block;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #2980b9;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #3498db;
        }

        .error {
            color: #e74c3c;
            text-align: center;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .success {
            color: #28a745;
            text-align: center;
            margin-bottom: 15px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Generate Key</h1>
        
        <?php if (isset($error)): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="success"><?= $_SESSION['message'] ?></div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <form action="generate_key.php" method="POST">
            <label for="expiry_days">Expiry Days:</label>
            <input type="number" id="expiry_days" name="expiry_days" required min="1">

            <label for="device_limit">Device Limit:</label>
            <select id="device_limit" name="device_limit" required>
                <option value="1">1</option>
                <option value="99">99</option>
            </select>

            <label for="custom_key">Custom Key (optional):</label>
            <input type="text" id="custom_key" name="custom_key" placeholder="Enter custom key">

            <button type="submit">Generate Key</button>
        </form>
    </div>

</body>
</html>

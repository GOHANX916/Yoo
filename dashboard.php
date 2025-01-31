<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Include database connection
include 'db.php';
$conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle search functionality
$search_key = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search_key = trim($_POST['search_key']);
}

// Fetch all keys or search for a specific key
if (!empty($search_key)) {
    $stmt = $conn->prepare("SELECT * FROM `keys` WHERE key_value LIKE ?");
    $search_param = "%$search_key%";
    $stmt->bind_param("s", $search_param);
} else {
    $stmt = $conn->prepare("SELECT * FROM `keys`");
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        /* Base Styling */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f4f8;
            color: #333;
            margin: 0;
            padding: 0;
        }

        /* Hamburger Menu Styling */
        .hamburger {
            cursor: pointer;
            width: 30px;
            height: 25px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background: transparent;
            border: none;
            padding: 0;
            z-index: 10;
            transition: transform 0.3s ease;
        }

        .hamburger div {
            height: 4px;
            width: 100%;
            background-color: #333;
            border-radius: 2px;
            transition: transform 0.3s ease, opacity 0.3s ease;
        }

        .hamburger.active div:nth-child(1) {
            transform: rotate(45deg);
            position: relative;
            top: 7px;
        }

        .hamburger.active div:nth-child(2) {
            opacity: 0;
        }

        .hamburger.active div:nth-child(3) {
            transform: rotate(-45deg);
            position: relative;
            top: -7px;
        }

        /* Sidebar Menu Styling */
        .menu {
            background-color: #2C3E50; /* Deep Blue */
            position: absolute;
            top: 60px;
            left: -200px;
            width: 200px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1;
            transition: left 0.3s ease;
        }

        .menu a {
            display: block;
            padding: 15px;
            color: #ecf0f1; /* Light Gray */
            text-decoration: none;
            border-bottom: 1px solid #34495E; /* Slightly darker gray */
        }

        .menu a:hover {
            background-color: #2980B9; /* Light Blue */
        }

        .menu.active {
            left: 0;
        }

        /* Page Title Styling */
        h1 {
            text-align: center;
            padding: 20px;
            font-size: 2rem;
            color: #2C3E50; /* Deep Blue */
        }

        /* Search Box Styling */
        .search-box {
            text-align: center;
            margin-bottom: 20px;
        }

        input[type="text"] {
            padding: 10px;
            width: 40%;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            padding: 10px 15px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* Table Styling */
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: white;
        }

        th, td {
            padding: 15px;
            border: 1px solid #ddd;
            text-align: center;
            font-size: 1rem;
        }

        th {
            background-color: #2980B9; /* Light Blue */
            color: white;
        }

        td {
            color: #34495E; /* Dark Gray */
        }

        /* Action Button Styling */
        a {
            color: #E74C3C; /* Red Accent */
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Not Found Message */
        .not-found {
            color: red;
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
        }

        /* Responsive Design */
        @media screen and (max-width: 600px) {
            table {
                width: 100%;
            }

            .hamburger {
                font-size: 28px;
                padding: 12px;
            }

            h1 {
                font-size: 1.5rem;
            }

            input[type="text"] {
                width: 70%;
            }
        }
    </style>
</head>
<body>

    <!-- Hamburger Button -->
    <div class="hamburger" onclick="toggleMenu()">
        <div></div>
        <div></div>
        <div></div>
    </div>

    <!-- Sidebar Menu -->
    <div class="menu" id="menu">
        <a href="generate_key.php">Generate Key</a>
        <a href="logout.php">Log Out</a>
    </div>

    <!-- Page Title -->
    <h1>All Keys</h1>

    <!-- Search Box -->
    <form method="POST" action="dashboard.php" class="search-box">
        <input type="text" name="search_key" placeholder="Search for a key..." value="<?= htmlspecialchars($search_key) ?>">
        <button type="submit">Search</button>
    </form>

    <!-- Keys Table -->
    <table>
        <tr>
            <th>Key</th>
            <th>Expiry Date</th>
            <th>Device Limit</th>
            <th>Action</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['key_value']) ?></td>
                    <td><?= htmlspecialchars($row['expiry_date']) ?></td>
                    <td><?= htmlspecialchars($row['device_limit']) ?></td>
                    <td><a href="delete_key.php?id=<?= $row['id'] ?>" style="color: red;">Delete</a></td>

                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="4" class="not-found">Key not found</td>
            </tr>
        <?php endif; ?>
    </table>

    <!-- JavaScript for Toggling Menu -->
    <script>
        function toggleMenu() {
            const menu = document.getElementById('menu');
            const hamburger = document.querySelector('.hamburger');
            menu.classList.toggle('active');
            hamburger.classList.toggle('active');
        }
    </script>

</body>
</html>

<?php $stmt->close(); $conn->close(); ?>

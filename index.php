
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        @keyframes slideIn {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        @keyframes float {
            0% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
            100% {
                transform: translateY(0);
            }
        }
        .fade-in {
            animation: fadeIn 1s ease-in-out;
        }
        .slide-in {
            animation: slideIn 1s ease-in-out;
        }
        .float {
            animation: float 3s ease-in-out infinite;
        }
    </style>
</head>
<body class="bg-black flex items-center justify-center h-screen">
    <div class="bg-gray-800 p-8 rounded-lg shadow-lg w-full max-w-md fade-in">
        <div class="text-center mb-6">
            <img alt="Company logo, a stylized representation of the company initials" class="mx-auto mb-4 w-20 h-20 float" src="photo.png"/>
            <h1 class="text-3xl font-bold text-white slide-in">
                Welcome
            </h1>
        </div>
        <form action="login_process.php" method="POST" class="slide-in">
            <div class="mb-4">
                <label class="block text-gray-300 font-semibold" for="username">
                    Username:
                </label>
                <input class="w-full px-2 py-1 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-700 text-white" id="username" name="username" required type="text"/>
            </div>
            <div class="mb-6">
                <label class="block text-gray-300 font-semibold" for="password">
                    Password:
                </label>
                <input class="w-full px-2 py-1 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-700 text-white" id="password" name="password" required type="password"/>
            </div>
            <button class="w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white py-2 rounded-lg hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500" type="submit">
                Login
            </button>
        </form>
    </div>
</body>
</html>

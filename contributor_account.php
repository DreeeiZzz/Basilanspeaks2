<?php
require 'db.php'; // Include your DB connection file

session_start();

// Ensure that the user is an admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_signin.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];

// Initialize error message variables
$error_message = '';
$success_message = '';

// Handle form submission to create a new contributor account
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user input
    $full_name = htmlspecialchars($_POST['full_name']);
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    
    // Basic validation
    if (empty($full_name) || empty($username) || empty($password)) {
        $error_message = "All fields are required!";
    } else {
        try {
            // Check if the username already exists
            $stmt = $db->prepare("SELECT id FROM contributors WHERE username = :username");
            $stmt->execute(['username' => $username]);
            $existing_user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($existing_user) {
                $error_message = "Username already exists!";
            } else {
                // Hash the password before storing it
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                
                // Insert new contributor account into the database
                $stmt = $db->prepare("INSERT INTO contributors (full_name, username, password, created_at) VALUES (:full_name, :username, :password, NOW())");
                $stmt->execute([
                    'full_name' => $full_name,
                    'username' => $username,
                    'password' => $hashed_password
                ]);

                $success_message = "Contributor account created successfully!";
            }
        } catch (PDOException $e) {
            $error_message = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Contributor Account</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500&display=swap" rel="stylesheet">
    <style>
        /* Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Roboto', sans-serif;
            background: #f2f2f2;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }
        /* Header Styles */
        header {
            background: #333;
            color: #fff;
            padding: 20px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        header h1 {
            font-size: 24px;
        }
        header nav ul {
            list-style: none;
            display: flex;
            gap: 15px;
        }
        header nav ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            transition: color 0.3s ease;
        }
        header nav ul li a:hover {
            color: #f39c12;
        }
        /* Container Styles */
        .container {
            max-width: 500px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        /* Form Styles */
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        form label {
            font-weight: bold;
            margin-bottom: 5px;
        }
        form input[type="text"],
        form input[type="password"] {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        form input[type="submit"] {
            padding: 10px;
            border: none;
            background: #333;
            color: #fff;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        form input[type="submit"]:hover {
            background: #f39c12;
        }
        /* Message Styles */
        .error {
            background: #ffe5e5;
            color: #d8000c;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .success {
            background: #e5ffe5;
            color: #4F8A10;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        /* Responsive Styles */
        @media (max-width: 600px) {
            header {
                flex-direction: column;
                text-align: center;
            }
            header nav ul {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Create Contributor Account</h1>
        <nav>
            <ul>
                <li><a href="admin_dashboard.php">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h2>Create a New Contributor Account</h2>
        
        <?php if ($error_message): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <?php if ($success_message): ?>
            <div class="success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="contributor_account.php">
            <label for="full_name">Full Name:</label>
            <input type="text" name="full_name" id="full_name" required>
            
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>
            
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
            
            <input type="submit" value="Create Contributor Account">
        </form>
    </div>
</body>
</html>

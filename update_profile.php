<?php
require 'db.php'; // Include your DB connection file

// Start session to track the logged-in user
session_start();

// Check if the user is logged in
if (!isset($_SESSION['admin_id'])) {
    // If the user is not logged in, redirect to the signin page
    header("Location: admin_signin.php");
    exit(); // Stop further script execution
}
// Fetch admin details based on the user_id from the session
$admin_id = $_SESSION['user_id'];
$stmt = $db->prepare("SELECT id, username FROM admins WHERE id = :id");
$stmt->execute(['id' => $admin_id]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle case where admin is not found
if (!$admin) {
    // Destroy the session and force login
    session_destroy();
    header("Location: admin_signin.php");
    exit();
}

// Update profile if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_username = $_POST['username'];
    $new_password = $_POST['password'];
    
    // Validate input (add more checks as necessary)
    if (empty($new_username) || empty($new_password)) {
        $error = "Both fields are required.";
    } else {
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        // Update the admin's profile in the database
        $update_query = "UPDATE admins SET username = :username, password = :password WHERE id = :id";
        $update_stmt = $db->prepare($update_query);
        $update_stmt->execute([
            'username' => $new_username,
            'password' => $hashed_password,
            'id' => $admin_id
        ]);
        
        // Set success message
        $success_message = "Profile updated successfully!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile - Admin Dashboard</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: url('./uploads/translator.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            margin: 0;
            padding: 0;
        }

        header {
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }

        header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            gap: 20px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            padding: 10px 20px;
            border-radius: 20px;
            transition: background-color 0.3s, transform 0.2s;
        }

        nav ul li a:hover {
            background: #f39c12;
            transform: scale(1.1);
        }

        .container {
            margin-top: 100px;
            padding: 30px;
            text-align: center;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
            border-radius: 12px;
            max-width: 800px;
            margin: 120px auto;
        }

        .container h2 {
            color: #333;
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .container form {
            display: flex;
            flex-direction: column;
            gap: 20px;
            align-items: center;
        }

        .container input {
            padding: 10px;
            width: 80%;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 16px;
        }

        .container button {
            padding: 12px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .container button:hover {
            background: #2980b9;
        }

        .error {
            color: red;
            font-size: 16px;
        }

        .success {
            color: green;
            font-size: 16px;
        }

        footer {
            background: rgba(0, 0, 0, 0.8);
            color: white;
            text-align: center;
            padding: 15px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
            font-size: 16px;
        }

        footer a {
            color: #f39c12;
            text-decoration: none;
            font-weight: bold;
        }

        footer a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .container {
                width: 90%;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>BASILAN SPEAKS - Admin Dashboard</h1>
        <nav>
            <ul>
            <li><a href="admin.php">Add Translation</a></li>
                <li><a href="add_history.php">Add History</a></li>
                <li><a href="add_dictionary.php">Add Dictionary</a></li>
                <li><a href="receive_feedback.php">Feedback</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h2>Update Your Profile</h2>

        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if (isset($success_message)): ?>
            <div class="success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="text" name="username" value="<?php echo htmlspecialchars($admin['username']); ?>" placeholder="New Username" required>
            <input type="password" name="password" placeholder="New Password" required>
            <button type="submit">Update Profile</button>
        </form>
    </div>

    <footer>
        &copy; <?php echo date("Y"); ?> Basilan Speaks. Made with ❤️ by Your Team. | <a href="privacy.php">Privacy Policy</a>
    </footer>
</body>
</html>

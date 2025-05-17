<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require 'db.php'; // this defines $db (PDO connection)

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        try {
            // Check for duplicate username
            $stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$username]);

            if ($stmt->rowCount() > 0) {
                $error = "Username is already taken.";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $db->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
                $stmt->execute([$username, $hashed_password]);

                header("Location: signin.php?message=Signup successful. Please log in.");
                exit();
            }
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Signup - Basilan Speaks</title>
  <style>
    * { box-sizing: border-box; }

    body {
      font-family: Arial, sans-serif;
      background: url('./uploads/translator.jpg') no-repeat center center fixed;
      background-size: cover;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }

    .container {
      background-color: rgba(255, 255, 255, 0.95);
      padding: 30px;
      width: 90%;
      max-width: 400px;
      border-radius: 10px;
      text-align: center;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    header {
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 20px;
    }

    header img {
      width: 50px;
      margin-right: 10px;
    }

    header h1 {
      font-size: 24px;
      margin: 0;
      color: #333;
    }

    input, button {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      font-size: 16px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }

    button {
      background-color: #5cb85c;
      color: white;
      font-weight: bold;
      border: none;
      cursor: pointer;
    }

    button:hover {
      background-color: #4cae4c;
    }

    .error {
      color: red;
      font-size: 14px;
      margin-bottom: 10px;
    }

    a {
      font-size: 14px;
      color: #f39c12;
      text-decoration: none;
    }

    a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="container">
    <header>
      <img src="./uploads/Logo Basilan speaks.png" alt="Logo">
      <h1>Basilan Speaks</h1>
    </header>

    <h2>Sign Up</h2>

    <?php if (!empty($error)): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <input type="password" name="confirm_password" placeholder="Confirm Password" required>
      <button type="submit">Sign Up</button>
    </form>

    <p>Already have an account? <a href="signin.php">Log In</a></p>
  </div>
</body>
</html>

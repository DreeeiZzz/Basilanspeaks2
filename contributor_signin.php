<?php
require 'db.php'; // Include your DB connection file
session_start();

if (isset($_SESSION['contributor_id'])) {
    header("Location: contributor_dashboard.php");
    exit();
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        $stmt = $db->prepare("SELECT * FROM contributors WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $contributor = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($contributor && password_verify($password, $contributor['password'])) {
            $_SESSION['contributor_id'] = $contributor['id'];
            header("Location: contributor_dashboard.php");
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contributor Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('./uploads/translator.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 400px;
            text-align: center;
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            background-color: #5cb85c;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #4cae4c;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }

        header img {
            width: 100px;
            height: auto;
            margin-bottom: 10px;
        }

        header h1 {
            font-size: 2em;
            margin-bottom: 20px;
        }

        p {
            font-size: 14px;
        }

        p a {
            color: #007bff;
            text-decoration: none;
        }

        p a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <img src="./uploads/Logo Basilan speaks.png" alt="Basilan Speaks Logo">
            <h1>Contributor Login</h1>
        </header>
        <h2>Login</h2>
        <?php if (!empty($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form action="" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>

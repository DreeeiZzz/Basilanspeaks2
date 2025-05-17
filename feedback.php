<?php
session_start();
require 'db.php'; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID
$message = "";

// Handle feedback submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['feedback'])) {
    $feedback = htmlspecialchars(trim($_POST['feedback'])); // Sanitize the input

    if (!empty($feedback)) {
        try {
            $stmt = $db->prepare("INSERT INTO feedbacks (user_id, message) VALUES (:user_id, :message)");
            $stmt->execute(['user_id' => $user_id, 'message' => $feedback]);
            $message = "Your feedback has been submitted successfully!";
        } catch (PDOException $e) {
            $message = "Error: " . $e->getMessage();
        }
    } else {
        $message = "Feedback message cannot be empty.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Feedback</title>
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

        .container p {
            color: #555;
            font-size: 20px;
            line-height: 1.5;
        }

        textarea {
            width: 100%;
            height: 150px;
            padding: 10px;
            border: 2px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            resize: none;
            margin-bottom: 15px;
        }

        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            align-self: flex-end;
        }

        button:hover {
            background-color: #0056b3;
        }

        .message {
            text-align: center;
            margin-top: 15px;
            font-size: 16px;
            color: green;
        }

        .error {
            color: red;
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
            header h1 {
                font-size: 24px;
            }

            nav ul {
                flex-direction: column;
                align-items: center;
            }

            .container {
                width: 90%;
                padding: 20px;
            }

            button {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>BASILAN SPEAKS</h1>
        <nav>
            <ul>
                <li><a href="dictionary.php">Dictionary</a></li>
                <li><a href="history.php">History</a></li>
                <li><a href="translate.php">Translation</a></li>
                <li><a href="feedback.php">Feedback</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h2>Send Feedback</h2>
        <form method="POST" action="feedback.php">
            <textarea name="feedback" placeholder="Write your feedback here..." required></textarea>
            <button type="submit">Submit Feedback</button>
        </form>
        <?php if (!empty($message)): ?>
            <p class="message <?php echo strpos($message, 'Error') === 0 ? 'error' : ''; ?>">
                <?php echo $message; ?>
            </p>
        <?php endif; ?>
    </div>

    <footer>
        &copy; <?php echo date("Y"); ?> Basilan Speaks. Made with ❤️ by Your Team. | <a href="privacy.php">Privacy Policy</a>
    </footer>
</body>
</html>

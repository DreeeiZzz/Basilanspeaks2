<?php
require 'db.php'; // Include your DB connection file
session_start();  // Start session to track logged-in user

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: signin.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$recent_entries = [];

// Fetch recent dictionary searches for the logged-in user
try {
    $stmt = $db->prepare("SELECT de.yakan_word, de.pilipino_word, de.english_word, rds.search_timestamp 
                          FROM recent_dictionary_searches rds
                          JOIN dictionary_entries de ON rds.dictionary_entry_id = de.id
                          WHERE rds.user_id = :user_id
                          ORDER BY rds.search_timestamp DESC
                          LIMIT 10");  // Limit to the last 10 searches
    $stmt->execute(['user_id' => $user_id]);
    $recent_entries = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recent Dictionary Searches</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: url('./uploads/translator.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
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
            color: white;
            transform: scale(1.1);
        }

        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 1000px;
            margin-top: 100px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .entry-card {
            background-color: #fff;
            margin: 20px 0;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, background-color 0.2s;
        }

        .entry-card:hover {
            transform: scale(1.05);
            background-color: #f7f7f7;
        }

        .entry-card h3 {
            color: #007bff;
        }

        .entry-card p {
            color: #555;
            font-size: 16px;
        }

        .no-results {
            text-align: center;
            font-size: 18px;
            color: #ff6347;
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
                width: 95%;
            }

            .entry-card {
                padding: 15px;
            }

            footer {
                font-size: 14px;
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
        <h1>Recent Dictionary Searches</h1>

        <?php if (!empty($recent_entries)): ?>
            <h2>Recent Searches:</h2>
            <?php foreach ($recent_entries as $entry): ?>
                <div class="entry-card">
                    <h3><?php echo htmlspecialchars($entry['yakan_word']); ?></h3>
                    <p><strong>Pilipino:</strong> <?php echo htmlspecialchars($entry['pilipino_word']); ?></p>
                    <p><strong>English:</strong> <?php echo htmlspecialchars($entry['english_word']); ?></p>
                    <p><strong>Searched on:</strong> <?php echo htmlspecialchars($entry['search_timestamp']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-results">No recent searches found.</p>
        <?php endif; ?>
    </div>

    <footer>
        &copy; <?php echo date("Y"); ?> Basilan Speaks. Made with ❤️ by Your Team. | <a href="privacy.php">Privacy Policy</a>
    </footer>
</body>
</html>

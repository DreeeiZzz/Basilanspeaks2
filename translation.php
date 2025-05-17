<?php
require 'db.php'; // Ensure this is correctly configured for your DB
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

// Fetch categories
$categories = ['school', 'offices', 'market', 'barangay', 'police station', 'port', 'city hall'];

// Fetch filtered sentences
$filter = $_GET['category'] ?? ''; // Default is no filter
$sentences = [];
if ($filter) {
    $stmt = $db->prepare("
        SELECT e.id, e.english_sentence, y.audio_path
        FROM english_sentences e
        INNER JOIN yakan_sentences y ON e.id = y.english_sentence_id
        WHERE e.is_validated = 1 AND y.is_validated = 1 AND e.category = ?
    ");
    $stmt->execute([$filter]);
    $sentences = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $stmt = $db->query("
        SELECT e.id, e.english_sentence, y.audio_path
        FROM english_sentences e
        INNER JOIN yakan_sentences y ON e.id = y.english_sentence_id
        WHERE e.is_validated = 1 AND y.is_validated = 1
    ");
    $sentences = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filtered Sentences</title>
    <style>
        /* General styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
            position: relative;
        }

        .container {
            width: 90%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #4CAF50;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
        }

        select, button {
            padding: 10px;
            font-size: 16px;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            max-width: 300px;
        }

        button {
            background-color: #4CAF50;
            color: #fff;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            background-color: #fff;
            margin: 10px 0;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        li button {
            margin-left: 10px;
        }

        audio {
            display: block;
            width: 100%;
            margin-top: 20px;
        }

        /* Positioning the Back Button */
        .back-button {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 10; /* To ensure it's on top of other elements */
        }

        /* Responsive design */
        @media (max-width: 600px) {
            h1 {
                font-size: 24px;
            }

            li {
                flex-direction: column;
                align-items: flex-start;
            }

            li button {
                margin-left: 0;
                margin-top: 10px;
            }

            /* Adjust the back button for mobile */
            .back-button {
                position: fixed;
                top: 15px;
                right: 15px;
                width: 90%;  /* Make it more mobile-friendly */
                max-width: 300px;
                margin: 0 auto;
                display: block;
            }

            .back-button button {
                width: 100%;
                padding: 12px 0;
                font-size: 18px;
            }
        }
    </style>
</head>
<body>
    <!-- Back Button in top-right corner -->
    <div class="back-button">
        <a href="homepage.php">
            <button type="button" style="background-color: #f44336; color: white; border: none; border-radius: 5px; cursor: pointer;">
                Back to Homepage
            </button>
        </a>
    </div>

    <div class="container">
        <h1>Filter and Play Translations</h1>

        <!-- Filter Form -->
        <form method="GET" action="">
            <label for="category">Filter by Category:</label>
            <select name="category" id="category">
                <option value="">All</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= htmlspecialchars($category) ?>" <?= $category === $filter ? 'selected' : '' ?>>
                        <?= htmlspecialchars(ucfirst($category)) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Filter</button>
        </form>

        <h2>English Sentences</h2>
        <?php if (count($sentences) > 0): ?>
            <ul>
                <?php foreach ($sentences as $sentence): ?>
                    <li>
                        <?= htmlspecialchars($sentence['english_sentence']) ?>
                        <?php if (!empty($sentence['audio_path'])): ?>
                            <button onclick="playAudio('<?= htmlspecialchars($sentence['audio_path']) ?>')">Play Audio</button>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No sentences found for the selected category.</p>
        <?php endif; ?>

        <!-- Audio Player -->
        <audio id="audioPlayer" controls style="display: none;"></audio>
        <script>
            function playAudio(audioPath) {
                const audioPlayer = document.getElementById('audioPlayer');
                audioPlayer.src = audioPath;
                audioPlayer.style.display = 'block';
                audioPlayer.play();
            }
        </script>
    </div>
</body>
</html>

<?php
require 'db.php';

$categories = ['school', 'offices', 'market', 'barangay', 'police station', 'port', 'city hall'];
$filter = $_GET['category'] ?? '';
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
    <title>Filtered Sentences - Basilan Speaks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: url('./uploads/translator.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            margin: 0;
            padding-top: 70px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header {
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 10px 20px;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }

        .navbar-nav .nav-link {
            color: white;
            margin-left: 15px;
            font-size: 16px;
            transition: color 0.3s;
        }

        .navbar-nav .nav-link:hover {
            color: #f39c12;
        }

        .container-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
            border-radius: 12px;
            margin: 30px auto;
            padding: 30px;
            width: 90%;
            max-width: 900px;
        }

        .sentence-list li {
            background: white;
            margin: 10px 0;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0px 3px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-play {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-play:hover {
            background-color: #218838;
        }

        footer {
            background: rgba(0, 0, 0, 0.8);
            color: white;
            text-align: center;
            padding: 15px 0;
            font-size: 14px;
            margin-top: auto;
        }

        footer a {
            color: #f39c12;
            text-decoration: none;
            font-weight: 500;
        }

        footer a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .navbar-nav {
                background: rgba(0, 0, 0, 0.9);
                padding: 10px;
                border-radius: 8px;
            }

            .sentence-list li {
                flex-direction: column;
                align-items: flex-start;
            }

            .btn-play {
                margin-top: 10px;
            }
        }
    </style>
</head>
<body>

<header>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="index.php">BASILAN SPEAKS</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="no_dictionary.php">Dictionary</a></li>
                    <li class="nav-item"><a class="nav-link" href="no_history.php">History</a></li>
                    <li class="nav-item"><a class="nav-link" href="no_translate.php">Translation</a></li>
                    <li class="nav-item"><a class="nav-link" href="selection.php">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<main class="container-main">
    <h1 class="mb-4 text-center">Filter and Play Translations</h1>

    <form method="GET" class="mb-4">
        <div class="mb-3">
            <label for="category" class="form-label fw-bold">Filter by Category:</label>
            <select name="category" id="category" class="form-select">
                <option value="">All</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= htmlspecialchars($category) ?>" <?= $category === $filter ? 'selected' : '' ?>>
                        <?= htmlspecialchars(ucfirst($category)) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    <h2 class="mb-3">English Sentences</h2>

    <?php if (count($sentences) > 0): ?>
        <ul class="sentence-list list-unstyled">
            <?php foreach ($sentences as $sentence): ?>
                <li>
                    <div><?= htmlspecialchars($sentence['english_sentence']) ?></div>
                    <?php if (!empty($sentence['audio_path'])): ?>
                        <button class="btn-play" onclick="playAudio('<?= htmlspecialchars($sentence['audio_path']) ?>')">Play Audio</button>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No sentences found for the selected category.</p>
    <?php endif; ?>

    <audio id="audioPlayer" controls style="display: none; margin-top: 20px;"></audio>
    <script>
        function playAudio(audioPath) {
            const audioPlayer = document.getElementById('audioPlayer');
            audioPlayer.src = audioPath;
            audioPlayer.style.display = 'block';
            audioPlayer.play();
        }
    </script>
</main>

<footer>
    &copy; <?php echo date("Y"); ?> Basilan Speaks. Made with ❤️ by Your Team. | <a href="privacy.php">Privacy Policy</a>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php
session_start();
require 'db.php';

// Function to log search
function log_search($user_id, $dictionary_entry_id, $db) {
    $stmt = $db->prepare("INSERT INTO recent_dictionary_searches (user_id, dictionary_entry_id, search_timestamp) VALUES (:user_id, :dictionary_entry_id, NOW())");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':dictionary_entry_id', $dictionary_entry_id);
    $stmt->execute();
}

// Fetch entries
$entries = [];
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = "%" . $_GET['search'] . "%";
    $stmt = $db->prepare("
        SELECT de.*, c.full_name AS contributor_name, v.full_name AS validator_name
        FROM dictionary_entries de
        LEFT JOIN contributors c ON de.submitted_by_contributor_id = c.id
        LEFT JOIN validators v ON de.validated_by_validator_id = v.id
        WHERE de.yakan_word LIKE :search OR de.pilipino_word LIKE :search OR de.english_word LIKE :search
    ");
    $stmt->execute(['search' => $search]);
    $entries = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($entries && isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        foreach ($entries as $entry) {
            log_search($user_id, $entry['id'], $db);
        }
    }
} else {
    $stmt = $db->query("
        SELECT de.*, c.full_name AS contributor_name, v.full_name AS validator_name
        FROM dictionary_entries de
        LEFT JOIN contributors c ON de.submitted_by_contributor_id = c.id
        LEFT JOIN validators v ON de.validated_by_validator_id = v.id
    ");
    $entries = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dictionary - Basilan Speaks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
            padding: 30px;
            margin: 30px auto;
            width: 90%;
            max-width: 1000px;
        }

        form {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin-bottom: 20px;
        }

        input[type="text"] {
            padding: 10px;
            width: 60%;
            margin-right: 10px;
            border-radius: 5px;
            border: 2px solid #ccc;
            font-size: 16px;
        }

        .btn-primary, .btn-secondary {
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            margin-top: 10px;
        }

        .entry-card {
            background: white;
            margin: 15px 0;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .entry-card h3 {
            color: #007bff;
        }

        .entry-card p {
            margin: 5px 0;
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

            form {
                flex-direction: column;
                align-items: center;
            }

            input[type="text"] {
                width: 100%;
                margin-bottom: 10px;
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
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<main class="container-main">
    <h1 class="mb-4 text-center">Dictionary Search</h1>

    <form method="get" action="">
        <input type="text" name="search" placeholder="Enter word to search" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" required>
        <button type="submit" class="btn btn-primary ms-2">Search</button>
        <a href="recent_dictionary.php" class="btn btn-secondary ms-2">Recent History</a>
        <a href="dictionary.php" class="btn btn-secondary ms-2">Show All Words</a>
    </form>

    <?php if (!empty($entries)): ?>
        <?php foreach ($entries as $entry): ?>
            <div class="entry-card">
                <h3><?= htmlspecialchars($entry['yakan_word']) ?></h3>
                <p><strong>Pilipino:</strong> <?= htmlspecialchars($entry['pilipino_word']) ?></p>
                <p><strong>English:</strong> <?= htmlspecialchars($entry['english_word']) ?></p>
                <p><strong>Synonyms:</strong> <?= htmlspecialchars($entry['synonyms']) ?></p>
                <p><strong>Examples:</strong> <?= htmlspecialchars($entry['examples']) ?></p>
                <p><strong>Contributor:</strong> <?= htmlspecialchars($entry['contributor_name']) ?></p>
                <p><strong>Validator:</strong> <?= $entry['validator_name'] ? htmlspecialchars($entry['validator_name']) : 'Not Validated'; ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-center text-danger fw-bold">No results found.</p>
    <?php endif; ?>
</main>

<footer>
    &copy; <?php echo date("Y"); ?> Basilan Speaks. Made with ❤️ by Your Team. | <a href="privacy.php">Privacy Policy</a>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

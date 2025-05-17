<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: signin.php");
    exit();
}

require 'db.php'; // Include your DB connection file

// Function to log the search into the recent_dictionary_searches table
function log_search($user_id, $dictionary_entry_id, $db) {
    $stmt = $db->prepare("INSERT INTO recent_dictionary_searches (user_id, dictionary_entry_id, search_timestamp) 
                           VALUES (:user_id, :dictionary_entry_id, NOW())");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':dictionary_entry_id', $dictionary_entry_id);
    $stmt->execute();
}

// Search functionality
$entries = [];
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = "%" . htmlspecialchars($_GET['search']) . "%";  // Prepare search term
    try {
        $stmt = $db->prepare("
            SELECT 
                de.*,
                c.full_name AS contributor_name,
                v.full_name AS validator_name
            FROM dictionary_entries de
            LEFT JOIN contributors c ON de.submitted_by_contributor_id = c.id
            LEFT JOIN validators v ON de.validated_by_validator_id = v.id
            WHERE de.yakan_word LIKE :search 
               OR de.pilipino_word LIKE :search 
               OR de.english_word LIKE :search
        ");
        $stmt->execute(['search' => $search]);
        $entries = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch results as associative arrays

        // Log search for each dictionary entry found
        if ($entries) {
            $user_id = $_SESSION['user_id']; // Get the logged-in user's ID
            foreach ($entries as $entry) {
                log_search($user_id, $entry['id'], $db); // Log each search
            }
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // If no search term is provided, fetch all entries
    try {
        $stmt = $db->prepare("
            SELECT 
                de.*,
                c.full_name AS contributor_name,
                v.full_name AS validator_name
            FROM dictionary_entries de
            LEFT JOIN contributors c ON de.submitted_by_contributor_id = c.id
            LEFT JOIN validators v ON de.validated_by_validator_id = v.id
        ");
        $stmt->execute();
        $entries = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all entries
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dictionary</title>
    <style>
        /* Your existing styles here */
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

        form {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        input[type="text"] {
            padding: 10px;
            width: 60%;
            margin-right: 10px;
            border: 2px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        button {
            padding: 10px 20px;
            border: none;
            background-color: #007bff;
            color: white;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
        }

        button:hover {
            background-color: #0056b3;
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

            input[type="text"] {
                width: 80%;
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
        <h1>Dictionary Search</h1>

        <!-- Search Form -->
        <form method="get" action="dictionary.php">
            <input type="text" name="search" placeholder="Enter word to search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" required>
            <button type="submit">Search</button>
        </form>

        <!-- Buttons for Recent History and Show All -->
        <div class="buttons">
            <a href="recent_dictionary.php"><button>Recent History</button></a>
            <a href="dictionary.php"><button>Show All Words</button></a>
        </div>

        <!-- Display Results -->
        <?php if (!empty($entries)): ?>
            <h2>Dictionary Entries:</h2>
            <?php foreach ($entries as $entry): ?>
                <div class="entry-card">
                    <h3><?php echo htmlspecialchars($entry['yakan_word']); ?></h3>
                    <p><strong>Pilipino:</strong> <?php echo htmlspecialchars($entry['pilipino_word']); ?></p>
                    <p><strong>English:</strong> <?php echo htmlspecialchars($entry['english_word']); ?></p>
                    <p><strong>Synonyms:</strong> <?php echo htmlspecialchars($entry['synonyms']); ?></p>
                    <p><strong>Examples:</strong> <?php echo htmlspecialchars($entry['examples']); ?></p>
                    <p><strong>Contributor:</strong> <?php echo htmlspecialchars($entry['contributor_name']); ?></p>
                    <p><strong>Validator:</strong> <?php echo $entry['validator_name'] ? htmlspecialchars($entry['validator_name']) : 'Not Validated'; ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-results">No results found</p>
        <?php endif; ?>
    </div>

    <footer>
        &copy; <?php echo date("Y"); ?> Basilan Speaks. Made with ❤️ by Your Team. | <a href="privacy.php">Privacy Policy</a>
    </footer>
</body>
</html>

<?php
require 'db.php'; // Ensure this is correctly configured for your DB
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch all validated English sentences
$stmt = $db->query("
    SELECT id, english_sentence, category 
    FROM english_sentences 
    WHERE is_validated = 1
    ORDER BY created_at DESC
");
$sentences = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle category assignment
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $sentenceId = $_POST['sentence_id'];
        $category = $_POST['category'];

        // Update the category in the database
        $updateStmt = $db->prepare("
            UPDATE english_sentences 
            SET category = ? 
            WHERE id = ?
        ");
        $updateStmt->execute([$category, $sentenceId]);

        $successMessage = "Category successfully assigned!";
    } catch (PDOException $e) {
        $errorMessage = "Error assigning category: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Category</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500&display=swap" rel="stylesheet">
    <style>
        /* Reset & Global Styles */
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
        /* Header & Navigation */
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
            max-width: 700px;
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
        form select,
        form button {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        form button {
            border: none;
            background: #333;
            color: #fff;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        form button:hover {
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
        <h1>Assign Category to English Sentences</h1>
        <nav>
            <ul>
                <li><a href="admin_dashboard.php">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h2>Assign Category to English Sentences</h2>
        
        <?php if (isset($successMessage)): ?>
            <div class="success"><?= htmlspecialchars($successMessage) ?></div>
        <?php endif; ?>

        <?php if (isset($errorMessage)): ?>
            <div class="error"><?= htmlspecialchars($errorMessage) ?></div>
        <?php endif; ?>

        <form method="POST">
            <label for="sentence_id">Select Sentence:</label>
            <select name="sentence_id" id="sentence_id" required>
                <option value="" disabled selected>-- Select a sentence --</option>
                <?php foreach ($sentences as $sentence): ?>
                    <option value="<?= $sentence['id'] ?>">
                        <?= htmlspecialchars($sentence['english_sentence']) ?> (Category: <?= htmlspecialchars($sentence['category'] ?? 'None') ?>)
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="category">Assign Category:</label>
            <select name="category" id="category" required>
                <option value="" disabled selected>-- Select a category --</option>
                <option value="School">School</option>
                <option value="Offices">Offices</option>
                <option value="Market">Market</option>
                <option value="Barangay">Barangay</option>
                <option value="Police Station">Police Station</option>
                <option value="Port">Port</option>
                <option value="City Hall">City Hall</option>
            </select>

            <button type="submit">Assign Category</button>
        </form>
    </div>
</body>
</html>

<?php
require 'db.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_signin.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $yakan_word = trim($_POST['yakan_word']);
    $pilipino_word = trim($_POST['pilipino_word']);
    $english_word = trim($_POST['english_word']);
    $synonyms = trim($_POST['synonyms']);
    $examples = trim($_POST['examples']);

    if (empty($yakan_word) || empty($pilipino_word) || empty($english_word)) {
        $error = 'Yakan, Pilipino, and English words are required.';
    } else {
        $stmt = $db->prepare("INSERT INTO dictionary_entries (yakan_word, pilipino_word, english_word, synonyms, examples) VALUES (:yakan_word, :pilipino_word, :english_word, :synonyms, :examples)");
        $stmt->execute([
            'yakan_word' => $yakan_word,
            'pilipino_word' => $pilipino_word,
            'english_word' => $english_word,
            'synonyms' => $synonyms,
            'examples' => $examples
        ]);
        $success = 'Dictionary entry added successfully!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Add Dictionary - Admin</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: url('./uploads/translator.jpg') no-repeat center center fixed;
      background-size: cover;
      margin: 0;
      padding: 0;
      color: #333;
    }

    header {
      background: rgba(0, 0, 0, 0.85);
      color: white;
      padding: 15px 20px;
      position: fixed;
      width: 100%;
      top: 0;
      z-index: 1000;
      box-shadow: 0 4px 6px rgba(0,0,0,0.3);
    }

    header h1 {
      margin: 0;
      font-size: 24px;
    }

    .container {
      margin: 120px auto 40px;
      max-width: 600px;
      background: rgba(255, 255, 255, 0.95);
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
      text-align: center;
    }

    h2 {
      margin-bottom: 20px;
    }

    form label {
      display: block;
      text-align: left;
      margin: 10px 0 5px;
      font-weight: bold;
    }

    input, textarea, button {
      width: 100%;
      padding: 10px;
      font-size: 16px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }

    button {
      background: #3498db;
      color: white;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    button:hover {
      background: #2980b9;
    }

    .success {
      color: green;
      margin-bottom: 15px;
    }

    .error {
      color: red;
      margin-bottom: 15px;
    }

    .back-btn {
      display: inline-block;
      background: #2ecc71;
      color: white;
      padding: 10px 20px;
      border-radius: 6px;
      text-decoration: none;
      font-weight: bold;
      margin-top: 10px;
      transition: background 0.3s;
    }

    .back-btn:hover {
      background: #27ae60;
    }

    footer {
      background: rgba(0, 0, 0, 0.85);
      color: white;
      text-align: center;
      padding: 15px 0;
      position: fixed;
      bottom: 0;
      width: 100%;
      font-size: 14px;
    }

    footer a {
      color: #f39c12;
      text-decoration: none;
      font-weight: 500;
    }

    footer a:hover {
      text-decoration: underline;
    }

    @media (max-width: 600px) {
      header h1 {
        font-size: 20px;
      }

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
  </header>

  <div class="container">
    <h2>Add New Dictionary Entry</h2>

    <?php if ($error): ?>
      <div class="error"><?= htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
      <div class="success"><?= htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <form method="POST">
      <label for="yakan_word">Yakan Word:</label>
      <input type="text" name="yakan_word" id="yakan_word" required>

      <label for="pilipino_word">Pilipino Word:</label>
      <input type="text" name="pilipino_word" id="pilipino_word" required>

      <label for="english_word">English Word:</label>
      <input type="text" name="english_word" id="english_word" required>

      <label for="synonyms">Synonyms:</label>
      <textarea name="synonyms" id="synonyms" rows="3"></textarea>

      <label for="examples">Examples:</label>
      <textarea name="examples" id="examples" rows="3"></textarea>

      <button type="submit">Add Entry</button>
    </form>

    <a href="admin_dashboard.php" class="back-btn">← Back to Dashboard</a>
  </div>

  <footer>
    &copy; <?= date("Y"); ?> Basilan Speaks. Made with ❤️ by Your Team. | <a href="privacy.php">Privacy Policy</a>
  </footer>
</body>
</html>

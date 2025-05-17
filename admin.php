<?php
require 'db.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_signin.php");
    exit();
}

$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $yakan = trim($_POST['yakan_sentence']);
    $english = trim($_POST['english_sentence']);
    $audioPath = null;

    if (!empty($_FILES['yakan_audio']['name'])) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $audioPath = $targetDir . basename($_FILES['yakan_audio']['name']);
        move_uploaded_file($_FILES['yakan_audio']['tmp_name'], $audioPath);
    }

    try {
        $stmt = $db->prepare("INSERT INTO english_sentences (english_sentence) VALUES (?)");
        $stmt->execute([$english]);
        $englishId = $db->lastInsertId();

        $stmt = $db->prepare("INSERT INTO yakan_sentences (yakan_sentence, audio_path, translation_id) VALUES (?, ?, ?)");
        $stmt->execute([$yakan, $audioPath, $englishId]);

        $successMessage = "Translation added successfully!";
    } catch (Exception $e) {
        $errorMessage = "Failed to add translation. Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Add Translation - Admin</title>
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
      top: 0;
      width: 100%;
      z-index: 1000;
      box-shadow: 0 4px 6px rgba(0,0,0,0.3);
    }

    header h1 {
      font-size: 24px;
      margin: 0;
    }

    .container {
      margin: 120px auto 40px;
      max-width: 600px;
      background: rgba(255, 255, 255, 0.95);
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    form label {
      display: block;
      margin: 10px 0 5px;
      font-weight: bold;
    }

    input[type="file"],
    textarea,
    button {
      width: 100%;
      padding: 12px;
      font-size: 16px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }

    textarea {
      resize: vertical;
    }

    button {
      background: #3498db;
      color: white;
      font-weight: bold;
      border: none;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    button:hover {
      background: #2980b9;
    }

    .success {
      color: green;
      font-size: 14px;
      margin-bottom: 10px;
    }

    .error {
      color: red;
      font-size: 14px;
      margin-bottom: 10px;
    }

    .back-btn {
      display: inline-block;
      background: #2ecc71;
      color: white;
      padding: 10px 20px;
      border-radius: 6px;
      text-decoration: none;
      font-weight: bold;
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
    <h2>Add Translation</h2>

    <?php if (!empty($successMessage)): ?>
      <div class="success"><?= htmlspecialchars($successMessage) ?></div>
    <?php endif; ?>
    <?php if (!empty($errorMessage)): ?>
      <div class="error"><?= htmlspecialchars($errorMessage) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
      <label for="yakan_sentence">Yakan Sentence:</label>
      <textarea name="yakan_sentence" id="yakan_sentence" rows="3" required></textarea>

      <label for="english_sentence">English Sentence:</label>
      <textarea name="english_sentence" id="english_sentence" rows="3" required></textarea>

      <label for="yakan_audio">Upload Yakan Audio (optional):</label>
      <input type="file" name="yakan_audio" id="yakan_audio" accept="audio/*">

      <button type="submit">Add Translation</button>
    </form>

    <!-- ✅ Centered Back Button -->
    <div style="text-align: center; margin-top: 20px;">
      <a href="admin_dashboard.php" class="back-btn">← Back to Dashboard</a>
    </div>
  </div>

  <footer>
    &copy; <?= date("Y"); ?> Basilan Speaks. Made with ❤️ by Your Team. | <a href="privacy.php">Privacy Policy</a>
  </footer>
</body>
</html>

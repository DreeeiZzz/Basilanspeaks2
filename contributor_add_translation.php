<?php
require 'db.php';
session_start();

if (!isset($_SESSION['contributor_id'])) {
    header("Location: contributor_signin.php");
    exit();
}

$contributor_id = $_SESSION['contributor_id'];
$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $english_sentence = htmlspecialchars($_POST['english_sentence']);
    $yakan_sentence = htmlspecialchars($_POST['yakan_sentence']);
    $audioPath = null;

    // Handle audio upload
    if (!empty($_FILES['yakan_audio']['name'])) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        // Avoid filename conflicts by prepending timestamp
        $fileName = time() . '_' . basename($_FILES['yakan_audio']['name']);
        $audioPath = $targetDir . $fileName;

        if (!move_uploaded_file($_FILES['yakan_audio']['tmp_name'], $audioPath)) {
            $error_message = "Audio upload failed!";
        }
    }

    try {
        $stmt = $db->prepare("INSERT INTO english_sentences (english_sentence, submitted_by_contributor_id) 
                              VALUES (:english_sentence, :contributor_id)");
        $stmt->execute(['english_sentence' => $english_sentence, 'contributor_id' => $contributor_id]);

        $english_sentence_id = $db->lastInsertId();

        $stmt = $db->prepare("INSERT INTO yakan_sentences (yakan_sentence, audio_path, submitted_by_contributor_id, english_sentence_id) 
                              VALUES (:yakan_sentence, :audio_path, :contributor_id, :english_sentence_id)");
        $stmt->execute([
            'yakan_sentence' => $yakan_sentence,
            'audio_path' => $audioPath,
            'contributor_id' => $contributor_id,
            'english_sentence_id' => $english_sentence_id
        ]);

        $success_message = "Translation submitted successfully!";
    } catch (PDOException $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Submit Translation - Basilan Speaks</title>
  <style>
    body {
      font-family: 'Arial', sans-serif;
      background: #f2f2f2;
      color: #333;
      padding: 20px;
      margin: 0;
    }

    header {
      background: #333;
      color: #fff;
      padding: 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      margin-bottom: 30px;
    }

    header h1 {
      font-size: 24px;
      margin: 0;
    }

    nav ul {
      list-style: none;
      display: flex;
      gap: 15px;
      padding: 0;
      margin: 0;
    }

    nav ul li a {
      color: #fff;
      text-decoration: none;
      font-size: 16px;
    }

    nav ul li a:hover {
      color: #f39c12;
    }

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

    form {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    label {
      font-weight: bold;
    }

    textarea, input[type="text"], input[type="file"] {
      padding: 10px;
      border-radius: 5px;
      border: 1px solid #ccc;
      font-size: 16px;
      width: 100%;
    }

    input[type="submit"] {
      padding: 12px;
      background: #333;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
    }

    input[type="submit"]:hover {
      background: #f39c12;
    }

    .error {
      background: #ffe5e5;
      color: #d8000c;
      padding: 10px;
      border-radius: 5px;
    }

    .success {
      background: #e5ffe5;
      color: #4F8A10;
      padding: 10px;
      border-radius: 5px;
    }

    @media (max-width: 600px) {
      header {
        flex-direction: column;
        text-align: center;
      }

      nav ul {
        flex-direction: column;
        gap: 10px;
        margin-top: 10px;
      }
    }
  </style>
</head>
<body>
  <header>
    <h1>Submit Translation</h1>
    <nav>
      <ul>
        <li><a href="contributor_dashboard.php">Dashboard</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </nav>
  </header>

  <div class="container">
    <h2>Submit Your Translation</h2>

    <?php if ($error_message): ?>
      <div class="error"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <?php if ($success_message): ?>
      <div class="success"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <form method="POST" action="contributor_add_translation.php" enctype="multipart/form-data">
      <label for="english_sentence">English Sentence:</label>
      <textarea name="english_sentence" id="english_sentence" rows="3" required></textarea>

      <label for="yakan_sentence">Yakan Sentence:</label>
      <textarea name="yakan_sentence" id="yakan_sentence" rows="3" required></textarea>

      <label for="yakan_audio">Upload Yakan Audio (optional):</label>
      <input type="file" name="yakan_audio" id="yakan_audio" accept="audio/*">

      <input type="submit" value="Submit Translation">
    </form>
  </div>
</body>
</html>

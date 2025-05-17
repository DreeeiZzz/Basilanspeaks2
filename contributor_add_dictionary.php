<?php
require 'db.php';
session_start();

if (!isset($_SESSION['contributor_id'])) {
    header("Location: contributor_signin.php");
    exit();
}

$contributor_id = $_SESSION['contributor_id'];
$feedback = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $yakan_word = trim($_POST['yakan_word']);
    $pilipino_word = trim($_POST['pilipino_word']);
    $english_word = trim($_POST['english_word']);
    $synonyms = trim($_POST['synonyms']);
    $examples = trim($_POST['examples']);

    if (empty($yakan_word) || empty($pilipino_word) || empty($english_word)) {
        $feedback = "<div class='error'>Please fill out all required fields.</div>";
    } else {
        try {
            $stmt = $db->prepare("
                INSERT INTO dictionary_entries 
                (yakan_word, pilipino_word, english_word, synonyms, examples, submitted_by_contributor_id) 
                VALUES (:yakan_word, :pilipino_word, :english_word, :synonyms, :examples, :contributor_id)
            ");
            $stmt->execute([
                'yakan_word' => $yakan_word,
                'pilipino_word' => $pilipino_word,
                'english_word' => $english_word,
                'synonyms' => $synonyms,
                'examples' => $examples,
                'contributor_id' => $contributor_id
            ]);
            $feedback = "<div class='success'>Dictionary entry submitted successfully!</div>";
        } catch (PDOException $e) {
            $feedback = "<div class='error'>Error: " . $e->getMessage() . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Submit Dictionary Entry - Basilan Speaks</title>
  <style>
    body {
      font-family: 'Arial', sans-serif;
      background: #f2f2f2;
      color: #333;
      margin: 0;
      padding: 20px;
    }

    header {
      background: #333;
      color: white;
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
      color: white;
      text-decoration: none;
      font-size: 16px;
    }

    nav ul li a:hover {
      color: #f39c12;
    }

    .container {
      max-width: 700px;
      margin: auto;
      background: white;
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

    form label {
      font-weight: bold;
    }

    input[type="text"],
    textarea {
      padding: 10px;
      font-size: 16px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }

    textarea {
      resize: vertical;
    }

    input[type="submit"] {
      padding: 12px;
      background: #333;
      color: white;
      border: none;
      font-size: 16px;
      border-radius: 5px;
      cursor: pointer;
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
    <h1>Submit Dictionary Entry</h1>
    <nav>
      <ul>
        <li><a href="contributor_dashboard.php">Dashboard</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </nav>
  </header>

  <div class="container">
    <h2>Submit Your Dictionary Entry</h2>

    <?= $feedback ?>

    <form method="POST" action="contributor_add_dictionary.php">
      <label for="yakan_word">Yakan Word <span style="color:red;">*</span></label>
      <input type="text" name="yakan_word" id="yakan_word" required>

      <label for="pilipino_word">Pilipino Word <span style="color:red;">*</span></label>
      <input type="text" name="pilipino_word" id="pilipino_word" required>

      <label for="english_word">English Word <span style="color:red;">*</span></label>
      <input type="text" name="english_word" id="english_word" required>

      <label for="synonyms">Synonyms</label>
      <textarea name="synonyms" id="synonyms" rows="3"></textarea>

      <label for="examples">Examples</label>
      <textarea name="examples" id="examples" rows="3"></textarea>

      <input type="submit" value="Submit Dictionary Entry">
    </form>
  </div>
</body>
</html>

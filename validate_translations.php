<?php
require 'db.php';
session_start();

if (!isset($_SESSION['validator_id'])) {
    header("Location: validator_signin.php");
    exit();
}

$validator_id = $_SESSION['validator_id'];

try {
    $stmt = $db->prepare("
        SELECT es.id AS english_sentence_id, es.english_sentence, ys.id AS yakan_sentence_id, ys.yakan_sentence, ys.audio_path
        FROM english_sentences es
        LEFT JOIN yakan_sentences ys ON es.id = ys.english_sentence_id
        WHERE es.is_validated = 0 OR ys.is_validated = 0
    ");
    $stmt->execute();
    $translations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $translation_id = $_POST['translation_id'];
        $action = $_POST['action'];

        if ($action === 'approve') {
            $stmt = $db->prepare("UPDATE english_sentences SET is_validated = 1, validated_by_validator_id = :validator_id, validation_timestamp = CURRENT_TIMESTAMP WHERE id = :translation_id");
            $stmt->execute(['validator_id' => $validator_id, 'translation_id' => $translation_id]);

            $stmt = $db->prepare("UPDATE yakan_sentences SET is_validated = 1, validated_by_validator_id = :validator_id, validation_timestamp = CURRENT_TIMESTAMP WHERE english_sentence_id = :translation_id");
            $stmt->execute(['validator_id' => $validator_id, 'translation_id' => $translation_id]);
        } elseif ($action === 'reject') {
            $stmt = $db->prepare("DELETE FROM yakan_sentences WHERE english_sentence_id = :translation_id");
            $stmt->execute(['translation_id' => $translation_id]);

            $stmt = $db->prepare("DELETE FROM english_sentences WHERE id = :translation_id");
            $stmt->execute(['translation_id' => $translation_id]);
        }

        header("Location: validate_translations.php");
        exit();
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Validate Translations - Basilan Speaks</title>
  <style>
    body {
      font-family: 'Arial', sans-serif;
      background: #f2f2f2;
      margin: 0;
      padding: 20px;
      color: #333;
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
      color: white;
      text-decoration: none;
      font-size: 16px;
    }

    nav ul li a:hover {
      color: #f39c12;
    }

    .container {
      max-width: 800px;
      margin: auto;
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    .container h2 {
      text-align: center;
      margin-bottom: 25px;
    }

    .entry-card {
      background: #fafafa;
      border: 1px solid #ddd;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 20px;
    }

    .entry-card p {
      margin: 0 0 10px;
    }

    .entry-card audio {
      width: 100%;
      margin-bottom: 10px;
    }

    .entry-card form {
      display: flex;
      gap: 10px;
      justify-content: center;
      flex-wrap: wrap;
    }

    .btn {
      padding: 10px 20px;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
      transition: 0.3s ease;
    }

    .btn-approve {
      background: #28a745;
      color: white;
    }

    .btn-approve:hover {
      background: #218838;
    }

    .btn-reject {
      background: #dc3545;
      color: white;
    }

    .btn-reject:hover {
      background: #c82333;
    }

    @media (max-width: 600px) {
      header {
        flex-direction: column;
        text-align: center;
      }

      nav ul {
        flex-direction: column;
        margin-top: 10px;
        gap: 10px;
      }

      .entry-card form {
        flex-direction: column;
      }

      .btn {
        width: 100%;
      }
    }
  </style>
</head>
<body>
  <header>
    <h1>Validate Translations</h1>
    <nav>
      <ul>
        <li><a href="validator_dashboard.php">Dashboard</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </nav>
  </header>

  <div class="container">
    <h2>Pending Translations</h2>
    <?php if (count($translations) === 0): ?>
      <p style="text-align:center;">No pending translations to validate.</p>
    <?php else: ?>
      <?php foreach ($translations as $translation): ?>
        <div class="entry-card">
          <p><strong>English:</strong> <?= htmlspecialchars($translation['english_sentence']) ?></p>
          <p><strong>Yakan:</strong> <?= htmlspecialchars($translation['yakan_sentence']) ?></p>

          <?php if (!empty($translation['audio_path'])): ?>
            <audio controls>
              <source src="<?= htmlspecialchars($translation['audio_path']) ?>" type="audio/mp3">
              Your browser does not support the audio element.
            </audio>
          <?php endif; ?>

          <form method="POST">
            <input type="hidden" name="translation_id" value="<?= $translation['english_sentence_id'] ?>">
            <button type="submit" name="action" value="approve" class="btn btn-approve">Approve</button>
            <button type="submit" name="action" value="reject" class="btn btn-reject">Reject</button>
          </form>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</body>
</html>

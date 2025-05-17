<?php
require 'db.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_signin.php");
    exit();
}

$query = "SELECT f.id, f.message, f.submitted_at, u.username 
          FROM feedbacks f
          JOIN users u ON f.user_id = u.id
          ORDER BY f.submitted_at DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Feedback Received - Basilan Speaks</title>
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
      text-align: center;
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
      text-align: center;
    }

    h2 {
      margin-bottom: 20px;
    }

    .feedback-item {
      background: #f9f9f9;
      padding: 15px;
      border: 1px solid #ddd;
      border-radius: 8px;
      margin-bottom: 20px;
      text-align: left;
    }

    .feedback-item h3 {
      font-size: 18px;
      margin: 0 0 8px;
    }

    .feedback-item p {
      font-size: 16px;
      margin: 5px 0;
    }

    .feedback-item small {
      color: #888;
      font-size: 13px;
    }

    .back-btn {
      display: inline-block;
      margin-top: 20px;
      background: #2ecc71;
      color: white;
      padding: 10px 20px;
      border-radius: 6px;
      text-decoration: none;
      font-weight: bold;
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

      .feedback-item h3 {
        font-size: 16px;
      }

      .feedback-item p {
        font-size: 14px;
      }

      .feedback-item small {
        font-size: 12px;
      }
    }
  </style>
</head>
<body>
  <header>
    <h1>BASILAN SPEAKS - Admin Dashboard</h1>
  </header>

  <div class="container">
    <h2>Feedback Received</h2>

    <?php if (empty($feedbacks)): ?>
      <p>No feedback submitted yet.</p>
    <?php else: ?>
      <?php foreach ($feedbacks as $feedback): ?>
        <div class="feedback-item">
          <h3><?php echo htmlspecialchars($feedback['username']); ?></h3>
          <p><?php echo nl2br(htmlspecialchars($feedback['message'])); ?></p>
          <small>Submitted on <?php echo date('F j, Y, g:i a', strtotime($feedback['submitted_at'])); ?></small>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>

    <div>
      <a href="admin_dashboard.php" class="back-btn">← Back to Dashboard</a>
    </div>
  </div>

  <footer>
    &copy; <?= date("Y"); ?> Basilan Speaks. Made with ❤️ by Your Team. | <a href="privacy.php">Privacy Policy</a>
  </footer>
</body>
</html>

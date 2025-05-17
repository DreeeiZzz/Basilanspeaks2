<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Basilan Speaks</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: Arial, sans-serif;
      background: url('./uploads/translator.jpg') no-repeat center center fixed;
      background-size: cover;
      margin: 0;
      padding: 0;
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
    }

    .container {
      margin-top: 140px;
      padding: 30px;
      text-align: center;
      background: rgba(255, 255, 255, 0.95);
      box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
      border-radius: 12px;
      max-width: 800px;
      margin-left: auto;
      margin-right: auto;
    }

    .quick-links {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 15px;
      margin-top: 30px;
    }

    .quick-links a {
      display: inline-block;
      width: 200px;
      padding: 15px 10px;
      background: #3498db;
      color: white;
      text-decoration: none;
      font-size: 18px;
      border-radius: 8px;
      transition: background 0.3s, transform 0.2s;
      text-align: center;
    }

    .quick-links a:hover {
      background: #2980b9;
      transform: scale(1.05);
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

    .navbar-collapse {
      background: rgba(0, 0, 0, 0.9);
      padding: 10px 20px;
      border-radius: 10px;
      position: absolute;
      top: 100%;
      right: 10px;
      z-index: 999;
    }

    .nav-item {
      margin: 5px 0;
    }

    .nav-link {
      color: white;
      text-decoration: none;
      font-size: 18px;
      padding: 10px 20px;
      border-radius: 20px;
      transition: background-color 0.3s, transform 0.2s;
    }

    .nav-link:hover {
      background: #f39c12;
      transform: scale(1.1);
    }

    @media (max-width: 768px) {
      header h1 {
        font-size: 24px;
      }

      .container {
        width: 90%;
        padding: 20px;
      }

      .quick-links a {
        width: 100%;
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
    <nav class="navbar navbar-expand-lg navbar-dark">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="dictionary.php">Dictionary</a></li>
          <li class="nav-item"><a class="nav-link" href="history.php">History</a></li>
          <li class="nav-item"><a class="nav-link" href="translate.php">Translation</a></li>
          <li class="nav-item"><a class="nav-link" href="feedback.php">Feedback</a></li>
          <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
        </ul>
      </div>
    </nav>
  </header>

  <div class="container">
    <h2>Welcome, <?= htmlspecialchars($_SESSION['username']); ?>!</h2>
    <p>Effortlessly translate between languages and explore your translation history or provide valuable feedback to improve our services.</p>

    <div class="quick-links">
      <a href="history.php">View History</a>
      <a href="translate.php">Start Translating</a>
      <a href="translation.php">Quick Translate</a>
      <a href="feedback.php">Send Feedback</a>
    </div>
  </div>

  <footer>
    &copy; <?= date("Y"); ?> Basilan Speaks. Made with ❤️ by Your Team. | <a href="privacy.php">Privacy Policy</a>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
require 'db.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_signin.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];

try {
    $stmt = $db->prepare("SELECT id, username FROM admins WHERE id = :id");
    $stmt->execute(['id' => $admin_id]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$admin) {
        session_destroy();
        header("Location: admin_signin.php");
        exit();
    }
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    session_destroy();
    header("Location: admin_signin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard - Basilan Speaks</title>
  <style>
    body {
      font-family: 'Arial', sans-serif;
      background: url('./uploads/translator.jpg') no-repeat center center fixed;
      background-size: cover;
      color: #333;
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

    nav {
      display: flex;
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
      transform: scale(1.05);
    }

    .container {
      margin-top: 100px;
      padding: 30px;
      text-align: center;
      background: rgba(255, 255, 255, 0.95);
      box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
      border-radius: 12px;
      max-width: 800px;
      margin-left: auto;
      margin-right: auto;
    }

    .container h2 {
      font-size: 32px;
      font-weight: bold;
      margin-bottom: 20px;
    }

    .container p {
      font-size: 20px;
      color: #555;
    }

    .quick-links {
      margin-top: 30px;
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 15px;
    }

    .quick-links a {
      background: #3498db;
      color: white;
      padding: 14px 25px;
      border-radius: 6px;
      text-decoration: none;
      font-size: 18px;
      width: 240px;
      text-align: center;
      transition: background 0.3s, transform 0.2s;
    }

    .quick-links a:hover {
      background: #2980b9;
      transform: scale(1.05);
    }

    .logout-mobile {
      display: none;
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
      nav {
        display: none;
      }

      header h1 {
        font-size: 20px;
      }

      .container {
        width: 90%;
        padding: 20px;
        padding-bottom: 80px; /* Add space above footer */
      }

      .quick-links a {
        font-size: 16px;
        padding: 12px 20px;
        width: 100%;
        box-sizing: border-box;
      }

      .logout-mobile {
        display: inline-block;
        margin-top: 10px;
        background: #e74c3c;
      }

      footer {
        font-size: 14px;
      }
    }
  </style>
</head>
<body>
  <header>
    <h1>BASILAN SPEAKS - Admin Dashboard</h1>
    <nav>
      <ul>
        <li><a href="admin.php">Add Translation</a></li>
        <li><a href="add_history.php">Add History</a></li>
        <li><a href="add_dictionary.php">Add Dictionary</a></li>
        <li><a href="receive_feedback.php">Feedback</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </nav>
  </header>

  <div class="container">
    <h2>Welcome, <?php echo htmlspecialchars($admin['username']); ?>!</h2>
    <p>Manage translations, dictionaries, and feedback easily with the tools below. You can update your profile or perform administrative tasks here.</p>
    <div class="quick-links">
      <a href="update_profile.php">Update Profile</a>
      <a href="contributor_account.php">Add Contributor</a>
      <a href="validator_account.php">Add Validator</a>
      <a href="assign_sentences.php">Assign Sentences</a>
      <a href="admin.php">Add Translation</a>
      <a href="add_history.php">Add History</a>
      <a href="add_dictionary.php">Add Dictionary</a>
      <a href="receive_feedback.php">View Feedback</a>
      <a href="logout.php" class="logout-mobile">Logout</a> <!-- Mobile-only logout -->
    </div>
  </div>

  <footer>
    &copy; <?php echo date("Y"); ?> Basilan Speaks. Made with ❤️ by Your Team. | <a href="privacy.php">Privacy Policy</a>
  </footer>
</body>
</html>

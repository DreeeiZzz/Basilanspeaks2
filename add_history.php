<?php
require 'db.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_signin.php");
    exit();
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $language = $_POST['language'];
    $media_type = $_POST['media_type'];
    $caption = htmlspecialchars(trim($_POST['caption']));

    if (isset($_FILES['media_file'])) {
        $file = $_FILES['media_file'];
        $file_name = $file['name'];
        $file_tmp = $file['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $allowed = ($media_type == 'image') ? ['jpg', 'jpeg', 'png', 'gif'] : ['mp4', 'avi', 'mov'];

        if (!in_array($file_ext, $allowed)) {
            $message = "Invalid file type.";
        } else {
            $upload_dir = 'uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $safe_name = preg_replace("/[^a-zA-Z0-9\-_\.]/", "", pathinfo($file_name, PATHINFO_FILENAME));
            $new_name = $safe_name . '_' . time() . '.' . $file_ext;
            $destination = $upload_dir . $new_name;

            if (move_uploaded_file($file_tmp, $destination)) {
                try {
                    $stmt = $db->prepare("INSERT INTO media_history (language, media_type, media_path, caption) 
                                          VALUES (:language, :media_type, :media_path, :caption)");
                    $stmt->execute([
                        'language' => $language,
                        'media_type' => $media_type,
                        'media_path' => $new_name,
                        'caption' => $caption
                    ]);
                    $message = "History added successfully!";
                } catch (PDOException $e) {
                    $message = "Error: " . $e->getMessage();
                }
            } else {
                $message = "Failed to upload file.";
            }
        }
    } else {
        $message = "Please upload a file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Add History - Basilan Speaks</title>
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
      text-align: center;
    }

    h2 {
      margin-bottom: 20px;
    }

    form {
      text-align: left;
    }

    form label {
      display: block;
      margin-top: 15px;
      font-weight: bold;
    }

    form select,
    form textarea,
    form input[type="file"] {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 16px;
    }

    form button {
      display: block;
      width: 100%;
      margin-top: 20px;
      padding: 12px;
      background: #007bff;
      color: white;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
    }

    form button:hover {
      background: #0056b3;
    }

    .message {
      margin-top: 20px;
      font-size: 16px;
      font-weight: bold;
    }

    .message.success { color: green; }
    .message.error { color: red; }

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
    }
  </style>
</head>
<body>
  <header>
    <h1>BASILAN SPEAKS - Admin Dashboard</h1>
  </header>

  <div class="container">
    <h2>Add History</h2>

    <form method="POST" enctype="multipart/form-data">
      <label for="language">Language</label>
      <select name="language" id="language" required>
        <option value="Yakan">Yakan</option>
        <option value="English">English</option>
      </select>

      <label for="media_type">Media Type</label>
      <select name="media_type" id="media_type" required>
        <option value="image">Image</option>
        <option value="video">Video</option>
      </select>

      <label for="media_file">Upload File</label>
      <input type="file" name="media_file" id="media_file" accept="image/*,video/*" required>

      <label for="caption">Caption</label>
      <textarea name="caption" id="caption" rows="4" required></textarea>

      <button type="submit" name="submit">Add History</button>
    </form>

    <?php if ($message): ?>
      <div class="message <?= strpos($message, 'Error') === 0 ? 'error' : 'success'; ?>">
        <?= htmlspecialchars($message); ?>
      </div>
    <?php endif; ?>

    <div style="text-align: center;">
      <a href="admin_dashboard.php" class="back-btn">← Back to Dashboard</a>
    </div>
  </div>

  <footer>
    &copy; <?= date("Y"); ?> Basilan Speaks. Made with ❤️ by Your Team. | <a href="privacy.php">Privacy Policy</a>
  </footer>
</body>
</html>

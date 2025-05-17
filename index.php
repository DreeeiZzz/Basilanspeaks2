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
      padding-top: 70px;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    header {
      background: rgba(0, 0, 0, 0.8);
      color: white;
      padding: 10px 20px;
      position: fixed;
      top: 0;
      width: 100%;
      z-index: 1000;
    }

    .navbar-nav .nav-link {
      color: white;
      margin-left: 15px;
      font-size: 16px;
      transition: color 0.3s;
    }

    .navbar-nav .nav-link:hover {
      color: #f39c12;
    }

    .container-main {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 30px;
      background: rgba(255, 255, 255, 0.95);
      box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
      border-radius: 12px;
      margin: 40px auto;
      width: 90%;
      max-width: 900px;
      text-align: center;
    }

    .quick-links {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 15px;
      margin-top: 20px;
    }

    .quick-links a {
      display: inline-block;
      background: #3498db;
      color: white;
      width: 200px;
      text-align: center;
      padding: 15px 10px;
      border-radius: 8px;
      text-decoration: none;
      font-size: 16px;
      transition: background 0.3s, transform 0.2s;
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

    @media (max-width: 768px) {
      .navbar-nav {
        background: rgba(0, 0, 0, 0.9);
        padding: 10px;
        border-radius: 8px;
      }

      .container-main {
        padding: 20px;
      }

      .quick-links a {
        width: 100%;
      }
    }
  </style>
</head>
<body>

  <header>
    <nav class="navbar navbar-expand-lg navbar-dark">
      <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#">BASILAN SPEAKS</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="no_dictionary.php">Dictionary</a></li>
            <li class="nav-item"><a class="nav-link" href="no_history.php">History</a></li>
            <li class="nav-item"><a class="nav-link" href="no_translate.php">Translation</a></li>
            <li class="nav-item"><a class="nav-link" href="selection.php">Login</a></li>
          </ul>
        </div>
      </div>
    </nav>
  </header>

  <main class="container-main">
    <p class="mb-4">Effortlessly translate between languages and explore your translation history or provide valuable feedback to improve our services.</p>

    <div class="quick-links">
      <a href="no_history.php">View History</a>
      <a href="no_translate.php">Start Translating</a>
      <a href="no_translation.php">Quick Translate</a>
      <a href="feedback.php">Send Feedback</a>
    </div>
  </main>

  <footer>
    &copy; <?= date("Y"); ?> Basilan Speaks. Made with ❤️ by Your Team. | <a href="privacy.php">Privacy Policy</a>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

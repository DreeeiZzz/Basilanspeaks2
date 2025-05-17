<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Role Selection - Basilan Speaks</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f4f6f9;
      min-height: 100vh;
      margin: 0;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      font-family: 'Poppins', sans-serif;
    }

    .logo {
      width: 150px;
      margin-bottom: 25px;
    }

    .card-option {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      border: none;
      border-radius: 10px;
      background-color: #ffffff;
      padding: 30px 20px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .card-option:hover {
      transform: translateY(-6px);
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    .card-option i {
      font-size: 2.5rem;
      color: #0d6efd;
    }

    .card-title {
      font-size: 1.1rem;
      font-weight: 600;
      margin-top: 12px;
      color: #343a40;
    }

    .welcome-text {
      font-size: 1.8rem;
      font-weight: bold;
      margin-top: 40px;
      color: #0d6efd;
      text-align: center;
    }

    .role-selection {
      width: 100%;
      max-width: 1000px;
      padding: 15px;
    }

    .guest-option {
      margin-top: 30px;
      text-align: center;
    }

    @media (max-width: 576px) {
      .card-option {
        padding: 20px 10px;
      }

      .card-option i {
        font-size: 2rem;
      }

      .card-title {
        font-size: 1rem;
      }

      .welcome-text {
        font-size: 1.5rem;
      }
    }
  </style>
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>
  <img src="uploads/logo.png" alt="Basilan Speaks Logo" class="logo">

  <div class="container role-selection">
    <div class="row g-4 justify-content-center">
      <div class="col-6 col-sm-6 col-md-3">
        <a href="signin.php" class="text-decoration-none">
          <div class="card-option text-center">
            <i class="fas fa-user"></i>
            <div class="card-title">User</div>
          </div>
        </a>
      </div>
      <div class="col-6 col-sm-6 col-md-3">
        <a href="admin_signin.php" class="text-decoration-none">
          <div class="card-option text-center">
            <i class="fas fa-user-shield"></i>
            <div class="card-title">Admin</div>
          </div>
        </a>
      </div>
      <div class="col-6 col-sm-6 col-md-3">
        <a href="contributor_signin.php" class="text-decoration-none">
          <div class="card-option text-center">
            <i class="fas fa-pencil-alt"></i>
            <div class="card-title">Contributor</div>
          </div>
        </a>
      </div>
      <div class="col-6 col-sm-6 col-md-3">
        <a href="validator_signin.php" class="text-decoration-none">
          <div class="card-option text-center">
            <i class="fas fa-check-circle"></i>
            <div class="card-title">Validator</div>
          </div>
        </a>
      </div>
    </div>

    <!-- Guest Button -->
    <div class="guest-option">
      <a href="index.php" class="btn btn-outline-primary btn-lg mt-4">
        <i class="fas fa-sign-in-alt me-2"></i>Continue as Guest
      </a>
    </div>

    <div class="welcome-text">Welcome to Basilan Speaks</div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

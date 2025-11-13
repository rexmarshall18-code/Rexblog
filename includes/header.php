<?php
    session_start();
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="User blog dashboard">
    <title>My Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <meta name="theme-color" content="#712cf9">
    <style>
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus, 
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 30px white inset !important;
            box-shadow: 0 0 0 30px white inset !important;
        }

        .navbar-brand img {
            height: 30px; 
            width: auto;
        }

        body {
            position: relative;
            background-color: #f8f9fa;
        }
        body::before {
            content: '';
            position: fixed; 
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: -1;
            opacity: 0.03; 
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 1440 560' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M0 143.5C240 180 480 143.5 720 143.5C960 143.5 1200 216.5 1440 289.5V560H0V143.5Z' fill='black'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: bottom;
            background-size: cover;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card, 
        main.form-signin, 
        div.bg-light.rounded-3,
        .alert,
        main.w-50,
        main.w-75
        {
            animation: fadeInUp 0.5s ease-out forwards;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15) !important;
        }
        
        .btn {
            transition: all 0.3s ease-out;
        }
    </style>
</head>
<body class="bg-light"> 
<nav class="navbar navbar-expand-lg bg-white shadow-sm">
  <div class="container">
    
    
    <a class="navbar-brand" href="dashboard.php">
        <img src="LogoREXBLOG.jpg" alt="REXBLOG Logo">
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      
      <ul class="navbar-nav ms-auto">
        
        <?php if(!isset($_SESSION['user_id'])) : ?>
        <li class="nav-item">
          <a class="nav-link" href="login.php">Login</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="register.php">Register</a>
        </li>
        <?php else : ?>

        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="dashboard.php">Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="create.php">Create</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?php echo htmlspecialchars($_SESSION['username']); ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="profile.php">Profile Settings</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
          </ul>
        </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
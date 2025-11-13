<?php
require "config.php";

session_start();


if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$errors = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $email = isset($_POST['email']) ? strtolower(trim($_POST['email'])) : '';
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if ($email === '' || $username === '' || $password === '') {
        $errors = 'Some inputs are empty';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors = 'Invalid email format';
    } elseif (strlen($password) < 8) {
        $errors = 'Password must be at least 8 characters';
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $errors = 'Password must contain at least one uppercase letter';
    } elseif (!preg_match('/[0-9]/', $password)) {
        $errors = 'Password must contain at least one number';
    } else {
        $check = $conn->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
        $check->execute([':email' => $email]);
        $exists = $check->fetch(PDO::FETCH_ASSOC);

        if ($exists) {
            $errors = 'Email already registered';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $insert = $conn->prepare("
                INSERT INTO users (email, username, password, created_at)
                VALUES (:email, :username, :password, NOW())
            ");

            $ok = $insert->execute([
                ':email' => $email,
                ':username' => $username,
                ':password' => $hash,
            ]);

            if ($ok && $insert->rowCount() > 0) {
                
                $_SESSION['user_id'] = $conn->lastInsertId();
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;
                
                header("Location: dashboard.php");
                exit;
            } else {
                $errors = 'Registration failed. Try again.';
            }
        }
    }
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - REXBLOG</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .navbar-brand img { height: 30px; width: auto; }
        .form-signin { animation: fadeInUp 0.5s ease-out forwards; }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg bg-white shadow-sm">
      <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="LogoREXBLOG.jpg" alt="REXBLOG Logo">
        </a>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <a class="nav-link" href="login.php">Sudah punya akun? Login</a>
            </li>
        </ul>
      </div>
    </nav>

<main class="form-signin w-50 m-auto">
  <form method="POST" action="register.php" autocomplete="off">
    <h1 class="h3 mt-5 fw-normal text-center">Register</h1>

    <?php if ($errors): ?>
      <div class="alert alert-danger" role="alert">
        <?= htmlspecialchars($errors) ?>
      </div>
    <?php endif; ?>

    <div class="form-floating mb-2">
      <input name="email" type="email" autocomplete="new-email" class="form-control" id="floatingInput" placeholder="name@example.com" required>
      <label for="floatingInput">Email address</label>
    </div>

    <div class="form-floating mb-2">
      <input name="username" type="text" autocomplete="off" class="form-control" id="floatingUsername" placeholder="username" required>
      <label for="floatingUsername">Username</label>
    </div>

    <div class="form-floating position-relative mb-2">
      <input name="password" type="password" autocomplete="new-password" class="form-control" id="passwordInput" placeholder="Password" required>
      <label for="passwordInput">Password</label>
      <i class="bi bi-eye-slash position-absolute end-0 top-50 translate-middle-y me-3" id="togglePassword" style="cursor: pointer;"></i>
    </div>
    
    <small class="text-muted d-block mb-3">
      Password must be at least 8 characters with 1 uppercase letter and 1 number.
    </small>

    <script>
      const togglePassword = document.querySelector('#togglePassword');
      const passwordInput = document.querySelector('#passwordInput');

      togglePassword.addEventListener('click', function () {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.classList.toggle('bi-eye');
        this.classList.toggle('bi-eye-slash');
      });
    </script>

    <button name="submit" class="w-100 btn btn-lg btn-primary mb-3" type="submit">Create account</button>
    <h6 class="text-center mb-3">Already have an account? <a href="login.php">Sign in</a></h6>
    
    
    <div class="text-center mt-3">
      <div id="g_id_onload"
           data-client_id="<?= htmlspecialchars($google_client_id) ?>"
           data-callback="handleCredentialResponse"
           data-auto_prompt="false">
      </div>
      <div class="g_id_signin"
           data-type="standard"
           data-shape="rectangular"
           data-theme="outline"
           data-text="signup_with"
           data-size="large"
           data-logo_alignment="center">
      </div>
    </div>
  </form>
</main>

<script>
function handleCredentialResponse(response) {
    fetch('google_auth.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ credential: response.credential })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            window.location.href = data.redirect;
        } else {
            alert('Signup failed: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Connection error. Please try again.');
    });
}
window.onload = function() {
    if (window.google && google.accounts && google.accounts.id) {
        google.accounts.id.disableAutoSelect();
    }
};
</script>
<script src="https://accounts.google.com/gsi/client" async defer></script>

<?php require "includes/footer.php"; ?>
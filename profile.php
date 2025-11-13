<?php 
require "includes/header.php"; 
require "config.php"; 

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$profile_error = '';
$profile_success = '';
$password_error = '';
$password_success = '';

if (isset($_POST['submit_profile'])) {
    $new_username = trim($_POST['username']);
    
    if (empty($new_username)) {
        $profile_error = 'Username tidak boleh kosong.';
    } elseif ($new_username === $_SESSION['username']) {
        $profile_error = 'Tidak ada perubahan.';
    } else {
        $check = $conn->prepare("SELECT id FROM users WHERE username = :username AND id != :user_id");
        $check->execute([':username' => $new_username, ':user_id' => $user_id]);
        
        if ($check->fetch()) {
            $profile_error = 'Username tersebut sudah digunakan.';
        } else {
            $update = $conn->prepare("UPDATE users SET username = :username WHERE id = :user_id");
            $update->execute([':username' => $new_username, ':user_id' => $user_id]);
            
            $_SESSION['username'] = $new_username;
            $profile_success = 'Username berhasil diperbarui!';
        }
    }
}

if (isset($_POST['submit_password'])) {
    $old_pass = $_POST['old_password'];
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    if (empty($old_pass) || empty($new_pass) || empty($confirm_pass)) {
        $password_error = 'Semua kolom password wajib diisi.';
    } elseif ($new_pass !== $confirm_pass) {
        $password_error = 'Password baru dan konfirmasi tidak cocok.';
    } elseif (strlen($new_pass) < 8) {
        $password_error = 'Password baru minimal 8 karakter.';
    } else {
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = :user_id");
        $stmt->execute([':user_id' => $user_id]);
        $user = $stmt->fetch();

        if ($user && password_verify($old_pass, $user['password'])) {
            $new_hash = password_hash($new_pass, PASSWORD_DEFAULT);
            
            $update = $conn->prepare("UPDATE users SET password = :password WHERE id = :user_id");
            $update->execute([':password' => $new_hash, ':user_id' => $user_id]);
            
            $password_success = 'Password berhasil diubah!';
        } else {
            $password_error = 'Password lama Anda salah.';
        }
    }
}

$stmt = $conn->prepare("SELECT email, username FROM users WHERE id = :user_id");
$stmt->execute([':user_id' => $user_id]);
$current_user = $stmt->fetch();
?>

<main class="w-50 m-auto">
    <h1 class="h3 mt-5 fw-normal text-center">Edit Profile</h1>

    <form method="POST" action="profile.php" autocomplete="off" class="p-4 border rounded-3 bg-light mb-4">
        
        <h5 class="mb-3">Update Info Profil</h5>

        <?php if ($profile_error): ?>
            <div class="alert alert-danger" role="alert"><?= htmlspecialchars($profile_error) ?></div>
        <?php endif; ?>
        <?php if ($profile_success): ?>
            <div class="alert alert-success" role="alert"><?= htmlspecialchars($profile_success) ?></div>
        <?php endif; ?>
        
        <div class="form-floating mb-2">
            <input type="email" class="form-control" id="email" value="<?= htmlspecialchars($current_user['email']) ?>" disabled readonly>
            <label for="email">Email</label>
        </div>

        <div class="form-floating mb-2">
            <input name="username" type="text" class="form-control" id="username" value="<?= htmlspecialchars($current_user['username']) ?>" required>
            <label for="username">Username</label>
        </div>
        
        <button name="submit_profile" class="w-100 btn btn-lg btn-primary" type="submit">Update Profile</button>
    </form>

    <form method="POST" action="profile.php" autocomplete="off" class="p-4 border rounded-3 bg-light">
        
        <h5 class="mb-3">Ubah Password</h5>

        <?php if ($password_error): ?>
            <div class="alert alert-danger" role="alert"><?= htmlspecialchars($password_error) ?></div>
        <?php endif; ?>
        <?php if ($password_success): ?>
            <div class="alert alert-success" role="alert"><?= htmlspecialchars($password_success) ?></div>
        <?php endif; ?>

        <div class="form-floating mb-2">
            <input name="old_password" type="password" class="form-control" id="old_password" placeholder="Password Lama" required>
            <label for="old_password">Password Lama</label>
        </div>
        <div class="form-floating mb-2">
            <input name="new_password" type="password" class="form-control" id="new_password" placeholder="Password Baru" required>
            <label for="new_password">Password Baru</label>
        </div>
        <div class="form-floating mb-2">
            <input name="confirm_password" type="password" class="form-control" id="confirm_password" placeholder="Konfirmasi Password Baru" required>
            <label for="confirm_password">Konfirmasi Password Baru</label>
        </div>
        
        <button name="submit_password" class="w-100 btn btn-lg btn-warning" type="submit">Ubah Password</button>
    </form>
</main>

<?php require "includes/footer.php"; ?>
<?php 
require "includes/header.php"; 
require "config.php"; 

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$errors = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $body = isset($_POST['body']) ? $_POST['body'] : '';
    $user_id = $_SESSION['user_id'];
    
    $allowed_tags = '<p><strong><em><u><ul><ol><li><br><a><h2><h3><h4><h5><h6><blockquote>';
    $body = strip_tags($body, $allowed_tags);
    
    if ($title === '' || $body === '') {
        $errors = 'Title and body are required';
    } elseif (strlen($title) < 3) {
        $errors = 'Title must be at least 3 characters';
    } elseif (strlen($body) < 10) {
        $errors = 'Body must be at least 10 characters';
    } else {
        try {
            $insert = $conn->prepare("
                INSERT INTO posts (title, body, user_id, created_at)
                VALUES (:title, :body, :user_id, NOW())
            ");
            
            $ok = $insert->execute([
                ':title' => $title,
                ':body' => $body,
                ':user_id' => $user_id 
            ]);
            
            if ($ok && $insert->rowCount() > 0) {
                $success = 'Post created successfully!';
                header("refresh:2;url=dashboard.php");
            } else {
                $errors = 'Failed to create post. Please try again.';
            }
        } catch (PDOException $e) {
            error_log("Create post error: " . $e->getMessage());
            $errors = 'Database error. Please try again.';
        }
    }
}
?>

<main class="form-signin w-75 m-auto">
    <form method="POST" action="create.php">
   
        <h1 class="h3 mt-5 fw-normal text-center">Create Post</h1>
        
        <?php if ($errors): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($errors) ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success" role="alert">
                <?= htmlspecialchars($success) ?> Redirecting...
            </div>
        <?php endif; ?>
        
        <div class="form-floating mb-3">
            <input name="title" type="text" class="form-control" id="floatingInput" placeholder="title" required>
            <label for="floatingInput">Title</label>
        </div>
        
        <div class="mb-3">
            <label for="post-content" class="form-label">Content</label>
            <textarea rows="15" name="body" class="form-control" id="post-content"></textarea>
        </div>
        
        <button name="submit" class="w-100 btn btn-lg btn-primary" type="submit">Create Post</button>
        <a href="dashboard.php" class="btn btn-lg btn-secondary w-100 mt-2">Cancel</a>
    </form>
</main>

<script src="https://cdn.tiny.cloud/1/ejty3zjyrgjmifqmlowuoxume6bjkl7m4c08c4r4qwu5et60/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>
<script>
  tinymce.init({
    selector: '#post-content',
    plugins: 'lists link autolink autoresize wordcount',
    toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright | bullist numlist | link',
    menubar: false,
    height: 400,
    autoresize_bottom_margin: 20
  });
</script>

<?php require "includes/footer.php"; ?>
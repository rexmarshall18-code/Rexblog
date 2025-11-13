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
$post = null;

if (isset($_GET['id'])) {
    $post_id = (int)$_GET['id'];
    
    try {
        $stmt = $conn->prepare("SELECT * FROM posts WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $post_id]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$post) {
            $errors = 'Post not found.';
        } elseif ($post['user_id'] !== $_SESSION['user_id']) {
            $errors = 'You do not have permission to edit this post.';
        }
    } catch (PDOException $e) {
        error_log("Error fetching post: " . $e->getMessage());
        $errors = 'Database error.';
    }
} else {
    $errors = 'No post ID specified.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) && $post) {
    if ($post['user_id'] !== $_SESSION['user_id']) {
        $errors = 'Permission denied.';
    } else {
        $title = isset($_POST['title']) ? trim($_POST['title']) : '';
        $body = isset($_POST['body']) ? $_POST['body'] : '';

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
                $update = $conn->prepare("
                    UPDATE posts 
                    SET title = :title, body = :body 
                    WHERE id = :id AND user_id = :user_id
                ");
                
                $ok = $update->execute([
                    ':title' => $title,
                    ':body' => $body,
                    ':id' => $post['id'],
                    ':user_id' => $_SESSION['user_id']
                ]);
                
                if ($ok) { 
                    $success = 'Post updated successfully!';
                    $post['title'] = $title;
                    $post['body'] = $body;
                    header("refresh:2;url=dashboard.php");
                } else {
                    $errors = 'Update failed.';
                }
            } catch (PDOException $e) {
                error_log("Update post error: " . $e->getMessage());
                $errors = 'Database error: ' . $e->getMessage();
            }
        }
    }
}
?>

<main class="form-signin w-75 m-auto">
    <?php if ($errors && !$post): ?>
        <div class="alert alert-danger mt-5" role="alert">
            <?= htmlspecialchars($errors) ?>
            <br><a href="dashboard.php">Back to Dashboard</a>
        </div>
    <?php elseif ($post): ?>
        <form method="POST" action="edit.php?id=<?= $post['id'] ?>">
        
            <h1 class="h3 mt-5 fw-normal text-center">Edit Post</h1>
            
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
            
            <div class="form-floating mb-3 mt-3">
                <input name="title" type="text" class="form-control" id="floatingInput" 
                       placeholder="title" required value="<?= htmlspecialchars($post['title']) ?>">
                <label for="floatingInput">Title</label>
            </div>
            
            <div class="mb-3">
                <label for="post-content" class="form-label">Content</label>
                <textarea rows="15" name="body" class="form-control" id="post-content">
                    <?= $post['body'] ?>
                </textarea>
            </div>
            
            <button name="submit" class="w-100 btn btn-lg btn-warning" type="submit">Update Post</button>
            <a href="dashboard.php" class="btn btn-lg btn-secondary w-100 mt-2">Cancel</a>
        </form>
    <?php endif; ?>
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
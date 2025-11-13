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
$posts_per_page = 4;
$search_term = "";

$count_params = []; 
$select_params = [];

$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($current_page < 1) $current_page = 1;

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_term = $_GET['search'];
}

$sql_base = "FROM posts JOIN users ON posts.user_id = users.id";
$sql_where = " WHERE posts.user_id = :user_id";
$count_params[':user_id'] = $user_id;
$select_params[':user_id'] = $user_id;

if (!empty($search_term)) {
    $sql_where .= " AND (posts.title LIKE :search_title OR posts.body LIKE :search_body)";
    $search_like = "%" . $search_term . "%";
    $count_params[':search_title'] = $search_like;
    $count_params[':search_body'] = $search_like;
    $select_params[':search_title'] = $search_like;
    $select_params[':search_body'] = $search_like;
}

$count_sql = "SELECT COUNT(*) as total $sql_base $sql_where";
$stmt_count = $conn->prepare($count_sql);
$stmt_count->execute($count_params);
$total_posts = $stmt_count->fetch(PDO::FETCH_ASSOC)['total'];
$total_pages = ceil($total_posts / $posts_per_page);

if ($current_page > $total_pages && $total_pages > 0) $current_page = $total_pages;

$offset = ($current_page - 1) * $posts_per_page;

$select_sql = "SELECT posts.*, users.username 
               $sql_base 
               $sql_where 
               ORDER BY posts.created_at DESC 
               LIMIT :limit OFFSET :offset";

$select_params[':limit'] = $posts_per_page;
$select_params[':offset'] = $offset;

$stmt = $conn->prepare($select_sql);

foreach ($select_params as $key => &$val) {
    if (is_int($val)) {
        $stmt->bindParam($key, $val, PDO::PARAM_INT);
    } else {
        $stmt->bindParam($key, $val, PDO::PARAM_STR);
    }
}

try {
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error fetching user posts: " . $e->getMessage());
    $posts = [];
}
?>

<style>
.post-body-preview {
    max-height: 250px;
    overflow-y: auto;
    word-wrap: break-word;
    line-height: 1.5; 
}
.post-body-preview p { margin-bottom: 0.5rem; }
.post-body-preview ul, .post-body-preview ol { padding-left: 1.2rem; }
</style>

<div class="container mt-5">
    
    <div class="text-center p-4 mb-4 bg-light rounded-3">
        <h2>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h2>
        <p class="lead">Anda memiliki total <?= $total_posts ?> postingan.</p>
        <a href="create.php" class="btn btn-primary">Create New Post</a>
    </div>

    <h1 class="text-center mb-4">My Posts</h1>

    <div class="row justify-content-center mb-4">
        <div class="col-md-8">
            <form action="dashboard.php" method="GET" class="d-flex">
                <input type="text" class="form-control me-2" 
                       placeholder="Cari di postingan Anda..." 
                       name="search" 
                       value="<?= htmlspecialchars($search_term) ?>">
                <button class="btn btn-outline-primary" type="submit">Cari</button>
                <?php if (!empty($search_term)): ?>
                    <a href="dashboard.php" class="btn btn-outline-secondary ms-2">Clear</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <?php if (empty($posts)): ?>
        <div class="alert alert-info text-center" role="alert">
            <?php if (!empty($search_term)): ?>
                Tidak ada postingan yang cocok dengan pencarian "<?= htmlspecialchars($search_term) ?>".
            <?php else: ?>
                Anda belum membuat postingan. <a href="create.php">Buat postingan pertama Anda!</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($posts as $post): ?>
                <div class="col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= htmlspecialchars($post['title']) ?></h5>
                            <div class="card-text text-muted post-body-preview flex-grow-1">
                                <?= $post['body'] ?>
                            </div>
                        </div>
                        <div class="card-footer text-muted d-flex justify-content-between align-items-center">
                            <div>
                                <small><strong>By:</strong> <?= htmlspecialchars($post['username']) ?></small><br>
                                <small><?= date('d M Y, H:i', strtotime($post['created_at'])) ?></small>
                            </div>
                            <div class="ms-2">
                                <a href="edit.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-secondary">Edit</a>
                                <button onclick="deletePost(<?= $post['id'] ?>)" class="btn btn-sm btn-danger">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ($total_pages > 1): ?>
    <nav aria-label="Page navigation" class="mt-4">
      <ul class="pagination justify-content-center">
        <li class="page-item <?= ($current_page <= 1) ? 'disabled' : '' ?>">
          <a class="page-link" href="?page=<?= $current_page - 1 ?>&search=<?= urlencode($search_term) ?>">Previous</a>
        </li>
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
          <li class="page-item <?= ($i == $current_page) ? 'active' : '' ?>">
            <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search_term) ?>"><?= $i ?></a>
          </li>
        <?php endfor; ?>
        <li class="page-item <?= ($current_page >= $total_pages) ? 'disabled' : '' ?>">
          <a class="page-link" href="?page=<?= $current_page + 1 ?>&search=<?= urlencode($search_term) ?>">Next</a>
        </li>
      </ul>
    </nav>
    <?php endif; ?>
</div>

<script>
function deletePost(postId) {
    if (confirm('Are you sure you want to delete this post?')) {
        fetch('delete.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: postId })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('Post deleted successfully!');
                window.location.reload(); 
            } else {
                alert('Failed to delete post: ' + (data.error || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Connection error. Please try again.');
        });
    }
}
</script>

<?php require "includes/footer.php"; ?>
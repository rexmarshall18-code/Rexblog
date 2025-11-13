<?php
session_start();
require "config.php";

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);
    
    if (isset($data['id'])) {
        $post_id = (int)$data['id'];
        
        try {
            $check = $conn->prepare("SELECT user_id FROM posts WHERE id = :id");
            $check->execute([':id' => $post_id]);
            $post = $check->fetch(PDO::FETCH_ASSOC);
            
            if (!$post) {
                echo json_encode(['success' => false, 'error' => 'Post not found']);
                exit;
            }
            
            if ($post['user_id'] !== $_SESSION['user_id']) {
                echo json_encode(['success' => false, 'error' => 'Permission denied']);
                exit;
            }
            
            $delete = $conn->prepare("DELETE FROM posts WHERE id = :id AND user_id = :user_id");
            $success = $delete->execute([
                ':id' => $post_id,
                ':user_id' => $_SESSION['user_id']
            ]);
            
            if ($success && $delete->rowCount() > 0) {
                echo json_encode(['success' => true]);
                exit;
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to delete']);
                exit;
            }
            
        } catch (PDOException $e) {
            error_log("Delete post error: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Database error']);
            exit;
        }
    }
}

echo json_encode(['success' => false, 'error' => 'Invalid request']);
exit;
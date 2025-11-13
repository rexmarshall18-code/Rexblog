<?php
session_start();
require "config.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);
    
    if (isset($data['credential'])) {
        $token = $data['credential'];
        
        $verify_url = "https://oauth2.googleapis.com/tokeninfo?id_token=" . $token;
        $response = @file_get_contents($verify_url);
        
        if ($response === false) {
            echo json_encode(['success' => false, 'error' => 'Failed to verify Google token']);
            exit;
        }
        
        $user_info = json_decode($response, true);
        
        if (isset($user_info['email']) && isset($user_info['email_verified']) && $user_info['email_verified']) {
            $email = $user_info['email'];
            $username = $user_info['name'] ?? explode('@', $email)[0];
            
            try {
                $check = $conn->prepare("SELECT * FROM users WHERE email = :email");
                $check->execute([':email' => $email]);
                $user = $check->fetch(PDO::FETCH_ASSOC);
                
                $user_id_to_session = null;

                if (!$user) {
                    $insert = $conn->prepare("INSERT INTO users (email, username, password, created_at) 
                                              VALUES (:email, :username, '', NOW())");
                    $success = $insert->execute([
                        ':email' => $email, 
                        ':username' => $username
                    ]);
                    
                    if (!$success) {
                        echo json_encode(['success' => false, 'error' => 'Failed to create user']);
                        exit;
                    }
                    $user_id_to_session = $conn->lastInsertId();
                } else {
                    $user_id_to_session = $user['id'];
                    $username = $user['username'];
                }
                
                $_SESSION['user_id'] = $user_id_to_session;
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;
                
                echo json_encode(['success' => true, 'redirect' => 'dashboard.php']);
                exit;
                
            } catch (PDOException $e) {
                error_log("Google Auth Error: " . $e->getMessage());
                echo json_encode(['success' => false, 'error' => 'Database error']);
                exit;
            }
        }
    }
}

echo json_encode(['success' => false, 'error' => 'Invalid request']);
exit;
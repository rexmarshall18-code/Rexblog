<?php 
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    // 1. KONEKSI DATABASE (ISI DENGAN INFO ANDA)
    $host = "localhost";
    $dbname = "auth-sys";
    $user = "root";
    $pass = "";

    // 2. KUNCI API (ISI DENGAN KUNCI ANDA)
    $gemini_api_key = "YOUR_GEMINI_API_KEY_HERE";
    $google_client_id = "YOUR_GOOGLE_CLIENT_ID_HERE";

    // 3. KONEKSI PDO
    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname;", $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Koneksi database gagal: " . $e->getMessage());
    }
?>
<?php
require "config.php";

$api_key = $gemini_api_key; 

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Metode tidak diizinkan']);
    exit;
}

$input = file_get_contents("php://input");
$data = json_decode($input, true);
$user_message = $data['message'] ?? '';

if (empty($user_message)) {
    echo json_encode(['reply' => 'Maaf, saya tidak menerima pesan kosong.']);
    exit;
}

$system_prompt = "Anda adalah 'Asisten REXBLOG', asisten AI yang ramah dan suportif. 
Tugas utama Anda adalah membantu pengguna menulis postingan blog. 
Anda bisa membantu:
- Membuat ide judul yang menarik
- Membuat kerangka (outline) artikel
- Memperbaiki tata bahasa
- Menyederhanakan paragraf yang rumit
- Memberikan ide untuk paragraf pembuka atau penutup
Jawablah selalu dalam bahasa Indonesia dengan gaya yang jelas dan profesional. 
Jangan menjawab pertanyaan di luar topik penulisan blog.";

$api_url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-preview-09-2025:generateContent?key=" . $api_key;
$payload = [
    'contents' => [
        [
            'parts' => [
                ['text' => $user_message]
            ]
        ]
    ],
    'systemInstruction' => [
        'parts' => [
            ['text' => $system_prompt]
        ]
    ]
];
$json_payload = json_encode($payload);

$ch = curl_init($api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json_payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Content-Length: ' . strlen($json_payload)
]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_error = curl_error($ch);
curl_close($ch);

$bot_reply = "Maaf, terjadi kesalahan saat menghubungi AI. Silakan coba lagi.";

if ($curl_error) {
    error_log("cURL Error: " . $curl_error);
    $bot_reply = "Error koneksi ke server AI. Detail: " . $curl_error;
} elseif ($http_code == 200) {
    $result = json_decode($response, true);
    $text = $result['candidates'][0]['content']['parts'][0]['text'] ?? null;
    if ($text) {
        $bot_reply = $text;
    } else {
        error_log("Gemini API Error: " . json_encode($result));
        $bot_reply = "Menerima balasan tidak valid dari AI. Coba lagi.";
    }
} else {
    error_log("Gemini API HTTP Error $http_code: " . $response);
    $bot_reply = "Server AI mengembalikan error $http_code. Cek API key Anda.";
}

header('Content-Type: application/json');
echo json_encode(['reply' => $bot_reply]);
exit;
?>
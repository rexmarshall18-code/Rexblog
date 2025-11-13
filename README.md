=== REXBLOG - AI-Native Blog Platform (Fullstack PoC) ===

REXBLOG adalah Proof of Concept platform blogging AI-native yang saya kembangkan dari nol dengan PHP Native, integrasi Google Auth, TinyMCE, dan Gemini AI.

Fokus utama proyek ini bukan pada scale, tapi pada keamanan arsitektur dan orkestrasi AI dari sisi backend untuk melatih kemampuan Fullstack AI Development yang aman dan efisien.

=== Fitur Unggulan ===

Proyek ini dibangun dari nol untuk mendemonstrasikan fondasi fullstack yang aman dan modern.

Arsitektur Privat: Workspace pribadi yang aman. User onlya bisa melihat dan mengelola postingannya sendiri (Isolasi Data).

Otentikasi Aman: Sistem login dan register dari nol menggunakan PHP Native ($_SESSION) dengan password hashing (password_verify).

Integrasi Google Auth: Opsi login dengan Google (OAuth2) dengan verifikasi token server-side yang aman.

Rich Text Editor (TinyMCE): Editor WYSIWYG untuk memformat postingan (Bold, Italic, List, Link).

Sanitasi HTML (Keamanan XSS): Input HTML dari editor disanitasi di backend menggunakan strip_tags dengan whitelist untuk mencegah Cross-Site Scripting (XSS).

Keamanan Database (SQL Injection): 100% semua query database menggunakan PDO Prepared Statements untuk mencegah SQL Injection.

Dashboard & Profil: Pengelolaan postingan (CRUD) dan profil (ubah username/password) yang terpisah.

Desain Responsif: Dibangun dengan TailwindCSS dan CSS3 (Animasi, Flexbox) untuk UI/UX yang clean dan modern.


=== Fitur Unggulan: Orkestrasi AI Backend ===

Fitur inti dari proyek ini adalah Asisten AI Terintegrasi yang ditenagai oleh Google Gemini.

Arsitekturnya dirancang untuk keamanan maksimum:

Frontend (JavaScript): Hanya mengirimkan prompt (pertanyaan) user.

Backend (PHP API): Sebuah endpoint kustom (ai-chat.php) menerima prompt tersebut.

Orkestrasi: Backend PHP menyuntikkan API key (yang tersembunyi di server) dan System Prompt (peran AI) sebelum meneruskannya ke Google.

Ini mencegah pencurian API key dan prompt injection dasar, menunjukkan arsitektur AI fullstack yang aman.


=== Skema Database ===

Arsitektur database dinormalisasi untuk efisiensi.

users: Menyimpan data identitas (username, email, hash password).

posts: Menyimpan konten. Menggunakan user_id (Foreign Key) yang me-referensi users.id untuk menghubungkan postingan ke pemiliknya.

Tech Stack

| Kategori | Teknologi |
| Backend | PHP (Native) |
| Database | MySQL |
| Frontend | HTML5, JavaScript (ES6+), TailwindCSS, CSS3 |
| Keamanan | PDO (Prepared Statements), Password Hashing, htmlspecialchars, strip_tags |
| Integrasi API | Google Gemini (AI), Google Auth (OAuth2), TinyMCE (Editor) |
| Environment | XAMPP (Apache) |

=== Cara Instalasi (Lokal) ===

Clone Repo:

git clone [https://github.com/rexmarshall18-code/rexblog.git](https://github.com/rexmarshall18-code/rexblog.git)
cd rexblog

Database:

Buka phpMyAdmin dan buat database baru (misal: rexblog_db).

Import file database.sql (struktur tabel) ke database baru Anda.

Konfigurasi (Langkah Kritis):

Cari file config.example.php.

Buat salinan dari file tersebut dan ganti namanya menjadi config.php.

Buka config.php dan isi semua placeholder (password database, $gemini_api_key, dan $google_client_id) dengan kredensial Anda.

Jalankan:

Taruh folder proyek di C:\xampp\htdocs\.

Nyalakan Apache & MySQL di XAMPP.

Buka http://localhost/rexblog/ (atau nama folder proyek Anda).

<!DOCTYPE html>

<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REXBLOG - Penulisan Blog Cerdas Bertenaga AI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa; 
        }
        .hero-wave {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: -1;
            opacity: 0.03; 
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 1440 560' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M0 143.5C240 180 480 143.5 720 143.5C960 143.5 1200 216.5 1440 289.5V560H0V143.5Z' fill='black'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: bottom;
            background-size: cover;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Initial state: hidden */
        .hero-section, .feature-card, .about-section {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }
        
        /* State ketika visible di viewport */
        .hero-section.animate-in, .feature-card.animate-in, .about-section.animate-in {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body class="text-gray-900">

    <div class="relative overflow-hidden">
        <div class="hero-wave"></div>

        <header class="container mx-auto px-6 py-6">
            <nav class="flex justify-between items-center">
                <a href="index.php" class="flex items-center space-x-2">
                    <img src="LogoREXBLOG.jpg" alt="REXBLOG Logo" class="h-14 w-auto"> 
                </a>
                
                <div class="hidden md:flex space-x-8">
                    <a href="#fitur" class="text-gray-600 hover:text-black">Fitur</a>
                    <a href="#tentang" class="text-gray-600 hover:text-black">Tentang</a>
                    <a href="#" class="text-gray-600 hover:text-black">Komunitas</a>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="login.php" class="text-gray-600 hover:text-black font-medium">Login</a>
                    <a href="register.php" class="bg-black text-white px-5 py-2 rounded-full font-medium shadow-lg hover:bg-gray-800 transition duration-300">
                        Mulai Gratis
                    </a>
                </div>
            </nav>
        </header>

        <main class="container mx-auto px-6 pt-24 pb-32 text-center hero-section">
            <h1 class="text-4xl md:text-6xl font-black max-w-3xl mx-auto leading-tight">
                Penulisan Blog Cerdas, <br class="hidden md:block"> Ditenagai oleh AI
            </h1>
            <p class="text-lg text-gray-700 max-w-xl mx-auto mt-6">
                Dapatkan ide judul instan, kerangka artikel otomatis, dan asisten AI pribadi Anda untuk membuat konten luar biasa, kapan saja, di mana saja.
            </p>
            <a href="register.php" class="bg-black text-white px-8 py-4 rounded-full font-bold shadow-xl hover:bg-gray-800 transition duration-300 inline-block mt-10 text-lg">
                Mulai Menulis Hari Ini &rarr;
            </a>
        </main>

        <section id="fitur" class="container mx-auto px-6 pb-24 pt-16">
            <h2 class="text-3xl font-bold text-center mb-12 hero-section">Fitur Unggulan Kami</h2>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-3xl shadow-2xl border border-gray-200 feature-card">
                    <div class="bg-blue-100 text-blue-600 w-16 h-16 rounded-2xl flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/></svg>
                    </div>
                    <h3 class="text-2xl font-bold mt-6">Asisten AI Terintegrasi</h3>
                    <p class="text-gray-600 mt-2">Gunakan chatbot AI kami untuk brainstorming ide, membuat outline, atau memperbaiki tata bahasa Anda langsung di editor.</p>
                </div>
                <div class="bg-white p-8 rounded-3xl shadow-2xl border border-gray-200 feature-card">
                    <div class="bg-green-100 text-green-600 w-16 h-16 rounded-2xl flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path><path d="m15 5 4 4"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold mt-6">Rich Text Editor</h3>
                    <p class="text-gray-600 mt-2">Format postingan Anda dengan mudah. Bold, italic, list, dan link seperti seorang profesional tanpa perlu tahu koding.</p>
                </div>
                <div class="bg-white p-8 rounded-3xl shadow-2xl border border-gray-200 feature-card">
                    <div class="bg-red-100 text-red-600 w-16 h-16 rounded-2xl flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold mt-6">Aman & Privat</h3>
                    <p class="text-gray-600 mt-2">Setiap akun adalah workspace pribadi Anda. Postingan Anda aman dan hanya bisa diedit oleh Anda.</p>
                </div>
            </div>
        </section>
        
        <section id="tentang" class="container mx-auto px-6 py-24 bg-white rounded-3xl shadow-xl border border-gray-200 about-section">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl font-bold mb-4">Tentang REXBLOG</h2>
                    <p class="text-gray-600 mb-4">
                        REXBLOG adalah <strong>Proof of Concept</strong> platform blogging AI-native yang saya kembangkan dari nol dengan PHP Native, integrasi Google Auth, TinyMCE, dan Gemini AI.
                    </p>
                    <p class="text-gray-600">
                        Fokus utama saya bukan pada scale, tapi pada keamanan arsitektur dan orkestrasi AI dari sisi backend untuk melatih kemampuan Fullstack AI Development yang aman dan efisien.
                    </p>
                </div>
                <div class="text-gray-600">
                    <h4 class="font-bold mb-2">Kontak Developer</h4>
                    <p>Rozaq Jafarudin (Rex)</p>
                    <p>Fullstack Web & AI Developer</p>
                    <a href="mailto:rozaqjafarudin@gmail.com" class="text-blue-600 hover:underline">rozaqjafarudin@gmail.com</a>
                </div>
            </div>
        </section>

        <footer class="container mx-auto px-6 py-12 mt-16 text-center text-gray-500">
            <p>&copy; <?= date("Y") ?> REXBLOG. Dibuat oleh Rozaq Jafarudin.</p>
        </footer>
    </div>

    <script>
        // Intersection Observer untuk trigger animasi scroll (bolak-balik)
        const observerOptions = {
            threshold: 0.15,
            rootMargin: '0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    // Elemen masuk viewport -> tampilkan
                    entry.target.classList.add('animate-in');
                } else {
                    // Elemen keluar viewport -> hide kembali (untuk animasi ulang)
                    entry.target.classList.remove('animate-in');
                }
            });
        }, observerOptions);

        // Observe semua elemen yang perlu animasi
        document.querySelectorAll('.hero-section, .feature-card, .about-section').forEach(el => {
            observer.observe(el);
        });
    </script>

</body>
</html>
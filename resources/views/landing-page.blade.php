<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Al-Furqanul Huda EduSystem - PPDB</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Inter&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        h1,
        h2,
        h3 {
            font-family: 'Playfair Display', serif;
        }

        .hero {
            background: url('{{ asset('img/foto Hero Section.png') }}') no-repeat center center;
            background-size: cover;
            position: relative;
            color: white;
            height: 100vh;
        }



        .overlay {
            background: rgba(0, 0, 0, 0.3);
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
        }

        .hero-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            max-width: 80%;
            color: white;
        }

        .btn-primary {
            background-color: rgba(16, 185, 129, 0.9);
            /* hijau solid agak transparan */
            color: white;
            font-weight: 700;
            padding: 12px 30px;
            border-radius: 30px;
            text-transform: uppercase;
            font-size: 16px;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.7);
            transition: background-color 0.3s ease, box-shadow 0.3s ease, transform 0.2s ease;
            backdrop-filter: blur(8px);
            /* blur halus untuk background tombol */
            border: 1.5px solid rgba(255, 255, 255, 0.3);
            /* border putih transparan */
            cursor: pointer;
            user-select: none;
        }

        .btn-primary:hover {
            background-color: rgba(16, 185, 129, 1);
            /* warna hijau penuh */
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.9);
            transform: translateY(-3px);
            border-color: rgba(255, 255, 255, 0.6);
            /* border jadi lebih jelas */
        }

        .btn-primary:active {
            transform: translateY(-1px);
            box-shadow: 0 3px 10px rgba(16, 185, 129, 0.6);
        }


        .navbar {
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            padding: 16px 32px;
            z-index: 10;
            background-color: rgba(255, 255, 255, 0.15);
            /* lebih transparan */
            backdrop-filter: blur(12px);
            /* efek blur yang halus */
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            /* sedikit bayangan untuk kedalaman */
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            /* garis bawah halus */
        }

        .navbar a {
            color: #333;
            font-weight: 600;
            text-decoration: none;
            margin-right: 20px;
            transition: color 0.3s ease;
        }

        .navbar .btn-primary:hover {
            color: #ffffff;
        }

        .navbar .nav:hover {
            color: #059669;
        }

        button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        @media (max-width: 768px) {
            .hero-content h1 {
                font-size: 2.5rem;
            }

            .hero-content p {
                font-size: 1.125rem;
            }

            .navbar {
                padding: 12px 16px;
            }

        }
    </style>
    {{-- css jadwal detail --}}
    <style>
        .step-item {
            position: relative;
            text-align: center;
            margin-bottom: 30px;
            width: 280px;
            /* Fixed width to ensure alignment */
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .step-circle {
            position: absolute;
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 0;
        }

        .step-line {
            position: absolute;
            top: 40%;
            left: 50%;
            transform: translateX(-50%);
            z-index: 5;
        }

        .step-content {
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: padding-top 0.3s ease;
            min-height: 200px;
            width: 100%;
        }

        .detail-info {
            padding: 10px;
            background-color: #f9fafb;
            border-left: 4px solid #10B981;
            margin-top: 10px;
            border-radius: 8px;
        }

        .detail-info p i {
            transition: transform 0.3s ease;
        }

        .detail-info p:hover i {
            transform: scale(1.2);
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

</head>

<body class="bg-gray-100 text-gray-900">

    <!-- Navbar -->
    <header class="navbar flex justify-between items-center">
        <div class="text-emerald-700 text-2xl font-semibold">
            Al-Furqanul Huda
        </div>
        <nav class="space-x-6">
            <a href="#tentang" class="nav">Tentang</a>
            <a href="#jadwal" class="nav">Jadwal PPDB</a>
            <a href="#fasilitas" class="nav">Fasilitas</a>
            <a href="#kontak" class="nav">Kontak</a>
            <a href="{{ route('login') }}" class="btn-primary">Login</a>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="hero">
        <div class="overlay"></div>
        <div class="hero-content">
            <h1 class="text-5xl md:text-6xl font-bold mb-4">
                Al-Furqanul Huda EduSystem
            </h1>
            <p class="text-lg md:text-xl mb-8">
                Platform PPDB Modern & Terpadu untuk Menyongsong Generasi Islam yang Unggul dan Berakhlak Mulia
            </p>
            <a href="#form-pendaftaran" class="btn-primary">Mulai Pendaftaran</a>
        </div>
    </section>

    <!-- Tentang Kami Section -->
    <section id="tentang" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-6 text-center">

            <!-- Judul -->
            <h2 class="text-4xl md:text-5xl font-extrabold text-emerald-700 mb-6">Tentang Al-Furqanul Huda</h2>

            <!-- Tentang Sekolah -->
            <div class="mb-12">
                <p class="text-lg md:text-xl text-gray-700 mb-6 max-w-3xl mx-auto leading-relaxed">
                    Al-Furqanul Huda adalah lembaga pendidikan berbasis Islam yang mengedepankan kualitas pendidikan
                    unggul
                    dengan kurikulum yang relevan dan bermanfaat bagi perkembangan generasi mendatang. Dengan lingkungan
                    yang mendukung, kami berkomitmen untuk mencetak siswa yang cerdas, berakhlak mulia, serta siap
                    menghadapi tantangan dunia.
                </p>

                <!-- Visi dan Misi -->
                <div class="flex justify-center gap-10 flex-wrap mb-12">
                    <!-- Visi -->
                    <div
                        class="bg-white rounded-xl shadow-lg p-8 text-center transition hover:shadow-2xl w-full sm:w-1/2 lg:w-1/3">
                        <div class="text-emerald-600 text-4xl mb-4">
                            <i class="fas fa-eye"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-emerald-700 mb-3">Visi Kami</h3>
                        <p class="text-gray-600 text-base leading-relaxed">
                            Menjadi lembaga pendidikan unggul yang mencetak generasi Islam yang cerdas, berakhlak mulia,
                            dan siap berkontribusi dalam pembangunan dunia.
                        </p>
                    </div>

                    <!-- Misi -->
                    <div
                        class="bg-white rounded-xl shadow-lg p-8 text-left transition hover:shadow-2xl w-full sm:w-1/2 lg:w-1/3">
                        <div class="text-center text-emerald-600 text-4xl mb-4">
                            <i class="fas fa-bullseye"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-emerald-700 mb-3 text-center">Misi Kami</h3>
                        <ul class="text-gray-600 space-y-3 list-none">
                            <li><i class="fas fa-check-circle text-emerald-600 mr-2"></i>Mempermudah proses pendaftaran
                                dengan sistem PPDB modern.</li>
                            <li><i class="fas fa-check-circle text-emerald-600 mr-2"></i>Meningkatkan kualitas
                                pendidikan
                                berbasis teknologi.</li>
                            <li><i class="fas fa-check-circle text-emerald-600 mr-2"></i>Mengembangkan siswa yang
                                cerdas,
                                berkarakter, dan terampil.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Tentang Sistem -->
            <div>
                <h2 class="text-4xl md:text-5xl font-extrabold text-emerald-700 mb-6">Tentang Al-Furqanul Huda EduSystem
                </h2>
                <p class="text-lg md:text-xl text-gray-700 mb-12 max-w-3xl mx-auto leading-relaxed">
                    Al-Furqanul Huda EduSystem adalah platform PPDB modern yang memudahkan proses pendaftaran dengan
                    sistem
                    transparan dan efisien. Dengan kurikulum berbasis nilai-nilai Islam, kami berkomitmen mencetak
                    generasi unggul,
                    berakhlak mulia, dan siap menghadapi tantangan zaman.
                </p>

                <!-- Keunggulan Sistem -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 justify-items-center">
                    <!-- Kolom 1 -->
                    <div class="bg-white rounded-xl shadow-lg p-8 text-center transition hover:shadow-2xl w-full">
                        <div class="text-emerald-600 text-4xl mb-4">
                            <i class="fas fa-desktop"></i> <!-- Ikon untuk User Interface -->
                        </div>
                        <h3 class="text-xl font-semibold text-emerald-700 mb-3 text-center">Keunggulan Sistem</h3>
                        <ul class="text-gray-600 space-y-3 list-none">
                            <li><i class="fas fa-check-circle text-emerald-600 mr-2"></i>User interface PPDB yang ramah
                                dan mudah digunakan.</li>
                        </ul>
                    </div>

                    <!-- Kolom 2 -->
                    <div class="bg-white rounded-xl shadow-lg p-8 text-center transition hover:shadow-2xl w-full">
                        <div class="text-emerald-600 text-4xl mb-4">
                            <i class="fas fa-globe"></i> <!-- Ikon untuk sistem otomatis -->
                        </div>
                        <h3 class="text-xl font-semibold text-emerald-700 mb-3 text-center">Keunggulan Sistem</h3>
                        <ul class="text-gray-600 space-y-3 list-none">
                            <li><i class="fas fa-check-circle text-emerald-600 mr-2"></i>Sistem pendaftaran online yang
                                efisien dan mudah diakses.</li>
                        </ul>
                    </div>

                    <!-- Kolom 3 -->
                    <div class="bg-white rounded-xl shadow-lg p-8 text-center transition hover:shadow-2xl w-full">
                        <div class="text-emerald-600 text-4xl mb-4">
                            <i class="fas fa-sync-alt"></i> <!-- Ikon untuk verifikasi otomatis -->
                        </div>
                        <h3 class="text-xl font-semibold text-emerald-700 mb-3 text-center">Keunggulan Sistem</h3>
                        <ul class="text-gray-600 space-y-3 list-none">
                            <li><i class="fas fa-check-circle text-emerald-600 mr-2"></i>Proses verifikasi otomatis
                                untuk memastikan transparansi.</li>
                        </ul>
                    </div>

                    <!-- Kolom 4 -->
                    <div class="bg-white rounded-xl shadow-lg p-8 text-center transition hover:shadow-2xl w-full">
                        <div class="text-emerald-600 text-4xl mb-4">
                            <i class="fas fa-balance-scale"></i> <!-- Ikon untuk transparansi -->
                        </div>
                        <h3 class="text-xl font-semibold text-emerald-700 mb-3 text-center">Keunggulan Sistem</h3>
                        <ul class="text-gray-600 space-y-3 list-none">
                            <li><i class="fas fa-check-circle text-emerald-600 mr-2"></i>Transparansi dalam proses
                                pendaftaran dan seleksi.</li>
                        </ul>
                    </div>

                    <!-- Kolom 5 -->
                    <div class="bg-white rounded-xl shadow-lg p-8 text-center transition hover:shadow-2xl w-full">
                        <div class="text-emerald-600 text-4xl mb-4">
                            <i class="fas fa-mobile-alt"></i> <!-- Ikon untuk platform berbasis teknologi -->
                        </div>
                        <h3 class="text-xl font-semibold text-emerald-700 mb-3 text-center">Keunggulan Sistem</h3>
                        <ul class="text-gray-600 space-y-3 list-none">
                            <li><i class="fas fa-check-circle text-emerald-600 mr-2"></i>Platform berbasis teknologi
                                yang mudah diakses.</li>
                        </ul>
                    </div>

                    <!-- Kolom 6 -->
                    <div class="bg-white rounded-xl shadow-lg p-8 text-center transition hover:shadow-2xl w-full">
                        <div class="text-emerald-600 text-4xl mb-4">
                            <i class="fas fa-bolt-lightning"></i> <!-- Ikon untuk pengembangan sistem -->
                        </div>
                        <h3 class="text-xl font-semibold text-emerald-700 mb-3 text-center">Keunggulan Sistem</h3>
                        <ul class="text-gray-600 space-y-3 list-none">
                            <li><i class="fas fa-check-circle text-emerald-600 mr-2"></i>Optimasi sistem untuk
                                pengelolaan data dan pengguna secara efisien.</li>
                        </ul>
                    </div>

                </div>

            </div>

        </div>
    </section>

    <!-- Program Section -->
    <section id="program" class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <h2 class="text-4xl font-extrabold text-gray-900 mb-8">
                Program Unggulan & Kurikulum Boarding School
            </h2>
            <p class="text-gray-600 mb-12 max-w-3xl mx-auto leading-relaxed">
                Berbagai program pendidikan dan pembinaan karakter yang terintegrasi untuk mencetak generasi muslim yang
                cerdas, mandiri, dan berakhlak mulia.
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-16">

                <!-- Program 1 -->
                <div class="group cursor-pointer">
                    <div
                        class="text-7xl text-emerald-600 mb-4 transition-transform duration-300 group-hover:scale-110">
                        <i class="fas fa-book-reader"></i>
                    </div>
                    <h3
                        class="text-2xl font-semibold text-gray-900 mb-2 border-b-4 border-emerald-600 pb-1 inline-block group-hover:text-emerald-600 transition-colors duration-300">
                        Program Tahfiz Qur'an
                    </h3>
                    <p class="text-gray-600 leading-relaxed">
                        Pembinaan hafalan Al-Qur'an dengan metode modern dan tradisional, didukung oleh ustadz
                        berpengalaman.
                    </p>
                </div>

                <!-- Program 2 -->
                <div class="group cursor-pointer">
                    <div class="text-7xl text-blue-600 mb-4 transition-transform duration-300 group-hover:scale-110">
                        <i class="fas fa-laptop-code"></i>
                    </div>
                    <h3
                        class="text-2xl font-semibold text-gray-900 mb-2 border-b-4 border-blue-600 pb-1 inline-block group-hover:text-blue-600 transition-colors duration-300">
                        Program Teknologi & Sains
                    </h3>
                    <p class="text-gray-600 leading-relaxed">
                        Kurikulum berbasis STEM yang mengembangkan keterampilan teknologi dan sains untuk mempersiapkan
                        masa depan.
                    </p>
                </div>

                <!-- Program 3 -->
                <div class="group cursor-pointer">
                    <div class="text-7xl text-yellow-500 mb-4 transition-transform duration-300 group-hover:scale-110">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3
                        class="text-2xl font-semibold text-gray-900 mb-2 border-b-4 border-yellow-500 pb-1 inline-block group-hover:text-yellow-500 transition-colors duration-300">
                        Program Pengembangan Karakter
                    </h3>
                    <p class="text-gray-600 leading-relaxed">
                        Pembinaan akhlak mulia, kepemimpinan, dan kedisiplinan melalui kegiatan ekstrakurikuler dan
                        pembiasaan sehari-hari.
                    </p>
                </div>

                <!-- Program 4 -->
                <div class="group cursor-pointer">
                    <div class="text-7xl text-purple-600 mb-4 transition-transform duration-300 group-hover:scale-110">
                        <i class="fas fa-globe"></i>
                    </div>
                    <h3
                        class="text-2xl font-semibold text-gray-900 mb-2 border-b-4 border-purple-600 pb-1 inline-block group-hover:text-purple-600 transition-colors duration-300">
                        Program Bahasa Asing
                    </h3>
                    <p class="text-gray-600 leading-relaxed">
                        Penguasaan bahasa Arab dan Inggris sebagai bahasa komunikasi utama dan pendukung akademik.
                    </p>
                </div>

                <!-- Program 5 -->
                <div class="group cursor-pointer">
                    <div class="text-7xl text-pink-600 mb-4 transition-transform duration-300 group-hover:scale-110">
                        <i class="fas fa-praying-hands"></i>
                    </div>
                    <h3
                        class="text-2xl font-semibold text-gray-900 mb-2 border-b-4 border-pink-600 pb-1 inline-block group-hover:text-pink-600 transition-colors duration-300">
                        Program Ibadah & Spiritual
                    </h3>
                    <p class="text-gray-600 leading-relaxed">
                        Pendalaman ilmu agama, shalat berjamaah, kajian rutin, dan penguatan spiritualitas siswa.
                    </p>
                </div>

                <!-- Program 6 -->
                <div class="group cursor-pointer">
                    <div class="text-7xl text-green-600 mb-4 transition-transform duration-300 group-hover:scale-110">
                        <i class="fas fa-tree"></i>
                    </div>
                    <h3
                        class="text-2xl font-semibold text-gray-900 mb-2 border-b-4 border-green-600 pb-1 inline-block group-hover:text-green-600 transition-colors duration-300">
                        Program Kemandirian & Kewirausahaan
                    </h3>
                    <p class="text-gray-600 leading-relaxed">
                        Pelatihan kewirausahaan dan kemandirian untuk membentuk karakter siswa yang siap mandiri dan
                        berdaya saing.
                    </p>
                </div>

                <!-- Program 7 -->
                <div class="group cursor-pointer">
                    <div class="text-7xl text-indigo-600 mb-4 transition-transform duration-300 group-hover:scale-110">
                        <i class="fas fa-bed"></i>
                    </div>
                    <h3
                        class="text-2xl font-semibold text-gray-900 mb-2 border-b-4 border-indigo-600 pb-1 inline-block group-hover:text-indigo-600 transition-colors duration-300">
                        Program Boarding Life & Soft Skills
                    </h3>
                    <p class="text-gray-600 leading-relaxed">
                        Pengelolaan kehidupan asrama yang mendukung pembentukan karakter mandiri, disiplin, dan kerja
                        sama tim.
                    </p>
                </div>

                <!-- Program 8 -->
                <div class="group cursor-pointer">
                    <div class="text-7xl text-red-600 mb-4 transition-transform duration-300 group-hover:scale-110">
                        <i class="fas fa-heartbeat"></i>
                    </div>
                    <h3
                        class="text-2xl font-semibold text-gray-900 mb-2 border-b-4 border-red-600 pb-1 inline-block group-hover:text-red-600 transition-colors duration-300">
                        Program Kesehatan & Kebugaran
                    </h3>
                    <p class="text-gray-600 leading-relaxed">
                        Kegiatan olahraga rutin dan edukasi gaya hidup sehat untuk menjaga kebugaran dan stamina siswa.
                    </p>
                </div>

                <!-- Program 9 -->
                <div class="group cursor-pointer">
                    <div class="text-7xl text-teal-600 mb-4 transition-transform duration-300 group-hover:scale-110">
                        <i class="fas fa-glasses"></i>
                    </div>
                    <h3
                        class="text-2xl font-semibold text-gray-900 mb-2 border-b-4 border-teal-600 pb-1 inline-block group-hover:text-teal-600 transition-colors duration-300">
                        Program Literasi & Studi Mandiri
                    </h3>
                    <p class="text-gray-600 leading-relaxed">
                        Fasilitasi pengembangan kemampuan belajar mandiri dan literasi informasi melalui perpustakaan
                        dan workshop.
                    </p>
                </div>

            </div>
        </div>
    </section>


    <!-- Fasilitas Section - Tab Layout -->
    <section id="fasilitas" class="py-20 bg-gray-75">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h2 class="text-4xl font-bold text-emerald-700 mb-6">Fasilitas Kami</h2>
            <p class="text-lg mb-12 max-w-3xl mx-auto">
                Al-Furqanul Huda International Boarding School menyediakan berbagai fasilitas untuk menunjang proses
                belajar mengajar,
                yang dirancang untuk memberikan pengalaman terbaik bagi setiap siswa.
            </p>

            <!-- Tabs Container -->
            <div class="tabs mb-12 relative">
                <!-- Arrow Buttons -->
                <button class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-white p-2 rounded-full shadow-lg"
                    id="prevTab">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                </button>
                <!-- Tabs Container with Center Alignment -->
                <div class="tabs-content flex justify-center space-x-4 pb-2 overflow-x-auto" id="tabsContainer">
                    <button
                        class="tab-btn hidden  text-lg font-medium text-emerald-700 border-b-4 border-transparent hover:border-blue-600 pb-2 transition duration-200"
                        data-tab="lab" data-index="0">Laboratorium</button>
                    <button
                        class="tab-btn hidden  text-lg font-medium text-emerald-700 border-b-4 border-transparent hover:border-blue-600 pb-2 transition duration-200"
                        data-tab="kelas" data-index="1">Kelas</button>
                    <button
                        class="tab-btn hidden  text-lg font-medium text-emerald-700 border-b-4 border-transparent hover:border-blue-600 pb-2 transition duration-200"
                        data-tab="olahraga" data-index="2">Olahraga</button>
                    <button
                        class="tab-btn hidden  text-lg font-medium text-emerald-700 border-b-4 border-transparent hover:border-blue-600 pb-2 transition duration-200"
                        data-tab="perpustakaan" data-index="3">Perpustakaan</button>
                    <button
                        class="tab-btn hidden  text-lg font-medium text-emerald-700 border-b-4 border-transparent hover:border-blue-600 pb-2 transition duration-200"
                        data-tab="kantin" data-index="4">Kantin</button>
                    <button
                        class="tab-btn hidden  text-lg font-medium text-emerald-700 border-b-4 border-transparent hover:border-blue-600 pb-2 transition duration-200"
                        data-tab="asrama" data-index="5">Asrama</button>
                    <button
                        class="tab-btn hidden  text-lg font-medium text-emerald-700 border-b-4 border-transparent hover:border-blue-600 pb-2 transition duration-200"
                        data-tab="wellness" data-index="6">Pusat Konseling & Kesehatan</button>
                    <button
                        class="tab-btn hidden  text-lg font-medium text-emerald-700 border-b-4 border-transparent hover:border-blue-600 pb-2 transition duration-200"
                        data-tab="auditorium" data-index="7">Auditorium</button>
                    <button
                        class="tab-btn hidden  text-lg font-medium text-emerald-700 border-b-4 border-transparent hover:border-blue-600 pb-2 transition duration-200"
                        data-tab="tech" data-index="8">Pusat Teknologi</button>
                    <button
                        class="tab-btn hidden  text-lg font-medium text-emerald-700 border-b-4 border-transparent hover:border-blue-600 pb-2 transition duration-200"
                        data-tab="green" data-index="9">Taman Hijau</button>
                    <button
                        class="tab-btn hidden  text-lg font-medium text-emerald-700 border-b-4 border-transparent hover:border-blue-600 pb-2 transition duration-200"
                        data-tab="lounge" data-index="10">Lounge Orang Tua</button>
                    <button
                        class="tab-btn hidden  text-lg font-medium text-emerald-700 border-b-4 border-transparent hover:border-blue-600 pb-2 transition duration-200"
                        data-tab="amphitheater" data-index="11">Amfiteater Outdoor</button>
                </div>
                <!-- Arrow Buttons -->
                <button class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-white p-2 rounded-full shadow-lg"
                    id="nextTab">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                        </path>
                    </svg>
                </button>
            </div>



            <!-- Tabs Content -->
            <div class="tab-content mt-8">
                <!-- Tab 1: Laboratorium -->
                <div id="lab" class="tab-pane hidden">
                    <div class="flex justify-center gap-8">
                        <div class="w-1/2">
                            <img src="{{ asset('img/Foto Lab.png') }}" alt="Laboratorium"
                                class="w-full h-64 object-cover rounded-lg shadow-lg">
                        </div>
                        <div class="w-1/2 text-left">
                            <h3 class="text-2xl font-semibold text-emerald-700 mb-4">Laboratorium Sains</h3>
                            <p class="text-lg text-gray-600">
                                Laboratorium kami dilengkapi dengan berbagai peralatan modern untuk mendukung
                                praktikum
                                sains yang menarik dan edukatif, memberikan pengalaman belajar yang lebih mendalam.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Tab 2: Kelas -->
                <div id="kelas" class="tab-pane hidden">
                    <div class="flex justify-center gap-8">
                        <div class="w-1/2">
                            <img src="{{ asset('img/Foto Ruang Kelas.png') }}" alt="Kelas"
                                class="w-full h-64 object-cover rounded-lg shadow-lg">
                        </div>
                        <div class="w-1/2 text-left">
                            <h3 class="text-2xl font-semibold text-emerald-700 mb-4">Ruang Kelas Modern</h3>
                            <p class="text-lg text-gray-600">
                                Setiap ruang kelas kami dilengkapi dengan teknologi canggih seperti proyektor dan
                                perangkat lunak interaktif untuk mendukung pengalaman belajar yang lebih menarik dan
                                dinamis.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Tab 3: Olahraga -->
                <div id="olahraga" class="tab-pane hidden">
                    <div class="flex justify-center gap-8">
                        <div class="w-1/2">
                            <img src="{{ asset('img/Foto Ruang Olahraga.png') }}" alt="Olahraga"
                                class="w-full h-64 object-cover rounded-lg shadow-lg">
                        </div>
                        <div class="w-1/2 text-left">
                            <h3 class="text-2xl font-semibold text-emerald-700 mb-4">Ruang Olahraga</h3>
                            <p class="text-lg text-gray-600">
                                Fasilitas olahraga kami meliputi lapangan bola, basket, dan ruang fitness untuk
                                menjaga kebugaran dan kesehatan siswa.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Tab 4: Perpustakaan -->
                <div id="perpustakaan" class="tab-pane hidden">
                    <div class="flex justify-center gap-8">
                        <div class="w-1/2">
                            <img src="{{ asset('img/Foto Ruang Library.png') }}" alt="Perpustakaan"
                                class="w-full h-64 object-cover rounded-lg shadow-lg">
                        </div>
                        <div class="w-1/2 text-left">
                            <h3 class="text-2xl font-semibold text-emerald-700 mb-4">Perpustakaan Digital</h3>
                            <p class="text-lg text-gray-600">
                                Kami memiliki perpustakaan digital dengan berbagai koleksi buku elektronik yang
                                dapat
                                diakses oleh siswa kapan saja untuk mendukung belajar mandiri.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- New Tab 5: Kantin -->
                <div id="kantin" class="tab-pane hidden">
                    <div class="flex justify-center gap-8">
                        <div class="w-1/2">
                            <img src="{{ asset('img/Foto Kantin.png') }}" alt="Kantin"
                                class="w-full h-64 object-cover rounded-lg shadow-lg">
                        </div>
                        <div class="w-1/2 text-left">
                            <h3 class="text-2xl font-semibold text-emerald-700 mb-4">Kantin Modern</h3>
                            <p class="text-lg text-gray-600">
                                Kantin kami menyediakan berbagai pilihan makanan sehat dan lezat dengan suasana yang
                                nyaman,
                                memberikan pengalaman makan yang menyenangkan bagi para siswa.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- New Tab 6: Asrama -->
                <div id="asrama" class="tab-pane hidden">
                    <div class="flex justify-center gap-8">
                        <div class="w-1/2">
                            <img src="{{ asset('img/Foto Asrama.png') }}" alt="Asrama"
                                class="w-full h-64 object-cover rounded-lg shadow-lg">
                        </div>
                        <div class="w-1/2 text-left">
                            <h3 class="text-2xl font-semibold text-emerald-700 mb-4">Asrama Siswa</h3>
                            <p class="text-lg text-gray-600">
                                Asrama kami dilengkapi dengan fasilitas lengkap dan nyaman, memberikan tempat
                                tinggal yang
                                aman dan mendukung perkembangan pribadi siswa.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- New Tab 7: Pusat Konseling & Kesehatan -->
                <div id="wellness" class="tab-pane hidden">
                    <div class="flex justify-center gap-8">
                        <div class="w-1/2">
                            <img src="{{ asset('img/Foto Konseling & Kesehatan.png') }}"
                                alt="Pusat Konseling & Kesehatan"
                                class="w-full h-64 object-cover rounded-lg shadow-lg">
                        </div>
                        <div class="w-1/2 text-left">
                            <h3 class="text-2xl font-semibold text-emerald-700 mb-4">Pusat Konseling & Kesehatan
                            </h3>
                            <p class="text-lg text-gray-600">
                                Fasilitas ini menyediakan ruang konseling dan dukungan kesehatan mental bagi siswa,
                                membantu mereka mengelola stres dan tantangan emosional.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- New Tab 8: Auditorium -->
                <div id="auditorium" class="tab-pane hidden">
                    <div class="flex justify-center gap-8">
                        <div class="w-1/2">
                            <img src="{{ asset('img/Foto Auditorium.png') }}" alt="Auditorium"
                                class="w-full h-64 object-cover rounded-lg shadow-lg">
                        </div>
                        <div class="w-1/2 text-left">
                            <h3 class="text-2xl font-semibold text-emerald-700 mb-4">Auditorium Sekolah</h3>
                            <p class="text-lg text-gray-600">
                                Auditorium kami dilengkapi dengan teknologi audio-visual terkini, ideal untuk
                                pertunjukan
                                seni, seminar, dan acara besar lainnya.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- New Tab 9: Pusat Teknologi -->
                <div id="tech" class="tab-pane hidden">
                    <div class="flex justify-center gap-8">
                        <div class="w-1/2">
                            <img src="{{ asset('img/Foto Pusat Teknologi.png') }}" alt="Pusat Teknologi"
                                class="w-full h-64 object-cover rounded-lg shadow-lg">
                        </div>
                        <div class="w-1/2 text-left">
                            <h3 class="text-2xl font-semibold text-emerald-700 mb-4">Pusat Teknologi</h3>
                            <p class="text-lg text-gray-600">
                                Kami memiliki pusat teknologi lengkap dengan perangkat komputer, VR, dan ruang untuk
                                pengembangan teknologi inovatif.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- New Tab 10: Taman Hijau -->
                <div id="green" class="tab-pane hidden">
                    <div class="flex justify-center gap-8">
                        <div class="w-1/2">
                            <img src="{{ asset('img/Foto Taman Hijau.png') }}" alt="Taman Hijau"
                                class="w-full h-64 object-cover rounded-lg shadow-lg">
                        </div>
                        <div class="w-1/2 text-left">
                            <h3 class="text-2xl font-semibold text-emerald-700 mb-4">Taman Hijau</h3>
                            <p class="text-lg text-gray-600">
                                Taman hijau yang luas memberikan area terbuka bagi siswa untuk bersantai dan
                                berolahraga di luar ruangan.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- New Tab 11: Lounge Orang Tua -->
                <div id="lounge" class="tab-pane hidden">
                    <div class="flex justify-center gap-8">
                        <div class="w-1/2">
                            <img src="{{ asset('img/Foto Lounge.png') }}" alt="Lounge Orang Tua"
                                class="w-full h-64 object-cover rounded-lg shadow-lg">
                        </div>
                        <div class="w-1/2 text-left">
                            <h3 class="text-2xl font-semibold text-emerald-700 mb-4">Lounge Orang Tua</h3>
                            <p class="text-lg text-gray-600">
                                Lounge khusus untuk orang tua yang menyediakan area nyaman untuk menunggu, bekerja,
                                atau beristirahat.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- New Tab 12: Amfiteater Outdoor -->
                <div id="amphitheater" class="tab-pane hidden">
                    <div class="flex justify-center gap-8">
                        <div class="w-1/2">
                            <img src="{{ asset('img/Foto Amfiteater.png') }}" alt="Amfiteater Outdoor"
                                class="w-full h-64 object-cover rounded-lg shadow-lg">
                        </div>
                        <div class="w-1/2 text-left">
                            <h3 class="text-2xl font-semibold text-emerald-700 mb-4">Amfiteater Outdoor</h3>
                            <p class="text-lg text-gray-600">
                                Amfiteater outdoor kami dapat digunakan untuk acara besar, pertunjukan seni, atau
                                kegiatan luar ruangan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Jadwal Stepper Section -->
    <section id="jadwal" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h2 class="text-4xl font-bold text-emerald-700 mb-12">Jadwal Pendaftaran PPDB</h2>

            <!-- Info Alert Jadwal -->
            <div class="mb-10 rounded-xl bg-yellow-100 border-l-8 border-yellow-500 p-5 text-left shadow-md">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l6.518 11.596c.75 1.336-.213 3.005-1.742 3.005H3.48c-1.53 0-2.493-1.669-1.743-3.005L8.257 3.1zM11 14a1 1 0 10-2 0 1 1 0 002 0zm-1-2a1 1 0 01-1-1V9a1 1 0 112 0v2a1 1 0 01-1 1z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-yellow-800 bold">Pengumuman</h3>
                        <p class="mt-1 text-sm text-yellow-700 leading-relaxed">
                            Informasi jadwal di bawah ditampilkan berdasarkan data sistem dan tidak selalu diperbarui
                            secara otomatis saat ada perubahan.
                            Panitia dapat menutup atau membuka pendaftaran lebih awal tanpa mengubah data di tampilan
                            ini.
                            Perubahan tersebut dapat terjadi sewaktu-waktu sesuai dengan <span
                                class="font-semibold text-yellow-800">kebijakan ketua yayasan</span> atau keputusan
                            panitia.
                            <strong>Pastikan untuk selalu memperhatikan label status</strong> di setiap jadwal untuk
                            mengetahui kondisi terbaru.
                        </p>
                    </div>
                </div>
            </div>


            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @php
                    use Carbon\Carbon;
                    Carbon::setLocale('id');
                    $now = Carbon::now();
                @endphp

                @forelse($jadwalPendaftaran as $index => $jadwal)
                    @php
                        $mulai = Carbon::parse($jadwal->tanggal_mulai_jadwal_pendaftaran);
                        $selesai = Carbon::parse($jadwal->tanggal_selesai_jadwal_pendaftaran)->endOfDay();

                        $statusDB = $jadwal->status_jadwal_pendaftaran;

                        if ($statusDB === 'Opened' && !$now->between($mulai, $selesai)) {
                            // Status DB bilang 'Opened' tapi tanggal tidak sesuai
                            if ($now->lt($mulai)) {
                                $status = ['label' => 'Sedang Dibuka', 'color' => 'bg-green-500'];
                            } else {
                                $status = ['label' => 'Sudah Ditutup', 'color' => 'bg-red-500'];
                            }
                        } elseif ($statusDB === 'Ongoing' && $now->gte($mulai)) {
                            // Status DB bilang 'Ongoing' tapi seharusnya sudah dibuka
                            if ($now->between($mulai, $selesai)) {
                                $status = ['label' => 'Sedang Dibuka', 'color' => 'bg-green-500'];
                            } else {
                                $status = ['label' => 'Sudah Ditutup', 'color' => 'bg-red-500'];
                            }
                        } elseif ($statusDB === 'Closed') {
                            $status = ['label' => 'Sudah Ditutup', 'color' => 'bg-red-500'];
                        } else {
                            // Jika status dan waktu cocok
                            switch ($statusDB) {
                                case 'Ongoing':
                                    $status = ['label' => 'Segera Dibuka', 'color' => 'bg-yellow-400'];
                                    break;
                                case 'Opened':
                                    $status = ['label' => 'Sedang Dibuka', 'color' => 'bg-green-500'];
                                    break;
                                default:
                                    $status = ['label' => 'Sudah Ditutup', 'color' => 'bg-red-500'];
                                    break;
                            }
                        }

                    @endphp



                    <div class="bg-white rounded-xl shadow-lg p-8 relative w-full">
                        <!-- Badge status -->
                        <div
                            class="absolute top-1 right-4 px-3 py-1 text-xs font-semibold text-white rounded-full {{ $status['color'] }}">
                            {{ $status['label'] }}
                        </div>

                        <!-- Header -->
                        <h3 class="text-2xl font-bold text-emerald-700 mb-2">
                            {{ $jadwal->gelombang_pendaftaran }}</h3>

                        <!-- Jadwal -->
                        <p class="text-gray-700 mb-1">📅 <strong>Mulai:</strong>
                            {{ $mulai->translatedFormat('d F Y') }}</p>
                        <p class="text-gray-700 mb-4">📅 <strong>Selesai:</strong>
                            {{ $selesai->translatedFormat('d F Y') }}</p>
                        <p class="text-sm text-gray-600 mt-2">
                            👥 <strong>{{ $jadwal->jumlah_daftar }}</strong> dari
                            <strong>{{ $jadwal->kuota_akun }}</strong> akun terdaftar.
                        </p>

                        @if ($jadwal->jumlah_daftar >= $jadwal->kuota_akun)
                            <div class="mt-3 p-3 bg-red-100 text-red-700 rounded-md font-semibold">
                                ⚠️ Kuota pendaftaran sudah penuh!
                            </div>
                        @endif


                        <!-- Button -->
                        <button
                            class="text-emerald-600 font-semibold underline text-sm hover:text-emerald-800 transition"
                            onclick="toggleDetail('detail{{ $index + 1 }}')">
                            Lihat Detail Jadwal
                        </button>

                        <!-- Detail -->
                        <div id="detail{{ $index + 1 }}"
                            class="hidden mt-4 text-sm text-left space-y-3 text-gray-700">
                            <p class="flex items-center gap-2">
                                <i class="fa-solid fa-user-check text-emerald-600 w-5"></i>
                                <strong>Verifikasi:</strong>
                                {{ Carbon::parse($jadwal->tanggal_mulai_verifikasi)->translatedFormat('d F Y') }} -
                                {{ Carbon::parse($jadwal->tanggal_selesai_verifikasi)->translatedFormat('d F Y') }}
                            </p>
                            <p class="flex items-center gap-2">
                                <i class="fa-solid fa-id-card text-yellow-600 w-5"></i>
                                <strong>Deadline Biodata:</strong>
                                {{ Carbon::parse($jadwal->deadline_biodata_calon_siswa)->translatedFormat('d F Y') }}
                            </p>
                            <p class="flex items-center gap-2">
                                <i class="fa-solid fa-upload text-blue-600 w-5"></i>
                                <strong>Deadline Upload:</strong>
                                {{ Carbon::parse($jadwal->deadline_upload_pendaftaran)->translatedFormat('d F Y') }}
                            </p>
                            <p class="flex items-center gap-2">
                                <i class="fa-solid fa-bullhorn text-red-600 w-5"></i>
                                <strong>Pengumuman Seleksi:</strong>
                                {{ Carbon::parse($jadwal->pengumuman_hasil_seleksi)->translatedFormat('d F Y') }}
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="col-span-3 text-lg text-red-500">Belum ada jadwal pendaftaran untuk periode aktif.</p>
                @endforelse
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Form Pendaftaran Section -->
    <section id="form-pendaftaran" class="py-20 bg-gradient-to-r from-gray-100 to-gray-300">
        <!-- Section Title -->
        <div class="text-center mb-12">
            <h2 class="text-4xl font-extrabold text-emerald-700 mb-6">Pendaftaran PPDB Al-Furqanul Huda</h2>
            <p class="text-lg max-w-3xl mx-auto text-gray-600">
                Isi formulir di bawah ini untuk mendaftar di Al-Furqanul Huda EduSystem. Kami akan segera memproses
                pendaftaran Anda.
            </p>
        </div>

        <div class="max-w-7xl mx-auto px-6 flex items-center justify-between space-x-12">
            <!-- Form Section -->
            <div class="flex-1">
                <!-- Form -->
                <form id="pendaftaranForm" action="{{ route('pendaftaran.guest.store') }}" method="POST"
                    class="space-y-8 p-12 rounded-3xl shadow-xl transform transition duration-300 hover:shadow-2xl bg-white">
                    @csrf
                    <div>
                        <label for="nama_calon_siswa" class="block text-lg font-medium text-gray-800">Nama
                            Lengkap</label>
                        <input type="text" id="name" name="nama_calon_siswa" required
                            class="w-full p-4 mt-2 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-opacity-50 placeholder-gray-500 transition duration-300 ease-in-out bg-white border border-gray-300 hover:border-emerald-600">
                    </div>

                    <div>
                        <label for="email_calon_siswa" class="block text-lg font-medium text-gray-800">Email</label>
                        <input type="email" id="email" name="email_calon_siswa" required
                            class="w-full p-4 mt-2 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-opacity-50 placeholder-gray-500 transition duration-300 ease-in-out bg-white border border-gray-300 hover:border-emerald-600">
                    </div>

                    <div class="relative">
                        <label for="password" class="block text-lg font-medium text-gray-800">Password</label>
                        <input type="password" id="password" name="password" required
                            class="w-full p-4 mt-2 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-opacity-50 placeholder-gray-500 transition duration-300 ease-in-out bg-white border border-gray-300 hover:border-emerald-600">

                        <span id="clear-password"
                            class="absolute right-10 top-2/3 transform -translate-y-1/4 cursor-pointer text-gray-400 hover:text-red-500 transition">
                            <i class="fas fa-times-circle"></i>
                        </span>
                        <!-- Mata Icon (untuk menampilkan/menyembunyikan password) -->
                        <span id="toggle-password"
                            class="absolute right-4 top-2/3 transform -translate-y-1/4 cursor-pointer">
                            <!-- Meningkatkan translate-y -->
                            <i class="fas fa-eye text-emerald-600"></i>
                        </span>

                    </div>


                    <div id="password-strength-container" class="mt-4 p-2 border border-gray-300 rounded-lg bg-white">
                        <span id="password-strength-text" class="text-lg text-gray-700">Kekuatan Password: </span>
                        <div id="password-strength-bar"
                            style="height: 10px; background-color: #ccc; border-radius: 5px;"></div>
                    </div>


                    <div>
                        <label class="block text-lg font-medium text-gray-800">Jenis Kelamin</label>
                        <div class="flex space-x-6 justify-center">
                            <label class="flex items-center">
                                <input type="radio" id="jenis_kelamin" name="jenis_kelamin" value="Laki-laki"
                                    required class="focus:ring-emerald-600">
                                <span class="ml-2 text-gray-600">Laki-laki</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" id="jenisKelaminP" name="jenis_kelamin" value="Perempuan"
                                    required class="focus:ring-emerald-600">
                                <span class="ml-2 text-gray-600">Perempuan</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        @if ($jadwalPendaftaranAktif && $jadwalPendaftaranAktif->jumlah_daftar < $jadwalPendaftaranAktif->kuota_akun)
                            <button type="submit"
                                class="w-full py-4 text-lg text-white bg-gradient-to-r from-emerald-600 to-emerald-700 rounded-2xl hover:from-emerald-700 hover:to-emerald-600 transition duration-500 ease-in-out transform hover:scale-105">
                                Daftar Sekarang
                            </button>
                        @else
                            <button type="submit" disabled
                                class="w-full py-4 text-lg text-white bg-gradient-to-r from-emerald-600 to-emerald-700 rounded-2xl hover:from-emerald-700 hover:to-emerald-600 transition duration-500 ease-in-out transform hover:scale-105">
                                Daftar Sekarang
                            </button>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Image Section -->
            <div class="flex-1 hidden lg:block">
                <img id="randomImage" alt="Pendaftaran" class="w-full h-auto rounded-3xl shadow-xl">
            </div>
        </div>
    </section>

    <script>
        // Ambil elemen password input dan ikon mata
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('toggle-password');
        const clearPassword = document.getElementById('clear-password');
        const eyeIcon = togglePassword.querySelector('i');

        // Fungsi untuk toggle visibility password
        togglePassword.addEventListener('click', function() {
            // Cek apakah password dalam mode input type 'password' atau 'text'
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text'; // Ubah menjadi text untuk menampilkan password
                eyeIcon.classList.remove('fa-eye'); // Ganti ikon mata tertutup
                eyeIcon.classList.add('fa-eye-slash'); // Ganti menjadi mata terbuka
            } else {
                passwordInput.type = 'password'; // Kembalikan menjadi password
                eyeIcon.classList.remove('fa-eye-slash'); // Ganti ikon mata terbuka
                eyeIcon.classList.add('fa-eye'); // Ganti kembali menjadi mata tertutup
            }
        });

        // Clear password
        clearPassword.addEventListener('click', function() {
            passwordInput.value = '';
            passwordInput.focus();
        });

        const passwordStrengthContainer = document.getElementById('password-strength-container');
        const passwordStrengthText = document.getElementById('password-strength-text');
        const passwordStrengthBar = document.getElementById('password-strength-bar');

        // Fungsi untuk mengecek kekuatan password
        passwordInput.addEventListener('input', function() {
            const password = passwordInput.value;

            if (password === '') {
                passwordStrengthContainer.style.display = 'none';
                return;
            } else {
                passwordStrengthContainer.style.display = 'block';
            }

            // Gunakan Zxcvbn untuk mengevaluasi kekuatan password
            const result = zxcvbn(password);

            // Menampilkan kekuatan password dalam bentuk teks
            switch (result.score) {
                case 0:
                    passwordStrengthText.textContent = 'Kekuatan Password: Sangat Lemah';
                    passwordStrengthBar.style.width = '20%';
                    passwordStrengthBar.style.backgroundColor = 'red';
                    break;
                case 1:
                    passwordStrengthText.textContent = 'Kekuatan Password: Lemah';
                    passwordStrengthBar.style.width = '40%';
                    passwordStrengthBar.style.backgroundColor = 'orange';
                    break;
                case 2:
                    passwordStrengthText.textContent = 'Kekuatan Password: Sedang';
                    passwordStrengthBar.style.width = '60%';
                    passwordStrengthBar.style.backgroundColor = 'yellow';
                    break;
                case 3:
                    passwordStrengthText.textContent = 'Kekuatan Password: Kuat';
                    passwordStrengthBar.style.width = '80%';
                    passwordStrengthBar.style.backgroundColor = 'lightgreen';
                    break;
                case 4:
                    passwordStrengthText.textContent = 'Kekuatan Password: Sangat Kuat';
                    passwordStrengthBar.style.width = '100%';
                    passwordStrengthBar.style.backgroundColor = 'green';
                    break;
            }
        });
    </script>


    <script>
        // Display SweetAlert when the form is submitted successfully
        const form = document.getElementById('pendaftaranForm');
        form.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission

            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Pastikan semua data yang Anda masukkan sudah benar!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Daftar!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Proceed with form submission
                    form.submit();

                    // Show success message after form submission
                    Swal.fire({
                        title: 'Pendaftaran Berhasil!',
                        text: 'Akun Anda telah berhasil didaftarkan.',
                        icon: 'success',
                        confirmButtonText: 'Ok'
                    }).then(() => {
                        form.reset(); // Reset form fields
                    });
                }
            });
        });
    </script>

    <!-- FAQ Section -->
    <section id="faq" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h2 class="text-4xl font-bold text-emerald-700 mb-6">FAQ (Frequently Asked Questions)</h2>
            <p class="text-lg mb-12 max-w-3xl mx-auto">
                Berikut adalah beberapa pertanyaan yang sering diajukan terkait dengan pendaftaran PPDB di Al-Furqanul
                Huda
                EduSystem.
            </p>

            <!-- Accordion -->
            <!-- Accordion -->
            <div class="space-y-4">
                @foreach ($faqs as $index => $faq)
                    <div class="border-b border-gray-200">
                        <button
                            class="w-full text-center py-4 text-lg font-semibold text-emerald-700 focus:outline-none"
                            onclick="toggleFaq({{ $index }})">
                            {{ $faq->question }}
                            <svg class="w-5 h-5 inline-block ml-2 transform transition-transform duration-300"
                                id="faq-icon{{ $index }}" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div id="faq-content{{ $index }}" class="faq-content hidden pl-6 pb-4 text-gray-600 ">
                            {!! $faq->answer !!}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Kontak Section -->
    <section id="kontak" class="py-20 bg-gray-100 relative">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16 max-w-3xl mx-auto">
                <h2 class="text-4xl md:text-5xl font-extrabold text-emerald-700 mb-4">Hubungi Kami</h2>
                <p class="text-lg text-gray-600">
                    Jangan ragu untuk menghubungi kami jika Anda memiliki pertanyaan, membutuhkan informasi, atau ingin
                    bekerjasama.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">

                <!-- Kontak Box -->
                <div class="bg-white rounded-xl shadow-md p-8 flex flex-col space-y-8">
                    <div class="flex items-center space-x-4">
                        <i class="fas fa-map-marker-alt text-emerald-600 text-2xl"></i>
                        <div>
                            <h4 class="font-semibold text-lg">Alamat</h4>
                            <p class="text-gray-700">{{ $contact->alamat }}</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <i class="fas fa-phone-alt text-emerald-600 text-2xl"></i>
                        <div>
                            <h4 class="font-semibold text-lg">Kontak</h4>
                            <p class="text-gray-700 mb-1">Telp: {{ $contact->telepon }}</p>
                            <p class="text-gray-700 mb-1">WA: {{ $contact->whatsapp }}</p>
                            <p class="text-gray-700">Email: {{ $contact->email }}</p>
                        </div>
                    </div>

                    <button id="openModalBtn"
                        class="mt-auto bg-emerald-700 hover:bg-emerald-600 text-white font-semibold py-3 rounded-lg transition">
                        Lihat Jadwal Operasional
                    </button>
                </div>

                <!-- Map Box -->
                <div class="rounded-xl shadow-md overflow-hidden border border-gray-200 h-[400px]" style="z-index:1;">
                    <div id="map" class="w-full h-full"></div>
                </div>

            </div>
        </div>

    </section>

    <!-- Modal -->
    <div id="operasionalModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-lg max-w-md w-full p-6 relative">
            <!-- Close button -->
            <button id="closeModalBtn" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <h4 class="font-semibold text-xl text-emerald-700 mb-4 flex items-center space-x-2">
                <i class="fas fa-clock"></i>
                <span>Jam Operasional</span>
            </h4>
            <table class="w-full text-gray-700">
                <tbody>
                    @foreach ($jamOperasionals as $jam)
                        <tr class="border-b">
                            <td class="py-2 font-medium">{{ ucfirst($jam->hari) }}</td>
                            <td
                                class="py-2 text-right font-semibold 
                {{ $jam->tutup_full ? 'text-red-600' : 'text-green-600' }}">
                                @if ($jam->tutup_full)
                                    Tutup
                                @else
                                    {{ \Carbon\Carbon::parse($jam->buka)->format('H.i') }} -
                                    {{ \Carbon\Carbon::parse($jam->tutup)->format('H.i') }} WIB
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>

    <script>
        const openBtn = document.getElementById('openModalBtn');
        const closeBtn = document.getElementById('closeModalBtn');
        const modal = document.getElementById('operasionalModal');

        openBtn.addEventListener('click', () => {
            modal.classList.remove('hidden');
        });

        closeBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
        });

        // Tutup modal saat klik di luar konten modal
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });
    </script>

    <!-- Floating WhatsApp Button -->
    <a href="https://wa.me/{{ $contact->whatsapp }}"
        class="wa-button fixed bottom-6 right-6 z-50 bg-emerald-900 hover:bg-emerald-700 text-white w-10 h-10 flex items-center justify-center rounded-full shadow-lg"
        target="_blank" aria-label="WhatsApp">
        <i class="fab fa-whatsapp text-xl"></i>
    </a>


    <!-- Footer -->
    <footer id="footer" class="bg-gradient-to-r from-emerald-700 to-emerald-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-12">

            <!-- Logo & Deskripsi -->
            <div>
                <h3 class="text-3xl font-bold mb-4 tracking-wide drop-shadow-lg">
                    Al-Furqanul Huda
                </h3>
                <p class="text-gray-200 leading-relaxed max-w-sm">
                    Lembaga pendidikan berbasis Islam yang berkomitmen membentuk generasi Qur’ani, cerdas, dan berakhlak
                    mulia.
                </p>
            </div>

            <!-- Navigasi -->
            <div>
                <h4 class="text-xl font-semibold mb-5 border-b border-white/30 pb-2">
                    Navigasi
                </h4>
                <ul class="space-y-3 text-gray-300 text-lg font-medium">
                    <li><a href="#hero" class="hover:text-white transition">Beranda</a></li>
                    <li><a href="#tentang" class="hover:text-white transition">Tentang Kami</a></li>
                    <li><a href="#program" class="hover:text-white transition">Program</a></li>
                    <li><a href="#kontak" class="hover:text-white transition">Kontak</a></li>
                </ul>
            </div>

            <!-- Kontak & Sosial Media -->
            <div>
                <h4 class="text-xl font-semibold mb-5 border-b border-white/30 pb-2">
                    Kontak
                </h4>
                <ul class="text-gray-300 space-y-2 text-lg font-medium">
                    <li>📍 {{ $contact->alamat }}</li>
                    <li>📞 {{ $contact->telepon }}</li>
                    <li>📧 {{ $contact->email }}</li>
                </ul>

                <div class="flex space-x-6 mt-6 text-white text-2xl drop-shadow-lg">
                    <a href="{{ $contact->facebook }}" aria-label="Facebook" class="hover:text-gray-300 transition">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="{{ $contact->instagram }}" aria-label="Instagram"
                        class="hover:text-gray-300 transition">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="{{ $contact->youtube }}" aria-label="YouTube" class="hover:text-gray-300 transition">
                        <i class="fab fa-youtube"></i>
                    </a>
                    <a href="{{ $contact->tiktok }}" aria-label="TikTok" class="hover:text-gray-300 transition">
                        <i class="fab fa-tiktok"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="mt-14 border-t border-white/20 pt-6 text-center text-sm text-white/70 select-none tracking-wide">
            &copy; 2025 Ache. All rights reserved.
        </div>
    </footer>



    <a href="#" id="scrollToTop"
        class="fixed bottom-6 left-6 z-50 bg-emerald-900 hover:bg-emerald-700 text-white w-10 h-10 flex items-center justify-center rounded-full shadow-lg hidden"
        aria-label="Scroll to top">
        <i class="fas fa-arrow-up text-xl"></i>
    </a>

    <script src="https://cdn.jsdelivr.net/npm/zxcvbn@4.4.2/dist/zxcvbn.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        const latitude = {{ $contact->latitude ?? '-6.208763' }};
        const longitude = {{ $contact->longitude ?? '106.845130' }};

        const map = L.map('map').setView([latitude, longitude], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        L.marker([latitude, longitude]).addTo(map)
            .bindPopup("{{ $contact->alamat ?? 'Lokasi tidak ditemukan' }}")
            .openPopup();
    </script>


    <script>
        const waButton = document.querySelector('.wa-button');
        const footer = document.getElementById('footer');

        window.addEventListener('scroll', () => {
            const footerTop = footer.getBoundingClientRect().top;
            const windowHeight = window.innerHeight;

            if (footerTop < windowHeight) {
                waButton.classList.remove('bg-emerald-900', 'hover:bg-emerald-700');
                waButton.classList.add('bg-white', 'text-emerald-900');
            } else {
                waButton.classList.remove('bg-white', 'text-emerald-900');
                waButton.classList.add('bg-emerald-900', 'hover:bg-emerald-700');
            }
        });
    </script>

    <script>
        // Array with image paths
        const images = [
            '{{ asset('img/Foto Pendaftaran.png') }}', // Foto Pendaftaran 1
            '{{ asset('img/Foto Pendaftaran 2.png') }}', // Foto Pendaftaran 2
            '{{ asset('img/Foto Pendaftaran 3.png') }}' // Foto Pendaftaran 3
        ];

        // Function to set random image
        function setRandomImage() {
            const randomIndex = Math.floor(Math.random() * images.length);
            document.getElementById("randomImage").src = images[randomIndex];
        }

        // Change image every 10 seconds (10000 milliseconds)
        setInterval(setRandomImage, 10000);

        // Set the first image immediately
        setRandomImage();
    </script>

    <script>
        // Accordion functionality
        document.querySelectorAll('.faq-content').forEach(function(content) {
            content.previousElementSibling.addEventListener('click', function() {
                const isOpen = content.classList.contains('hidden');
                // Close all other open content
                document.querySelectorAll('.faq-content').forEach(function(faq) {
                    faq.classList.add('hidden');
                    faq.previousElementSibling.querySelector('svg').classList.remove('rotate-180');
                });

                // Toggle the clicked content
                if (isOpen) {
                    content.classList.remove('hidden');
                    content.previousElementSibling.querySelector('svg').classList.add('rotate-180');
                }
            });
        });
    </script>


    {{-- js jadwal detail --}}
    <script>
        // Function to toggle the detail section and show the line
        function toggleDetail(detailId, lineId) {
            const detailElement = document.getElementById(detailId);
            const lineElement = document.getElementById(lineId);

            if (detailElement.classList.contains('hidden')) {
                detailElement.classList.remove('hidden');
                lineElement.classList.remove('hidden'); // Show line after expanding
            } else {
                detailElement.classList.add('hidden');
                lineElement.classList.add('hidden'); // Hide line after collapsing
            }
        }
    </script>

    {{-- js fasilitas section --}}
    <script>
        window.onload = function() {
            const tabs = document.querySelectorAll('.tab-btn');
            const contents = document.querySelectorAll('.tab-pane');
            const prevBtn = document.getElementById("prevTab");
            const nextBtn = document.getElementById("nextTab");
            const tabContainer = document.querySelector('.tabs-content');

            const tabsPerPage = 3;
            let currentPage = 0;

            function showTabPage(page) {
                const start = page * tabsPerPage;
                const end = start + tabsPerPage;

                tabs.forEach((tab, index) => {
                    if (index >= start && index < end) {
                        tab.classList.remove('hidden');
                    } else {
                        tab.classList.add('hidden');
                    }
                });

                // Auto-click the first visible tab on this page
                const firstVisibleTab = [...tabs].find((tab, index) => index >= start && index < end);
                if (firstVisibleTab) {
                    firstVisibleTab.click();
                }

                // Disable prev/next button if on first/last page
                prevBtn.disabled = currentPage === 0;
                nextBtn.disabled = end >= tabs.length;
            }

            function resetTabs() {
                tabs.forEach(tab => {
                    tab.classList.remove('border-emerald-600', 'text-emerald-600');
                    tab.classList.add('border-transparent');
                });
                contents.forEach(content => content.classList.add('hidden'));
            }

            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    resetTabs();

                    const target = tab.getAttribute('data-tab');
                    tab.classList.remove('border-transparent');
                    tab.classList.add('border-emerald-600', 'text-emerald-600');
                    document.getElementById(target).classList.remove('hidden');
                });
            });

            prevBtn.addEventListener('click', function() {
                if (currentPage > 0) {
                    currentPage--;
                    showTabPage(currentPage);
                }
            });

            nextBtn.addEventListener('click', function() {
                if ((currentPage + 1) * tabsPerPage < tabs.length) {
                    currentPage++;
                    showTabPage(currentPage);
                }
            });

            // Inisialisasi tampilan pertama
            showTabPage(currentPage);
        };
    </script>


    {{-- js navbar --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const links = document.querySelectorAll('a[href^="#"]'); // Ambil semua link yang mengarah ke ID

            links.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault(); // Mencegah perubahan URL

                    const targetId = link.getAttribute('href').substring(
                        1); // Ambil ID dari href (misalnya #tentang)
                    const targetElement = document.getElementById(
                        targetId); // Temukan elemen dengan ID tersebut

                    if (targetElement) {
                        targetElement.scrollIntoView({
                            behavior: 'smooth'
                        }); // Gulir ke elemen target dengan smooth scrolling
                    }
                });
            });
        });
    </script>

    <script>
        // Scroll to Top Button Visibility and Functionality
        document.addEventListener('scroll', function() {
            const scrollToTopButton = document.getElementById('scrollToTop');
            if (window.scrollY > 300) { // Show the button after scrolling 300px down
                scrollToTopButton.classList.remove('hidden');
            } else {
                scrollToTopButton.classList.add('hidden');
            }
        });

        document.getElementById('scrollToTop').addEventListener('click', function(e) {
            e.preventDefault(); // Prevent default anchor behavior
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    </script>

</body>

</html>

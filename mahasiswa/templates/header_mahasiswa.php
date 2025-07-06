<?php
// Pastikan session sudah dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek jika pengguna belum login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Panel Mahasiswa - <?php echo $pageTitle ?? 'Dashboard'; ?></title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#F97316',
                        'primary-dark': '#C2410C',
                        'primary-light': '#FB923C',
                    }
                }
            }
        }
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 font-sans min-h-screen">

    <!-- Navigation Bar -->
    <nav class="bg-gradient-to-r from-orange-600 via-orange-700 to-red-700 shadow-2xl border-b border-orange-500/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Logo Section -->
                <div class="flex items-center">
                    <div class="flex-shrink-0 group">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm border border-white/30 group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-graduation-cap text-white text-xl"></i>
                            </div>
                            <div>
                                <span class="text-white text-2xl font-bold tracking-wide">SIMPRAK</span>
                                <p class="text-orange-200 text-xs font-medium -mt-1">Sistem Praktikum</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Desktop Navigation -->
                    <div class="hidden md:block ml-12">
                        <div class="flex items-center space-x-2">
                            <?php 
                                $activeClass = 'bg-white/20 text-white shadow-lg backdrop-blur-sm border border-white/30';
                                $inactiveClass = 'text-orange-100 hover:bg-white/10 hover:text-white hover:backdrop-blur-sm';
                            ?>
                            <a href="dashboard.php" class="<?php echo ($activePage == 'dashboard') ? $activeClass : $inactiveClass; ?> px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-300 flex items-center space-x-2">
                                <i class="fas fa-home text-sm"></i>
                                <span>Dashboard</span>
                            </a>
                            <a href="my_courses.php" class="<?php echo ($activePage == 'my_courses') ? $activeClass : $inactiveClass; ?> px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-300 flex items-center space-x-2">
                                <i class="fas fa-book text-sm"></i>
                                <span>Praktikum Saya</span>
                            </a>
                            <a href="courses.php" class="<?php echo ($activePage == 'courses') ? $activeClass : $inactiveClass; ?> px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-300 flex items-center space-x-2">
                                <i class="fas fa-search text-sm"></i>
                                <span>Cari Praktikum</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- User Section -->
                <div class="hidden md:flex items-center space-x-4">
                    <!-- User Info -->
                    <div class="flex items-center space-x-3 bg-white/10 backdrop-blur-sm rounded-xl px-4 py-2 border border-white/20">
                        <div class="w-8 h-8 bg-gradient-to-br from-orange-400 to-orange-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-white text-sm"></i>
                        </div>
                        <div class="text-white">
                            <p class="text-sm font-medium">
                                <?php echo isset($_SESSION['nama']) ? $_SESSION['nama'] : 'Mahasiswa'; ?>
                            </p>
                            <p class="text-xs text-orange-200">Mahasiswa</p>
                        </div>
                    </div>
                    
                    <!-- Logout Button -->
                    <a href="../logout.php" class="group bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-medium py-2.5 px-5 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center space-x-2">
                        <i class="fas fa-sign-out-alt text-sm group-hover:rotate-12 transition-transform duration-300"></i>
                        <span>Logout</span>
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button id="mobile-menu-btn" class="text-white hover:text-orange-200 p-2">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="md:hidden hidden bg-orange-800/95 backdrop-blur-sm border-t border-orange-500/20">
            <div class="px-4 pt-4 pb-6 space-y-2">
                <a href="dashboard.php" class="<?php echo ($activePage == 'dashboard') ? 'bg-orange-700 text-white' : 'text-orange-100 hover:bg-orange-700 hover:text-white'; ?> block px-4 py-3 rounded-lg text-base font-medium transition-colors duration-200 flex items-center space-x-3">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
                <a href="my_courses.php" class="<?php echo ($activePage == 'my_courses') ? 'bg-orange-700 text-white' : 'text-orange-100 hover:bg-orange-700 hover:text-white'; ?> block px-4 py-3 rounded-lg text-base font-medium transition-colors duration-200 flex items-center space-x-3">
                    <i class="fas fa-book"></i>
                    <span>Praktikum Saya</span>
                </a>
                <a href="courses.php" class="<?php echo ($activePage == 'courses') ? 'bg-orange-700 text-white' : 'text-orange-100 hover:bg-orange-700 hover:text-white'; ?> block px-4 py-3 rounded-lg text-base font-medium transition-colors duration-200 flex items-center space-x-3">
                    <i class="fas fa-search"></i>
                    <span>Cari Praktikum</span>
                </a>
                
                <!-- Mobile User Info -->
                <div class="border-t border-orange-500/20 pt-4 mt-4">
                    <div class="flex items-center space-x-3 px-4 py-2 text-orange-100">
                        <div class="w-10 h-10 bg-gradient-to-br from-orange-400 to-orange-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        <div>
                            <p class="font-medium">
                                <?php echo isset($_SESSION['nama']) ? $_SESSION['nama'] : 'Mahasiswa'; ?>
                            </p>
                            <p class="text-sm text-orange-200">Mahasiswa</p>
                        </div>
                    </div>
                    <a href="../logout.php" class="mt-3 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-medium py-3 px-4 rounded-lg transition-all duration-300 flex items-center space-x-3">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Container -->
    <div class="container mx-auto p-6 lg:p-8">
        

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        });
    </script>
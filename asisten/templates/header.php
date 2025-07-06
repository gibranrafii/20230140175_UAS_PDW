<?php
// Pastikan session sudah dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek jika pengguna belum login atau bukan asisten
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'asisten') {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Panel Asisten - <?php echo $pageTitle ?? 'Dashboard'; ?></title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Konfigurasi warna custom dari style header mahasiswa
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
<body class="bg-gray-50">

<div class="flex h-screen">
    <aside class="w-64 bg-gradient-to-b from-orange-600 via-orange-700 to-red-700 text-white flex flex-col shadow-2xl">
        
        <div class="p-6 border-b border-orange-500/20">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm border border-white/30">
                    <i class="fas fa-user-shield text-white text-xl"></i>
                </div>
                <div>
                    <span class="text-white text-2xl font-bold tracking-wide">SIMPRAK</span>
                    <p class="text-orange-200 text-xs font-medium -mt-1">Panel Asisten</p>
                </div>
            </div>
        </div>
        
        <nav class="flex-grow p-4">
            <ul class="space-y-2">
                <?php 
                    // Mengadopsi style class 'active' dan 'inactive' dari header mahasiswa
                    $activeClass = 'bg-white/20 text-white shadow-lg backdrop-blur-sm border border-white/30';
                    $inactiveClass = 'text-orange-100 hover:bg-white/10 hover:text-white hover:backdrop-blur-sm';
                ?>
                <li>
                    <a href="dashboard.php" class="<?php echo ($activePage == 'dashboard') ? $activeClass : $inactiveClass; ?> flex items-center px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-300 space-x-3">
                        <i class="fas fa-home fa-fw"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="manajemen_praktikum.php" class="<?php echo ($activePage == 'manajemen_praktikum') ? $activeClass : $inactiveClass; ?> flex items-center px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-300 space-x-3">
                        <i class="fas fa-tasks fa-fw"></i>
                        <span>Manajemen Praktikum</span>
                    </a>
                </li>
                 <li>
                    <a href="manajemen_akun.php" class="<?php echo ($activePage == 'manajemen_akun') ? $activeClass : $inactiveClass; ?> flex items-center px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-300 space-x-3">
                        <i class="fas fa-users-cog fa-fw"></i>
                        <span>Manajemen Akun</span>
                    </a>
                </li>
                <li>
                    <a href="laporan.php" class="<?php echo ($activePage == 'laporan') ? $activeClass : $inactiveClass; ?> flex items-center px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-300 space-x-3">
                        <i class="fas fa-file-alt fa-fw"></i>
                        <span>Laporan Masuk</span>
                    </a>
                </li>
            </ul>
        </nav>

        <div class="p-4 border-t border-orange-500/20 space-y-4">
            <div class="flex items-center space-x-3 bg-white/10 backdrop-blur-sm rounded-xl px-4 py-2 border border-white/20">
                <div class="w-8 h-8 bg-gradient-to-br from-orange-400 to-orange-600 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-user text-white text-sm"></i>
                </div>
                <div class="text-white overflow-hidden">
                    <p class="text-sm font-medium truncate">
                        <?php echo isset($_SESSION['nama']) ? htmlspecialchars($_SESSION['nama']) : 'Asisten'; ?>
                    </p>
                    <p class="text-xs text-orange-200">Asisten</p>
                </div>
            </div>

            <a href="../logout.php" class="group bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-medium py-2.5 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl w-full flex items-center justify-center space-x-2">
                <i class="fas fa-sign-out-alt text-sm group-hover:rotate-12 transition-transform duration-300"></i>
                <span>Logout</span>
            </a>
        </div>
    </aside>

    <main class="flex-1 p-6 lg:p-8 overflow-y-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6"><?php echo $pageTitle ?? 'Halaman'; ?></h1>
        
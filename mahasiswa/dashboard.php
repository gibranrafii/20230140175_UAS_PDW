<?php

$pageTitle = 'Dashboard';
$activePage = 'dashboard';
// Header ini sudah mengandung semua style yang kita butuhkan (Tailwind, Font Awesome, warna custom)
require_once 'templates/header_mahasiswa.php'; 

?>

<div class="bg-gradient-to-r from-orange-500 to-red-500 text-white p-8 rounded-2xl shadow-xl mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold">Selamat Datang, <?php echo htmlspecialchars($_SESSION['nama']); ?>!</h1>
        <p class="mt-2 opacity-90">Terus semangat dalam menyelesaikan semua modul praktikummu. ✨</p>
    </div>
    <i class="fas fa-rocket text-5xl text-white/30 hidden sm:block"></i>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-8">
    
    <div class="bg-white p-6 rounded-2xl shadow-lg border-t-4 border-orange-400 transform hover:-translate-y-2 transition-transform duration-300">
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <div class="text-5xl font-extrabold text-orange-500">3</div>
                <div class="text-lg text-gray-600 font-medium">Praktikum Diikuti</div>
            </div>
            <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center">
                <i class="fas fa-book-open text-3xl text-orange-500"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white p-6 rounded-2xl shadow-lg border-t-4 border-green-400 transform hover:-translate-y-2 transition-transform duration-300">
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <div class="text-5xl font-extrabold text-green-500">8</div>
                <div class="text-lg text-gray-600 font-medium">Tugas Selesai</div>
            </div>
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-check-double text-3xl text-green-500"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white p-6 rounded-2xl shadow-lg border-t-4 border-yellow-400 transform hover:-translate-y-2 transition-transform duration-300">
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <div class="text-5xl font-extrabold text-yellow-500">4</div>
                <div class="text-lg text-gray-600 font-medium">Tugas Menunggu</div>
            </div>
            <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center">
                <i class="fas fa-hourglass-half text-3xl text-yellow-500"></i>
            </div>
        </div>
    </div>
    
</div>

<div class="bg-white p-6 rounded-2xl shadow-lg">
    <h3 class="text-2xl font-bold text-gray-800 mb-6">Notifikasi Terbaru</h3>
    <ul class="space-y-2">
        
        <li class="flex items-center p-4 rounded-lg hover:bg-yellow-50 transition-colors duration-200">
            <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                <i class="fas fa-bell text-yellow-500"></i>
            </div>
            <div>
                Nilai untuk <a href="#" class="font-semibold text-primary hover:underline">Modul 1: HTML & CSS</a> telah diberikan.
            </div>
        </li>

        <li class="flex items-center p-4 rounded-lg hover:bg-red-50 transition-colors duration-200">
            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                <i class="fas fa-clock text-red-500"></i>
            </div>
            <div>
                Batas waktu pengumpulan laporan untuk <a href="#" class="font-semibold text-primary hover:underline">Modul 2: PHP Native</a> adalah <strong>besok</strong>!
            </div>
        </li>

        <li class="flex items-center p-4 rounded-lg hover:bg-green-50 transition-colors duration-200">
            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                <i class="fas fa-check-circle text-green-500"></i>
            </div>
            <div>
                Anda berhasil mendaftar pada mata praktikum <a href="#" class="font-semibold text-primary hover:underline">Jaringan Komputer</a>.
            </div>
        </li>
        
    </ul>
</div>

<?php
// Panggil Footer
require_once 'templates/footer_mahasiswa.php';
?>
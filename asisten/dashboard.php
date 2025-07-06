<?php
// 1. Definisi Variabel untuk Template
$pageTitle = 'Dashboard Admin';
$activePage = 'dashboard';

// 2. Panggil Header Admin (asumsi: header.php sudah memiliki style sidebar oranye)
require_once 'templates/header.php'; 

// Di sini Anda bisa menambahkan query PHP untuk mengambil data dinamis, 
// untuk saat ini kita gunakan angka statis dari contoh Anda.
$totalModul = 12;
$totalLaporan = 152;
$laporanBelumDinilai = 18;
?>

<div class="p-6 lg:p-8">

    <div class="bg-gradient-to-r from-orange-500 to-red-500 text-white p-8 rounded-2xl shadow-xl mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold">Selamat Datang, Admin!</h1>
            <p class="mt-2 opacity-90">Kelola seluruh sistem praktikum dari sini. 🚀</p>
        </div>
        <i class="fas fa-user-shield text-5xl text-white/30 hidden sm:block"></i>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-8">
        
        <div class="bg-white p-6 rounded-2xl shadow-lg border-t-4 border-orange-400 transform hover:-translate-y-2 transition-transform duration-300">
            <div class="flex items-center justify-between">
                <div class="space-y-1">
                    <div class="text-5xl font-extrabold text-orange-500"><?= $totalModul ?></div>
                    <div class="text-lg text-gray-600 font-medium">Total Modul</div>
                </div>
                <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-book-open text-3xl text-orange-500"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-2xl shadow-lg border-t-4 border-green-400 transform hover:-translate-y-2 transition-transform duration-300">
            <div class="flex items-center justify-between">
                <div class="space-y-1">
                    <div class="text-5xl font-extrabold text-green-500"><?= $totalLaporan ?></div>
                    <div class="text-lg text-gray-600 font-medium">Laporan Masuk</div>
                </div>
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-file-invoice text-3xl text-green-500"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-2xl shadow-lg border-t-4 border-yellow-400 transform hover:-translate-y-2 transition-transform duration-300">
            <div class="flex items-center justify-between">
                <div class="space-y-1">
                    <div class="text-5xl font-extrabold text-yellow-500"><?= $laporanBelumDinilai ?></div>
                    <div class="text-lg text-gray-600 font-medium">Belum Dinilai</div>
                </div>
                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-hourglass-start text-3xl text-yellow-500"></i>
                </div>
            </div>
        </div>
        
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-lg">
        <h3 class="text-2xl font-bold text-gray-800 mb-6">Aktivitas Terbaru</h3>
        <ul class="space-y-3">
            
            <li class="flex items-center p-4 rounded-lg hover:bg-orange-50 transition-colors duration-200">
                <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                    <i class="fas fa-paper-plane text-orange-500"></i>
                </div>
                <div class="flex-grow">
                    <p class="text-gray-800"><strong class="font-semibold">Budi Santoso</strong> mengumpulkan laporan untuk <strong class="font-semibold">Modul 2</strong></p>
                    <p class="text-sm text-gray-500">10 menit lalu</p>
                </div>
            </li>

            <li class="flex items-center p-4 rounded-lg hover:bg-orange-50 transition-colors duration-200">
                <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                    <i class="fas fa-paper-plane text-orange-500"></i>
                </div>
                <div>
                    <p class="text-gray-800"><strong class="font-semibold">Citra Lestari</strong> mengumpulkan laporan untuk <strong class="font-semibold">Modul 2</strong></p>
                    <p class="text-sm text-gray-500">45 menit lalu</p>
                </div>
            </li>

            <li class="flex items-center p-4 rounded-lg hover:bg-green-50 transition-colors duration-200">
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                    <i class="fas fa-check-double text-green-500"></i>
                </div>
                <div>
                    <p class="text-gray-800"><strong class="font-semibold">Anton (Asisten)</strong> telah menilai laporan <strong class="font-semibold">Budi Santoso</strong></p>
                    <p class="text-sm text-gray-500">2 jam lalu</p>
                </div>
            </li>
            
        </ul>
    </div>

</div>

<?php
// 3. Panggil Footer
require_once 'templates/footer.php';
?>
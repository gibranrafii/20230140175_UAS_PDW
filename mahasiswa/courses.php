<?php
require_once '../config.php';
$pageTitle  = 'Cari Praktikum';
$activePage = 'courses';
require_once 'templates/header_mahasiswa.php';

// Ambil data praktikum dari DB
$daftarPraktikum = [];
$sql = "SELECT mp.id, mp.nama, mp.deskripsi, COALESCE(u.nama, 'Belum Ditentukan') AS asisten
        FROM mata_praktikum mp
        LEFT JOIN users u ON mp.id_asisten = u.id
        ORDER BY mp.nama";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $daftarPraktikum[] = $row;
}
$stmt->close();
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="bg-gradient-to-r from-orange-50 to-red-50 rounded-2xl p-8 mb-10 border border-orange-100 shadow-sm relative overflow-hidden">
        <div class="relative z-10">
            <h1 class="text-4xl font-extrabold text-gray-800">Katalog Praktikum</h1>
            <p class="text-gray-600 mt-2 max-w-2xl">
                Jelajahi semua mata praktikum yang tersedia. Pilih praktikum yang Anda minati dan klik tombol daftar untuk bergabung.
            </p>
        </div>
        <i class="fas fa-layer-group text-9xl text-orange-500/10 absolute -right-4 -top-4 transform rotate-12"></i>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php foreach ($daftarPraktikum as $praktikum): ?>
            <div class="bg-white rounded-2xl shadow-lg flex flex-col transform hover:-translate-y-2 transition-transform duration-300 overflow-hidden border border-gray-100">
                
                <div class="bg-gradient-to-r from-orange-400 to-primary h-36 flex items-center justify-center">
                    <i class="fas fa-laptop-code text-6xl text-white/50"></i>
                </div>

                <div class="p-6 flex flex-col flex-grow">
                    <h3 class="text-xl font-bold text-gray-900 mb-2"><?= htmlspecialchars($praktikum['nama']) ?></h3>
                    
                    <div class="flex items-center text-sm text-orange-600 font-medium mb-4">
                        <i class="fas fa-user-tie mr-2"></i>
                        <span>Asisten: <?= htmlspecialchars($praktikum['asisten']) ?></span>
                    </div>

                    <p class="text-gray-600 mb-6 flex-grow line-clamp-4">
                        <?= htmlspecialchars($praktikum['deskripsi']) ?>
                    </p>
                    
                    <a href="daftar_action.php?id_praktikum=<?= $praktikum['id'] ?>"
                       class="mt-auto group bg-primary hover:bg-primary-dark text-white text-center font-semibold py-3 px-4 rounded-lg transition-all duration-300 flex items-center justify-center space-x-2">
                        <span>Daftar Praktikum</span>
                        <i class="fas fa-arrow-right transform group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (empty($daftarPraktikum)): ?>
            <div class="col-span-1 md:col-span-2 lg:col-span-3 bg-white p-8 rounded-2xl shadow-lg text-center text-gray-500">
                <i class="fas fa-info-circle text-4xl mb-4"></i>
                <h3 class="text-xl font-medium">Belum Ada Praktikum</h3>
                <p>Saat ini belum ada mata praktikum yang tersedia untuk didaftarkan.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'templates/footer_mahasiswa.php'; ?>
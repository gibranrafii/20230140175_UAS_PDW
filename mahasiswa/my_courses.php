<?php
$pageTitle = 'Praktikum Saya';
$activePage = 'my_courses';
require_once 'templates/header_mahasiswa.php';
require_once '../config.php';

$id_mahasiswa = $_SESSION['user_id'];

// Query untuk mengambil praktikum yang sudah didaftarkan oleh mahasiswa yang login
$stmt = $conn->prepare(
    "SELECT mp.id, mp.nama, mp.deskripsi, COALESCE(u.nama, 'N/A') as asisten,
    (SELECT COUNT(*) FROM modul WHERE id_praktikum = mp.id) as jumlah_modul
    FROM mata_praktikum mp 
    JOIN pendaftaran p ON mp.id = p.id_praktikum 
    LEFT JOIN users u ON mp.id_asisten = u.id
    WHERE p.id_mahasiswa = ? 
    ORDER BY mp.nama ASC"
);
$stmt->bind_param("i", $id_mahasiswa);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container mx-auto py-10 px-4">
    <div class="bg-gradient-to-r from-orange-50 to-red-50 rounded-2xl p-8 mb-8 border border-orange-100 shadow-sm relative overflow-hidden">
        <div class="relative z-10">
            <h1 class="text-4xl font-extrabold text-gray-800">Praktikum Saya</h1>
            <p class="text-gray-600 mt-2 max-w-2xl">
                Ini adalah daftar semua praktikum yang sedang Anda ikuti. Kelola tugas dan lihat progres Anda di sini.
            </p>
        </div>
        <i class="fas fa-bookmark text-9xl text-orange-500/10 absolute -right-4 -bottom-8 transform -rotate-12"></i>
    </div>

    <?php if (isset($_GET['status']) && $_GET['status'] == 'daftar_sukses'): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-800 p-4 rounded-lg mb-6 shadow-md flex items-center" role="alert">
            <i class="fas fa-check-circle text-xl mr-3"></i>
            <p class="font-medium">Anda berhasil terdaftar di praktikum!</p>
        </div>
    <?php endif; ?>

    <div class="space-y-6">
        <?php if ($result->num_rows > 0): ?>
            <?php while($praktikum = $result->fetch_assoc()): ?>
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden border border-gray-100 flex flex-col md:flex-row items-center">
                    <div class="w-full md:w-32 h-24 md:h-full bg-gradient-to-br from-orange-400 to-primary flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-chalkboard-teacher text-5xl text-white/60"></i>
                    </div>
                    <div class="p-6 flex-grow w-full">
                        <h3 class="text-2xl font-bold text-gray-900"><?php echo htmlspecialchars($praktikum['nama']); ?></h3>
                        <div class="flex flex-wrap gap-4 text-sm text-gray-600 mt-2">
                            <span class="flex items-center"><i class="fas fa-user-graduate mr-2 text-orange-500"></i>Asisten: <?php echo htmlspecialchars($praktikum['asisten']); ?></span>
                            <span class="flex items-center"><i class="fas fa-book mr-2 text-orange-500"></i><?php echo $praktikum['jumlah_modul']; ?> Modul</span>
                        </div>
                    </div>
                    <div class="p-6 flex-shrink-0">
                        <a href="course_detail.php?id_praktikum=<?php echo $praktikum['id']; ?>" class="group inline-flex items-center justify-center bg-primary hover:bg-primary-dark text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                            <span>Lihat Detail</span>
                            <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform duration-300"></i>
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="bg-white p-12 rounded-2xl shadow-lg text-center border border-gray-100">
                <div class="w-24 h-24 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-folder-open text-5xl text-orange-500"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800">Anda Belum Terdaftar</h3>
                <p class="text-gray-500 mt-2 mb-6">Sepertinya Anda belum mengikuti praktikum apa pun. Ayo cari yang menarik!</p>
                <a href="courses.php" class="group inline-flex items-center justify-center bg-primary hover:bg-primary-dark text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    <span>Cari Praktikum Sekarang</span>
                    <i class="fas fa-search ml-2 transform group-hover:scale-110 transition-transform"></i>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
$stmt->close();
$conn->close();
require_once 'templates/footer_mahasiswa.php';
?>
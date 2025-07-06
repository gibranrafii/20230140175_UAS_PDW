<?php
session_start();
require_once '../config.php';

// Validasi sesi asisten
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'asisten') {
    header("Location: ../login.php");
    exit();
}

$id_laporan = $_GET['id'] ?? null;
if (!$id_laporan) {
    echo "ID Laporan tidak valid.";
    exit;
}

// Proses form SEBELUM mencetak HTML
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nilai = $_POST['nilai'];
    $feedback = $_POST['feedback'];

    if ($nilai === '' || $nilai < 0 || $nilai > 100) {
        // Redirect kembali dengan pesan error jika nilai tidak valid
        // (Penanganan error sederhana, bisa ditingkatkan dengan session)
        header("Location: laporan_nilai.php?id=" . $id_laporan . "&error=invalid_nilai");
        exit();
    }

    $stmt = $conn->prepare("UPDATE laporan SET nilai = ?, feedback = ?, status = 'Dinilai' WHERE id = ?");
    $stmt->bind_param("isi", $nilai, $feedback, $id_laporan);
    $stmt->execute();
    $stmt->close();
    header("Location: laporan.php?status=nilai_sukses");
    exit();
}

// Ambil detail laporan
$stmt_get = $conn->prepare("SELECT l.*, u.nama as nama_mahasiswa, m.nama_modul 
                            FROM laporan l 
                            JOIN users u ON l.id_mahasiswa = u.id 
                            JOIN modul m ON l.id_modul = m.id 
                            WHERE l.id = ?");
$stmt_get->bind_param("i", $id_laporan);
$stmt_get->execute();
$laporan = $stmt_get->get_result()->fetch_assoc();
if (!$laporan) {
    echo "Laporan tidak ditemukan.";
    exit;
}
$stmt_get->close();

// Setelah semua logika selesai, baru panggil header
$pageTitle = 'Beri Nilai Laporan';
$activePage = 'laporan';
require_once 'templates/header.php';
?>

<div class="p-6 lg:p-8">
    <div class="mb-8">
        <nav class="text-sm font-semibold mb-2" aria-label="Breadcrumb">
            <ol class="list-none p-0 inline-flex">
                <li class="flex items-center">
                    <a href="laporan.php" class="text-gray-500 hover:text-primary transition-colors duration-200">Laporan Masuk</a>
                </li>
                <li class="flex items-center mx-2">
                    <i class="fas fa-angle-right text-gray-400"></i>
                </li>
                <li class="flex items-center">
                    <span class="text-gray-800">Penilaian Laporan</span>
                </li>
            </ol>
        </nav>
        <h1 class="text-4xl font-extrabold text-gray-800">Penilaian Laporan</h1>
    </div>

    <div class="bg-white rounded-2xl shadow-lg p-6 lg:p-8 max-w-3xl mx-auto">
        
        <div class="bg-orange-50 border border-orange-200 rounded-xl p-6 mb-8">
             <h3 class="text-xl font-bold text-gray-800 mb-4">Detail Laporan</h3>
             <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-700">
                <div class="flex items-center">
                    <i class="fas fa-user-graduate w-5 text-center mr-3 text-primary"></i>
                    <div>
                        <p class="text-xs font-semibold">MAHASISWA</p>
                        <p class="font-medium"><?php echo htmlspecialchars($laporan['nama_mahasiswa']); ?></p>
                    </div>
                </div>
                 <div class="flex items-center">
                    <i class="fas fa-book w-5 text-center mr-3 text-primary"></i>
                    <div>
                        <p class="text-xs font-semibold">MODUL</p>
                        <p class="font-medium"><?php echo htmlspecialchars($laporan['nama_modul']); ?></p>
                    </div>
                </div>
             </div>
             <div class="mt-4 border-t border-orange-200 pt-4">
                 <a href="../laporan/<?php echo htmlspecialchars($laporan['file_laporan']); ?>" download class="inline-flex items-center bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-transform duration-200 transform hover:scale-105">
                    <i class="fas fa-download mr-2"></i>
                    Unduh File Laporan
                </a>
             </div>
        </div>

        <h3 class="text-xl font-bold text-gray-800 mb-6">Formulir Penilaian</h3>
        <form action="laporan_nilai.php?id=<?php echo $id_laporan; ?>" method="POST" class="space-y-6">
            <div>
                <label for="nilai" class="block text-gray-700 text-sm font-bold mb-2">Nilai (0-100)</label>
                <input type="number" name="nilai" id="nilai" min="0" max="100" value="<?php echo htmlspecialchars($laporan['nilai'] ?? ''); ?>" placeholder="Masukkan nilai antara 0 sampai 100" class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" required>
            </div>
            <div>
                <label for="feedback" class="block text-gray-700 text-sm font-bold mb-2">Feedback (Opsional)</label>
                <textarea name="feedback" id="feedback" rows="5" placeholder="Berikan masukan atau komentar untuk mahasiswa..." class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"><?php echo htmlspecialchars($laporan['feedback'] ?? ''); ?></textarea>
            </div>
            
            <div class="flex items-center justify-end pt-4 space-x-4">
                <a href="laporan.php" class="text-gray-600 font-semibold py-3 px-6 rounded-lg hover:bg-gray-100 transition-colors">
                    Batal
                </a>
                <button type="submit" class="group inline-flex items-center justify-center bg-primary hover:bg-primary-dark text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span>Simpan Nilai</span>
                </button>
            </div>
        </form>
    </div>
</div>

<?php
$conn->close();
require_once 'templates/footer.php';
?>
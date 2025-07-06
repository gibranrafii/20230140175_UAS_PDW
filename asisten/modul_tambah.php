<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'asisten') {
    header("Location: ../login.php");
    exit();
}

$id_praktikum = $_GET['id_praktikum'] ?? null;
if (!$id_praktikum) {
    echo "ID Praktikum tidak valid.";
    exit;
}

// Tambahan: Ambil nama praktikum untuk breadcrumb
$stmt_praktikum = $conn->prepare("SELECT nama FROM mata_praktikum WHERE id = ?");
$stmt_praktikum->bind_param("i", $id_praktikum);
$stmt_praktikum->execute();
$praktikum_result = $stmt_praktikum->get_result();
$praktikum = $praktikum_result->fetch_assoc();
$nama_praktikum = $praktikum['nama'] ?? 'Praktikum';
$stmt_praktikum->close();


$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_modul = trim($_POST['nama_modul']);
    $deskripsi = trim($_POST['deskripsi']);
    $file_materi_path = null;

    if (empty($nama_modul)) {
        $error = "Nama modul wajib diisi!";
    } else {
        if (isset($_FILES['file_materi']) && $_FILES['file_materi']['error'] == UPLOAD_ERR_OK) {
            $file_info = $_FILES['file_materi'];
            $file_name = time() . '_' . basename($file_info['name']);
            $destination = '../uploads/' . $file_name;

            if (move_uploaded_file($file_info['tmp_name'], $destination)) {
                $file_materi_path = $file_name;
            } else {
                $error = "Terjadi kesalahan saat mengunggah file.";
            }
        }

        if (empty($error)) {
            $stmt = $conn->prepare("INSERT INTO modul (id_praktikum, nama_modul, deskripsi, file_materi) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $id_praktikum, $nama_modul, $deskripsi, $file_materi_path);

            if ($stmt->execute()) {
                header("Location: modul.php?id_praktikum=" . $id_praktikum . "&status=tambah_sukses");
                exit();
            } else {
                $error = "Gagal menyimpan data ke database: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}

$pageTitle = 'Tambah Modul';
$activePage = 'manajemen_praktikum';
require_once 'templates/header.php';
?>

<div class="p-6 lg:p-8">
    <div class="mb-8">
        <nav class="text-sm font-semibold mb-2" aria-label="Breadcrumb">
            <ol class="list-none p-0 inline-flex">
                <li class="flex items-center">
                    <a href="manajemen_praktikum.php" class="text-gray-500 hover:text-primary transition-colors duration-200">Manajemen Praktikum</a>
                </li>
                <li class="flex items-center mx-2">
                    <i class="fas fa-angle-right text-gray-400"></i>
                </li>
                <li class="flex items-center">
                     <a href="modul.php?id_praktikum=<?php echo $id_praktikum; ?>" class="text-gray-500 hover:text-primary transition-colors duration-200"><?php echo htmlspecialchars($nama_praktikum); ?></a>
                </li>
                <li class="flex items-center mx-2">
                    <i class="fas fa-angle-right text-gray-400"></i>
                </li>
                <li class="flex items-center">
                    <span class="text-gray-800">Tambah Modul</span>
                </li>
            </ol>
        </nav>
        <h1 class="text-4xl font-extrabold text-gray-800">Tambah Modul Baru</h1>
    </div>

    <div class="bg-white rounded-2xl shadow-lg p-6 lg:p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Formulir Modul</h2>

        <?php if (!empty($error)): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-800 p-4 rounded-lg mb-6 shadow-md flex items-center" role="alert">
                <i class="fas fa-exclamation-triangle text-xl mr-3"></i>
                <p><?php echo htmlspecialchars($error); ?></p>
            </div>
        <?php endif; ?>

        <form action="modul_tambah.php?id_praktikum=<?php echo $id_praktikum; ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
            <div>
                <label for="nama_modul" class="block text-gray-700 text-sm font-bold mb-2">Nama Modul</label>
                <input type="text" id="nama_modul" name="nama_modul" placeholder="Contoh: Modul 1 - Pengenalan HTML" class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" required>
            </div>
            
            <div>
                <label for="deskripsi" class="block text-gray-700 text-sm font-bold mb-2">Deskripsi Singkat</label>
                <textarea id="deskripsi" name="deskripsi" rows="4" placeholder="Jelaskan secara singkat isi dari modul ini..." class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"></textarea>
            </div>
            
            <div>
                <label for="file_materi" class="block text-gray-700 text-sm font-bold mb-2">File Materi (Opsional)</label>
                <input type="file" id="file_materi" name="file_materi" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-100 file:text-primary hover:file:bg-orange-200 cursor-pointer">
                <p class="text-xs text-gray-500 mt-1">Format yang diizinkan: PDF, DOC, DOCX.</p>
            </div>
            
            <div class="flex items-center justify-end pt-4 space-x-4">
                <a href="modul.php?id_praktikum=<?php echo $id_praktikum; ?>" class="text-gray-600 font-semibold py-3 px-6 rounded-lg hover:bg-gray-100 transition-colors">
                    Batal
                </a>
                <button type="submit" class="group inline-flex items-center justify-center bg-primary hover:bg-primary-dark text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    <i class="fas fa-plus-circle mr-2"></i>
                    <span>Simpan Modul</span>
                </button>
            </div>
        </form>
    </div>
</div>

<?php 
$conn->close();
require_once 'templates/footer.php'; 
?>
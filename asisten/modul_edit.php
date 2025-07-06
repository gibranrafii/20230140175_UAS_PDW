<?php
session_start();
require_once '../config.php';

// Verifikasi sesi dan peran pengguna
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'asisten') {
    header("Location: ../login.php");
    exit();
}

$error = '';
$id_modul = $_GET['id'] ?? null;

// Validasi ID modul
if (!$id_modul) {
    header("Location: manajemen_praktikum.php");
    exit();
}

// Ambil data modul yang akan diedit
$stmt_get = $conn->prepare("SELECT * FROM modul WHERE id = ?");
$stmt_get->bind_param("i", $id_modul);
$stmt_get->execute();
$result_get = $stmt_get->get_result();
$modul = $result_get->fetch_assoc();
$stmt_get->close();

// Jika modul tidak ditemukan, hentikan eksekusi
if (!$modul) {
    echo "Modul tidak ditemukan.";
    exit;
}

$id_praktikum = $modul['id_praktikum'];

// Proses data saat form disubmit (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_modul = trim($_POST['nama_modul']);
    $deskripsi = trim($_POST['deskripsi']);
    $file_materi_path = $modul['file_materi']; // Simpan path file lama sebagai default

    if (empty($nama_modul)) {
        $error = "Nama modul wajib diisi!";
    } else {
        // Cek jika ada file baru yang diunggah
        if (isset($_FILES['file_materi']) && $_FILES['file_materi']['error'] == UPLOAD_ERR_OK) {
            // Hapus file lama jika ada
            if (!empty($modul['file_materi']) && file_exists('../uploads/' . $modul['file_materi'])) {
                unlink('../uploads/' . $modul['file_materi']);
            }

            // Proses file baru
            $file_info = $_FILES['file_materi'];
            $file_name = time() . '_' . basename($file_info['name']);
            $destination = '../uploads/' . $file_name;
            
            // Pindahkan file baru ke folder uploads
            if (move_uploaded_file($file_info['tmp_name'], $destination)) {
                $file_materi_path = $file_name;
            } else {
                $error = "Terjadi kesalahan saat mengunggah file baru.";
            }
        }

        // Jika tidak ada error, update database
        if (empty($error)) {
            $stmt_update = $conn->prepare("UPDATE modul SET nama_modul = ?, deskripsi = ?, file_materi = ? WHERE id = ?");
            $stmt_update->bind_param("sssi", $nama_modul, $deskripsi, $file_materi_path, $id_modul);
            
            if ($stmt_update->execute()) {
                header("Location: modul.php?id_praktikum=" . $id_praktikum . "&status=edit_sukses");
                exit();
            } else {
                $error = "Gagal memperbarui data: " . $stmt_update->error;
            }
            $stmt_update->close();
        }
    }
}

// Set variabel untuk header
$pageTitle = 'Edit Modul';
$activePage = 'manajemen_praktikum';
require_once 'templates/header.php';
?>

<div class="p-6 lg:p-8">
    <div class="bg-gradient-to-r from-orange-50 to-red-50 rounded-2xl p-8 mb-8 border border-orange-100 shadow-sm relative overflow-hidden">
        <div class="relative z-10">
            <h1 class="text-4xl font-extrabold text-gray-800">Edit Modul</h1>
            <p class="text-gray-600 mt-2 max-w-2xl">
                Ubah detail modul praktikum. Anda dapat memperbarui nama, deskripsi, dan mengganti file materi.
            </p>
        </div>
        <i class="fas fa-edit text-9xl text-orange-500/10 absolute -right-4 -top-4 transform rotate-12"></i>
    </div>

    <div class="bg-white rounded-2xl shadow-lg p-6 lg:p-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Formulir Perubahan</h2>
             <a href="modul.php?id_praktikum=<?php echo $id_praktikum; ?>" class="inline-flex items-center text-primary hover:text-primary-dark font-semibold transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Daftar Modul
            </a>
        </div>
        
        <?php if (!empty($error)): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-800 p-4 rounded-lg mb-6 shadow-md flex items-center" role="alert">
                <i class="fas fa-exclamation-triangle text-xl mr-3"></i>
                <p><?php echo htmlspecialchars($error); ?></p>
            </div>
        <?php endif; ?>

        <form action="modul_edit.php?id=<?php echo $id_modul; ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
            <div>
                <label for="nama_modul" class="block text-gray-700 text-sm font-bold mb-2">Nama Modul</label>
                <input type="text" id="nama_modul" name="nama_modul" value="<?php echo htmlspecialchars($modul['nama_modul']); ?>" class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" required>
            </div>
            
            <div>
                <label for="deskripsi" class="block text-gray-700 text-sm font-bold mb-2">Deskripsi Singkat</label>
                <textarea id="deskripsi" name="deskripsi" rows="4" class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"><?php echo htmlspecialchars($modul['deskripsi']); ?></textarea>
            </div>
            
            <div>
                <label for="file_materi" class="block text-gray-700 text-sm font-bold mb-2">Ganti File Materi (Opsional)</label>
                <?php if (!empty($modul['file_materi'])): ?>
                    <p class="text-sm text-gray-600 mb-2">File saat ini: 
                        <a href="../uploads/<?php echo htmlspecialchars($modul['file_materi']); ?>" target="_blank" class="text-primary hover:underline font-medium">
                            <i class="fas fa-file-alt mr-1"></i><?php echo htmlspecialchars($modul['file_materi']); ?>
                        </a>
                    </p>
                <?php endif; ?>
                <input type="file" id="file_materi" name="file_materi" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-100 file:text-primary hover:file:bg-orange-200 cursor-pointer">
                <p class="text-xs text-gray-500 mt-1">Format yang diizinkan: PDF, DOC, DOCX.</p>
            </div>
            
            <div class="flex items-center justify-end pt-4">
                <button type="submit" class="group inline-flex items-center justify-center bg-primary hover:bg-primary-dark text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    <i class="fas fa-save mr-2"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>
</div>

<?php 
$conn->close();
require_once 'templates/footer.php'; 
?>
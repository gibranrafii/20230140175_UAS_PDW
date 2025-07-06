<?php
session_start();
require_once '../config.php';

// Validasi sesi asisten
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'asisten') {
    header("Location: ../login.php");
    exit();
}

$error = '';
$id = $_GET['id'] ?? null;

// Validasi ID praktikum
if (!$id) {
    header("Location: manajemen_praktikum.php");
    exit();
}

// Proses form SEBELUM mencetak HTML
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kode_praktikum = trim($_POST['kode_praktikum']);
    $nama = trim($_POST['nama']);
    $deskripsi = trim($_POST['deskripsi']);

    if (empty($kode_praktikum) || empty($nama)) {
        $error = "Kode dan Nama Praktikum tidak boleh kosong!";
    } else {
        $stmt = $conn->prepare("UPDATE mata_praktikum SET kode_praktikum = ?, nama = ?, deskripsi = ? WHERE id = ?");
        $stmt->bind_param("sssi", $kode_praktikum, $nama, $deskripsi, $id);
        
        if ($stmt->execute()) {
            header("Location: manajemen_praktikum.php?status=edit_sukses");
            exit();
        } else {
            $error = "Gagal memperbarui data: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Ambil data praktikum yang akan di-edit
$stmt_select = $conn->prepare("SELECT * FROM mata_praktikum WHERE id = ?");
$stmt_select->bind_param("i", $id);
$stmt_select->execute();
$result = $stmt_select->get_result();
$praktikum = $result->fetch_assoc();
$stmt_select->close();

if (!$praktikum) {
    echo "Data tidak ditemukan.";
    exit;
}

// Setelah semua logika selesai, baru panggil header
$pageTitle = 'Edit Praktikum';
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
                    <span class="text-gray-800">Edit Praktikum</span>
                </li>
            </ol>
        </nav>
        <h1 class="text-4xl font-extrabold text-gray-800">Edit Mata Praktikum</h1>
    </div>

    <div class="bg-white rounded-2xl shadow-lg p-6 lg:p-8 max-w-2xl mx-auto">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Formulir Perubahan Praktikum</h2>

        <?php if (!empty($error)): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-800 p-4 rounded-lg mb-6 shadow-md flex items-center" role="alert">
                <i class="fas fa-exclamation-triangle text-xl mr-3"></i>
                <p><?php echo htmlspecialchars($error); ?></p>
            </div>
        <?php endif; ?>

        <form action="praktikum_edit.php?id=<?php echo $id; ?>" method="POST" class="space-y-6">
            <div>
                <label for="kode_praktikum" class="block text-gray-700 text-sm font-bold mb-2">Kode Praktikum</label>
                <input type="text" id="kode_praktikum" name="kode_praktikum" value="<?php echo htmlspecialchars($praktikum['kode_praktikum']); ?>" placeholder="Contoh: IF210" class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" required>
            </div>

            <div>
                <label for="nama" class="block text-gray-700 text-sm font-bold mb-2">Nama Praktikum</label>
                <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($praktikum['nama']); ?>" placeholder="Contoh: Pemrograman Web Lanjut" class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" required>
            </div>

            <div>
                <label for="deskripsi" class="block text-gray-700 text-sm font-bold mb-2">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="4" placeholder="Jelaskan secara singkat tentang praktikum ini..." class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"><?php echo htmlspecialchars($praktikum['deskripsi']); ?></textarea>
            </div>
            
            <div class="flex items-center justify-end pt-4 space-x-4">
                <a href="manajemen_praktikum.php" class="text-gray-600 font-semibold py-3 px-6 rounded-lg hover:bg-gray-100 transition-colors">
                    Batal
                </a>
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
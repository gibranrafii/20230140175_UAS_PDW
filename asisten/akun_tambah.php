<?php
// Mulai sesi dan panggil config di atas
session_start();
require_once '../config.php';

// Validasi sesi asisten
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'asisten') {
    header("Location: ../login.php");
    exit();
}

$error = '';

// Proses form SEBELUM mencetak HTML
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);

    if (empty($nama) || empty($email) || empty($password) || empty($role)) {
        $error = "Semua field wajib diisi!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid!";
    } else {
        $stmt_check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt_check->bind_param("s", $email);
        $stmt_check->execute();
        if ($stmt_check->get_result()->num_rows > 0) {
            $error = "Email sudah terdaftar.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $stmt_insert = $conn->prepare("INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt_insert->bind_param("ssss", $nama, $email, $hashed_password, $role);
            if ($stmt_insert->execute()) {
                header("Location: manajemen_akun.php?status=tambah_sukses");
                exit();
            } else {
                $error = "Gagal membuat akun.";
            }
            $stmt_insert->close();
        }
        $stmt_check->close();
    }
}

// Setelah semua logika selesai, baru panggil header
$pageTitle = 'Tambah Akun';
$activePage = 'manajemen_akun';
require_once 'templates/header.php';
?>

<div class="p-6 lg:p-8">
    <div class="mb-8">
        <nav class="text-sm font-semibold mb-2" aria-label="Breadcrumb">
            <ol class="list-none p-0 inline-flex">
                <li class="flex items-center">
                    <a href="manajemen_akun.php" class="text-gray-500 hover:text-primary transition-colors duration-200">Manajemen Akun</a>
                </li>
                <li class="flex items-center mx-2">
                    <i class="fas fa-angle-right text-gray-400"></i>
                </li>
                <li class="flex items-center">
                    <span class="text-gray-800">Tambah Akun Baru</span>
                </li>
            </ol>
        </nav>
        <h1 class="text-4xl font-extrabold text-gray-800">Buat Akun Pengguna Baru</h1>
    </div>

    <div class="bg-white rounded-2xl shadow-lg p-6 lg:p-8 max-w-2xl mx-auto">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Formulir Akun</h2>

        <?php if ($error): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-800 p-4 rounded-lg mb-6 shadow-md flex items-center" role="alert">
                <i class="fas fa-exclamation-triangle text-xl mr-3"></i>
                <p><?php echo htmlspecialchars($error); ?></p>
            </div>
        <?php endif; ?>

        <form action="akun_tambah.php" method="POST" class="space-y-6">
            <div>
                <label for="nama" class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap</label>
                <input type="text" name="nama" id="nama" placeholder="Masukkan nama lengkap" class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" required>
            </div>
            <div>
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Alamat Email</label>
                <input type="email" name="email" id="email" placeholder="contoh@email.com" class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" required>
            </div>
            <div>
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input type="password" name="password" id="password" placeholder="Masukkan password" class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" required>
            </div>
            <div>
                <label for="role" class="block text-gray-700 text-sm font-bold mb-2">Peran (Role)</label>
                <select name="role" id="role" class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" required>
                    <option value="" disabled selected>-- Pilih Peran --</option>
                    <option value="mahasiswa">Mahasiswa</option>
                    <option value="asisten">Asisten</option>
                    </select>
            </div>
            
            <div class="flex items-center justify-end pt-4 space-x-4">
                <a href="manajemen_akun.php" class="text-gray-600 font-semibold py-3 px-6 rounded-lg hover:bg-gray-100 transition-colors">
                    Batal
                </a>
                <button type="submit" class="group inline-flex items-center justify-center bg-primary hover:bg-primary-dark text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    <i class="fas fa-save mr-2"></i>
                    <span>Simpan Akun</span>
                </button>
            </div>
        </form>
    </div>
</div>

<?php
$conn->close();
require_once 'templates/footer.php';
?>
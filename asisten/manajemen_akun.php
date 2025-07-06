<?php
$pageTitle = 'Manajemen Akun';
$activePage = 'manajemen_akun';
require_once 'templates/header.php'; // Menggunakan header admin/asisten
require_once '../config.php';

// Ambil semua pengguna dari database, diurutkan berdasarkan peran lalu nama
$result = $conn->query("SELECT id, nama, email, role FROM users ORDER BY FIELD(role, 'admin', 'asisten', 'mahasiswa'), nama ASC");
?>

<div class="p-6 lg:p-8">
    <div class="bg-gradient-to-r from-orange-50 to-red-50 rounded-2xl p-8 mb-8 border border-orange-100 shadow-sm relative overflow-hidden">
        <div class="relative z-10">
            <h1 class="text-4xl font-extrabold text-gray-800">Manajemen Akun Pengguna</h1>
            <p class="text-gray-600 mt-2 max-w-2xl">
                Kelola semua akun pengguna yang terdaftar dalam sistem, termasuk admin, asisten, dan mahasiswa.
            </p>
        </div>
        <i class="fas fa-users-cog text-9xl text-orange-500/10 absolute -right-4 -top-4 transform rotate-12"></i>
    </div>

    <div class="bg-white rounded-2xl shadow-lg p-6">
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
            <h2 class="text-2xl font-bold text-gray-800">Daftar Pengguna</h2>
            <a href="akun_tambah.php" class="group w-full sm:w-auto inline-flex items-center justify-center bg-primary hover:bg-primary-dark text-white font-bold py-2 px-5 rounded-lg transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                <i class="fas fa-user-plus mr-2"></i>
                <span>Tambah Akun Baru</span>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-orange-50">
                    <tr>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm text-gray-600">Nama</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm text-gray-600">Email</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm text-gray-600">Peran</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while($user = $result->fetch_assoc()): 
                            // Logika untuk menentukan style badge berdasarkan peran
                            $roleClass = '';
                            $roleIcon = '';
                            switch ($user['role']) {
                                case 'admin':
                                    $roleClass = 'bg-red-100 text-red-700';
                                    $roleIcon = 'fas fa-user-shield';
                                    break;
                                case 'asisten':
                                    $roleClass = 'bg-orange-100 text-orange-700';
                                    $roleIcon = 'fas fa-user-tie';
                                    break;
                                default: // mahasiswa
                                    $roleClass = 'bg-blue-100 text-blue-700';
                                    $roleIcon = 'fas fa-user-graduate';
                            }
                        ?>
                            <tr class="border-b border-gray-200 hover:bg-orange-50/50 transition-colors duration-200">
                                <td class="py-3 px-4 font-medium text-gray-800"><?php echo htmlspecialchars($user['nama']); ?></td>
                                <td class="py-3 px-4"><?php echo htmlspecialchars($user['email']); ?></td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex items-center capitalize px-3 py-1 text-xs font-semibold rounded-full <?php echo $roleClass; ?>">
                                        <i class="<?php echo $roleIcon; ?> mr-2"></i>
                                        <?php echo htmlspecialchars($user['role']); ?>
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center space-x-2">
                                        <a href="akun_edit.php?id=<?php echo $user['id']; ?>" class="flex items-center justify-center w-8 h-8 bg-yellow-500 hover:bg-yellow-600 text-white rounded-md transition-transform duration-200 transform hover:scale-110 shadow-md" title="Edit Akun">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <?php if ($_SESSION['user_id'] != $user['id']): // Cegah admin menghapus dirinya sendiri ?>
                                            <a href="akun_hapus.php?id=<?php echo $user['id']; ?>" class="flex items-center justify-center w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-md transition-transform duration-200 transform hover:scale-110 shadow-md" title="Hapus Akun" onclick="return confirm('Apakah Anda yakin ingin menghapus akun ini?');">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center py-12">
                                <div class="w-24 h-24 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <i class="fas fa-users text-5xl text-orange-500"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800">Belum Ada Akun</h3>
                                <p class="text-gray-500 mt-2">Silakan tambahkan akun baru untuk memulai.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
$conn->close();
require_once 'templates/footer.php';
?>
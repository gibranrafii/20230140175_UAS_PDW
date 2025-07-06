<?php
$pageTitle = 'Manajemen Modul';
$activePage = 'manajemen_praktikum';
require_once 'templates/header.php';
require_once '../config.php';

// Ambil ID praktikum dari URL
$id_praktikum = $_GET['id_praktikum'] ?? null;
if (!$id_praktikum) {
    echo "ID Praktikum tidak valid.";
    exit;
}

// Ambil nama praktikum untuk judul halaman
$stmt_praktikum = $conn->prepare("SELECT nama FROM mata_praktikum WHERE id = ?");
$stmt_praktikum->bind_param("i", $id_praktikum);
$stmt_praktikum->execute();
$result_praktikum = $stmt_praktikum->get_result();
$praktikum = $result_praktikum->fetch_assoc();

if (!$praktikum) {
    echo "Praktikum tidak ditemukan.";
    exit;
}
$nama_praktikum = $praktikum['nama'];

// Ambil semua modul yang terkait dengan praktikum ini
$stmt_modul = $conn->prepare("SELECT * FROM modul WHERE id_praktikum = ? ORDER BY created_at ASC");
$stmt_modul->bind_param("i", $id_praktikum);
$stmt_modul->execute();
$result_modul = $stmt_modul->get_result();
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
                    <span class="text-gray-800"><?php echo htmlspecialchars($nama_praktikum); ?></span>
                </li>
            </ol>
        </nav>
        <h1 class="text-4xl font-extrabold text-gray-800">Manajemen Modul</h1>
    </div>

    <?php if (isset($_GET['status']) && $_GET['status'] == 'edit_sukses'): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-800 p-4 rounded-lg mb-6 shadow-md flex items-center" role="alert">
            <i class="fas fa-check-circle text-xl mr-3"></i>
            <p class="font-medium">Perubahan modul berhasil disimpan!</p>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl shadow-lg p-6">
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
            <h2 class="text-2xl font-bold text-gray-800">Daftar Modul</h2>
            <a href="modul_tambah.php?id_praktikum=<?php echo $id_praktikum; ?>" class="group w-full sm:w-auto inline-flex items-center justify-center bg-primary hover:bg-primary-dark text-white font-bold py-2 px-5 rounded-lg transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                <i class="fas fa-plus mr-2"></i>
                <span>Tambah Modul</span>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-orange-50">
                    <tr>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm text-gray-600">Nama Modul</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm text-gray-600">File Materi</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    <?php if ($result_modul->num_rows > 0): ?>
                        <?php while($modul = $result_modul->fetch_assoc()): ?>
                            <tr class="border-b border-gray-200 hover:bg-orange-50/50 transition-colors duration-200">
                                <td class="py-3 px-4 font-medium text-gray-800"><?php echo htmlspecialchars($modul['nama_modul']); ?></td>
                                <td class="py-3 px-4">
                                    <?php if (!empty($modul['file_materi'])): ?>
                                        <a href="../uploads/<?php echo htmlspecialchars($modul['file_materi']); ?>" target="_blank" class="inline-flex items-center text-sm font-medium text-primary hover:text-primary-dark hover:underline">
                                            <i class="fas fa-file-download mr-2"></i>
                                            <span>Lihat File</span>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-gray-400 italic text-sm">Belum diunggah</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center space-x-2">
                                        <a href="modul_edit.php?id=<?php echo $modul['id']; ?>" class="flex items-center justify-center w-8 h-8 bg-yellow-500 hover:bg-yellow-600 text-white rounded-md transition-transform duration-200 transform hover:scale-110 shadow-md" title="Edit Modul">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <a href="modul_hapus.php?id=<?php echo $modul['id']; ?>&id_praktikum=<?php echo $id_praktikum; ?>" class="flex items-center justify-center w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-md transition-transform duration-200 transform hover:scale-110 shadow-md" title="Hapus Modul" onclick="return confirm('Apakah Anda yakin ingin menghapus modul ini?');">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center py-12">
                                <div class="w-24 h-24 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <i class="fas fa-puzzle-piece text-5xl text-orange-500"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800">Belum Ada Modul</h3>
                                <p class="text-gray-500 mt-2">Praktikum ini belum memiliki modul. Silakan tambahkan satu.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
$stmt_praktikum->close();
$stmt_modul->close();
$conn->close();
require_once 'templates/footer.php';
?>
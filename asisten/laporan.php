<?php
$pageTitle = 'Laporan Masuk';
$activePage = 'laporan';
require_once 'templates/header.php';
require_once '../config.php';

// Bangun query dasar
$sql = "SELECT l.id, l.tgl_kumpul, l.status, u.nama as nama_mahasiswa, m.nama_modul 
        FROM laporan l 
        JOIN users u ON l.id_mahasiswa = u.id 
        JOIN modul m ON l.id_modul = m.id";

$whereClauses = [];
$params = [];
$types = '';

// Logika filter
$id_mahasiswa_filter = $_GET['id_mahasiswa'] ?? '';
$id_modul_filter = $_GET['id_modul'] ?? '';
$status_filter = $_GET['status'] ?? '';

if (!empty($id_mahasiswa_filter)) {
    $whereClauses[] = "l.id_mahasiswa = ?";
    $params[] = $id_mahasiswa_filter;
    $types .= 'i';
}
if (!empty($id_modul_filter)) {
    $whereClauses[] = "l.id_modul = ?";
    $params[] = $id_modul_filter;
    $types .= 'i';
}
if (!empty($status_filter)) {
    $whereClauses[] = "l.status = ?";
    $params[] = $status_filter;
    $types .= 's';
}

if (!empty($whereClauses)) {
    $sql .= " WHERE " . implode(" AND ", $whereClauses);
}
$sql .= " ORDER BY l.tgl_kumpul DESC";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result_laporan = $stmt->get_result();

// Ambil data untuk filter dropdown
$mahasiswas = $conn->query("SELECT id, nama FROM users WHERE role = 'mahasiswa' ORDER BY nama");
$moduls = $conn->query("SELECT id, nama_modul FROM modul ORDER BY nama_modul");
?>

<div class="p-6 lg:p-8">
    <div class="bg-gradient-to-r from-orange-50 to-red-50 rounded-2xl p-8 mb-8 border border-orange-100 shadow-sm relative overflow-hidden">
        <div class="relative z-10">
            <h1 class="text-4xl font-extrabold text-gray-800">Laporan Masuk</h1>
            <p class="text-gray-600 mt-2 max-w-2xl">
                Saring dan kelola semua laporan yang telah dikumpulkan oleh mahasiswa. Berikan penilaian atau lihat kembali hasil penilaian Anda.
            </p>
        </div>
        <i class="fas fa-file-import text-9xl text-orange-500/10 absolute -right-4 -bottom-4 transform rotate-12"></i>
    </div>

    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center"><i class="fas fa-filter mr-3 text-primary"></i>Filter Laporan</h3>
        <form action="" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 items-end">
            <div>
                <label for="id_mahasiswa" class="block text-sm font-medium text-gray-700 mb-1">Mahasiswa</label>
                <select name="id_mahasiswa" id="id_mahasiswa" class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                    <option value="">Semua Mahasiswa</option>
                    <?php while($row = $mahasiswas->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>" <?php echo ($id_mahasiswa_filter == $row['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($row['nama']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div>
                <label for="id_modul" class="block text-sm font-medium text-gray-700 mb-1">Modul</label>
                <select name="id_modul" id="id_modul" class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                    <option value="">Semua Modul</option>
                     <?php while($row = $moduls->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>" <?php echo ($id_modul_filter == $row['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($row['nama_modul']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status" class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                    <option value="">Semua Status</option>
                    <option value="Terkumpul" <?php echo ($status_filter == 'Terkumpul') ? 'selected' : ''; ?>>Belum Dinilai</option>
                    <option value="Dinilai" <?php echo ($status_filter == 'Dinilai') ? 'selected' : ''; ?>>Sudah Dinilai</option>
                </select>
            </div>
            <div class="flex space-x-2">
                 <button type="submit" class="w-full group inline-flex items-center justify-center bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded-lg transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    <i class="fas fa-search mr-2"></i>
                    <span>Cari</span>
                </button>
                <a href="laporan.php" class="w-auto bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded-lg" title="Reset Filter">
                    <i class="fas fa-sync-alt"></i>
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-lg p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-orange-50">
                    <tr>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm text-gray-600">Mahasiswa</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm text-gray-600">Modul</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm text-gray-600">Tgl Kumpul</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm text-gray-600">Status</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    <?php if ($result_laporan->num_rows > 0): ?>
                        <?php while($laporan = $result_laporan->fetch_assoc()): ?>
                            <tr class="border-b border-gray-200 hover:bg-orange-50/50 transition-colors duration-200">
                                <td class="py-3 px-4 font-medium text-gray-800"><?php echo htmlspecialchars($laporan['nama_mahasiswa']); ?></td>
                                <td class="py-3 px-4"><?php echo htmlspecialchars($laporan['nama_modul']); ?></td>
                                <td class="py-3 px-4 text-sm"><?php echo date('d M Y, H:i', strtotime($laporan['tgl_kumpul'])); ?> WIB</td>
                                <td class="py-3 px-4">
                                    <?php if ($laporan['status'] == 'Dinilai'): ?>
                                        <span class="inline-flex items-center capitalize px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-2"></i>Sudah Dinilai
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center capitalize px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-hourglass-half mr-2"></i>Belum Dinilai
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-4">
                                    <a href="laporan_nilai.php?id=<?php echo $laporan['id']; ?>" class="group inline-flex items-center justify-center bg-primary hover:bg-primary-dark text-white font-semibold py-2 px-4 rounded-lg transition-all duration-300 shadow-md">
                                        <i class="fas fa-marker mr-2"></i>
                                        <span><?php echo ($laporan['status'] == 'Dinilai') ? 'Lihat Nilai' : 'Beri Nilai'; ?></span>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-12">
                                <div class="w-24 h-24 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <i class="fas fa-search-minus text-5xl text-orange-500"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800">Laporan Tidak Ditemukan</h3>
                                <p class="text-gray-500 mt-2">Coba ubah atau reset filter pencarian Anda untuk melihat hasil.</p>
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
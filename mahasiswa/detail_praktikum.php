<?php
require_once '../config.php';
session_start();
if(!isset($_SESSION['id'])||$_SESSION['role']!=='mahasiswa'){header('Location:../login.php');exit;}
$praktikum_id=intval($_GET['id']??0);$mahasiswa_id=$_SESSION['id'];
$praktikum=$conn->query("SELECT nama,deskripsi FROM praktikum WHERE id=$praktikum_id")->fetch_assoc();
if(!$praktikum){echo'Praktikum tidak ditemukan';exit;}
$modules=$conn->query("SELECT * FROM modul WHERE praktikum_id=$praktikum_id ORDER BY id");
include '../templates/header_mahasiswa.php';
?>
<div class="container mx-auto p-6">
  <h1 class="text-2xl font-bold"><?= htmlspecialchars($praktikum['nama']) ?></h1>
  <p class="text-gray-700 mb-6"><?= htmlspecialchars($praktikum['deskripsi']) ?></p>
  <?php while($module=$modules->fetch_assoc()):
        $sub=$conn->query("SELECT * FROM submissions WHERE modul_id={$module['id']} AND mahasiswa_id=$mahasiswa_id")->fetch_assoc(); ?>
    <div class="bg-white rounded shadow p-5 mb-4">
      <h2 class="text-lg font-semibold mb-2"><?= htmlspecialchars($module['judul']) ?></h2>
      <div class="flex flex-wrap gap-4 items-center">
        <a href="../uploads/<?= urlencode($module['materi_path']) ?>" class="text-sm text-blue-600 underline">Unduh Materi</a>
        <?php if(!$sub): ?>
          <form action="upload_laporan.php" method="post" enctype="multipart/form-data" class="flex items-center gap-2 text-sm">
            <input type="hidden" name="modul_id" value="<?= $module['id'] ?>">
            <input type="hidden" name="praktikum_id" value="<?= $praktikum_id ?>">
            <input type="file" name="laporan" required>
            <button class="px-3 py-1 bg-green-600 text-white rounded" type="submit">Upload Laporan</button>
          </form>
        <?php else: ?>
          <span class="text-sm text-gray-600">Laporan diunggah.</span>
          <?php if(isset($sub['nilai'])): ?>
            <span class="text-sm text-blue-700">Nilai: <?= $sub['nilai'] ?></span>
            <span class="text-sm"><?= htmlspecialchars($sub['feedback']) ?></span>
          <?php else: ?>
            <span class="italic text-yellow-600 text-sm">Menunggu penilaian…</span>
          <?php endif; ?>
        <?php endif; ?>
      </div>
    </div>
  <?php endwhile; ?>
</div>
<?php include '../templates/footer_mahasiswa.php'; ?>

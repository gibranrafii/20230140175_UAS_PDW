<?php
require_once '../config.php';
session_start();
if(!isset($_SESSION['id'])||$_SESSION['role']!=='asisten'){header('Location:../login.php');exit;}
// Handle add/edit/delete
if($_SERVER['REQUEST_METHOD']==='POST'){
  $action=$_POST['action'];
  if($action==='add'){
     $stmt=$conn->prepare("INSERT INTO praktikum (nama,deskripsi,kuota) VALUES (?,?,?)");
     $stmt->bind_param('ssi',$_POST['nama'],$_POST['deskripsi'],$_POST['kuota']);
     $stmt->execute();
  }elseif($action==='edit'){
     $stmt=$conn->prepare("UPDATE praktikum SET nama=?, deskripsi=?, kuota=? WHERE id=?");
     $stmt->bind_param('ssii',$_POST['nama'],$_POST['deskripsi'],$_POST['kuota'],$_POST['id']);
     $stmt->execute();
  }elseif($action==='delete'){
     $conn->query("DELETE FROM praktikum WHERE id=".intval($_POST['id']));
  }
  header('Location: praktikum.php');exit;
}
$rows=$conn->query("SELECT * FROM praktikum");
include '../templates/header_asisten.php';
?>
<div class="container mx-auto p-6">
  <h1 class="text-2xl font-bold mb-4">Kelola Mata Praktikum</h1>
  <!-- Form tambah -->
  <form class="bg-white p-4 rounded shadow mb-6" method="post">
    <input type="hidden" name="action" value="add">
    <div class="grid md:grid-cols-3 gap-4">
      <input class="border p-2" name="nama" placeholder="Nama Praktikum" required>
      <input class="border p-2" name="kuota" type="number" placeholder="Kuota" required>
      <textarea class="border p-2 md:col-span-3" name="deskripsi" placeholder="Deskripsi"></textarea>
      <button class="bg-blue-600 text-white px-4 py-2 rounded md:col-span-3" type="submit">Tambah</button>
    </div>
  </form>
  <!-- Tabel -->
  <table class="w-full bg-white rounded shadow text-sm">
    <thead class="bg-slate-100"><tr><th class="p-2">Nama</th><th>Kuota</th><th class="w-1/2">Aksi</th></tr></thead>
    <tbody>
    <?php while($r=$rows->fetch_assoc()): ?>
      <tr class="border-b">
        <td class="p-2 font-semibold"><?= htmlspecialchars($r['nama']) ?></td>
        <td class="text-center"><?= $r['kuota'] ?></td>
        <td class="p-2 flex gap-2">
          <form method="post" class="flex gap-2">
            <input type="hidden" name="id" value="<?= $r['id'] ?>">
            <input type="hidden" name="action" value="delete">
            <button class="px-3 py-1 bg-red-600 text-white rounded" onclick="return confirm('Hapus?')">Hapus</button>
          </form>
          <a href="modul.php?praktikum_id=<?= $r['id'] ?>" class="px-3 py-1 bg-yellow-500 text-white rounded">Modul</a>
        </td>
      </tr>
    <?php endwhile; ?>
    </tbody>
  </table>
</div>
<?php include '../templates/footer_asisten.php'; ?>

<?php
require_once '../config.php';
session_start();
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'mahasiswa') {
    header('Location: ../login.php');
    exit;
}
$praktikum_id = intval($_GET['id'] ?? 0);
$mahasiswa_id = $_SESSION['id'];

// Cek kuota
$cek = $conn->prepare("SELECT kuota, (SELECT COUNT(*) FROM pendaftaran WHERE praktikum_id = ?) AS terdaftar FROM praktikum WHERE id = ?");
$cek->bind_param('ii', $praktikum_id, $praktikum_id);
$cek->execute();
$info = $cek->get_result()->fetch_assoc();
if ($info && $info['terdaftar'] >= $info['kuota']) {
    header('Location: courses.php?kuota=habis');
    exit;
}

$stmt = $conn->prepare("INSERT IGNORE INTO pendaftaran (mahasiswa_id, praktikum_id) VALUES (?,?)");
$stmt->bind_param('ii', $mahasiswa_id, $praktikum_id);
$stmt->execute();
header('Location: my_courses.php?status=sukses');
?>
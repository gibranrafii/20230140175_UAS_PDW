<?php
require_once '../config.php';
session_start();
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'asisten') {
  header('Location: ../login.php');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id       = intval($_POST['id'] ?? 0);
  $nilai    = floatval($_POST['nilai'] ?? 0);
  $feedback = $_POST['feedback'] ?? '';

  $stmt = $conn->prepare("UPDATE submissions SET nilai=?, feedback=?, graded_at=NOW() WHERE id=?");
  $stmt->bind_param('dsi', $nilai, $feedback, $id);
  $stmt->execute();
}

header('Location: laporan.php?status='.$_GET['filter'] ?? 'all');
exit;
?>

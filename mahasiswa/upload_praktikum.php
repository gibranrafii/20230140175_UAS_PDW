<?php
require_once '../config.php';
session_start();
if(!isset($_SESSION['id'])||$_SESSION['role']!=='mahasiswa'){header('Location:../login.php');exit;}
$modul_id=intval($_POST['modul_id']??0);$praktikum_id=intval($_POST['praktikum_id']??0);$mahasiswa_id=$_SESSION['id'];
if(isset($_FILES['laporan'])){
  $fileName=time().'_'.basename($_FILES['laporan']['name']);
  $targetDir='../uploads/';
  if(move_uploaded_file($_FILES['laporan']['tmp_name'],$targetDir.$fileName)){
    $stmt=$conn->prepare("INSERT INTO submissions (modul_id,mahasiswa_id,file_path) VALUES (?,?,?)");
    $stmt->bind_param('iis',$modul_id,$mahasiswa_id,$fileName);
    $stmt->execute();
    header('Location: detail_praktikum.php?id='.$praktikum_id.'&uploaded=1');
  }else{echo'Gagal upload.';}
}
?>

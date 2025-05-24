<?php
session_start();
include '../db/db.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'guru_bk') {
    header("Location: ../login/login.php");
    exit;
}

$id = $_POST['id'];
$pesan_bk = $_POST['pesan_bk'];

// Update status otomatis jadi 'Sudah Ditangani'
mysqli_query($conn, "UPDATE surat_bk SET status='Sudah Ditangani', pesan_bk='$pesan_bk' WHERE id=$id");

header("Location: dashboard.php");
exit;
?>

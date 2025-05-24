<?php
session_start();
include __DIR__ . '/../db/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'guru_bk') {
    header("Location: ../login/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus']) && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    mysqli_query($conn, "DELETE FROM surat_bk WHERE id = $id");
    $_SESSION['pesan'] = "Surat berhasil dihapus.";
}

header("Location: surat_masuk.php");
exit;
?>

<?php
session_start();
include '../db/db.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'guru_bk') {
    header("Location: ../login/login.php");
    exit;
}

$id = $_GET['id'];
$q = mysqli_query($conn, "SELECT * FROM surat_bk WHERE id=$id");
$d = mysqli_fetch_assoc($q);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Detail Surat</title>
    <link rel="stylesheet" href="../adminlte/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../adminlte/dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <div class="content-wrapper p-3">
        <h3>Detail Surat BK</h3>
        <p><strong>Nama Siswa:</strong> <?= $d['nama_siswa'] ?></p>
        <p><strong>Alasan:</strong> <?= $d['alasan'] ?></p>
        <p><strong>Tanggal:</strong> <?= $d['tanggal'] ?></p>
        <p><strong>Status:</strong> <?= $d['status'] ?></p>
        <form method="POST" action="update_laporan.php">
            <input type="hidden" name="id" value="<?= $d['id'] ?>">
            <div class="form-group">
                <label>Status Tindakan:</label>
                <select name="status" class="form-control">
                    <option value="Belum Ditangani" <?= $d['status'] == 'Belum Ditangani' ? 'selected' : '' ?>>Belum Ditangani</option>
                    <option value="Sudah Ditangani" <?= $d['status'] == 'Sudah Ditangani' ? 'selected' : '' ?>>Sudah Ditangani</option>
                </select>
            </div>
            <div class="form-group">
                <label>Pesan untuk Wali Kelas:</label>
                <textarea name="pesan_bk" class="form-control"><?= $d['pesan_bk'] ?></textarea>
            </div>
            <button type="submit" class="btn btn-success">Update</button>
        </form>
    </div>
</div>
</body>
</html>

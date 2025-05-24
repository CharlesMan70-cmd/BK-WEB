<?php
session_start();
include __DIR__ . '/../db/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'wali_kelas') {
    header("Location: ../login/login.php");
    exit;
}

$id_wali = $_SESSION['id'];
$query = mysqli_query($koneksi, "
    SELECT surat_bk.*, siswa.nama AS nama_siswa, siswa.kelas
    FROM surat_bk
    LEFT JOIN siswa ON surat_bk.id_siswa = siswa.id
    WHERE id_pengirim = $id_wali
    ORDER BY tanggal DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Surat</title>
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top">
<div id="wrapper">

<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard.php">
        <div class="sidebar-brand-text mx-3">BK Online</div>
    </a>
    <hr class="sidebar-divider my-0">
    <li class="nav-item <?= basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : '' ?>">
        <a class="nav-link" href="dashboard.php"><i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a>
    </li>
    <li class="nav-item <?= basename($_SERVER['PHP_SELF']) === 'kirim_surat.php' ? 'active' : '' ?>">
        <a class="nav-link" href="kirim_surat.php"><i class="fas fa-fw fa-paper-plane"></i><span>Kirim Surat</span></a>
    </li>
    <li class="nav-item <?= basename($_SERVER['PHP_SELF']) === 'laporan_surat.php' ? 'active' : '' ?>">
        <a class="nav-link" href="laporan_surat.php"><i class="fas fa-fw fa-file-alt"></i><span>Laporan Surat</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#" onclick="confirmLogout()"><i class="fas fa-fw fa-sign-out-alt"></i><span>Logout</span></a>
    </li>
</ul>

<!-- Content -->
<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
            <h5 class="fw-bold text-primary">Laporan Surat Terkirim</h5>
        </nav>

        <div class="container-fluid">
            <div class="card shadow mb-4">
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-primary text-center">
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>Alasan</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Pesan Guru BK</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; while ($row = mysqli_fetch_assoc($query)) : ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td><?= htmlspecialchars($row['nama_siswa']) ?> (<?= htmlspecialchars($row['kelas']) ?>)</td>
                                <td><?= htmlspecialchars($row['alasan']) ?></td>
                                <td class="text-center"><?= $row['tanggal'] ?></td>
                                <td class="text-center">
                                    <span class="badge <?= $row['status'] === 'Sudah Ditangani' ? 'bg-success' : 'bg-warning text-dark' ?>">
                                        <?= $row['status'] ?>
                                    </span>
                                </td>
                                <td><?= $row['pesan_bk'] ?? '-' ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JS -->
<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../js/sb-admin-2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmLogout() {
    Swal.fire({
        title: 'Yakin ingin logout?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Logout',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "../login/logout.php";
        }
    });
}
</script>
</body>
</html>

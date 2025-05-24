<?php
session_start();
include __DIR__ . '/../db/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'guru_bk') {
    header("Location: ../login/login.php");
    exit;
}

$surat = mysqli_query($koneksi, "
    SELECT surat_bk.*, users.username AS wali_kelas, siswa.nama AS nama_siswa, siswa.kelas
    FROM surat_bk
    LEFT JOIN users ON users.id = surat_bk.id_pengirim
    LEFT JOIN siswa ON siswa.id = surat_bk.id_siswa
    ORDER BY tanggal DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Masuk</title>
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top">
<div id="wrapper">

<!-- SIDEBAR -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard.php">
        <div class="sidebar-brand-text mx-3">BK Online</div>
    </a>
    <li class="nav-item"><a class="nav-link" href="dashboard.php"><i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a></li>
    <li class="nav-item active"><a class="nav-link" href="surat_masuk.php"><i class="fas fa-fw fa-inbox"></i><span>Surat Masuk</span></a></li>
    <li class="nav-item"><a class="nav-link" href="#" onclick="confirmLogout()"><i class="fas fa-fw fa-sign-out-alt"></i><span>Logout</span></a></li>
</ul>

<!-- CONTENT -->
<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
            <h5 class="fw-bold text-primary">Surat Masuk Konseling</h5>
        </nav>

        <div class="container-fluid">
            <?php if (isset($_SESSION['pesan'])): ?>
                <div class="alert alert-success text-center">
                    <?= $_SESSION['pesan']; unset($_SESSION['pesan']); ?>
                </div>
            <?php endif; ?>

            <div class="card shadow mb-4">
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-primary text-center">
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Alasan</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Pesan BK</th>
                                <th>Wali Kelas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; while ($row = mysqli_fetch_assoc($surat)) : ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td><?= htmlspecialchars($row['nama_siswa']) ?></td>
                                <td><?= htmlspecialchars($row['kelas']) ?></td>
                                <td><?= htmlspecialchars($row['alasan']) ?></td>
                                <td class="text-center"><?= $row['tanggal'] ?></td>
                                <td class="text-center">
                                    <span class="badge <?= $row['status'] === 'Sudah Ditangani' ? 'bg-success' : 'bg-warning text-dark' ?>">
                                        <?= $row['status'] ?>
                                    </span>
                                </td>
                                <td><?= $row['pesan_bk'] ? htmlspecialchars($row['pesan_bk']) : '-' ?></td>
                                <td><?= $row['wali_kelas'] ?></td>
                                <td class="text-center">
                                    <form method="post" action="hapus_surat.php" onsubmit="return confirmHapus(this)">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <button type="submit" name="hapus" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
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

function confirmHapus(form) {
    event.preventDefault();
    Swal.fire({
        title: 'Yakin ingin hapus surat ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
    return false;
}
</script>
</body>
</html>

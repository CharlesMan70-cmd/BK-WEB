<?php
session_start();
include __DIR__ . '/../db/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'wali_kelas') {
    header("Location: ../login/login.php");
    exit;
}

$pesan = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_siswa = $_POST['id_siswa'];
    $alasan = htmlspecialchars($_POST['alasan']);
    $tanggal = date('Y-m-d');
    $id_pengirim = $_SESSION['id'];

    $insert = mysqli_query($koneksi, "INSERT INTO surat_bk (id_pengirim, id_siswa, alasan, tanggal) VALUES ('$id_pengirim', '$id_siswa', '$alasan', '$tanggal')");
    $pesan = $insert ? "Surat berhasil dikirim!" : "Gagal mengirim surat.";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kirim Surat</title>
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body id="page-top">
<div id="wrapper">

<!-- SIDEBAR -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard.php">
        <div class="sidebar-brand-text mx-3">BK Online</div>
    </a>
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

<!-- CONTENT -->
<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
            <h5 class="fw-bold text-primary">Kirim Surat ke Guru BK</h5>
        </nav>

        <div class="container-fluid">
            <?php if ($pesan): ?>
                <div class="alert alert-info"><?= $pesan ?></div>
            <?php endif; ?>

            <div class="card shadow mb-4">
                <div class="card-body">
                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label">Nama Siswa</label>
                            <select name="id_siswa" id="id_siswa" class="form-control" required>
                                <option></option>
                                <?php
                                $siswa = mysqli_query($koneksi, "SELECT * FROM siswa ORDER BY nama ASC");
                                while ($row = mysqli_fetch_assoc($siswa)) {
                                    echo "<option value='{$row['id']}'>{$row['nama']} - {$row['kelas']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alasan Konseling</label>
                            <textarea name="alasan" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal</label>
                            <input type="date" class="form-control" value="<?= date('Y-m-d') ?>" readonly>
                        </div>
                        <button type="submit" class="btn btn-primary">Kirim</button>
                        <a href="dashboard.php" class="btn btn-secondary">Kembali</a>
                    </form>
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('#id_siswa').select2({ placeholder: "Pilih nama siswa...", width: '100%' });
});

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

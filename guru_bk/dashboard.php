<?php
session_start();
include __DIR__ . '/../db/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'guru_bk') {
    header("Location: ../login/login.php");
    exit;
}

$total = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM surat_bk"))['total'];
$belum = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM surat_bk WHERE status = 'Belum Ditangani'"))['total'];
$sudah = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM surat_bk WHERE status = 'Sudah Ditangani'"))['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Guru BK</title>
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top">

<div id="wrapper">
    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
            <div class="sidebar-brand-text mx-3">BK Online</div>
        </a>
        <hr class="sidebar-divider my-0">
        <li class="nav-item active">
            <a class="nav-link" href="dashboard.php"><i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="surat_masuk.php"><i class="fas fa-fw fa-inbox"></i><span>Surat Masuk</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" onclick="confirmLogout()"><i class="fas fa-fw fa-sign-out-alt"></i><span>Logout</span></a>
        </li>
    </ul>

    <!-- Content -->
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">

            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <span class="fw-bold text-primary">Dashboard Guru BK</span>
            </nav>

            <!-- Begin Page Content -->
            <div class="container-fluid">

                <!-- Alert sukses hapus -->
                <?php if (isset($_SESSION['pesan'])): ?>
                    <div class="alert alert-success text-center">
                        <?= $_SESSION['pesan']; unset($_SESSION['pesan']); ?>
                    </div>
                <?php endif; ?>

                <!-- Kartu statistik -->
                <div class="row">
                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Surat</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Belum Ditangani</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $belum ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Sudah Ditangani</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $sudah ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grafik Pie -->
                <div class="row">
                    <div class="col-lg-6 offset-lg-3">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Status Surat Konseling</h6>
                            </div>
                            <div class="card-body">
                                <canvas id="statusChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tombol Hapus Semua Surat -->
                <div class="text-center mb-4">
                    <form method="post" action="hapus_surat.php" onsubmit="return confirmHapus(event)">
                        <button type="submit" name="hapus" class="btn btn-danger">
                            <i class="fas fa-trash-alt"></i> Hapus Semua Surat
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- JS -->
<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../vendor/chart.js/Chart.min.js"></script>
<script src="../js/sb-admin-2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
const ctx = document.getElementById('statusChart').getContext('2d');
new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ['Belum Ditangani', 'Sudah Ditangani'],
        datasets: [{
            data: [<?= $belum ?>, <?= $sudah ?>],
            backgroundColor: ['#f6c23e', '#1cc88a']
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' }
        }
    }
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

function confirmHapus(e) {
    e.preventDefault();
    Swal.fire({
        title: 'Yakin ingin hapus semua surat?',
        text: 'Data yang dihapus tidak bisa dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            e.target.submit();
        }
    });
    return false;
}
</script>
</body>
</html>

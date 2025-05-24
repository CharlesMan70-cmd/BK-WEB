<?php
session_start();
include __DIR__ . '/../db/db.php'; // koneksi ke database

// Jika sudah login, langsung arahkan sesuai role
if (isset($_SESSION['id']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'guru_bk') {
        header("Location: ../guru_bk/dashboard.php");
        exit;
    } elseif ($_SESSION['role'] === 'wali_kelas') {
        header("Location: ../wali_kelas/dashboard.php");
        exit;
    }
}

// Proses login
$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Ambil data user berdasarkan username
    $stmt = $koneksi->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Periksa apakah user ditemukan
    if ($row = $result->fetch_assoc()) {
        // Verifikasi password
        if (password_verify($password, $row['password'])) {
            // Set session dan redirect berdasarkan role
            session_regenerate_id(true); // keamanan sesi
            $_SESSION['id'] = $row['id'];
            $_SESSION['role'] = $row['role'];

            if ($_SESSION['role'] === 'guru_bk') {
                header("Location: ../guru_bk/dashboard.php");
            } elseif ($_SESSION['role'] === 'wali_kelas') {
                header("Location: ../wali_kelas/dashboard.php");
            } else {
                $error = "Role pengguna tidak dikenali.";
            }
            exit;
        } else {
            $error = "Password salah.";
        }
    } else {
        $error = "Username tidak ditemukan.";
    }
    $stmt->close();
}
?>

<!-- Tampilan login HTML -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login BK Online</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header text-center">
                    <h4>Login BK Online</h4>
                </div>
                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Masuk</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

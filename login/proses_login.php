<?php
session_start();
include '../db/db.php'; // sesuaikan dengan path file koneksi db kamu

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Query cek user
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        // cek password (anggap password disimpan plain text, sebaiknya pakai hash)
        if ($password === $user['password']) {
            // login berhasil
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role']; // contoh kalau ada role
            header('Location: ../guru_bk/dashboard.php'); // arahkan ke dashboard sesuai role
            exit();
        } else {
            $_SESSION['error'] = "Password salah!";
            header('Location: login.php');
            exit();
        }
    } else {
        $_SESSION['error'] = "Username tidak ditemukan!";
        header('Location: login.php');
        exit();
    }
} else {
    header('Location: login.php');
    exit();
}
?>

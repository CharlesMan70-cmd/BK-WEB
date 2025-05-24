<?php
session_start();
include __DIR__ . '/db/db.php'; // pastikan path sesuai struktur folder Anda

$username = 'bk1';
$password = '123';

$stmt = $koneksi->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo "User ditemukan: " . htmlspecialchars($row['username']) . "<br>";
    echo "Role: " . htmlspecialchars($row['role']) . "<br>";
    echo "Password DB: " . htmlspecialchars($row['password']) . "<br>";
    if (password_verify($password, $row['password'])) {
        echo "Password cocok! Login berhasil.";
    } else {
        echo "Password TIDAK cocok!";
    }
} else {
    echo "User tidak ditemukan.";
}

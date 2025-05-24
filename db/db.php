<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "bk_surat"; // ubah dari 'db' ke nama database Anda yang benar

$koneksi = mysqli_connect($host, $user, $password, $database);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

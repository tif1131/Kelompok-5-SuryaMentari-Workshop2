<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include('config.php');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Service Mobil - Beranda</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <a href="login.php">Login</a>
        <a href="register_layanan.php">Pendaftaran Service</a>
        <a href="schedule_layanan.php">Penjadwalan Service</a>
        <a href="laporan.php">Laporan Service</a>
        <a href="logout.php">Logout</a>
    </nav>
    <div class="container">
        <h1>Selamat Datang di Aplikasi Service Mobil</h1>
        <p>Pilih menu di atas untuk memulai.</p>
    </div>
</body>
</html>
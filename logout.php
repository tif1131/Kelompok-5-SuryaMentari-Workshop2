<?php
session_start();
session_destroy(); // Menghapus semua data sesi
header("Location: login.php");
exit();
?>
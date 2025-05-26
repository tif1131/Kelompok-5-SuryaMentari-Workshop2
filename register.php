<?php
include("config.php");

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validasi
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $message = "Semua field harus diisi.";
    } elseif ($password != $confirm_password) {
        $message = "Password tidak cocok.";
    } else {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Gunakan prepared statement untuk mencegah SQL injection
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashed_password);

        if ($stmt->execute()) {
            $message = "Registrasi berhasil. Silakan <a href='login.php'>Login</a>.";
        } else {
            $message = "Terjadi kesalahan saat registrasi: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Registrasi</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h2>Form Registrasi</h2>
        <?php if (!empty($message)) echo "<p class='error'>$message</p>"; ?>
        <form method="post" action="register.php">
            <label>Username:</label>
            <input type="text" name="username" required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <label>Konfirmasi Password:</label>
            <input type="password" name="confirm_password" required>

            <input type="submit" value="Daftar">
        </form>
        <p>Sudah punya akun? <a href="login.php">Login</a></p>
    </div>
</body>

</html>
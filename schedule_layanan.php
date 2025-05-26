<?php
include('config.php');
$message = '';

// Ensure $conn is a valid connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the keys exist in $_POST before accessing them
    $transaction_id = $_POST['transaction_id'] ?? null;
    $tanggal_jadwal = $_POST['tanggal_jadwal'] ?? null;

    if ($transaction_id && $tanggal_jadwal) {
        $sql = 'UPDATE transaksi SET tanggal_jadwal = ? WHERE id = ?';
        $stmt = $conn->prepare($sql);

        // Check if prepare() failed
        if ($stmt === false) {
            // Output the error from MySQL and the query for debugging
            die("Error preparing statement: " . $conn->error . "<br>SQL: " . htmlspecialchars($sql));
        }

        // Bind parameters. 's' for string (datetime-local value), 'i' for integer.
        $stmt->bind_param('si', $tanggal_jadwal, $transaction_id);

        if ($stmt->execute()) {
            $message = 'Penjadwalan berhasil!';
        } else {
            $message = 'Error executing statement: ' . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = 'ID Transaksi atau Tanggal Jadwal tidak diterima.';
    }
}

$pending_sql = '
    SELECT t.id, v.pemilik, v.merk, v.tipe, v.plat_nomor, t.total_biaya
    FROM transaksi t
    JOIN kendaraan v ON v.id = t.kendaraan_id
    WHERE t.tanggal_jadwal IS NULL
    ORDER BY t.tanggal ASC
';
$pending = $conn->query($pending_sql);

if ($pending === false) {
    die("Error fetching pending transactions: " . $conn->error . "<br>SQL: " . htmlspecialchars($pending_sql));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Penjadwalan Service</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <a href="index.php">Beranda</a>
        <a href="register_layanan.php">Pendaftaran Service</a>
        <a href="laporan.php">Laporan Service</a>
    </nav>
    <div class="container">
        <h1>Penjadwalan Service</h1>
        <?php if ($message): ?>
            <p class="success flash"><?= $message ?></p>
        <?php endif; ?>
        <table>
            <tr>
                <th>ID Transaksi</th>
                <th>Pemilik</th>
                <th>Kendaraan</th>
                <th>Total Biaya</th>
                <th>Jadwalkan</th>
            </tr>
            <?php while($row = $pending->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['pemilik'] ?></td>
                    <td><?= $row['merk'] . ' ' . $row['tipe'] ?> (<?= $row['plat_nomor'] ?>)</td>
                    <td>Rp<?= number_format($row['total_biaya'],0,',','.') ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="transaction_id" value="<?= htmlspecialchars($row['id']) ?>">
                            <input type="datetime-local" name="tanggal_jadwal" required>
                            <button type="submit">Atur</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
    <script src="script.js"></script>
</body>
</html>

<?php
include('config.php');
$message = '';

// Fetch layanan types
$layanans = $conn->query('SELECT * FROM jenis_layanan');

// ... (previous code) ...

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pemilik      = $_POST['pemilik']    ?? '';
    $merk         = $_POST['merk']       ?? '';
    $tipe         = $_POST['tipe']       ?? '';
    $plat_nomor   = $_POST['plat_nomor'] ?? '';
    // Corrected: Use 'layanans' to match the form input name
    $layanan_ids  = $_POST['layanans']   ?? []; // Changed 'layanan' to 'layanans'

    // Insert kendaraan
    $stmt = $conn->prepare('INSERT INTO kendaraan (pemilik, merk, tipe, plat_nomor) VALUES (?,?,?,?)');
    if ($stmt === false) {
        die("Error preparing kendaraan statement: " . $conn->error);
    }
    $stmt->bind_param('ssss', $pemilik, $merk, $tipe, $plat_nomor);
    $stmt->execute();
    $kendaraan_id = $stmt->insert_id;
    $stmt->close();

    $total_biaya = 0;
    // Check if layanan_ids is not empty before proceeding
    if (!empty($layanan_ids)) {
        $ids_placeholder = implode(',', array_fill(0, count($layanan_ids), '?'));
        $types = str_repeat('i', count($layanan_ids));
        // Store SQL for debugging if needed
        $sql_layanan = "SELECT biaya FROM jenis_layanan WHERE id IN ($ids_placeholder)";
        $layanan_stmt = $conn->prepare($sql_layanan);

        // Check if prepare() failed
        if ($layanan_stmt === false) {
            // Output the error from MySQL
            die("Error preparing layanan statement: " . $conn->error . "<br>SQL: " . htmlspecialchars($sql_layanan));
        }

        $layanan_stmt->bind_param($types, ...$layanan_ids);
        $layanan_stmt->execute();
        $result = $layanan_stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $total_biaya += $row['biaya'];
        }
        $layanan_stmt->close();
    } else {
        // Handle the case where no services are selected, if necessary
        // For example, $total_biaya remains 0, or you might want to show a message.
    }

    // Insert transaction
    $tstmt = $conn->prepare('INSERT INTO transaksi (kendaraan_id, tanggal, total_biaya) VALUES (?,NOW(),?)');
    // Using NOW() directly in SQL is often simpler for the current timestamp.
    // If you must use PHP's date: $now = date('Y-m-d H:i:s');
    if ($tstmt === false) {
        die("Error preparing transaksi statement: " . $conn->error);
    }
    $tstmt->bind_param('id', $kendaraan_id, $total_biaya); // Adjusted types if NOW() is used
    $tstmt->execute();
    $transaction_id = $tstmt->insert_id;
    $tstmt->close();

    // Link layanans
    // Also check if layanan_ids is not empty here
    if (!empty($layanan_ids)) {
        $link_stmt = $conn->prepare('INSERT INTO layanan_transaksi (transaction_id, layanan_id) VALUES (?,?)');
        if ($link_stmt === false) {
            die("Error preparing layanan_transaksi statement: " . $conn->error);
        }
        foreach ($layanan_ids as $sid) {
            $link_stmt->bind_param('ii', $transaction_id, $sid);
            $link_stmt->execute();
        }
        $link_stmt->close();
    }

    $message = 'Pendaftaran berhasil!';
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pendaftaran Service</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <a href="index.php">Beranda</a>
        <a href="schedule_layanan.php">Penjadwalan Service</a>
        <a href="laporan.php">Laporan Service</a>
    </nav>
    <div class="container">
        <h1>Pendaftaran Service</h1>
        <?php if ($message): ?>
            <p class="success flash"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
        <form method="POST">
            <h3>Data Kendaraan</h3>
            <label>Nama Pemilik</label>
            <input type="text" name="pemilik" required>
            <label>Merk</label>
            <input type="text" name="merk" required>
            <label>Model</label>
            <input type="text" name="tipe" required>
            <label>Nomor Plat</label>
            <input type="text" name="plat_nomor" required>

            <h3>Pilih Layanan</h3>
            <?php if ($layanans && $layanans->num_rows > 0): ?>
                <?php while($row = $layanans->fetch_assoc()): ?>
                    <label>
                        <input type="checkbox" name="layanans[]" value="<?= $row['id'] ?>">
                        <?= htmlspecialchars($row['nama']) ?> (Rp<?= number_format($row['biaya'],0,',','.') ?>)
                    </label><br>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Tidak ada layanan yang tersedia saat ini.</p>
            <?php endif; ?>

            <button type="submit">Daftar Service</button>
        </form>
    </div>
    <script src="script.js"></script>
</body>
</html>
<?php
session_start();
include '../config/database.php';

// Pastikan ada ID transaksi di URL
if (!isset($_GET['id'])) {
    die("Error: ID Transaksi tidak ditemukan.");
}

$id_transaksi = $_GET['id'];

// Ambil data transaksi utama, join dengan pelanggan
$sql_transaksi = "SELECT t.*, p.nama_pelanggan 
                  FROM transaksi t 
                  JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
                  WHERE t.id_transaksi = ?";
$stmt_transaksi = $pdo->prepare($sql_transaksi);
$stmt_transaksi->execute([$id_transaksi]);
$transaksi = $stmt_transaksi->fetch(PDO::FETCH_ASSOC);

if (!$transaksi) {
    die("Error: Transaksi tidak ditemukan.");
}

// Ambil detail item transaksi, join dengan produk
$sql_detail = "SELECT dt.jumlah, dt.subtotal, p.nama_produk, p.harga 
               FROM detail_transaksi dt
               JOIN produk p ON dt.id_produk = p.id_produk
               WHERE dt.id_transaksi = ?";
$stmt_detail = $pdo->prepare($sql_detail);
$stmt_detail->execute([$id_transaksi]);
$detail_items = $stmt_detail->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Transaksi #<?php echo $id_transaksi; ?></title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            width: 300px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .info {
            margin-bottom: 20px;
        }
        .info table {
            width: 100%;
        }
        .items table {
            width: 100%;
            border-collapse: collapse;
        }
        .items th, .items td {
            padding: 5px 0;
        }
        .items .item-row td {
            border-bottom: 1px dashed #ccc;
        }
        .total {
            margin-top: 20px;
            text-align: right;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
        }
        hr.dashed {
            border: none;
            border-top: 1px dashed #000;
        }
        .print-button {
            width: 100%;
            padding: 10px;
            background: #0d6efd;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
        }
        @media print {
            .print-button {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Nama Toko Anda</h1>
        <p>Jl. Pahlawan No. 123, Kota Anda</p>
    </div>

    <hr class="dashed">

    <div class="info">
        <table>
            <tr>
                <td>No. Transaksi</td>
                <td>: <?php echo htmlspecialchars($transaksi['id_transaksi']); ?></td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>: <?php echo date('d/m/Y H:i', strtotime($transaksi['tanggal_transaksi'])); ?></td>
            </tr>
            <tr>
                <td>Pelanggan</td>
                <td>: <?php echo htmlspecialchars($transaksi['nama_pelanggan']); ?></td>
            </tr>
            <tr>
                <td>Kasir</td>
                <td>: <?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?></td>
            </tr>
        </table>
    </div>

    <hr class="dashed">

    <div class="items">
        <table>
            <?php foreach ($detail_items as $item): ?>
            <tr class="item-row">
                <td colspan="3"><?php echo htmlspecialchars($item['nama_produk']); ?></td>
            </tr>
            <tr>
                <td style="text-align: left;"><?php echo $item['jumlah']; ?> x</td>
                <td style="text-align: right;"><?php echo number_format($item['harga'], 0, ',', '.'); ?></td>
                <td style="text-align: right;"><?php echo number_format($item['subtotal'], 0, ',', '.'); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <hr class="dashed">

    <div class="total">
        <h3>TOTAL : <?php echo 'Rp ' . number_format($transaksi['total_harga'], 0, ',', '.'); ?></h3>
    </div>

    <div class="footer">
        <p>Terima kasih telah berbelanja!</p>
    </div>

    <button class="print-button" onclick="window.print()">Cetak Struk</button>

</body>
</html>

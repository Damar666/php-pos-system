<?php
include '../templates/header.php';
// Pastikan hanya Admin yang bisa mengakses halaman ini
if ($_SESSION['role'] !== 'Admin') {
    die("Akses ditolak. Halaman ini hanya untuk Admin.");
}
include '../config/database.php';

// Atur filter tanggal default (bulan ini)
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');

// Ambil data transaksi utama berdasarkan rentang tanggal
$sql = "SELECT t.*, p.nama_pelanggan 
        FROM transaksi t
        LEFT JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
        WHERE t.tanggal_transaksi BETWEEN ? AND ?
        ORDER BY t.tanggal_transaksi DESC, t.id_transaksi DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$start_date, $end_date]);
$transaksi = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Laporan Penjualan</h2>
<p>Menampilkan laporan untuk periode: <strong><?php echo $start_date; ?></strong> s/d <strong><?php echo $end_date; ?></strong></p>

<!-- Form Filter Tanggal -->
<form method="get" class="mb-4 card card-body">
    <div class="row">
        <div class="col-md-5">
            <label for="start_date" class="form-label">Dari Tanggal</label>
            <input type="date" class="form-control" name="start_date" value="<?php echo $start_date; ?>">
        </div>
        <div class="col-md-5">
            <label for="end_date" class="form-label">Sampai Tanggal</label>
            <input type="date" class="form-control" name="end_date" value="<?php echo $end_date; ?>">
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
    </div>
</form>

<!-- Tombol Ekspor -->
<div class="mb-3">
    <a href="export_excel.php?start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>" class="btn btn-success">Export ke Excel</a>
    <a href="export_pdf.php?start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>" class="btn btn-danger">Export ke PDF</a>
</div>

<!-- Daftar Kartu Transaksi -->
<?php if (empty($transaksi)): ?>
    <div class="alert alert-info">Tidak ada data transaksi pada periode yang dipilih.</div>
<?php else: ?>
    <?php foreach ($transaksi as $t) : ?>
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <strong>ID Transaksi: <?php echo $t['id_transaksi']; ?></strong> | 
                    Tanggal: <?php echo date('d M Y', strtotime($t['tanggal_transaksi'])); ?> | 
                    Pelanggan: <?php echo htmlspecialchars($t['nama_pelanggan'] ?? 'Umum'); ?>
                </div>
                <div>
                    <!-- Tombol Cetak Ulang Struk -->
                    <a href="../transaksi/cetak_struk.php?id=<?php echo $t['id_transaksi']; ?>" target="_blank" class="btn btn-sm btn-outline-secondary">Cetak Ulang</a>
                </div>
            </div>
            <div class="card-body">
                <!-- Tabel detail item transaksi -->
                <table class="table table-sm table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>Produk</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-end">Harga Satuan</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Ambil detail item untuk setiap transaksi
                        $detail_sql = "SELECT dt.jumlah, dt.subtotal, pr.nama_produk, pr.harga 
                                       FROM detail_transaksi dt 
                                       JOIN produk pr ON dt.id_produk = pr.id_produk 
                                       WHERE dt.id_transaksi = ?";
                        $detail_stmt = $pdo->prepare($detail_sql);
                        $detail_stmt->execute([$t['id_transaksi']]);
                        $details = $detail_stmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($details as $d) :
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($d['nama_produk']); ?></td>
                                <td class="text-center"><?php echo $d['jumlah']; ?></td>
                                <td class="text-end">Rp <?php echo number_format($d['harga'], 0, ',', '.'); ?></td>
                                <td class="text-end">Rp <?php echo number_format($d['subtotal'], 0, ',', '.'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="table-group-divider">
                        <tr>
                            <th colspan="3" class="text-end">Total Harga Transaksi:</th>
                            <th class="text-end fw-bold">Rp <?php echo number_format($t['total_harga'], 0, ',', '.'); ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php include '../templates/footer.php'; ?>


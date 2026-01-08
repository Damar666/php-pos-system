<?php
include '../templates/header.php';
include '../config/database.php';

// Ambil semua data pelanggan untuk ditampilkan di dropdown
$stmt_pelanggan = $pdo->query("SELECT id_pelanggan, nama_pelanggan FROM pelanggan ORDER BY nama_pelanggan ASC");
$pelanggan_list = $stmt_pelanggan->fetchAll(PDO::FETCH_ASSOC);

// Siapkan variabel
$laporan = [];
$pelanggan_terpilih = null;
$total_belanja = 0;
$id_pelanggan_terpilih = isset($_GET['id_pelanggan']) ? $_GET['id_pelanggan'] : null;

// Cek jika form sudah disubmit dan ada pelanggan yang dipilih
if (!empty($id_pelanggan_terpilih)) {
    // 1. Ambil informasi detail pelanggan yang dipilih
    $stmt_info = $pdo->prepare("SELECT * FROM pelanggan WHERE id_pelanggan = ?");
    $stmt_info->execute([$id_pelanggan_terpilih]);
    $pelanggan_terpilih = $stmt_info->fetch(PDO::FETCH_ASSOC);

    // 2. Ambil semua riwayat transaksi beserta detail barangnya
    $sql_laporan = "SELECT t.tanggal_transaksi, t.id_transaksi, p.nama_produk, dt.jumlah, dt.subtotal
                    FROM transaksi t
                    JOIN detail_transaksi dt ON t.id_transaksi = dt.id_transaksi
                    JOIN produk p ON dt.id_produk = p.id_produk
                    WHERE t.id_pelanggan = ?
                    ORDER BY t.tanggal_transaksi DESC, t.id_transaksi DESC";
    $stmt_laporan = $pdo->prepare($sql_laporan);
    $stmt_laporan->execute([$id_pelanggan_terpilih]);
    $laporan = $stmt_laporan->fetchAll(PDO::FETCH_ASSOC);
    
    // 3. Hitung total belanja keseluruhan dari pelanggan tersebut
    $stmt_total = $pdo->prepare("SELECT SUM(total_harga) as total FROM transaksi WHERE id_pelanggan = ?");
    $stmt_total->execute([$id_pelanggan_terpilih]);
    $total_belanja = $stmt_total->fetchColumn();
}
?>

<h2>Laporan per Pelanggan</h2>

<div class="card card-body mb-4">
    <form method="GET" action="laporan_pelanggan.php">
        <label for="id_pelanggan" class="form-label">Pilih Pelanggan untuk Melihat Riwayat Transaksi</label>
        <div class="input-group">
            <select name="id_pelanggan" id="id_pelanggan" class="form-select" required>
                <option value="">-- Pilih Nama Pelanggan --</option>
                <?php foreach ($pelanggan_list as $p): ?>
                    <option value="<?php echo $p['id_pelanggan']; ?>" <?php echo ($p['id_pelanggan'] == $id_pelanggan_terpilih) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($p['nama_pelanggan']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary">Tampilkan</button>
        </div>
    </form>
</div>

<?php if ($pelanggan_terpilih): ?>
    <div class="card">
        <div class="card-header">
            <h4>Riwayat Transaksi: <?php echo htmlspecialchars($pelanggan_terpilih['nama_pelanggan']); ?></h4>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Tanggal</th>
                        <th>ID Transaksi</th>
                        <th>Produk Dibeli</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($laporan)): ?>
                        <tr><td colspan="5" class="text-center">Tidak ada riwayat transaksi.</td></tr>
                    <?php else: ?>
                        <?php foreach ($laporan as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['tanggal_transaksi']); ?></td>
                                <td><?php echo htmlspecialchars($item['id_transaksi']); ?></td>
                                <td><?php echo htmlspecialchars($item['nama_produk']); ?></td>
                                <td><?php echo htmlspecialchars($item['jumlah']); ?></td>
                                <td class="text-end">Rp <?php echo number_format($item['subtotal'], 0, ',', '.'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr class="table-primary">
                        <th colspan="4" class="text-end">Total Seluruh Belanja:</th>
                        <th class="text-end">Rp <?php echo number_format($total_belanja, 0, ',', '.'); ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
<?php endif; ?>

<?php include '../templates/footer.php'; ?>
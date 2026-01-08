<?php
include 'templates/header.php';
include 'config/database.php';

// --- Data untuk Kartu Informasi ---
$stmt_pelanggan = $pdo->query("SELECT COUNT(*) FROM pelanggan");
$jumlah_pelanggan = $stmt_pelanggan->fetchColumn();
$stmt_produk = $pdo->query("SELECT COUNT(*) FROM produk");
$jumlah_produk = $stmt_produk->fetchColumn();
$today = date('Y-m-d');
$stmt_transaksi = $pdo->prepare("SELECT COUNT(*) FROM transaksi WHERE tanggal_transaksi = ?");
$stmt_transaksi->execute([$today]);
$jumlah_transaksi_hari_ini = $stmt_transaksi->fetchColumn();
$stmt_total_penjualan = $pdo->query("SELECT SUM(total_harga) FROM transaksi");
$total_penjualan = $stmt_total_penjualan->fetchColumn();

// --- Data Peringatan Stok Menipis ---
$batas_stok_menipis = 10;
$stmt_stok = $pdo->prepare("SELECT nama_produk, stok FROM produk WHERE stok <= ? ORDER BY stok ASC");
$stmt_stok->execute([$batas_stok_menipis]);
$produk_menipis = $stmt_stok->fetchAll(PDO::FETCH_ASSOC);

// --- KODE BARU: MENGAMBIL DATA 5 PRODUK TERLARIS ---
$sql_terlaris = "SELECT p.nama_produk, SUM(dt.jumlah) as total_terjual
                 FROM detail_transaksi dt
                 JOIN produk p ON dt.id_produk = p.id_produk
                 GROUP BY p.nama_produk
                 ORDER BY total_terjual DESC
                 LIMIT 5";
$stmt_terlaris = $pdo->query($sql_terlaris);
$produk_terlaris = $stmt_terlaris->fetchAll(PDO::FETCH_ASSOC);


// --- Data untuk Grafik Penjualan Mingguan ---
$chart_labels = [];
$chart_data = [];
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $chart_labels[] = date('d M', strtotime($date));
    $chart_data[$date] = 0;
}
$sql_chart = "SELECT tanggal_transaksi, SUM(total_harga) as total_harian FROM transaksi WHERE tanggal_transaksi >= CURDATE() - INTERVAL 6 DAY GROUP BY tanggal_transaksi";
$stmt_chart = $pdo->query($sql_chart);
$sales_data = $stmt_chart->fetchAll(PDO::FETCH_ASSOC);
foreach ($sales_data as $sale) {
    $chart_data[$sale['tanggal_transaksi']] = $sale['total_harian'];
}
$chart_labels_json = json_encode($chart_labels);
$chart_data_json = json_encode(array_values($chart_data));
?>

<h2>Dashboard</h2>
<hr>

<!-- Peringatan Stok Menipis -->
<?php if (!empty($produk_menipis)): ?>
<div class="alert alert-warning" role="alert">
    <h4 class="alert-heading">Peringatan Stok Menipis!</h4>
    <p>Produk berikut memiliki stok <strong><?php echo $batas_stok_menipis; ?></strong> atau kurang. Segera lakukan pemesanan ulang.</p>
    <hr>
    <ul>
        <?php foreach ($produk_menipis as $produk): ?>
            <li><strong><?php echo htmlspecialchars($produk['nama_produk']); ?></strong> - Sisa stok: <?php echo $produk['stok']; ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<!-- Kartu Informasi -->
<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="card text-white bg-danger mb-3">
            <div class="card-header">Total Penjualan</div>
            <div class="card-body"><h5 class="card-title"><?php echo 'Rp ' . number_format($total_penjualan, 0, ',', '.'); ?></h5></div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card text-white bg-primary mb-3">
            <div class="card-header">Jumlah Pelanggan</div>
            <div class="card-body"><h5 class="card-title"><?php echo $jumlah_pelanggan; ?></h5></div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card text-white bg-success mb-3">
            <div class="card-header">Jumlah Produk</div>
            <div class="card-body"><h5 class="card-title"><?php echo $jumlah_produk; ?></h5></div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card text-white bg-info mb-3">
            <div class="card-header">Transaksi Hari Ini</div>
            <div class="card-body"><h5 class="card-title"><?php echo $jumlah_transaksi_hari_ini; ?></h5></div>
        </div>
    </div>
</div>

<!-- Grafik dan Tabel Produk Terlaris -->
<div class="row">
    <div class="col-lg-7">
        <div class="card mt-4">
            <div class="card-header">Grafik Penjualan Mingguan</div>
            <div class="card-body"><canvas id="grafikPenjualan"></canvas></div>
        </div>
    </div>
    <div class="col-lg-5">
        <!-- KODE BARU: TABEL PRODUK TERLARIS -->
        <div class="card mt-4">
            <div class="card-header">5 Produk Terlaris (Berdasarkan Unit)</div>
            <div class="card-body">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Produk</th>
                            <th>Total Terjual</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($produk_terlaris)): ?>
                            <tr><td colspan="3" class="text-center">Belum ada data penjualan.</td></tr>
                        <?php else: ?>
                            <?php $no = 1; foreach ($produk_terlaris as $produk): ?>
                            <tr>
                                <td><?php echo $no++; ?>.</td>
                                <td><?php echo htmlspecialchars($produk['nama_produk']); ?></td>
                                <td class="text-center"><strong><?php echo $produk['total_terjual']; ?></strong></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Sertakan library Chart.js dan plugin datalabels -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

<script>
    Chart.register(ChartDataLabels);
    const labels = <?php echo $chart_labels_json; ?>;
    const data = {
        labels: labels,
        datasets: [{
            label: 'Total Penjualan (Rp)',
            backgroundColor: 'rgba(0, 123, 255, 0.5)',
            borderColor: 'rgba(0, 123, 255, 1)',
            borderWidth: 1,
            data: <?php echo $chart_data_json; ?>,
        }]
    };
    const config = {
        type: 'bar',
        data: data,
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true } },
            plugins: {
                datalabels: {
                    anchor: 'end',
                    align: 'top',
                    formatter: function(value) {
                        return new Intl.NumberFormat('id-ID').format(value);
                    },
                    font: { weight: 'bold' }
                }
            }
        }
    };
    const myChart = new Chart(document.getElementById('grafikPenjualan'), config);
</script>

<?php include 'templates/footer.php'; ?>


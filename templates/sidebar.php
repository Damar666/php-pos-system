<div class="sidebar">
    <h4 class="px-3">Menu Navigasi</h4>
    <hr class="text-secondary">

    <!-- Daftar Menu Navigasi -->
    <ul class="nav flex-column">
        <!-- Menu yang bisa dilihat semua role -->
        <li class="nav-item">
            <a class="nav-link" href="/index.php">Dashboard</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/pelanggan/index.php">Data Pelanggan</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/transaksi/index.php">Transaksi Baru</a>
        </li>

        <?php // --- Blok Menu Khusus untuk Admin --- ?>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'Admin'): ?>
            <li class="nav-item">
                <a class="nav-link" href="/produk/index.php">Data Produk</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/laporan/index.php">Laporan Penjualan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/laporan/laporan_pelanggan.php">Laporan per Pelanggan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/admin/backup.php">Backup Restore & Reset</a>
            </li>
        <?php endif; ?>
    </ul>
</div>


<?php
include '../templates/header.php';

// Pastikan hanya Admin yang bisa mengakses halaman ini
if ($_SESSION['role'] !== 'Admin') {
    die("Akses ditolak. Halaman ini hanya untuk Admin.");
}

include '../config/database.php';

$pesan = ''; // Variabel untuk menyimpan pesan notifikasi
$batas_stok_menipis = 10; // Batas minimal stok

// Proses Hapus Data
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM produk WHERE id_produk = ?");
        $stmt->execute([$_GET['id']]);
        $pesan = '<div class="alert alert-success">Data produk berhasil dihapus.</div>';
    } catch (PDOException $e) {
        // Cek jika error disebabkan oleh foreign key constraint (kode error: 23000)
        if ($e->getCode() == '23000') {
            $pesan = '<div class="alert alert-danger"><strong>Gagal Menghapus!</strong> Produk ini tidak dapat dihapus karena sudah pernah digunakan dalam data transaksi.</div>';
        } else {
            // Untuk error lainnya
            $pesan = '<div class="alert alert-danger">Gagal menghapus data: ' . $e->getMessage() . '</div>';
        }
    }
}

// Ambil semua data produk
$statement = $pdo->query("SELECT * FROM produk ORDER BY id_produk ASC");
$produk = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Manajemen Produk</h2>

<?php echo $pesan; // Tampilkan pesan notifikasi di sini ?>

<a href="tambah.php" class="btn btn-primary mb-3">Tambah Produk</a>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Kode Produk</th>
            <th>Nama Produk</th>
            <th>Kategori</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($produk as $p) : ?>
            <?php
                // Tentukan class untuk baris berdasarkan jumlah stok
                $row_class = $p['stok'] <= $batas_stok_menipis ? 'table-danger' : '';
            ?>
            <tr class="<?php echo $row_class; ?>">
                <td><?php echo htmlspecialchars($p['id_produk']); ?></td>
                <td><?php echo htmlspecialchars($p['kode_produk']); ?></td>
                <td><?php echo htmlspecialchars($p['nama_produk']); ?></td>
                <td><?php echo htmlspecialchars($p['kategori']); ?></td>
                <td class="text-end">Rp <?php echo number_format($p['harga'], 0, ',', '.'); ?></td>
                <td class="text-center"><strong><?php echo htmlspecialchars($p['stok']); ?></strong></td>
                <td>
                    <a href="edit.php?id=<?php echo $p['id_produk']; ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="index.php?action=delete&id=<?php echo $p['id_produk']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?');">Hapus</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include '../templates/footer.php'; ?>


<?php
include '../templates/header.php';
include '../config/database.php';

$pesan = ''; // Variabel untuk menyimpan pesan notifikasi

// Proses Hapus Data
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    // HANYA ADMIN yang bisa menjalankan aksi hapus
    if ($_SESSION['role'] !== 'Admin') {
        die("Akses ditolak. Anda tidak memiliki izin untuk menghapus data.");
    }
    try {
        $stmt = $pdo->prepare("DELETE FROM pelanggan WHERE id_pelanggan = ?");
        $stmt->execute([$_GET['id']]);
        // Menggunakan sistem notifikasi toast
        set_toast('success', 'Data pelanggan berhasil dihapus.');
        header('Location: index.php'); // Redirect untuk menampilkan toast
        exit();
    } catch (PDOException $e) {
        // Cek jika error disebabkan oleh foreign key constraint
        if ($e->getCode() == '23000') {
            set_toast('error', 'Gagal! Pelanggan ini memiliki riwayat transaksi.');
        } else {
            set_toast('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
        header('Location: index.php'); // Redirect untuk menampilkan toast
        exit();
    }
}

// Ambil semua data pelanggan
$statement = $pdo->query("SELECT * FROM pelanggan ORDER BY id_pelanggan ASC");
$pelanggan = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Manajemen Pelanggan</h2>

<!-- Tombol Tambah bisa diakses Admin dan Kasir -->
<a href="tambah.php" class="btn btn-primary mb-3">Tambah Pelanggan</a>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Nama Pelanggan</th>
            <th>Email</th>
            <th>No HP</th>
            <th>Alamat</th>
            <?php // Tampilkan header kolom Aksi HANYA untuk Admin ?>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'Admin'): ?>
                <th>Aksi</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($pelanggan as $p) : ?>
            <tr>
                <td><?php echo htmlspecialchars($p['id_pelanggan']); ?></td>
                <td><?php echo htmlspecialchars($p['nama_pelanggan']); ?></td>
                <td><?php echo htmlspecialchars($p['email']); ?></td>
                <td><?php echo htmlspecialchars($p['no_hp']); ?></td>
                <td><?php echo htmlspecialchars($p['alamat']); ?></td>
                
                <?php // Tampilkan sel <td> untuk Aksi HANYA untuk Admin ?>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'Admin'): ?>
                    <td>
                        <a href="edit.php?id=<?php echo $p['id_pelanggan']; ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="index.php?action=delete&id=<?php echo $p['id_pelanggan']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?');">Hapus</a>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php 

include '../templates/footer.php'; 
?>


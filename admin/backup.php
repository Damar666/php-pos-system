<?php
include '../templates/header.php';

// Pastikan hanya Admin yang bisa mengakses halaman ini
if ($_SESSION['role'] !== 'Admin') {
    die("Error: Anda tidak memiliki hak akses untuk mengakses halaman ini.");
}

$backup_message = '';
$restore_message = '';
$reset_message = '';

// --- LOGIKA UNTUK PROSES BACKUP ---
if (isset($_POST['backup'])) {
    // Pengaturan database
    $host = 'localhost';
    $user = 'root';
    $pass = ''; // Sesuaikan jika database Anda memiliki password
    $db   = 'db_penjualan';

    $mysqldump_path = 'C:\xampp\mysql\bin\mysqldump.exe'; 
    $backup_file = $db . '-backup-' . date("Y-m-d-H-i-s") . '.sql';
    $command = "\"{$mysqldump_path}\" --user={$user} --password={$pass} --host={$host} {$db} > {$backup_file}";

    system($command, $return_var);

    if ($return_var === 0 && file_exists($backup_file)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($backup_file) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($backup_file));
        readfile($backup_file);
        unlink($backup_file);
        exit;
    } else {
        $backup_message = '<div class="alert alert-danger mt-3">Backup gagal dibuat. Pastikan path ke `mysqldump.exe` sudah benar.</div>';
    }
}

// --- LOGIKA UNTUK PROSES RESTORE ---
if (isset($_POST['restore'])) {
    if (isset($_FILES['backup_file']) && $_FILES['backup_file']['error'] == UPLOAD_ERR_OK) {
        $file_tmp_path = $_FILES['backup_file']['tmp_name'];
        $file_name = $_FILES['backup_file']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if ($file_ext == 'sql') {
            try {
                $host = 'localhost'; $user = 'root'; $pass = ''; $db = 'db_penjualan';
                $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
                $sql_commands = file_get_contents($file_tmp_path);
                $pdo->exec('SET FOREIGN_KEY_CHECKS=0;' . $sql_commands . 'SET FOREIGN_KEY_CHECKS=1;');
                $restore_message = '<div class="alert alert-success mt-3">Database berhasil di-restore dari file ' . htmlspecialchars($file_name) . '.</div>';
            } catch (Exception $e) {
                $restore_message = '<div class="alert alert-danger mt-3">Restore gagal: ' . $e->getMessage() . '</div>';
            }
        } else {
            $restore_message = '<div class="alert alert-warning mt-3">Restore gagal. Harap unggah file dengan format `.sql`.</div>';
        }
    } else {
        $restore_message = '<div class="alert alert-danger mt-3">Restore gagal. Tidak ada file yang diunggah atau terjadi error.</div>';
    }
}

// --- LOGIKA BARU UNTUK PROSES RESET ---
if (isset($_POST['reset_database'])) {
    try {
        $host = 'localhost'; $user = 'root'; $pass = ''; $db = 'db_penjualan';
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

        // Perintah untuk mengosongkan tabel data (tabel users tidak dihapus)
        $sql_reset = "
            SET FOREIGN_KEY_CHECKS=0;
            TRUNCATE TABLE `detail_transaksi`;
            TRUNCATE TABLE `transaksi`;
            TRUNCATE TABLE `produk`;
            TRUNCATE TABLE `pelanggan`;
            SET FOREIGN_KEY_CHECKS=1;
        ";
        $pdo->exec($sql_reset);
        $reset_message = '<div class="alert alert-success mt-3">Semua data (transaksi, produk, pelanggan) berhasil di-reset. Akun pengguna tidak dihapus.</div>';

    } catch (Exception $e) {
        $reset_message = '<div class="alert alert-danger mt-3">Reset database gagal: ' . $e->getMessage() . '</div>';
    }
}
?>

<h2>Backup & Restore Database</h2>
<hr>

<!-- KARTU UNTUK BACKUP -->
<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title">1. Backup Database</h5>
        <p class="card-text">
            Klik tombol di bawah untuk mengunduh salinan lengkap dari database saat ini dalam format `.sql`.
        </p>
        <?php echo $backup_message; ?>
        <form method="post">
            <button type="submit" name="backup" class="btn btn-primary">Backup Database Sekarang</button>
        </form>
    </div>
</div>

<!-- KARTU UNTUK RESTORE -->
<div class="card mb-4 border-danger">
    <div class="card-body">
        <h5 class="card-title text-danger">2. Restore Database</h5>
        <p class="card-text">
            Pilih file backup `.sql` yang sebelumnya sudah Anda unduh untuk mengembalikan data.
        </p>
        <div class="alert alert-danger">
            <strong>PERINGATAN!</strong> Proses ini akan **menghapus semua data yang ada saat ini** dan menggantinya dengan data dari file backup.
        </div>
        <?php echo $restore_message; ?>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="backup_file" class="form-label">Pilih file .sql untuk di-restore:</label>
                <input class="form-control" type="file" id="backup_file" name="backup_file" accept=".sql" required>
            </div>
            <button type="submit" name="restore" class="btn btn-danger" onclick="return confirm('APAKAH ANDA YAKIN? Semua data saat ini akan terhapus dan diganti dengan data dari file backup. Aksi ini tidak bisa dibatalkan.');">Restore Database</button>
        </form>
    </div>
</div>

<!-- KARTU BARU UNTUK RESET -->
<div class="card border-dark">
    <div class="card-body">
        <h5 class="card-title text-dark">3. Reset Database</h5>
        <p class="card-text">
            Gunakan fitur ini untuk menghapus semua data transaksi, produk, dan pelanggan.
        </p>
        <div class="alert alert-dark">
            <strong>PERHATIAN!</strong> Fitur ini akan mengosongkan database Anda, tetapi **tidak akan menghapus akun pengguna (Admin/Kasir)**. Gunakan jika Anda ingin memulai dari awal dengan data kosong.
        </div>
        <?php echo $reset_message; ?>
        <form method="post">
            <button type="submit" name="reset_database" class="btn btn-dark" onclick="return confirm('ANDA YAKIN INGIN MENGHAPUS SEMUA DATA TRANSAKSI, PRODUK, DAN PELANGGAN? Aksi ini akan mengosongkan database Anda.');">Reset Database Sekarang</button>
        </form>
    </div>
</div>


<?php include '../templates/footer.php'; ?>


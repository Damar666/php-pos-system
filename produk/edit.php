<?php
include '../templates/header.php';

// Pastikan hanya Admin yang bisa mengakses halaman ini
if ($_SESSION['role'] !== 'Admin') {
    die("Akses ditolak. Halaman ini hanya untuk Admin.");
}

include '../config/database.php';

// Cek apakah ada ID produk di URL
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$id = $_GET['id'];

// Proses update data saat form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    validate_csrf_token();
    
    $kode_produk = $_POST['kode_produk'];
    $nama_produk = $_POST['nama_produk'];
    $kategori = $_POST['kategori'];
    // Ambil nilai angka murni dari harga
    $harga = preg_replace('/[^\d]/', '', $_POST['harga']);
    $stok = $_POST['stok'];
    
    // Validasi kode produk unik (kecuali untuk produk saat ini)
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM produk WHERE kode_produk = ? AND id_produk != ?");
    $stmt->execute([$kode_produk, $id]);
    if ($stmt->fetchColumn() > 0) {
        echo "<div class='alert alert-danger'>Kode Produk sudah digunakan oleh produk lain!</div>";
    } else {
        // Lakukan update
        $sql = "UPDATE produk SET kode_produk = ?, nama_produk = ?, kategori = ?, harga = ?, stok = ? WHERE id_produk = ?";
        $pdo->prepare($sql)->execute([$kode_produk, $nama_produk, $kategori, $harga, $stok, $id]);
        echo "<div class='alert alert-success'>Data produk berhasil diubah. <a href='index.php'>Kembali ke daftar</a></div>";
    }
}

// Ambil data produk yang akan diedit
$stmt = $pdo->prepare("SELECT * FROM produk WHERE id_produk = ?");
$stmt->execute([$id]);
$produk = $stmt->fetch(PDO::FETCH_ASSOC);

// Jika produk tidak ditemukan
if (!$produk) {
    echo "<div class='alert alert-danger'>Produk tidak ditemukan.</div>";
    include '../templates/footer.php';
    exit();
}
?>

<!-- 1. Tambahkan JS Library AutoNumeric -->
<script src="https://cdn.jsdelivr.net/npm/autonumeric@4.6.0/dist/autoNumeric.min.js"></script>

<h2>Edit Produk</h2>
<form action="edit.php?id=<?php echo $id; ?>" method="post">
    <?php csrf_input_field(); ?>
    <div class="mb-3">
        <label for="kode_produk" class="form-label">Kode Produk</label>
        <input type="text" class="form-control" id="kode_produk" name="kode_produk" value="<?php echo htmlspecialchars($produk['kode_produk']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="nama_produk" class="form-label">Nama Produk</label>
        <input type="text" class="form-control" id="nama_produk" name="nama_produk" value="<?php echo htmlspecialchars($produk['nama_produk']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="kategori" class="form-label">Kategori</label>
        <input type="text" class="form-control" id="kategori" name="kategori" value="<?php echo htmlspecialchars($produk['kategori']); ?>">
    </div>
    <div class="mb-3">
        <label for="harga" class="form-label">Harga</label>
        <!-- 2. Ubah tipe input menjadi 'text' dan isi nilainya -->
        <input type="text" class="form-control" id="harga" name="harga" value="<?php echo htmlspecialchars($produk['harga']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="stok" class="form-label">Stok</label>
        <input type="number" class="form-control" id="stok" name="stok" value="<?php echo htmlspecialchars($produk['stok']); ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
</form>

<!-- 3. Inisialisasi Plugin AutoNumeric -->
<script>
    // Inisialisasi AutoNumeric pada input harga
    new AutoNumeric('#harga', {
        currencySymbol : 'Rp ',
        decimalCharacter : ',',
        digitGroupSeparator : '.',
        decimalPlaces: 0,
        unformatOnSubmit: true // Penting: hapus format saat submit
    });
</script>

<?php include '../templates/footer.php'; ?>

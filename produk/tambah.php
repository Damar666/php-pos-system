<?php
include '../templates/header.php';

// Pastikan hanya Admin yang bisa mengakses halaman ini
if ($_SESSION['role'] !== 'Admin') {
    die("Akses ditolak. Halaman ini hanya untuk Admin.");
}

include '../config/database.php';

// Proses penambahan data saat form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    validate_csrf_token();
    
    $kode_produk = $_POST['kode_produk'];
    $nama_produk = $_POST['nama_produk'];
    $kategori = $_POST['kategori'];
    // Ambil nilai angka murni dari harga
    $harga = preg_replace('/[^\d]/', '', $_POST['harga']); 
    $stok = $_POST['stok'];

    // Validasi kode produk unik
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM produk WHERE kode_produk = ?");
    $stmt->execute([$kode_produk]);
    if ($stmt->fetchColumn() > 0) {
        echo "<div class='alert alert-danger'>Kode Produk sudah ada!</div>";
    } else {
        // Simpan data ke database
        $sql = "INSERT INTO produk (kode_produk, nama_produk, kategori, harga, stok) VALUES (?, ?, ?, ?, ?)";
        $pdo->prepare($sql)->execute([$kode_produk, $nama_produk, $kategori, $harga, $stok]);
        echo "<div class='alert alert-success'>Produk berhasil ditambahkan. <a href='index.php'>Kembali ke daftar</a></div>";
    }
}
?>

<!-- 1. Tambahkan JS Library AutoNumeric -->
<script src="https://cdn.jsdelivr.net/npm/autonumeric@4.6.0/dist/autoNumeric.min.js"></script>

<h2>Tambah Produk Baru</h2>
<form action="tambah.php" method="post">
    <?php csrf_input_field(); ?>
    <div class="mb-3">
        <label for="kode_produk" class="form-label">Kode Produk</label>
        <input type="text" class="form-control" id="kode_produk" name="kode_produk" required>
    </div>
    <div class="mb-3">
        <label for="nama_produk" class="form-label">Nama Produk</label>
        <input type="text" class="form-control" id="nama_produk" name="nama_produk" required>
    </div>
    <div class="mb-3">
        <label for="kategori" class="form-label">Kategori</label>
        <input type="text" class="form-control" id="kategori" name="kategori">
    </div>
    <div class="mb-3">
        <label for="harga" class="form-label">Harga</label>
        <!-- 2. Ubah tipe input menjadi 'text' agar bisa diformat -->
        <input type="text" class="form-control" id="harga" name="harga" required>
    </div>
    <div class="mb-3">
        <label for="stok" class="form-label">Stok</label>
        <input type="number" class="form-control" id="stok" name="stok" required>
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
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

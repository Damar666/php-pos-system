<?php
include '../templates/header.php';

// Pastikan hanya Admin yang bisa mengakses halaman ini
if ($_SESSION['role'] !== 'Admin') {
    die("Akses ditolak. Anda bukan Admin.");
}

include '../config/database.php';

$id = $_GET['id']; // Ambil ID dari URL

// Proses update data saat form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    validate_csrf_token();
    
    // Ambil data dari form
    $nama_pelanggan = $_POST['nama_pelanggan'];
    $email = $_POST['email'];
    $no_hp = $_POST['no_hp']; // Ini akan berisi nomor lengkap dengan kode negara
    $alamat = $_POST['alamat'];
    
    // Validasi email unik, kecuali untuk email milik pelanggan saat ini
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM pelanggan WHERE email = ? AND id_pelanggan != ?");
    $stmt->execute([$email, $id]);
    if ($stmt->fetchColumn() > 0) {
        echo "<div class='alert alert-danger'>Email sudah digunakan oleh pelanggan lain!</div>";
    } else {
        // Lakukan update ke database
        $sql = "UPDATE pelanggan SET nama_pelanggan = ?, email = ?, no_hp = ?, alamat = ? WHERE id_pelanggan = ?";
        $pdo->prepare($sql)->execute([$nama_pelanggan, $email, $no_hp, $alamat, $id]);
        echo "<div class='alert alert-success'>Data berhasil diubah. <a href='index.php'>Kembali ke daftar</a></div>";
    }
}

// Ambil data pelanggan saat ini untuk ditampilkan di form
$stmt = $pdo->prepare("SELECT * FROM pelanggan WHERE id_pelanggan = ?");
$stmt->execute([$id]);
$pelanggan = $stmt->fetch(PDO::FETCH_ASSOC);

// Jika data pelanggan tidak ditemukan
if (!$pelanggan) {
    echo "<div class='alert alert-danger'>Data pelanggan tidak ditemukan.</div>";
    include '../templates/footer.php';
    exit();
}
?>

<!-- 1. Tambahkan CSS & JS Library -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>

<h2>Edit Pelanggan</h2>
<form action="edit.php?id=<?php echo $id; ?>" method="post">
    <?php csrf_input_field(); ?>
    <div class="mb-3">
        <label for="nama_pelanggan" class="form-label">Nama Pelanggan</label>
        <input type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan" value="<?php echo htmlspecialchars($pelanggan['nama_pelanggan']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($pelanggan['email']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="no_hp" class="form-label">No HP</label><br>
        <!-- 2. Ubah Input menjadi Tipe 'tel' -->
        <input type="tel" class="form-control" id="no_hp" name="no_hp" value="<?php echo htmlspecialchars($pelanggan['no_hp']); ?>">
    </div>
    <div class="mb-3">
        <label for="alamat" class="form-label">Alamat</label>
        <textarea class="form-control" id="alamat" name="alamat" rows="3"><?php echo htmlspecialchars($pelanggan['alamat']); ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
</form>

<!-- 3. Inisialisasi Plugin -->
<script>
    const phoneInputField = document.querySelector("#no_hp");
    const phoneInput = window.intlTelInput(phoneInputField, {
        initialCountry: "auto",
        geoIpLookup: function(callback) {
            callback("id");
        },
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
        // --- PERUBAHAN DI SINI ---
        placeholderNumberType: "MOBILE", // Menampilkan contoh nomor HP
        separateDialCode: true, // Memisahkan kode negara dari input
        nationalMode: false, // Selalu tampilkan kode negara
        onlyCountries: ["id", "us", "gb", "sg", "my", "au"] // Opsi: Batasi hanya negara tertentu
    });

    // Saat form disubmit, pastikan nilai input adalah nomor lengkap
    const form = phoneInputField.closest("form");
    form.addEventListener("submit", function() {
        phoneInputField.value = phoneInput.getNumber();
    });

    // Batasi input hanya untuk angka
    phoneInputField.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
</script>

<?php include '../templates/footer.php'; ?>


<?php
include '../templates/header.php';
include '../config/database.php';

// Proses penambahan data saat form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    validate_csrf_token();
    
    $nama_pelanggan = $_POST['nama_pelanggan'];
    $email = $_POST['email'];
    $no_hp = $_POST['no_hp']; // Ini akan berisi nomor lengkap dengan kode negara
    $alamat = $_POST['alamat'];

    // Validasi email unik
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM pelanggan WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetchColumn() > 0) {
        echo "<div class='alert alert-danger'>Email sudah terdaftar!</div>";
    } else {
        // Simpan data
        $sql = "INSERT INTO pelanggan (nama_pelanggan, email, no_hp, alamat) VALUES (?, ?, ?, ?)";
        $pdo->prepare($sql)->execute([$nama_pelanggan, $email, $no_hp, $alamat]);
        echo "<div class='alert alert-success'>Data berhasil ditambahkan. <a href='index.php'>Kembali ke daftar</a></div>";
    }
}
?>

<!-- 1. Tambahkan CSS & JS Library -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>

<h2>Tambah Pelanggan Baru</h2>
<form action="tambah.php" method="post">
    <?php csrf_input_field(); ?>
    <div class="mb-3">
        <label for="nama_pelanggan" class="form-label">Nama Pelanggan</label>
        <input type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="mb-3">
        <label for="no_hp" class="form-label">No HP</label><br>
        <!-- 2. Ubah Input Teks menjadi Tipe 'tel' -->
        <input type="tel" class="form-control" id="no_hp" name="no_hp">
    </div>
    <div class="mb-3">
        <label for="alamat" class="form-label">Alamat</label>
        <textarea class="form-control" id="alamat" name="alamat" rows="3"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
</form>

<!-- 3. Inisialisasi Plugin dengan Opsi Lengkap -->
<script>
    const phoneInputField = document.querySelector("#no_hp");
    const phoneInput = window.intlTelInput(phoneInputField, {
        initialCountry: "id", // Atur negara default ke Indonesia
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
        placeholderNumberType: "MOBILE", // Menampilkan contoh nomor HP
        separateDialCode: true, // Memisahkan kode negara dari input
        nationalMode: false, // Selalu tampilkan kode negara
    });

    // Saat form disubmit, pastikan nilai input adalah nomor lengkap dengan kode negara
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


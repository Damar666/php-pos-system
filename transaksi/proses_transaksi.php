<?php
include '../templates/header.php';
include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    validate_csrf_token();
    
    // Pastikan ada produk yang dibeli
    if (empty($_POST['produk'])) {
        echo "<div class='alert alert-danger'>Tidak ada produk yang dipilih untuk transaksi.</div>";
        include '../templates/footer.php';
        exit();
    }
    
    $id_pelanggan = $_POST['id_pelanggan'];
    $tanggal_transaksi = $_POST['tanggal_transaksi'];
    $produk_dibeli = $_POST['produk'];

    $pdo->beginTransaction();

    try {
        // Validasi stok sebelum memproses lebih lanjut
        foreach ($produk_dibeli as $item) {
            $stmt_cek_stok = $pdo->prepare("SELECT stok, nama_produk FROM produk WHERE id_produk = ?");
            $stmt_cek_stok->execute([$item['id']]);
            $produk = $stmt_cek_stok->fetch(PDO::FETCH_ASSOC);

            if ($produk['stok'] < $item['jumlah']) {
                throw new Exception("Stok untuk produk '{$produk['nama_produk']}' tidak mencukupi (sisa: {$produk['stok']}).");
            }
        }
        
        // Hitung ulang total harga di sisi server untuk keamanan
        $total_harga_server = 0;
        foreach ($produk_dibeli as $item) {
             $stmt_harga = $pdo->prepare("SELECT harga FROM produk WHERE id_produk = ?");
             $stmt_harga->execute([$item['id']]);
             $harga_produk = $stmt_harga->fetchColumn();
             $total_harga_server += $harga_produk * $item['jumlah'];
        }

        // 1. Simpan data utama ke tabel 'transaksi'
        $sql_transaksi = "INSERT INTO transaksi (id_pelanggan, tanggal_transaksi, total_harga) VALUES (?, ?, ?)";
        $stmt_transaksi = $pdo->prepare($sql_transaksi);
        $stmt_transaksi->execute([$id_pelanggan, $tanggal_transaksi, $total_harga_server]);
        $id_transaksi_terakhir = $pdo->lastInsertId();
        
        // 2. Simpan detail dan update stok
        foreach ($produk_dibeli as $item) {
            $stmt_harga = $pdo->prepare("SELECT harga FROM produk WHERE id_produk = ?");
            $stmt_harga->execute([$item['id']]);
            $harga_produk = $stmt_harga->fetchColumn();
            $subtotal = $harga_produk * $item['jumlah'];

            $sql_detail = "INSERT INTO detail_transaksi (id_transaksi, id_produk, jumlah, subtotal) VALUES (?, ?, ?, ?)";
            $stmt_detail = $pdo->prepare($sql_detail);
            $stmt_detail->execute([$id_transaksi_terakhir, $item['id'], $item['jumlah'], $subtotal]);
            
            $sql_update_stok = "UPDATE produk SET stok = stok - ? WHERE id_produk = ?";
            $stmt_update_stok = $pdo->prepare($sql_update_stok);
            $stmt_update_stok->execute([$item['jumlah'], $item['id']]);
        }

        $pdo->commit();
        
        // --- INI BAGIAN YANG DIUBAH ---
        // Menampilkan pesan sukses dengan tombol cetak struk
        echo "<div class='alert alert-success'>";
        echo "<h4>Transaksi Berhasil Disimpan!</h4>";
        echo "<p>Transaksi dengan ID <strong>#{$id_transaksi_terakhir}</strong> telah berhasil dibuat.</p>";
        echo "<a href='cetak_struk.php?id={$id_transaksi_terakhir}' target='_blank' class='btn btn-primary me-2'>Cetak Struk</a>";
        echo "<a href='index.php' class='btn btn-secondary'>Buat Transaksi Baru</a>";
        echo "</div>";

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<div class='alert alert-danger'><strong>Transaksi Gagal:</strong> " . $e->getMessage() . "</div>";
    }
}

include '../templates/footer.php';
?>


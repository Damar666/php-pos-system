<?php
include '../templates/header.php';
include '../config/database.php';

// Ambil data pelanggan dan produk untuk dropdown
$pelanggan = $pdo->query("SELECT * FROM pelanggan ORDER BY nama_pelanggan ASC")->fetchAll(PDO::FETCH_ASSOC);
$produk = $pdo->query("SELECT * FROM produk WHERE stok > 0 ORDER BY nama_produk ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Form Transaksi Penjualan</h2>

<form action="proses_transaksi.php" method="post" id="form-transaksi">
    <?php csrf_input_field(); ?>
    <!-- Informasi Pelanggan dan Tanggal -->
    <div class="row mb-3">
        <div class="col-md-6">
            <label for="id_pelanggan" class="form-label">Pelanggan</label>
            <select class="form-control" id="id_pelanggan" name="id_pelanggan" required>
                <option value="">-- Pilih Pelanggan --</option>
                <?php foreach ($pelanggan as $p) : ?>
                    <option value="<?php echo $p['id_pelanggan']; ?>"><?php echo htmlspecialchars($p['nama_pelanggan']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
            <label for="tanggal_transaksi" class="form-label">Tanggal Transaksi</label>
            <input type="date" class="form-control" id="tanggal_transaksi" name="tanggal_transaksi" value="<?php echo date('Y-m-d'); ?>" required>
        </div>
    </div>

    <!-- Container untuk baris-baris produk -->
    <div id="produk-container">
        <div class="row produk-item mb-3 align-items-center">
            <div class="col-md-5">
                <label class="form-label">Produk</label>
                <select class="form-control produk-pilihan" name="produk[0][id]" onchange="updateHarga(this)" required>
                    <option value="">-- Pilih Produk --</option>
                    <?php foreach ($produk as $prod) : ?>
                        <option value="<?php echo $prod['id_produk']; ?>" data-harga="<?php echo $prod['harga']; ?>" data-stok="<?php echo $prod['stok']; ?>">
                            <?php echo htmlspecialchars($prod['nama_produk']); ?> (Stok: <?php echo $prod['stok']; ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Jumlah</label>
                <input type="number" class="form-control jumlah" name="produk[0][jumlah]" placeholder="Jumlah" min="1" oninput="hitungSubtotal(this)" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Subtotal</label>
                <!-- Input tersembunyi untuk menyimpan nilai asli -->
                <input type="hidden" class="subtotal-input" name="produk[0][subtotal]" value="0">
                <!-- Input yang terlihat untuk menampilkan format Rupiah -->
                <input type="text" class="form-control subtotal-display" placeholder="Subtotal" readonly>
            </div>
            <div class="col-md-2 pt-4">
                <button type="button" class="btn btn-danger w-100" onclick="hapusProduk(this)">Hapus</button>
            </div>
        </div>
    </div>

    <button type="button" class="btn btn-success mt-2" id="tambah-produk">Tambah Produk</button>

    <!-- Total Harga Keseluruhan -->
    <div class="mt-4">
        <h4 class="text-end">Total Harga: <span id="total-harga-display">Rp 0</span></h4>
        <input type="hidden" id="total_harga_input" name="total_harga" value="0">
    </div>

    <hr>
    <button type="submit" class="btn btn-primary mt-3 float-end">Simpan Transaksi</button>
</form>

<script>
    // Fungsi untuk memformat angka menjadi format Rupiah (Rp)
    function formatRupiah(angka) {
        if (isNaN(angka)) return "";
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(angka);
    }

    let produkIndex = 1;
    document.getElementById('tambah-produk').addEventListener('click', function() {
        const container = document.getElementById('produk-container');
        // Kloning baris produk pertama
        const newProdukRow = container.querySelector('.produk-item').cloneNode(true);

        // Reset nilai-nilai di baris baru
        newProdukRow.querySelector('.produk-pilihan').name = `produk[${produkIndex}][id]`;
        newProdukRow.querySelector('.produk-pilihan').value = "";
        newProdukRow.querySelector('.jumlah').name = `produk[${produkIndex}][jumlah]`;
        newProdukRow.querySelector('.jumlah').value = "";
        newProdukRow.querySelector('.subtotal-input').name = `produk[${produkIndex}][subtotal]`;
        newProdukRow.querySelector('.subtotal-input').value = "0";
        newProdukRow.querySelector('.subtotal-display').value = "";

        // Tambahkan baris baru ke container
        container.appendChild(newProdukRow);
        produkIndex++;
    });

    function hapusProduk(button) {
        // Jangan hapus jika hanya tersisa satu baris
        if (document.querySelectorAll('.produk-item').length > 1) {
            button.closest('.produk-item').remove();
            hitungTotalKeseluruhan();
        } else {
            alert('Minimal harus ada satu produk dalam transaksi.');
        }
    }

    function hitungSubtotal(input) {
        const item = input.closest('.produk-item');
        const select = item.querySelector('.produk-pilihan');
        const selectedOption = select.options[select.selectedIndex];
        const harga = selectedOption.getAttribute('data-harga');
        const stok = parseInt(selectedOption.getAttribute('data-stok'));
        const jumlah = parseInt(input.value);

        // Validasi stok
        if (jumlah > stok) {
            alert(`Stok tidak mencukupi! Sisa stok untuk produk ini adalah ${stok}.`);
            input.value = stok; // Set jumlah ke nilai stok maksimal
            // Panggil kembali fungsi ini untuk menghitung ulang dengan nilai yang benar
            if (stok > 0) {
                hitungSubtotal(input);
            }
            return;
        }

        const subtotalInputField = item.querySelector('.subtotal-input');
        const subtotalDisplayField = item.querySelector('.subtotal-display');

        if (harga && jumlah > 0) {
            const subtotal = parseFloat(harga) * jumlah;
            subtotalInputField.value = subtotal; // Simpan nilai angka
            subtotalDisplayField.value = formatRupiah(subtotal); // Tampilkan format Rp
        } else {
            subtotalInputField.value = "0";
            subtotalDisplayField.value = "";
        }
        hitungTotalKeseluruhan();
    }

    function hitungTotalKeseluruhan() {
        let total = 0;
        document.querySelectorAll('.subtotal-input').forEach(function(sub) {
            if (sub.value) {
                total += parseFloat(sub.value);
            }
        });
        document.getElementById('total-harga-display').innerText = formatRupiah(total); // Tampilkan format Rp
        document.getElementById('total_harga_input').value = total; // Simpan nilai angka
    }

    function updateHarga(select) {
        // Saat produk diganti, panggil hitungSubtotal untuk baris tersebut
        hitungSubtotal(select.closest('.produk-item').querySelector('.jumlah'));
    }

    // Panggil sekali di awal untuk memastikan tampilan Total Harga sudah benar
    document.addEventListener('DOMContentLoaded', function() {
        hitungTotalKeseluruhan();
    });
</script>

<?php include '../templates/footer.php'; ?>


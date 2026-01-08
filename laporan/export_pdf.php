<?php
require '../vendor/autoload.php';
include '../config/database.php';

// Mengambil tanggal dari URL
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');

// Query untuk rekap harian
$sql = "SELECT tanggal_transaksi, SUM(total_harga) as total_omzet_harian FROM transaksi WHERE tanggal_transaksi BETWEEN ? AND ? GROUP BY tanggal_transaksi ORDER BY tanggal_transaksi ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$start_date, $end_date]);
$laporan_harian = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Membuat object PDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A4', true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Sistem Penjualan');
$pdf->SetTitle('Laporan Rekap Penjualan');
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(true);
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 11);

// --- HEADER LAPORAN ---
$logoPath = '../assets/pdf-logo.png';
if (file_exists($logoPath)) {
    $pdf->Image($logoPath, 15, 12, 25, 0, 'PNG');
}
$pdf->SetY(15);
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Laporan Rekap Penjualan Harian', 0, 1, 'C');
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(0, 8, 'Periode: ' . $start_date . ' s/d ' . $end_date, 0, 1, 'C');
$pdf->Ln(10);
// --- AKHIR HEADER ---


// --- TABEL LAPORAN (Metode Manual Menggunakan MultiCell) ---
// Pengaturan untuk Header Tabel
$pdf->SetFont('helvetica', 'B', 11);
$pdf->SetFillColor(52, 58, 64); // Warna abu-abu tua
$pdf->SetTextColor(255, 255, 255); // Warna teks putih
$pdf->SetDrawColor(108, 117, 125); // Warna border
$pdf->SetLineWidth(0.3);

// Lebar kolom (total 180mm untuk A4 dengan margin standar)
$w = array(25, 95, 60); 

// Cetak Header Tabel
$pdf->Cell($w[0], 7, 'No', 1, 0, 'C', 1);
$pdf->Cell($w[1], 7, 'Tanggal', 1, 0, 'C', 1);
$pdf->Cell($w[2], 7, 'Total Omzet Harian', 1, 1, 'C', 1);

// Pengaturan untuk Isi Tabel
$pdf->SetFont('helvetica', '', 10);
$pdf->SetFillColor(248, 249, 250); // Warna abu-abu muda
$pdf->SetTextColor(0, 0, 0); // Warna teks hitam
$fill = false; // Untuk warna selang-seling

$total_omzet_keseluruhan = 0;
$no = 1;
if (empty($laporan_harian)) {
    $pdf->Cell(array_sum($w), 10, 'Tidak ada data transaksi pada periode ini.', 1, 1, 'C', $fill);
} else {
    foreach ($laporan_harian as $data) {
        $total_omzet_keseluruhan += $data['total_omzet_harian'];
        
        // Cetak baris data
        // Parameter MultiCell: width, height, text, border, align, fill, ln
        $pdf->MultiCell($w[0], 8, $no++, 1, 'C', $fill, 0);
        $pdf->MultiCell($w[1], 8, date('d F Y', strtotime($data['tanggal_transaksi'])), 1, 'L', $fill, 0);
        $pdf->MultiCell($w[2], 8, 'Rp ' . number_format($data['total_omzet_harian'], 0, ',', '.'), 1, 'R', $fill, 1);
        
        $fill = !$fill; // Ganti warna untuk baris berikutnya
    }
}

// Pengaturan untuk Footer Tabel
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell($w[0] + $w[1], 8, 'Total Omzet Keseluruhan', 1, 0, 'R', $fill);
$pdf->Cell($w[2], 8, 'Rp ' . number_format($total_omzet_keseluruhan, 0, ',', '.'), 1, 1, 'R', $fill);
// --- AKHIR TABEL ---

// Menyiapkan output PDF
$filename = 'rekap-penjualan-' . $start_date . '-' . $end_date . '.pdf';
$pdf->Output($filename, 'I');
exit();
?>
<?php
// Memanggil autoloader dari Composer
require '../vendor/autoload.php';
include '../config/database.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;

// Mengambil tanggal dari URL
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');

// Query untuk mengambil data laporan lengkap
$sql = "SELECT
            t.id_transaksi,
            t.tanggal_transaksi,
            pl.nama_pelanggan,
            pr.nama_produk,
            dt.jumlah,
            pr.harga AS harga_satuan,
            dt.subtotal
        FROM transaksi t
        JOIN pelanggan pl ON t.id_pelanggan = pl.id_pelanggan
        JOIN detail_transaksi dt ON t.id_transaksi = dt.id_transaksi
        JOIN produk pr ON dt.id_produk = pr.id_produk
        WHERE t.tanggal_transaksi BETWEEN ? AND ?
        ORDER BY t.tanggal_transaksi, t.id_transaksi ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute([$start_date, $end_date]);
$laporan = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Membuat object Spreadsheet baru
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Menulis Judul Utama
$sheet->setCellValue('A1', 'Laporan Penjualan');
$sheet->mergeCells('A1:G1');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Menulis Periode Laporan
$sheet->setCellValue('A2', 'Periode: ' . $start_date . ' s/d ' . $end_date);
$sheet->mergeCells('A2:G2');
$sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Header Tabel
$sheet->setCellValue('A4', 'ID Transaksi');
$sheet->setCellValue('B4', 'Tanggal');
$sheet->setCellValue('C4', 'Nama Pelanggan');
$sheet->setCellValue('D4', 'Nama Produk');
$sheet->setCellValue('E4', 'Jumlah');
$sheet->setCellValue('F4', 'Harga Satuan');
$sheet->setCellValue('G4', 'Subtotal');
$sheet->getStyle('A4:G4')->getFont()->setBold(true);

// Menulis data dari database ke dalam baris Excel
$row = 5;
$total_omzet = 0;
if (empty($laporan)) {
    $sheet->setCellValue('A5', 'Tidak ada data transaksi pada periode ini.');
    $sheet->mergeCells('A5:G5');
    $sheet->getStyle('A5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
} else {
    foreach ($laporan as $data) {
        $sheet->setCellValue('A' . $row, $data['id_transaksi']);
        $sheet->setCellValue('B' . $row, $data['tanggal_transaksi']);
        $sheet->setCellValue('C' . $row, $data['nama_pelanggan']);
        $sheet->setCellValue('D' . $row, $data['nama_produk']);
        $sheet->setCellValue('E' . $row, $data['jumlah']);
        $sheet->setCellValue('F' . $row, $data['harga_satuan']);
        $sheet->setCellValue('G' . $row, $data['subtotal']);
        $total_omzet += $data['subtotal'];
        $row++;
    }

    // Menambahkan baris Total Omzet
    $sheet->setCellValue('F' . $row, 'Total Omzet');
    $sheet->setCellValue('G' . $row, $total_omzet);
    $sheet->getStyle('F' . $row . ':G' . $row)->getFont()->setBold(true);
}

// Mengatur format angka untuk kolom harga dan subtotal
$sheet->getStyle('F5:G' . $row)->getNumberFormat()->setFormatCode('_("Rp"* #,##0.00_);_("Rp"* \(#,##0.00\);_("Rp"* "-"??_);_(@_)');

// Mengatur lebar kolom agar otomatis menyesuaikan
foreach (range('A', 'G') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

// Mengatur header HTTP untuk memaksa download file
$writer = new Xlsx($spreadsheet);
$filename = 'laporan-penjualan-' . $start_date . '-' . $end_date . '.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');
$writer->save('php://output');
exit();
?>
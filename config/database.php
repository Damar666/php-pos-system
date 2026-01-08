<?php
// Konfigurasi Database
$host = 'localhost';
$user = 'root'; // Sesuaikan dengan username database Anda
$pass = ''; // Sesuaikan dengan password database Anda
$db   = 'db_penjualan'; 

// Membuat koneksi menggunakan PDO untuk keamanan
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    // Set mode error PDO ke exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Tampilkan pesan error jika koneksi gagal
    die("Koneksi atau query bermasalah: " . $e->getMessage());
}
?>
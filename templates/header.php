<?php
session_start();

// Cek sesi login dan redirect ke URL yang benar untuk Laragon
if (!isset($_SESSION['user_id']) && basename($_SERVER['PHP_SELF']) != 'login.php') {
    header("Location: /login.php");
    exit();
}

// Panggil file csrf.php jika user sudah login
if (isset($_SESSION['user_id'])) {
    require_once __DIR__ . '/../config/csrf.php';
    generate_csrf_token();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { display: flex; min-height: 100vh; background-color: #f4f7f6; }
        .sidebar { flex-shrink: 0; width: 250px; background: #212529; color: white; }
        .sidebar a { color: rgba(255, 255, 255, 0.8); padding: 10px 15px; }
        .sidebar a:hover { color: white; background-color: #343a40; text-decoration: none; }
        .content { flex-grow: 1; padding: 0; }
        .content-inner { padding: 30px; }
        .topbar {
            background: white;
            padding: 10px 30px;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }
        .user-info { text-align: right; }
        .user-info strong { display: block; }
        .user-info small { color: #6c757d; }
    </style>
</head>
<body>
    <?php
    if (isset($_SESSION['user_id'])) {
        include 'sidebar.php';
    }
    ?>
    <div class="content">
        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="topbar">
                <div class="user-info me-3">
                    <strong><?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?></strong>
                    <small>Role: <?php echo htmlspecialchars($_SESSION['role']); ?></small>
                </div>
                <a href="/logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
            </div>
        <?php endif; ?>
        
        <div class="content-inner">

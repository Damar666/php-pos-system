<?php

// Fungsi untuk membuat token CSRF jika belum ada
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
}

// Fungsi untuk memvalidasi token CSRF
function validate_csrf_token() {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        // Jika token tidak valid, hentikan proses
        die('Error: Aksi tidak diizinkan (Invalid CSRF Token).');
    }
}

// Fungsi untuk mencetak input field tersembunyi yang berisi token
function csrf_input_field() {
    echo '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($_SESSION['csrf_token']) . '">';
}

?>
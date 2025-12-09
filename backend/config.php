<?php
// backend/config.php
// Konfigurasi Database

// Database credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'petcare_health');

// Site configuration
define('SITE_URL', 'http://localhost/petcare');
define('SITE_NAME', 'Pet Care Health');

// Admin notification
define('ADMIN_PHONE', '6281234567890');
define('ADMIN_EMAIL', 'admin@petcare.com');

// Fungsi koneksi database
function getDBConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        die("Koneksi database gagal: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8");
    return $conn;
}

// Fungsi untuk mengirim notifikasi WhatsApp
function sendWhatsAppNotification($phone, $message) {
    $whatsapp_url = "https://wa.me/" . $phone . "?text=" . urlencode($message);
    return $whatsapp_url;
}

// Fungsi untuk mengirim email
function sendEmailNotification($to, $subject, $message) {
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: ' . SITE_NAME . ' <' . ADMIN_EMAIL . '>' . "\r\n";
    
    return mail($to, $subject, $message, $headers);
}

// Fungsi sanitasi input
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Fungsi format rupiah
function formatRupiah($angka) {
    return "Rp " . number_format($angka, 0, ',', '.');
}

// Fungsi format tanggal Indonesia
function formatTanggalIndo($tanggal) {
    $bulan = array(
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    );
    
    $pecahkan = explode('-', $tanggal);
    return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
}

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
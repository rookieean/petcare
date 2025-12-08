<?php
// backend/contact_process.php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = getDBConnection();
    
    // Sanitasi input
    $name = sanitizeInput($_POST['name']);
    $email = sanitizeInput($_POST['email']);
    $phone = isset($_POST['phone']) ? sanitizeInput($_POST['phone']) : '';
    $subject = sanitizeInput($_POST['subject']);
    $message = sanitizeInput($_POST['message']);
    
    // Validasi
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        header('Location: ../kontak.php?error=' . urlencode('Semua field wajib harus diisi!'));
        exit();
    }
    
    // Validasi email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: ../kontak.php?error=' . urlencode('Format email tidak valid!'));
        exit();
    }
    
    // Prepared statement
    $stmt = $conn->prepare("INSERT INTO contacts (name, email, phone, subject, message, is_read) VALUES (?, ?, ?, ?, ?, FALSE)");
    $stmt->bind_param("sssss", $name, $email, $phone, $subject, $message);
    
    if ($stmt->execute()) {
        $contact_id = $conn->insert_id;
        
        // Send email notification to admin
        $admin_subject = "Pesan Kontak Baru #" . $contact_id . " - " . $subject;
        $admin_message = "<h2>Pesan Kontak Baru</h2>";
        $admin_message .= "<p><strong>ID:</strong> #" . $contact_id . "</p>";
        $admin_message .= "<p><strong>Nama:</strong> " . $name . "</p>";
        $admin_message .= "<p><strong>Email:</strong> " . $email . "</p>";
        if (!empty($phone)) {
            $admin_message .= "<p><strong>Telepon:</strong> " . $phone . "</p>";
        }
        $admin_message .= "<p><strong>Subject:</strong> " . $subject . "</p>";
        $admin_message .= "<p><strong>Pesan:</strong><br>" . nl2br($message) . "</p>";
        
        sendEmailNotification(ADMIN_EMAIL, $admin_subject, $admin_message);
        
        // Send confirmation email to customer
        $customer_subject = "Terima Kasih - Pet Care Health";
        $customer_message = "<h2>Terima kasih atas pesan Anda!</h2>";
        $customer_message .= "<p>Dear " . $name . ",</p>";
        $customer_message .= "<p>Kami telah menerima pesan Anda dengan subject: <strong>" . $subject . "</strong></p>";
        $customer_message .= "<p>Tim kami akan segera merespons pesan Anda dalam waktu 1x24 jam.</p>";
        $customer_message .= "<p>Terima kasih,<br>Pet Care Health Team</p>";
        
        sendEmailNotification($email, $customer_subject, $customer_message);
        
        header('Location: ../kontak.php?success=' . urlencode('Pesan berhasil dikirim! Kami akan segera menghubungi Anda.'));
        exit();
    } else {
        header('Location: ../kontak.php?error=' . urlencode('Terjadi kesalahan. Silakan coba lagi.'));
        exit();
    }
    
    $stmt->close();
    $conn->close();
} else {
    header('Location: ../kontak.php');
    exit();
}
?>
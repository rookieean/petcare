<?php
// backend/booking_process.php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = getDBConnection();
    
    // Sanitasi input
    $owner_name = sanitizeInput($_POST['owner_name']);
    $phone = sanitizeInput($_POST['phone']);
    $email = sanitizeInput($_POST['email']);
    $pet_name = sanitizeInput($_POST['pet_name']);
    $pet_type = sanitizeInput($_POST['pet_type']);
    $service_type = sanitizeInput($_POST['service_type']);
    $booking_date = sanitizeInput($_POST['booking_date']);
    $booking_time = sanitizeInput($_POST['booking_time']);
    $notes = sanitizeInput($_POST['notes']);
    
    // Validasi
    if (empty($owner_name) || empty($phone) || empty($email) || empty($pet_name) || 
        empty($pet_type) || empty($service_type) || empty($booking_date) || empty($booking_time)) {
        $_SESSION['error'] = 'Semua field wajib diisi!';
        header('Location: ../booking.php');
        exit();
    }
    
    // Validasi email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'Format email tidak valid!';
        header('Location: ../booking.php');
        exit();
    }
    
    // Validasi tanggal (tidak boleh di masa lalu)
    if (strtotime($booking_date) < strtotime(date('Y-m-d'))) {
        $_SESSION['error'] = 'Tanggal booking tidak boleh di masa lalu!';
        header('Location: ../booking.php');
        exit();
    }
    
    // Prepared statement untuk keamanan
    $stmt = $conn->prepare("INSERT INTO bookings (owner_name, phone, email, pet_name, pet_type, service_type, booking_date, booking_time, notes, status, is_read) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', FALSE)");
    $stmt->bind_param("sssssssss", $owner_name, $phone, $email, $pet_name, $pet_type, $service_type, $booking_date, $booking_time, $notes);
    
    if ($stmt->execute()) {
        $booking_id = $conn->insert_id;
        
        // Mark as unread so it appears in admin notifications
        $conn->query("UPDATE bookings SET is_read = FALSE WHERE id = $booking_id");
        
        // Format pesan untuk admin
        $tanggal_indo = formatTanggalIndo($booking_date);
        $message = "🐾 *BOOKING BARU - Pet Care Health* 🐾\n\n";
        $message .= "📋 *ID Booking:* #" . $booking_id . "\n";
        $message .= "👤 *Pemilik:* " . $owner_name . "\n";
        $message .= "📱 *Telepon:* " . $phone . "\n";
        $message .= "✉️ *Email:* " . $email . "\n\n";
        $message .= "🐕 *Hewan:* " . $pet_name . " (" . $pet_type . ")\n";
        $message .= "💊 *Layanan:* " . $service_type . "\n";
        $message .= "📅 *Tanggal:* " . $tanggal_indo . "\n";
        $message .= "⏰ *Waktu:* " . $booking_time . "\n\n";
        if (!empty($notes)) {
            $message .= "📝 *Catatan:* " . $notes . "\n\n";
        }
        $message .= "Status: ⏳ Pending\n\n";
        $message .= "Segera konfirmasi booking ini!";
        
        // Simpan URL WhatsApp untuk redirect
        $whatsapp_url = sendWhatsAppNotification(ADMIN_PHONE, $message);
        
        // Kirim email ke admin
        $email_subject = "Booking Baru #" . $booking_id . " - " . $owner_name;
        $email_message = "<h2>Booking Baru Pet Care Health</h2>";
        $email_message .= "<p><strong>ID Booking:</strong> #" . $booking_id . "</p>";
        $email_message .= "<p><strong>Pemilik:</strong> " . $owner_name . "</p>";
        $email_message .= "<p><strong>Telepon:</strong> " . $phone . "</p>";
        $email_message .= "<p><strong>Email:</strong> " . $email . "</p>";
        $email_message .= "<p><strong>Hewan:</strong> " . $pet_name . " (" . $pet_type . ")</p>";
        $email_message .= "<p><strong>Layanan:</strong> " . $service_type . "</p>";
        $email_message .= "<p><strong>Tanggal:</strong> " . $tanggal_indo . "</p>";
        $email_message .= "<p><strong>Waktu:</strong> " . $booking_time . "</p>";
        if (!empty($notes)) {
            $email_message .= "<p><strong>Catatan:</strong> " . $notes . "</p>";
        }
        
        sendEmailNotification(ADMIN_EMAIL, $email_subject, $email_message);
        
        // Kirim email konfirmasi ke customer
        $customer_subject = "Konfirmasi Booking - Pet Care Health";
        $customer_message = "<h2>Terima kasih atas booking Anda!</h2>";
        $customer_message .= "<p>Dear " . $owner_name . ",</p>";
        $customer_message .= "<p>Booking Anda telah kami terima dengan detail sebagai berikut:</p>";
        $customer_message .= "<p><strong>ID Booking:</strong> #" . $booking_id . "</p>";
        $customer_message .= "<p><strong>Hewan:</strong> " . $pet_name . " (" . $pet_type . ")</p>";
        $customer_message .= "<p><strong>Layanan:</strong> " . $service_type . "</p>";
        $customer_message .= "<p><strong>Tanggal:</strong> " . $tanggal_indo . "</p>";
        $customer_message .= "<p><strong>Waktu:</strong> " . $booking_time . "</p>";
        $customer_message .= "<p>Tim kami akan segera menghubungi Anda untuk konfirmasi.</p>";
        $customer_message .= "<p>Terima kasih,<br>Pet Care Health Team</p>";
        
        sendEmailNotification($email, $customer_subject, $customer_message);
        
        $_SESSION['success'] = 'Booking berhasil! Tim kami akan segera menghubungi Anda.';
        $_SESSION['whatsapp_url'] = $whatsapp_url;
        $_SESSION['show_whatsapp'] = true;
        
        header('Location: ../booking.php');
        exit();
    } else {
        $_SESSION['error'] = 'Terjadi kesalahan. Silakan coba lagi.';
        header('Location: ../booking.php');
        exit();
    }
    
    $stmt->close();
    $conn->close();
} else {
    header('Location: ../booking.php');
    exit();
}
?>
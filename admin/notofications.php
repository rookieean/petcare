<?php
// admin/notifications.php - Notification Management
session_start();
require_once '../backend/config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

$conn = getDBConnection();

// Handle mark as read
if (isset($_GET['mark_read']) && isset($_GET['type'])) {
    $id = intval($_GET['mark_read']);
    $type = $_GET['type'];
    
    if ($type === 'booking') {
        $conn->query("UPDATE bookings SET is_read = TRUE WHERE id = $id");
    } elseif ($type === 'contact') {
        $conn->query("UPDATE contacts SET is_read = TRUE WHERE id = $id");
    }
    
    header('Location: notifications.php?success=Notifikasi ditandai sebagai dibaca');
    exit();
}

// Handle status update for booking
if (isset($_POST['update_status'])) {
    $booking_id = intval($_POST['booking_id']);
    $new_status = $_POST['status'];
    
    $stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $booking_id);
    $stmt->execute();
    $stmt->close();
    
    header('Location: notifications.php?success=Status booking berhasil diupdate');
    exit();
}

// Get all unread notifications
$unread_bookings = $conn->query("SELECT * FROM bookings WHERE is_read = FALSE ORDER BY created_at DESC");
$unread_contacts = $conn->query("SELECT * FROM contacts WHERE is_read = FALSE ORDER BY created_at DESC");

// Get all notifications (read and unread)
$all_bookings = $conn->query("SELECT * FROM bookings ORDER BY created_at DESC LIMIT 20");
$all_contacts = $conn->query("SELECT * FROM contacts ORDER BY created_at DESC LIMIT 20");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi - Admin Pet Care Health</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .admin-container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 20px;
        }
        .admin-header {
            background: var(--primary-color);
            color: white;
            padding: 1rem 0;
            margin-bottom: 2rem;
        }
        .admin-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .tabs {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            border-bottom: 2px solid var(--gray-light);
        }
        .tab {
            padding: 1rem 2rem;
            background: transparent;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            color: var(--gray);
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
        }
        .tab.active {
            color: var(--primary-color);
            border-bottom-color: var(--primary-color);
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        .notification-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border-left: 4px solid var(--secondary-color);
        }
        .notification-card.unread {
            border-left-color: var(--accent-color);
            background: #FFF8F0;
        }
        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 1rem;
        }
        .notification-title {
            color: var(--primary-color);
            font-size: 1.2rem;
            font-weight: 600;
        }
        .notification-badge {
            background: var(--accent-color);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
        }
        .notification-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin: 1rem 0;
        }
        .detail-item {
            display: flex;
            flex-direction: column;
        }
        .detail-label {
            color: var(--gray);
            font-size: 0.85rem;
            margin-bottom: 0.25rem;
        }
        .detail-value {
            color: var(--dark);
            font-weight: 600;
        }
        .notification-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
            flex-wrap: wrap;
        }
        .btn-small {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
        .status-form {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }
        .status-form select {
            padding: 0.5rem;
            border: 2px solid var(--gray-light);
            border-radius: 8px;
        }
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: var(--gray);
        }
        .empty-state-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="admin-header">
        <div class="container">
            <div class="admin-nav">
                <h1>🔔 Notifikasi & Manajemen</h1>
                <div>
                    <a href="index.php" class="btn btn-secondary">← Dashboard</a>
                    <a href="logout.php" class="btn btn-primary" style="margin-left: 1rem;">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <div class="admin-container">
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
        <?php endif; ?>

        <!-- Tabs -->
        <div class="tabs">
            <button class="tab active" onclick="switchTab('unread-bookings')">
                Booking Belum Dibaca (<?php echo $unread_bookings->num_rows; ?>)
            </button>
            <button class="tab" onclick="switchTab('all-bookings')">
                Semua Booking
            </button>
            <button class="tab" onclick="switchTab('unread-contacts')">
                Pesan Belum Dibaca (<?php echo $unread_contacts->num_rows; ?>)
            </button>
            <button class="tab" onclick="switchTab('all-contacts')">
                Semua Pesan
            </button>
        </div>

        <!-- Unread Bookings -->
        <div id="unread-bookings" class="tab-content active">
            <h2 style="color: var(--primary-color); margin-bottom: 1.5rem;">Booking Belum Dibaca</h2>
            <?php if ($unread_bookings->num_rows > 0): ?>
                <?php while($booking = $unread_bookings->fetch_assoc()): ?>
                    <div class="notification-card unread">
                        <div class="notification-header">
                            <div>
                                <div class="notification-title">Booking #<?php echo $booking['id']; ?> - <?php echo htmlspecialchars($booking['owner_name']); ?></div>
                                <small style="color: var(--gray);">
                                    <?php echo date('d/m/Y H:i', strtotime($booking['created_at'])); ?>
                                </small>
                            </div>
                            <span class="notification-badge">BARU</span>
                        </div>
                        
                        <div class="notification-details">
                            <div class="detail-item">
                                <span class="detail-label">Hewan Peliharaan</span>
                                <span class="detail-value"><?php echo htmlspecialchars($booking['pet_name']); ?> (<?php echo htmlspecialchars($booking['pet_type']); ?>)</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Layanan</span>
                                <span class="detail-value"><?php echo htmlspecialchars($booking['service_type']); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Tanggal Booking</span>
                                <span class="detail-value"><?php echo formatTanggalIndo($booking['booking_date']); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Waktu</span>
                                <span class="detail-value"><?php echo $booking['booking_time']; ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Telepon</span>
                                <span class="detail-value"><?php echo htmlspecialchars($booking['phone']); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Email</span>
                                <span class="detail-value"><?php echo htmlspecialchars($booking['email']); ?></span>
                            </div>
                        </div>
                        
                        <?php if (!empty($booking['notes'])): ?>
                            <div style="margin: 1rem 0; padding: 1rem; background: var(--light-bg); border-radius: 8px;">
                                <span class="detail-label">Catatan:</span>
                                <p style="margin-top: 0.5rem;"><?php echo nl2br(htmlspecialchars($booking['notes'])); ?></p>
                            </div>
                        <?php endif; ?>
                        
                        <div class="notification-actions">
                            <form method="POST" class="status-form">
                                <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                <select name="status" required>
                                    <option value="pending" <?php echo $booking['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="confirmed" <?php echo $booking['status'] == 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                    <option value="completed" <?php echo $booking['status'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
                                    <option value="cancelled" <?php echo $booking['status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                                <button type="submit" name="update_status" class="btn btn-primary btn-small">Update Status</button>
                            </form>
                            
                            <a href="tel:<?php echo $booking['phone']; ?>" class="btn btn-secondary btn-small">📞 Telepon</a>
                            <a href="https://wa.me/62<?php echo ltrim($booking['phone'], '0'); ?>" target="_blank" class="btn btn-secondary btn-small">💬 WhatsApp</a>
                            <a href="mailto:<?php echo $booking['email']; ?>" class="btn btn-outline btn-small">✉️ Email</a>
                            <a href="?mark_read=<?php echo $booking['id']; ?>&type=booking" class="btn btn-outline btn-small">✓ Tandai Dibaca</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-state-icon">✓</div>
                    <h3>Tidak ada booking baru</h3>
                    <p>Semua booking sudah dibaca</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- All Bookings -->
        <div id="all-bookings" class="tab-content">
            <h2 style="color: var(--primary-color); margin-bottom: 1.5rem;">Semua Booking</h2>
            <?php if ($all_bookings->num_rows > 0): ?>
                <?php 
                $all_bookings->data_seek(0); // Reset pointer
                while($booking = $all_bookings->fetch_assoc()): 
                ?>
                    <div class="notification-card <?php echo !$booking['is_read'] ? 'unread' : ''; ?>">
                        <div class="notification-header">
                            <div>
                                <div class="notification-title">
                                    Booking #<?php echo $booking['id']; ?> - <?php echo htmlspecialchars($booking['owner_name']); ?>
                                    <?php if (!$booking['is_read']): ?>
                                        <span style="color: var(--accent-color); margin-left: 0.5rem;">●</span>
                                    <?php endif; ?>
                                </div>
                                <small style="color: var(--gray);">
                                    <?php echo date('d/m/Y H:i', strtotime($booking['created_at'])); ?>
                                </small>
                            </div>
                            <span class="status-badge status-<?php echo $booking['status']; ?>">
                                <?php echo ucfirst($booking['status']); ?>
                            </span>
                        </div>
                        
                        <div class="notification-details">
                            <div class="detail-item">
                                <span class="detail-label">Hewan</span>
                                <span class="detail-value"><?php echo htmlspecialchars($booking['pet_name']); ?> (<?php echo htmlspecialchars($booking['pet_type']); ?>)</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Layanan</span>
                                <span class="detail-value"><?php echo htmlspecialchars($booking['service_type']); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Jadwal</span>
                                <span class="detail-value"><?php echo formatTanggalIndo($booking['booking_date']); ?> - <?php echo $booking['booking_time']; ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Kontak</span>
                                <span class="detail-value"><?php echo htmlspecialchars($booking['phone']); ?></span>
                            </div>
                        </div>
                        
                        <div class="notification-actions">
                            <a href="tel:<?php echo $booking['phone']; ?>" class="btn btn-secondary btn-small">📞</a>
                            <a href="https://wa.me/62<?php echo ltrim($booking['phone'], '0'); ?>" target="_blank" class="btn btn-secondary btn-small">💬</a>
                            <a href="mailto:<?php echo $booking['email']; ?>" class="btn btn-outline btn-small">✉️</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>

        <!-- Unread Contacts -->
        <div id="unread-contacts" class="tab-content">
            <h2 style="color: var(--primary-color); margin-bottom: 1.5rem;">Pesan Kontak Belum Dibaca</h2>
            <?php if ($unread_contacts->num_rows > 0): ?>
                <?php while($contact = $unread_contacts->fetch_assoc()): ?>
                    <div class="notification-card unread">
                        <div class="notification-header">
                            <div>
                                <div class="notification-title">Pesan #<?php echo $contact['id']; ?> - <?php echo htmlspecialchars($contact['name']); ?></div>
                                <small style="color: var(--gray);">
                                    <?php echo date('d/m/Y H:i', strtotime($contact['created_at'])); ?>
                                </small>
                            </div>
                            <span class="notification-badge">BARU</span>
                        </div>
                        
                        <div class="notification-details">
                            <div class="detail-item">
                                <span class="detail-label">Subject</span>
                                <span class="detail-value"><?php echo htmlspecialchars($contact['subject']); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Email</span>
                                <span class="detail-value"><?php echo htmlspecialchars($contact['email']); ?></span>
                            </div>
                            <?php if (!empty($contact['phone'])): ?>
                            <div class="detail-item">
                                <span class="detail-label">Telepon</span>
                                <span class="detail-value"><?php echo htmlspecialchars($contact['phone']); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div style="margin: 1rem 0; padding: 1rem; background: var(--light-bg); border-radius: 8px;">
                            <span class="detail-label">Pesan:</span>
                            <p style="margin-top: 0.5rem;"><?php echo nl2br(htmlspecialchars($contact['message'])); ?></p>
                        </div>
                        
                        <div class="notification-actions">
                            <a href="mailto:<?php echo $contact['email']; ?>" class="btn btn-primary btn-small">✉️ Balas Email</a>
                            <?php if (!empty($contact['phone'])): ?>
                            <a href="tel:<?php echo $contact['phone']; ?>" class="btn btn-secondary btn-small">📞 Telepon</a>
                            <a href="https://wa.me/62<?php echo ltrim($contact['phone'], '0'); ?>" target="_blank" class="btn btn-secondary btn-small">💬 WhatsApp</a>
                            <?php endif; ?>
                            <a href="?mark_read=<?php echo $contact['id']; ?>&type=contact" class="btn btn-outline btn-small">✓ Tandai Dibaca</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-state-icon">✓</div>
                    <h3>Tidak ada pesan baru</h3>
                    <p>Semua pesan sudah dibaca</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- All Contacts -->
        <div id="all-contacts" class="tab-content">
            <h2 style="color: var(--primary-color); margin-bottom: 1.5rem;">Semua Pesan Kontak</h2>
            <?php if ($all_contacts->num_rows > 0): ?>
                <?php 
                $all_contacts->data_seek(0);
                while($contact = $all_contacts->fetch_assoc()): 
                ?>
                    <div class="notification-card <?php echo !$contact['is_read'] ? 'unread' : ''; ?>">
                        <div class="notification-header">
                            <div>
                                <div class="notification-title">
                                    Pesan #<?php echo $contact['id']; ?> - <?php echo htmlspecialchars($contact['name']); ?>
                                    <?php if (!$contact['is_read']): ?>
                                        <span style="color: var(--accent-color); margin-left: 0.5rem;">●</span>
                                    <?php endif; ?>
                                </div>
                                <small style="color: var(--gray);">
                                    <?php echo date('d/m/Y H:i', strtotime($contact['created_at'])); ?>
                                </small>
                            </div>
                        </div>
                        
                        <div class="notification-details">
                            <div class="detail-item">
                                <span class="detail-label">Subject</span>
                                <span class="detail-value"><?php echo htmlspecialchars($contact['subject']); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Kontak</span>
                                <span class="detail-value"><?php echo htmlspecialchars($contact['email']); ?></span>
                            </div>
                        </div>
                        
                        <div style="margin: 1rem 0; padding: 1rem; background: var(--light-bg); border-radius: 8px;">
                            <p><?php echo nl2br(htmlspecialchars(substr($contact['message'], 0, 200))); ?>
                            <?php echo strlen($contact['message']) > 200 ? '...' : ''; ?></p>
                        </div>
                        
                        <div class="notification-actions">
                            <a href="mailto:<?php echo $contact['email']; ?>" class="btn btn-primary btn-small">✉️ Balas</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>

    <?php $conn->close(); ?>

    <script>
        function switchTab(tabId) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            
            // Remove active class from all tabs
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Show selected tab content
            document.getElementById(tabId).classList.add('active');
            
            // Add active class to clicked tab
            event.target.classList.add('active');
        }
    </script>
</body>
</html>
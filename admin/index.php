<?php
// admin/index.php - Admin Dashboard
session_start();
require_once '../backend/config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

$conn = getDBConnection();

// Get statistics
$stats_bookings = $conn->query("SELECT COUNT(*) as total FROM bookings")->fetch_assoc()['total'];
$stats_pending = $conn->query("SELECT COUNT(*) as total FROM bookings WHERE status='pending'")->fetch_assoc()['total'];
$stats_today = $conn->query("SELECT COUNT(*) as total FROM bookings WHERE booking_date = CURDATE()")->fetch_assoc()['total'];
$stats_unread = $conn->query("SELECT COUNT(*) as total FROM bookings WHERE is_read=FALSE")->fetch_assoc()['total'];

// Get recent bookings
$bookings_sql = "SELECT * FROM bookings ORDER BY created_at DESC LIMIT 10";
$bookings_result = $conn->query($bookings_sql);

// Get recent contacts
$contacts_sql = "SELECT * FROM contacts ORDER BY created_at DESC LIMIT 5";
$contacts_result = $conn->query($contacts_sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Pet Care Health</title>
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
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .stat-card h3 {
            color: var(--gray);
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        .stat-card .number {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--primary-color);
        }
        .data-table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .table-header {
            background: var(--primary-color);
            color: white;
            padding: 1rem 1.5rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--gray-light);
        }
        th {
            background: var(--light-bg);
            font-weight: 600;
            color: var(--primary-color);
        }
        tr:hover {
            background: var(--light-bg);
        }
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .status-pending {
            background: #FED7D7;
            color: #742A2A;
        }
        .status-confirmed {
            background: #C6F6D5;
            color: #22543D;
        }
        .status-completed {
            background: #BEE3F8;
            color: #2C5282;
        }
        .status-cancelled {
            background: #E2E8F0;
            color: #4A5568;
        }
        .action-btns {
            display: flex;
            gap: 0.5rem;
        }
        .btn-small {
            padding: 0.4rem 0.8rem;
            font-size: 0.85rem;
        }
        .unread {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="admin-header">
        <div class="container">
            <div class="admin-nav">
                <h1>🐾 Pet Care Health - Admin Dashboard</h1>
                <div>
                    <span>Halo, Admin</span>
                    <a href="logout.php" class="btn btn-primary" style="margin-left: 1rem;">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <div class="admin-container">
        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Booking</h3>
                <div class="number"><?php echo $stats_bookings; ?></div>
            </div>
            <div class="stat-card">
                <h3>Booking Pending</h3>
                <div class="number" style="color: var(--warning);"><?php echo $stats_pending; ?></div>
            </div>
            <div class="stat-card">
                <h3>Booking Hari Ini</h3>
                <div class="number" style="color: var(--success);"><?php echo $stats_today; ?></div>
            </div>
            <div class="stat-card">
                <h3>Belum Dibaca</h3>
                <div class="number" style="color: var(--danger);"><?php echo $stats_unread; ?></div>
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="data-table">
            <div class="table-header">
                <h2>Booking Terbaru</h2>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pemilik</th>
                        <th>Hewan</th>
                        <th>Layanan</th>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($bookings_result->num_rows > 0): ?>
                        <?php while($booking = $bookings_result->fetch_assoc()): ?>
                            <tr class="<?php echo !$booking['is_read'] ? 'unread' : ''; ?>">
                                <td>#<?php echo $booking['id']; ?></td>
                                <td>
                                    <?php echo htmlspecialchars($booking['owner_name']); ?>
                                    <?php if (!$booking['is_read']): ?>
                                        <span style="color: var(--danger);">●</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($booking['pet_name']) . ' (' . htmlspecialchars($booking['pet_type']) . ')'; ?></td>
                                <td><?php echo htmlspecialchars($booking['service_type']); ?></td>
                                <td><?php echo formatTanggalIndo($booking['booking_date']); ?></td>
                                <td><?php echo $booking['booking_time']; ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo $booking['status']; ?>">
                                        <?php echo ucfirst($booking['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-btns">
                                        <a href="tel:<?php echo $booking['phone']; ?>" class="btn btn-primary btn-small">📞</a>
                                        <a href="https://wa.me/62<?php echo ltrim($booking['phone'], '0'); ?>" target="_blank" class="btn btn-secondary btn-small">💬</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" style="text-align: center;">Belum ada booking</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Recent Contacts -->
        <div class="data-table">
            <div class="table-header">
                <h2>Pesan Kontak Terbaru</h2>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($contacts_result->num_rows > 0): ?>
                        <?php while($contact = $contacts_result->fetch_assoc()): ?>
                            <tr class="<?php echo !$contact['is_read'] ? 'unread' : ''; ?>">
                                <td>#<?php echo $contact['id']; ?></td>
                                <td>
                                    <?php echo htmlspecialchars($contact['name']); ?>
                                    <?php if (!$contact['is_read']): ?>
                                        <span style="color: var(--danger);">●</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($contact['email']); ?></td>
                                <td><?php echo htmlspecialchars($contact['subject']); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($contact['created_at'])); ?></td>
                                <td>
                                    <a href="mailto:<?php echo $contact['email']; ?>" class="btn btn-primary btn-small">✉️ Reply</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center;">Belum ada pesan</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php $conn->close(); ?>
</body>
</html>
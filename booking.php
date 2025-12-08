<?php
// booking.php - Halaman Booking
require_once 'backend/config.php';
$current_page = 'booking';

// Get services for dropdown
$conn = getDBConnection();
$services_sql = "SELECT * FROM products ORDER BY category, name";
$services_result = $conn->query($services_sql);
$conn->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking - Pet Care Health</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Header & Navigation -->
    <header>
        <nav class="container">
            <a href="index.php" class="logo">
                <div class="logo-icon">🐾</div>
                <span>Pet Care Health</span>
            </a>
            <ul class="nav-links">
                <li><a href="index.php">Beranda</a></li>
                <li><a href="produk.php">Produk</a></li>
                <li><a href="blog.php">Blog</a></li>
                <li><a href="kontak.php">Kontak</a></li>
                <li><a href="booking.php" class="btn btn-primary">Booking</a></li>
            </ul>
        </nav>
    </header>

    <!-- Booking Section -->
    <section class="booking-section">
        <div class="container">
            <h1 class="section-title">Booking Appointment</h1>
            
            <div id="alert-container">
                <?php 
                if (isset($_SESSION['success'])) {
                    $success_msg = $_SESSION['success'];
                    $booking_id = isset($_SESSION['booking_id']) ? $_SESSION['booking_id'] : '';
                    unset($_SESSION['success']);
                    unset($_SESSION['booking_id']);
                ?>
                    <div class="alert alert-success">
                        <p><strong>✓ <?php echo htmlspecialchars($success_msg); ?></strong></p>
                        <p style="margin-top: 10px;">
                            <a href="https://wa.me/<?php echo ADMIN_PHONE; ?>?text=<?php echo urlencode('Halo Admin Pet Care Health, saya baru saja melakukan booking dengan ID #' . $booking_id . '. Mohon konfirmasinya. Terima kasih!'); ?>" 
                               target="_blank" 
                               class="btn btn-primary" 
                               style="display: inline-block; padding: 0.75rem 1.5rem; text-decoration: none;">
                                📱 Hubungi Admin via WhatsApp
                            </a>
                        </p>
                    </div>
                <?php } ?>
                
                <?php 
                if (isset($_SESSION['error'])) {
                    echo '<div class="alert alert-error">' . htmlspecialchars($_SESSION['error']) . '</div>';
                    unset($_SESSION['error']);
                }
                ?>
            </div>
            
            <form class="booking-form" id="bookingForm" method="POST" action="backend/booking_process.php">
                <div class="form-group">
                    <label for="owner_name">Nama Pemilik *</label>
                    <input type="text" id="owner_name" name="owner_name" required>
                </div>

                <div class="form-group">
                    <label for="phone">Nomor Telepon *</label>
                    <input type="tel" id="phone" name="phone" placeholder="08123456789" required>
                </div>

                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="pet_name">Nama Hewan Peliharaan *</label>
                    <input type="text" id="pet_name" name="pet_name" required>
                </div>

                <div class="form-group">
                    <label for="pet_type">Jenis Hewan *</label>
                    <select id="pet_type" name="pet_type" required>
                        <option value="">Pilih Jenis Hewan</option>
                        <option value="Anjing">Anjing</option>
                        <option value="Kucing">Kucing</option>
                        <option value="Kelinci">Kelinci</option>
                        <option value="Hamster">Hamster</option>
                        <option value="Burung">Burung</option>
                        <option value="Reptil">Reptil</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="service_type">Jenis Layanan *</label>
                    <select id="service_type" name="service_type" required>
                        <option value="">Pilih Layanan</option>
                        <?php if ($services_result->num_rows > 0): ?>
                            <?php 
                            $current_category = '';
                            while($row = $services_result->fetch_assoc()): 
                                if ($current_category != $row['category']) {
                                    if ($current_category != '') echo '</optgroup>';
                                    echo '<optgroup label="' . htmlspecialchars($row['category']) . '">';
                                    $current_category = $row['category'];
                                }
                            ?>
                                <option value="<?php echo htmlspecialchars($row['name']); ?>">
                                    <?php echo htmlspecialchars($row['name']) . ' - ' . formatRupiah($row['price']); ?>
                                </option>
                            <?php endwhile; ?>
                            </optgroup>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="booking_date">Tanggal Kunjungan *</label>
                    <input type="date" id="booking_date" name="booking_date" min="<?php echo date('Y-m-d'); ?>" required>
                </div>

                <div class="form-group">
                    <label for="booking_time">Waktu Kunjungan *</label>
                    <select id="booking_time" name="booking_time" required>
                        <option value="">Pilih Waktu</option>
                        <option value="09:00">09:00 - 10:00</option>
                        <option value="10:00">10:00 - 11:00</option>
                        <option value="11:00">11:00 - 12:00</option>
                        <option value="13:00">13:00 - 14:00</option>
                        <option value="14:00">14:00 - 15:00</option>
                        <option value="15:00">15:00 - 16:00</option>
                        <option value="16:00">16:00 - 17:00</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="notes">Catatan Tambahan</label>
                    <textarea id="notes" name="notes" rows="4" placeholder="Ceritakan kondisi atau keluhan hewan peliharaan Anda..."></textarea>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">Kirim Booking</button>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Pet Care Health</h3>
                    <p>Klinik hewan profesional yang berkomitmen memberikan layanan kesehatan terbaik untuk hewan peliharaan Anda.</p>
                </div>
                <div class="footer-section">
                    <h3>Layanan</h3>
                    <a href="produk.php">Medical Check-Up</a>
                    <a href="produk.php">Vaksinasi</a>
                    <a href="produk.php">Grooming</a>
                    <a href="produk.php">Operasi</a>
                </div>
                <div class="footer-section">
                    <h3>Informasi</h3>
                    <a href="blog.php">Blog</a>
                    <a href="kontak.php">Kontak</a>
                    <a href="booking.php">Booking</a>
                </div>
                <div class="footer-section">
                    <h3>Kontak</h3>
                    <p>📍 Jl. Kesehatan No. 123, Jakarta</p>
                    <p>📞 (021) 1234-5678</p>
                    <p>✉️ info@petcarehealth.com</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 Pet Care Health. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>
<?php
// kontak.php - Halaman Kontak
require_once 'backend/config.php';
$current_page = 'kontak';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontak - Pet Care Health</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
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
                <li><a href="kontak.php" class="active">Kontak</a></li>
                <li><a href="booking.php" class="btn btn-primary">Booking</a></li>
            </ul>
        </nav>
    </header>

    <section class="hero" style="padding: 3rem 0;">
        <div class="container">
            <h1>Hubungi Kami</h1>
            <p>Kami siap membantu Anda dan hewan peliharaan kesayangan</p>
        </div>
    </section>

    <section class="contact-section">
        <div class="container">
            <div id="alert-container"></div>
            
            <div class="contact-grid">
                <div class="contact-info">
                    <h2 style="color: var(--primary-color); margin-bottom: 1.5rem;">Informasi Kontak</h2>
                    
                    <div class="contact-item">
                        <div class="contact-icon">📍</div>
                        <div>
                            <h3>Alamat</h3>
                            <p>Jl. Kesehatan No. 123<br>Jakarta Selatan, DKI Jakarta 12345</p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">📞</div>
                        <div>
                            <h3>Telepon</h3>
                            <p>(021) 1234-5678<br>0812-3456-7890</p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">✉️</div>
                        <div>
                            <h3>Email</h3>
                            <p>info@petcarehealth.com<br>support@petcarehealth.com</p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">⏰</div>
                        <div>
                            <h3>Jam Operasional</h3>
                            <p><strong>Senin - Jumat:</strong> 08:00 - 20:00<br>
                            <strong>Sabtu:</strong> 08:00 - 17:00<br>
                            <strong>Minggu:</strong> 09:00 - 15:00<br>
                            <strong>Darurat 24/7:</strong> Tersedia</p>
                        </div>
                    </div>
                </div>

                <div class="booking-form">
                    <h2 style="color: var(--primary-color); margin-bottom: 1.5rem;">Kirim Pesan</h2>
                    
                    <form id="contactForm" method="POST" action="backend/contact_process.php">
                        <div class="form-group">
                            <label for="name">Nama Lengkap *</label>
                            <input type="text" id="name" name="name" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" required>
                        </div>

                        <div class="form-group">
                            <label for="phone">Nomor Telepon</label>
                            <input type="tel" id="phone" name="phone" placeholder="08123456789">
                        </div>

                        <div class="form-group">
                            <label for="subject">Subject *</label>
                            <input type="text" id="subject" name="subject" required>
                        </div>

                        <div class="form-group">
                            <label for="message">Pesan *</label>
                            <textarea id="message" name="message" rows="5" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%;">Kirim Pesan</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

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
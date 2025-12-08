<?php
// index.php - Halaman Beranda
require_once 'backend/config.php';
$current_page = 'beranda';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Care Health - Klinik Hewan Professional</title>
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
                <li><a href="index.php" class="active">Beranda</a></li>
                <li><a href="produk.php">Produk</a></li>
                <li><a href="blog.php">Blog</a></li>
                <li><a href="kontak.php">Kontak</a></li>
                <li><a href="booking.php" class="btn btn-primary">Booking</a></li>
            </ul>
        </nav>
    </header>

    <!-- Hero Section -->
        <section class="hero">
            <div class="container">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: center;">
                    <!-- Text Side -->
                    <div class="hero-content">
                        <h1>Kesehatan Hewan Peliharaan Anda, Prioritas Kami</h1>
                        <p>Layanan kesehatan profesional untuk hewan kesayangan Anda dengan dokter berpengalaman dan fasilitas modern</p>
                        <a href="booking.php" class="btn btn-primary">Booking Sekarang</a>
                    </div>
                    
                    <!-- Image Side -->
                    <div style="text-align: center;">
                        <img src="images/a-dog.jpg" alt="Pet Care" style="max-width: 100%; height: auto; filter: drop-shadow(0 10px 30px rgba(0,0,0,0.3));">
                    </div>
                </div>
            </div>
        </section>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <h2 class="section-title">Mengapa Memilih Pet Care Health?</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">👨‍⚕️</div>
                    <h3>Dokter Profesional</h3>
                    <p>Tim dokter hewan bersertifikat dengan pengalaman lebih dari 10 tahun dalam merawat berbagai jenis hewan peliharaan</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🏥</div>
                    <h3>Fasilitas Modern</h3>
                    <p>Dilengkapi dengan peralatan medis canggih dan ruang perawatan yang bersih serta nyaman untuk hewan Anda</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">⏰</div>
                    <h3>Layanan 24/7</h3>
                    <p>Siap melayani kebutuhan kesehatan hewan peliharaan Anda kapan saja, termasuk layanan darurat</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">💊</div>
                    <h3>Produk Berkualitas</h3>
                    <p>Menyediakan vitamin, obat-obatan, dan perlengkapan kesehatan hewan dengan kualitas terjamin</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">💰</div>
                    <h3>Harga Terjangkau</h3>
                    <p>Layanan kesehatan berkualitas dengan harga yang kompetitif dan berbagai paket hemat</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📱</div>
                    <h3>Booking Online</h3>
                    <p>Kemudahan booking konsultasi dan perawatan melalui sistem online yang praktis dan cepat</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Preview -->
    <section class="products">
        <div class="container">
            <h2 class="section-title">Layanan Kami</h2>
            <?php
            $conn = getDBConnection();
            $sql = "SELECT * FROM products ORDER BY id DESC LIMIT 3";
            $result = $conn->query($sql);
            ?>
            <div class="products-grid">
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <div class="product-card">
                            <div class="product-image">🐶</div>
                            <div class="product-info">
                                <div class="product-category"><?php echo htmlspecialchars($row['category']); ?></div>
                                <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                                <p><?php echo htmlspecialchars(substr($row['description'], 0, 100)) . '...'; ?></p>
                                <div class="product-price"><?php echo formatRupiah($row['price']); ?></div>
                                <a href="booking.php" class="btn btn-secondary">Book Now</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Belum ada layanan tersedia.</p>
                <?php endif; ?>
                <?php $conn->close(); ?>
            </div>
            <div style="text-align: center; margin-top: 2rem;">
                <a href="produk.php" class="btn btn-outline">Lihat Semua Layanan</a>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="hero" style="padding: 3rem 0;">
        <div class="container">
            <h2>Siap Merawat Hewan Kesayangan Anda?</h2>
            <p>Booking konsultasi sekarang dan dapatkan pemeriksaan kesehatan gratis!</p>
            <a href="booking.php" class="btn btn-primary">Booking Sekarang</a>
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
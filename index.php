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
                        <img src="images/medic.jpg" alt="Pet Care" style="max-width: 100%; height: auto; filter: drop-shadow(0 10px 30px rgba(0,0,0,0.3));">
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
                    <p>Tim dokter hewan bersertifikat dengan pengalaman lebih dari 10 tahun</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🏥</div>
                    <h3>Fasilitas Modern</h3>
                    <p>Dilengkapi dengan peralatan medis canggih dan ruang perawatan bersih</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">⏰</div>
                    <h3>Layanan 24/7</h3>
                    <p>Siap melayani kebutuhan kesehatan hewan peliharaan Anda kapan saja</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">💊</div>
                    <h3>Produk Berkualitas</h3>
                    <p>Menyediakan vitamin, obat-obatan, dan perlengkapan kesehatan hewan</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">💰</div>
                    <h3>Harga Terjangkau</h3>
                    <p>Layanan kesehatan berkualitas dengan harga kompetitif</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📱</div>
                    <h3>Booking Online</h3>
                    <p>Kemudahan booking konsultasi melalui sistem online yang praktis</p>
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
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php 
                    $service_images = ['service1.jpg', 'service2.jpg', 'service3.jpg'];
                    $index = 0;
                    while($row = $result->fetch_assoc()): 
                    ?>
                        <div class="product-card">
                            <div class="product-image">
                                <?php 
                                $image_path = 'images/services/' . $service_images[$index];
                                if (file_exists($image_path)): 
                                ?>
                                    <img src="<?php echo $image_path; ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                                <?php else: ?>
                                    <div class="product-image-placeholder">🐶</div>
                                <?php endif; ?>
                            </div>
                            <div class="product-info">
                                <div class="product-category"><?php echo htmlspecialchars($row['category']); ?></div>
                                <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                                <p><?php echo htmlspecialchars(substr($row['description'], 0, 100)) . '...'; ?></p>
                                <div class="product-price"><?php echo formatRupiah($row['price']); ?></div>
                                <a href="booking.php" class="btn btn-secondary">Book Now</a>
                            </div>
                        </div>
                    <?php 
                    $index++;
                    endwhile; 
                    ?>
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

    <!-- Testimonials Section -->
    <section class="testimonials">
        <div class="container">
            <h2 class="section-title">Apa Kata Pelanggan Kami</h2>
            <p style="text-align: center; color: var(--gray); margin-bottom: 2rem;">Kepercayaan dan kepuasan pelanggan adalah prioritas utama kami</p>
            
            <div class="testimonials-grid">
                <!-- Testimonial 1 -->
                <div class="testimonial-card">
                    <div class="testimonial-image">
                        <?php if (file_exists('images/testimonials/customer1.jpg')): ?>
                            <img src="images/testimonials/customer1.jpg" alt="Sarah Martinez">
                        <?php else: ?>
                            <div class="testimonial-image-placeholder">SM</div>
                        <?php endif; ?>
                    </div>
                    <div class="testimonial-stars">★★★★★</div>
                    <p class="testimonial-text">"Pelayanan yang sangat profesional! Dokter sangat sabar dan teliti dalam memeriksa kucing saya. Milo sekarang jauh lebih sehat dan aktif. Terima kasih Pet Care Health!"</p>
                    <h4 class="testimonial-name">Sarah Martinez</h4>
                    <p class="testimonial-pet">Pemilik Milo (Kucing Persia)</p>
                </div>

                <!-- Testimonial 2 -->
                <div class="testimonial-card">
                    <div class="testimonial-image">
                        <?php if (file_exists('images/testimonials/customer2.jpg')): ?>
                            <img src="images/testimonials/customer2.jpg" alt="Budi Santoso">
                        <?php else: ?>
                            <div class="testimonial-image-placeholder">BS</div>
                        <?php endif; ?>
                    </div>
                    <div class="testimonial-stars">★★★★★</div>
                    <p class="testimonial-text">"Fasilitas yang bersih dan modern. Grooming untuk anjing saya hasilnya sangat memuaskan. Harga juga terjangkau. Highly recommended!"</p>
                    <h4 class="testimonial-name">Budi Santoso</h4>
                    <p class="testimonial-pet">Pemilik Rocky (Golden Retriever)</p>
                </div>

                <!-- Testimonial 3 -->
                <div class="testimonial-card">
                    <div class="testimonial-image">
                        <?php if (file_exists('images/testimonials/customer3.jpg')): ?>
                            <img src="images/testimonials/customer3.jpg" alt="Diana Putri">
                        <?php else: ?>
                            <div class="testimonial-image-placeholder">DP</div>
                        <?php endif; ?>
                    </div>
                    <div class="testimonial-stars">★★★★★</div>
                    <p class="testimonial-text">"Tim dokter sangat care dan responsif. Vaksinasi berjalan lancar dan kelinci saya tidak stress sama sekali. Pet Care Health memang yang terbaik!"</p>
                    <h4 class="testimonial-name">Diana Putri</h4>
                    <p class="testimonial-pet">Pemilik Snowy (Kelinci Anggora)</p>
                </div>
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
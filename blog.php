<?php
// blog.php - Halaman Blog
require_once 'backend/config.php';
$current_page = 'blog';

$conn = getDBConnection();
$sql = "SELECT * FROM blog_posts ORDER BY published_date DESC";
$result = $conn->query($sql);
$conn->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - Pet Care Health</title>
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
                <li><a href="blog.php" class="active">Blog</a></li>
                <li><a href="kontak.php">Kontak</a></li>
                <li><a href="booking.php" class="btn btn-primary">Booking</a></li>
            </ul>
        </nav>
    </header>

    <section class="hero" style="padding: 3rem 0;">
        <div class="container">
            <h1>Blog & Artikel</h1>
            <p>Tips dan informasi seputar kesehatan hewan peliharaan</p>
        </div>
    </section>

    <section class="products" style="padding: 4rem 0; background: var(--light-bg);">
        <div class="container">
            <div class="blog-grid">
                <?php if ($result->num_rows > 0): ?>
                    <?php while($post = $result->fetch_assoc()): ?>
                        <div class="blog-card">
                            <div class="blog-image">📝</div>
                            <div class="blog-content">
                                <div class="blog-meta">
                                    <span><?php echo $post['category']; ?></span> • 
                                    <span><?php echo formatTanggalIndo($post['published_date']); ?></span>
                                </div>
                                <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                                <p><?php echo htmlspecialchars(substr($post['content'], 0, 150)) . '...'; ?></p>
                                <a href="#" class="btn btn-outline">Baca Selengkapnya</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Belum ada artikel.</p>
                <?php endif; ?>
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
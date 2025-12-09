<?php
// produk.php - Halaman Produk/Layanan
require_once 'backend/config.php';
$current_page = 'produk';

$conn = getDBConnection();
$sql = "SELECT * FROM products ORDER BY category, name";
$result = $conn->query($sql);

// Group products by category
$products_by_category = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $category = $row['category'];
        if (!isset($products_by_category[$category])) {
            $products_by_category[$category] = [];
        }
        $products_by_category[$category][] = $row;
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk & Layanan - Pet Care Health</title>
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
                <li><a href="produk.php" class="active">Produk</a></li>
                <li><a href="blog.php">Blog</a></li>
                <li><a href="kontak.php">Kontak</a></li>
                <li><a href="booking.php" class="btn btn-primary">Booking</a></li>
            </ul>
        </nav>
    </header>

    <section class="hero" style="padding: 3rem 0;">
        <div class="container">
            <h1>Produk & Layanan Kami</h1>
            <p>Berbagai layanan kesehatan profesional untuk hewan peliharaan Anda</p>
        </div>
    </section>

    <section class="products">
        <div class="container">
            <?php foreach($products_by_category as $category => $products): ?>
                <h2 class="section-title" style="margin-top: 3rem;"><?php echo htmlspecialchars($category); ?></h2>
                <div class="products-grid">
                    <?php foreach($products as $product): ?>
                        <div class="product-card">
                            <div class="product-image">
                                <?php 
                                // Generate image filename based on product name
                                $image_filename = strtolower(str_replace(' ', '-', $product['name'])) . '.jpg';
                                $image_path = 'images/products/' . $image_filename;
                                
                                if (file_exists($image_path)): 
                                ?>
                                    <img src="<?php echo $image_path; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                <?php else: ?>
                                    <?php 
                                    // Icon placeholder based on category
                                    $icons = [
                                        'Vaksinasi' => '💉',
                                        'Pemeriksaan' => '🩺',
                                        'Perawatan' => '✂️',
                                        'Operasi' => '🏥'
                                    ];
                                    $icon = isset($icons[$product['category']]) ? $icons[$product['category']] : '🐾';
                                    ?>
                                    <div class="product-image-placeholder"><?php echo $icon; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="product-info">
                                <div class="product-category"><?php echo htmlspecialchars($product['category']); ?></div>
                                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                                <p><?php echo htmlspecialchars($product['description']); ?></p>
                                <div class="product-price"><?php echo formatRupiah($product['price']); ?></div>
                                <?php if ($product['stock'] > 0): ?>
                                    <a href="booking.php" class="btn btn-secondary">Book Sekarang</a>
                                <?php else: ?>
                                    <button class="btn btn-outline" disabled>Stok Habis</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="hero" style="padding: 3rem 0;">
        <div class="container">
            <h2>Tertarik dengan Layanan Kami?</h2>
            <p>Booking sekarang dan dapatkan konsultasi gratis!</p>
            <a href="booking.php" class="btn btn-primary">Booking Sekarang</a>
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
-- Database Pet Care Health
-- Jalankan script ini di phpMyAdmin

CREATE DATABASE IF NOT EXISTS petcare_health;
USE petcare_health;

-- Tabel untuk admin
CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel untuk produk
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    stock INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel untuk booking/pemesanan
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    owner_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    pet_name VARCHAR(100) NOT NULL,
    pet_type VARCHAR(50) NOT NULL,
    service_type VARCHAR(100) NOT NULL,
    booking_date DATE NOT NULL,
    booking_time TIME NOT NULL,
    notes TEXT,
    status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel untuk kontak/pesan
CREATE TABLE IF NOT EXISTS contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel untuk artikel blog
CREATE TABLE IF NOT EXISTS blog_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    content TEXT NOT NULL,
    image VARCHAR(255),
    category VARCHAR(50),
    author VARCHAR(100),
    published_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin
-- Password: admin123 (sudah di-hash)
INSERT INTO admin (username, password, email, phone) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@petcare.com', '081234567890');

-- Insert sample products
INSERT INTO products (name, category, description, price, stock) VALUES 
('Vaksin Rabies', 'Vaksinasi', 'Vaksinasi rabies untuk anjing dan kucing, melindungi dari virus rabies', 250000, 50),
('Medical Check-Up Basic', 'Pemeriksaan', 'Pemeriksaan kesehatan dasar meliputi suhu, detak jantung, dan kondisi umum', 150000, 100),
('Grooming Lengkap', 'Perawatan', 'Paket grooming lengkap: mandi, potong kuku, sikat gigi, dan styling', 200000, 30),
('Sterilisasi', 'Operasi', 'Operasi sterilisasi untuk kucing dan anjing kecil', 500000, 20),
('Perawatan Gigi', 'Perawatan', 'Pembersihan karang gigi dan perawatan kesehatan gigi', 300000, 40);

-- Insert sample blog posts
INSERT INTO blog_posts (title, slug, content, category, author, published_date) VALUES 
('Tips Merawat Kesehatan Hewan Peliharaan', 'tips-merawat-kesehatan-hewan', 'Merawat hewan peliharaan membutuhkan perhatian khusus. Berikut beberapa tips penting...', 'Kesehatan', 'Dr. Hewan', '2024-01-15'),
('Pentingnya Vaksinasi Rutin', 'pentingnya-vaksinasi-rutin', 'Vaksinasi adalah cara terbaik melindungi hewan peliharaan dari berbagai penyakit...', 'Vaksinasi', 'Dr. Hewan', '2024-01-20'),
('Panduan Nutrisi Hewan Peliharaan', 'panduan-nutrisi-hewan', 'Nutrisi yang tepat sangat penting untuk kesehatan hewan peliharaan Anda...', 'Nutrisi', 'Dr. Hewan', '2024-01-25');
<?php
/**
 * Error Checker - Pet Care Health
 * Jalankan file ini untuk diagnosa masalah
 * Akses: http://localhost/petcare/check_errors.php
 */

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

$checks = [];
$errors = [];
$warnings = [];

// Check 1: PHP Version
$php_version = phpversion();
$checks[] = [
    'name' => 'PHP Version',
    'status' => version_compare($php_version, '7.0.0', '>=') ? 'success' : 'error',
    'message' => 'PHP ' . $php_version,
    'required' => 'PHP 7.0 or higher'
];

// Check 2: backend/config.php exists
$config_exists = file_exists('backend/config.php');
$checks[] = [
    'name' => 'Config File',
    'status' => $config_exists ? 'success' : 'error',
    'message' => $config_exists ? 'File ada' : 'File TIDAK ADA!',
    'path' => 'backend/config.php'
];

if (!$config_exists) {
    $errors[] = 'File backend/config.php tidak ditemukan. Copy dari artifact petcare_config';
}

// Check 3: Include config
if ($config_exists) {
    try {
        require_once 'backend/config.php';
        $checks[] = [
            'name' => 'Include Config',
            'status' => 'success',
            'message' => 'Config berhasil di-load'
        ];
        
        // Check 4: Function exists
        if (function_exists('getDBConnection')) {
            $checks[] = [
                'name' => 'Function getDBConnection',
                'status' => 'success',
                'message' => 'Fungsi tersedia'
            ];
            
            // Check 5: Database connection
            try {
                $conn = getDBConnection();
                $checks[] = [
                    'name' => 'Database Connection',
                    'status' => 'success',
                    'message' => 'Koneksi berhasil ke database: ' . DB_NAME
                ];
                
                // Check 6: Tables exist
                $tables = ['admin', 'products', 'bookings', 'contacts', 'blog_posts'];
                foreach ($tables as $table) {
                    $result = $conn->query("SHOW TABLES LIKE '$table'");
                    if ($result && $result->num_rows > 0) {
                        $checks[] = [
                            'name' => 'Tabel ' . $table,
                            'status' => 'success',
                            'message' => 'Tabel ada'
                        ];
                    } else {
                        $checks[] = [
                            'name' => 'Tabel ' . $table,
                            'status' => 'error',
                            'message' => 'Tabel TIDAK ADA!'
                        ];
                        $errors[] = "Tabel $table tidak ditemukan. Import database.sql";
                    }
                }
                
                $conn->close();
            } catch (Exception $e) {
                $checks[] = [
                    'name' => 'Database Connection',
                    'status' => 'error',
                    'message' => 'Error: ' . $e->getMessage()
                ];
                $errors[] = 'Koneksi database gagal: ' . $e->getMessage();
            }
        } else {
            $checks[] = [
                'name' => 'Function getDBConnection',
                'status' => 'error',
                'message' => 'Fungsi TIDAK DITEMUKAN!'
            ];
            $errors[] = 'Fungsi getDBConnection tidak ada di config.php';
        }
        
    } catch (Exception $e) {
        $checks[] = [
            'name' => 'Include Config',
            'status' => 'error',
            'message' => 'Error: ' . $e->getMessage()
        ];
        $errors[] = 'Error saat include config.php: ' . $e->getMessage();
    }
}

// Check 7: Folder structure
$folders = [
    'backend' => 'Backend folder',
    'css' => 'CSS folder',
    'js' => 'JavaScript folder',
    'images' => 'Images folder',
    'admin' => 'Admin folder'
];

foreach ($folders as $folder => $desc) {
    $exists = is_dir($folder);
    $checks[] = [
        'name' => $desc,
        'status' => $exists ? 'success' : 'warning',
        'message' => $exists ? 'Folder ada' : 'Folder tidak ada (buat jika perlu)',
        'path' => $folder . '/'
    ];
    
    if (!$exists) {
        $warnings[] = "Folder $folder tidak ada. Buat folder ini jika diperlukan.";
    }
}

// Check 8: Required files
$required_files = [
    'css/style.css' => 'CSS File',
    'js/script.js' => 'JavaScript File',
    'backend/config.php' => 'Config File',
    'backend/booking_process.php' => 'Booking Process',
    'backend/contact_process.php' => 'Contact Process'
];

foreach ($required_files as $file => $desc) {
    $exists = file_exists($file);
    $checks[] = [
        'name' => $desc,
        'status' => $exists ? 'success' : 'error',
        'message' => $exists ? 'File ada' : 'File TIDAK ADA!',
        'path' => $file
    ];
    
    if (!$exists) {
        $errors[] = "File $file tidak ditemukan.";
    }
}

// Check 9: Image folders
$image_folders = [
    'images/services',
    'images/products',
    'images/blog',
    'images/testimonials'
];

foreach ($image_folders as $folder) {
    $exists = is_dir($folder);
    $checks[] = [
        'name' => 'Folder ' . basename($folder),
        'status' => $exists ? 'success' : 'info',
        'message' => $exists ? 'Folder ada' : 'Folder belum dibuat (opsional)',
        'path' => $folder . '/'
    ];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error Checker - Pet Care Health</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #365194, #5ba4cf);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
            margin: 2rem auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            padding: 3rem;
        }
        h1 {
            color: #365194;
            margin-bottom: 0.5rem;
            text-align: center;
        }
        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 2rem;
        }
        .summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .summary-box {
            padding: 1.5rem;
            border-radius: 8px;
            text-align: center;
        }
        .summary-box.success {
            background: #C6F6D5;
            border: 2px solid #48BB78;
        }
        .summary-box.error {
            background: #FED7D7;
            border: 2px solid #F56565;
        }
        .summary-box.warning {
            background: #FEEBC8;
            border: 2px solid #ECC94B;
        }
        .summary-number {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        .summary-label {
            font-size: 0.9rem;
            font-weight: 600;
        }
        .check-item {
            padding: 1rem;
            margin-bottom: 0.5rem;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .check-item.success {
            background: #C6F6D5;
            border-left: 4px solid #48BB78;
        }
        .check-item.error {
            background: #FED7D7;
            border-left: 4px solid #F56565;
        }
        .check-item.warning {
            background: #FEEBC8;
            border-left: 4px solid #ECC94B;
        }
        .check-item.info {
            background: #BEE3F8;
            border-left: 4px solid #4299E1;
        }
        .check-name {
            font-weight: 600;
            color: #2D3748;
        }
        .check-message {
            color: #666;
            font-size: 0.9rem;
        }
        .check-path {
            font-family: 'Courier New', monospace;
            font-size: 0.85rem;
            color: #999;
            margin-top: 0.25rem;
        }
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }
        .status-success {
            background: #48BB78;
            color: white;
        }
        .status-error {
            background: #F56565;
            color: white;
        }
        .status-warning {
            background: #ECC94B;
            color: #7C2D12;
        }
        .status-info {
            background: #4299E1;
            color: white;
        }
        .error-list {
            background: #FED7D7;
            border: 2px solid #F56565;
            border-radius: 8px;
            padding: 1.5rem;
            margin: 2rem 0;
        }
        .error-list h3 {
            color: #742A2A;
            margin-bottom: 1rem;
        }
        .error-list ul {
            list-style: none;
            padding: 0;
        }
        .error-list li {
            padding: 0.5rem 0;
            color: #742A2A;
            border-bottom: 1px solid rgba(245, 101, 101, 0.2);
        }
        .error-list li:before {
            content: "⚠️ ";
            margin-right: 0.5rem;
        }
        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            justify-content: center;
        }
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-primary {
            background: #365194;
            color: white;
        }
        .btn-primary:hover {
            background: #2a3f72;
        }
        .btn-secondary {
            background: #f5c400;
            color: #12151f;
        }
        .section {
            margin-bottom: 2rem;
        }
        .section h2 {
            color: #365194;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #f3ead3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Error Checker</h1>
        <p class="subtitle">Diagnosis sistem Pet Care Health</p>

        <!-- Summary -->
        <div class="summary">
            <div class="summary-box success">
                <div class="summary-number" style="color: #48BB78;">
                    <?php echo count(array_filter($checks, function($c) { return $c['status'] === 'success'; })); ?>
                </div>
                <div class="summary-label">Passed</div>
            </div>
            <div class="summary-box error">
                <div class="summary-number" style="color: #F56565;">
                    <?php echo count(array_filter($checks, function($c) { return $c['status'] === 'error'; })); ?>
                </div>
                <div class="summary-label">Errors</div>
            </div>
            <div class="summary-box warning">
                <div class="summary-number" style="color: #ECC94B;">
                    <?php echo count(array_filter($checks, function($c) { return $c['status'] === 'warning'; })); ?>
                </div>
                <div class="summary-label">Warnings</div>
            </div>
        </div>

        <?php if (!empty($errors)): ?>
        <div class="error-list">
            <h3>❌ Critical Errors (Harus Diperbaiki)</h3>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <?php if (!empty($warnings)): ?>
        <div class="error-list" style="background: #FEEBC8; border-color: #ECC94B;">
            <h3 style="color: #7C2D12;">⚠️ Warnings (Perhatian)</h3>
            <ul>
                <?php foreach ($warnings as $warning): ?>
                    <li style="color: #7C2D12; border-color: rgba(236, 201, 75, 0.2);"><?php echo htmlspecialchars($warning); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <!-- Detailed Checks -->
        <div class="section">
            <h2>📋 Detailed Check Results</h2>
            <?php foreach ($checks as $check): ?>
                <div class="check-item <?php echo $check['status']; ?>">
                    <div>
                        <div class="check-name"><?php echo $check['name']; ?></div>
                        <div class="check-message"><?php echo $check['message']; ?></div>
                        <?php if (isset($check['path'])): ?>
                            <div class="check-path"><?php echo $check['path']; ?></div>
                        <?php endif; ?>
                    </div>
                    <span class="status-badge status-<?php echo $check['status']; ?>">
                        <?php 
                        $badges = [
                            'success' => '✓ OK',
                            'error' => '✗ Error',
                            'warning' => '⚠ Warning',
                            'info' => 'ℹ Info'
                        ];
                        echo $badges[$check['status']];
                        ?>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Actions -->
        <div class="action-buttons">
            <a href="index.php" class="btn btn-primary">🏠 Kembali ke Beranda</a>
            <a href="?refresh=1" class="btn btn-secondary">🔄 Refresh Check</a>
        </div>

        <!-- Info -->
        <div style="margin-top: 2rem; padding: 1.5rem; background: #f3ead3; border-radius: 8px;">
            <h3 style="color: #365194; margin-bottom: 0.5rem;">💡 Solusi Cepat:</h3>
            <ul style="margin-left: 1.5rem; color: #666;">
                <li>Jika ada error "config.php tidak ada" → Copy dari artifact <code>petcare_config</code></li>
                <li>Jika ada error "database" → Import <code>database.sql</code> di phpMyAdmin</li>
                <li>Jika ada error "tabel tidak ada" → Jalankan <code>database.sql</code></li>
                <li>Jika ada warning "folder tidak ada" → Buat folder manual atau abaikan jika tidak perlu</li>
            </ul>
        </div>
    </div>
</body>
</html>
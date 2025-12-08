<?php
// admin/login.php - Admin Login Page
session_start();
require_once '../backend/config.php';

// Redirect if already logged in
if (isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitizeInput($_POST['username']);
    $password = $_POST['password'];
    
    if (!empty($username) && !empty($password)) {
        $conn = getDBConnection();
        $stmt = $conn->prepare("SELECT id, username, password FROM admin WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $admin = $result->fetch_assoc();
            // Verify password
            if (password_verify($password, $admin['password'])) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                header('Location: index.php');
                exit();
            } else {
                $error = 'Username atau password salah!';
            }
        } else {
            $error = 'Username atau password salah!';
        }
        
        $stmt->close();
        $conn->close();
    } else {
        $error = 'Semua field harus diisi!';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Pet Care Health</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        }
        .login-box {
            background: white;
            padding: 3rem;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
        }
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-header .logo-icon {
            width: 60px;
            height: 60px;
            margin: 0 auto 1rem;
            font-size: 2rem;
        }
        .login-header h1 {
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        .error-message {
            background: #FED7D7;
            color: #742A2A;
            padding: 0.75rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            border: 1px solid #F56565;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <div class="logo-icon">🐾</div>
                <h1>Admin Login</h1>
                <p style="color: var(--gray);">Pet Care Health</p>
            </div>
            
            <?php if (!empty($error)): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required autofocus>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%;">Login</button>
            </form>
            
            <div style="text-align: center; margin-top: 1.5rem;">
                <a href="../index.php" style="color: var(--primary-color);">← Kembali ke Website</a>
            </div>
            
            <div style="margin-top: 2rem; padding-top: 1rem; border-top: 1px solid var(--gray-light); text-align: center; color: var(--gray); font-size: 0.9rem;">
                <p><strong>Demo Login:</strong></p>
                <p>Username: admin</p>
                <p>Password: admin123</p>
            </div>
        </div>
    </div>
</body>
</html>
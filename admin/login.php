<?php
require_once '../config/db.php';

session_start();

if (isAdmin()) {
    header('Location: dashboard.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND role = 'admin'");
    $stmt->execute([$email]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['name'];
        $_SESSION['admin_email'] = $admin['email'];
        $_SESSION['user_role'] = 'admin';
        
        header('Location: dashboard.php');
        exit();
    } else {
        $error = 'Invalid admin credentials!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - VMART</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f8f9fa; }
        .login-box { max-width: 400px; margin: 100px auto; background: white; padding: 40px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        .logo { font-size: 32px; font-weight: 700; text-align: center; margin-bottom: 30px; }
        .logo span { color: #2ecc71; }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-box">
            <div class="logo">V<span>MART</span> Admin</div>
            <p class="text-center text-muted mb-4">Login to admin panel</p>
            
            <?php if ($error) { ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php } ?>
            
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required value="admin@vmart.com">
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required value="password">
                </div>
                <button type="submit" class="btn btn-success w-100 py-2">Login</button>
            </form>
            
            <div class="text-center mt-3">
                <a href="../index.php" class="text-muted text-decoration-none">← Back to Website</a>
            </div>
        </div>
    </div>
</body>
</html>
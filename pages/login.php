<?php
require '../config/db.php';

session_start();

if (isLoggedIn()) {
    header('Location: ../index.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        
        header('Location: ../index.php');
        exit();
    } else {
        $error = 'Invalid email or password!';
    }
}

$page_title = "Login";
include '../includes/header.php';
?>

<section class="auth-section">
    <div class="container">
        <div class="auth-card">
            <h2 class="auth-title">Welcome Back</h2>
            <p class="auth-subtitle">Login to your VMART account</p>
            
            <?php if ($error) { ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php } ?>
            
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" required value="test@test.com">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required value="test123">
                </div>
                
                <button type="submit" class="btn btn-auth">Login</button>
            </form>
            
            <p class="auth-link">
                Don't have an account? <a href="register.php">Register</a>
            </p>
            
            <hr>
            <p class="text-muted small">Quick login: test@test.com / test123</p>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
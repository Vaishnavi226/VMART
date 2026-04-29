<?php
$page_title = "Register";
include '../includes/header.php';

if (isLoggedIn()) {
    header('Location: ../index.php');
    exit();
}


$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if ($password !== $confirm_password) {
        $error = 'Passwords do not match!';
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->fetch()) {
            $error = 'Email already registered!';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            try {
                $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')");
                $stmt->execute([$name, $email, $hashed_password]);
                
                $success = 'Registration successful! Please login.';
            } catch (Exception $e) {
                $error = 'Registration failed. Please try again.';
            }
        }
    }
}
?>

<section class="auth-section">
    <div class="container">
        <div class="auth-card">
            <h2 class="auth-title">Create Account</h2>
            <p class="auth-subtitle">Join VMART and start shopping</p>
            
            <?php if ($error) { ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php } ?>
            
            <?php if ($success) { ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
            <p class="auth-link">
                Already have an account? <a href="login.php">Login</a>
            </p>
            <?php } else { ?>
            
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required minlength="6">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control" required>
                </div>
                
                <button type="submit" class="btn btn-auth">Register</button>
            </form>
            
            <p class="auth-link">
                Already have an account? <a href="login.php">Login</a>
            </p>
            <?php } ?>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
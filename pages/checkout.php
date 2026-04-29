<?php
// Start session and load dependencies first
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/db.php';
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    header('Location: login.php?redirect=checkout');
    exit();
}

$cart_items = getCartItems($conn, $_SESSION['user_id']);
$cart_total = getCartTotal($conn, $_SESSION['user_id']);

if (empty($cart_items)) {
    header('Location: cart.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $address = sanitize($_POST['address']);
    $city = sanitize($_POST['city']);
    $payment_method = $_POST['payment_method'];
    
    if (empty($name) || empty($email) || empty($phone) || empty($address) || empty($city)) {
        $error = "All fields are required";
    } else {
        try {
            $conn->beginTransaction();
            
            $order_number = 'ORD' . time() . rand(100, 999);
            $stmt = $conn->prepare("INSERT INTO orders (user_id, order_number, total_amount, name, email, phone, address, city, payment_method, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
            $stmt->execute([$_SESSION['user_id'], $order_number, $cart_total, $name, $email, $phone, $address, $city, $payment_method]);
            $order_id = $conn->lastInsertId();
            
            foreach ($cart_items as $item) {
                $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, product_name, price, quantity, subtotal) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$order_id, $item['id'], $item['name'], $item['price'], $item['quantity'], $item['price'] * $item['quantity']]);
            }
            
            $conn->commit();
            
            $conn->prepare("DELETE FROM cart WHERE user_id = ?")->execute([$_SESSION['user_id']]);
            
            $_SESSION['order_success'] = "Order placed successfully!";
            header('Location: order_confirmation.php?id=' . $order_id);
            exit();
        } catch (Exception $e) {
            $conn->rollBack();
            $error = "Error placing order: " . $e->getMessage();
        }
    }
}

$page_title = "Checkout";
include '../includes/header.php';
?>

<section class="checkout-section">
    <div class="container">
        <h2 class="section-title mb-4">Checkout</h2>
        
        <div class="row">
            <div class="col-lg-7">
                <form method="POST" class="checkout-form">
                    <h4 class="mb-4">Shipping Information</h4>
                    
                    <?php if (isset($error)) { ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php } ?>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="name" class="form-control" required value="<?php echo $user['name'] ?? ''; ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control" required value="<?php echo $user['email'] ?? ''; ?>">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone *</label>
                            <input type="tel" name="phone" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">City *</label>
                            <input type="text" name="city" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Full Address *</label>
                        <textarea name="address" class="form-control" rows="3" required></textarea>
                    </div>
                    
                    <h4 class="mt-4 mb-4">Payment Method</h4>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="payment_method" value="cod" id="cod" checked>
                        <label class="form-check-label" for="cod">
                            <i class="fas fa-money-bill-wave me-2"></i> Cash on Delivery
                        </label>
                    </div>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="payment_method" value="card" id="card">
                        <label class="form-check-label" for="card">
                            <i class="fas fa-credit-card me-2"></i> Credit/Debit Card
                        </label>
                    </div>
                    
                    <button type="submit" class="btn btn-hero w-100 mt-4">Place Order</button>
                </form>
            </div>
            
            <div class="col-lg-5">
                <div class="cart-summary">
                    <h4 class="summary-title">Order Summary</h4>
                    
                    <?php foreach ($cart_items as $item) { ?>
                    <div class="summary-row">
                        <span><?php echo htmlspecialchars($item['name']); ?> x <?php echo $item['quantity']; ?></span>
                        <span><?php echo formatPrice($item['price'] * $item['quantity']); ?></span>
                    </div>
                    <?php } ?>
                    
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span><?php echo formatPrice($cart_total); ?></span>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Coupon Code</label>
                        <input type="text" name="coupon_code" class="form-control" placeholder="Enter coupon code">
                    </div>
                    
                    <div class="summary-row total">
                        <span>Total</span>
                        <span><?php echo formatPrice($cart_total); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
<?php
$page_title = "Order Detail";
include '../includes/header.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$order_number = $_GET['order'] ?? '';


$stmt = $conn->prepare("SELECT * FROM orders WHERE order_number = ? AND user_id = ?");
$stmt->execute([$order_number, $_SESSION['user_id']]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo '<div class="container py-5"><div class="alert alert-danger">Order not found!</div></div>';
    exit();
}

$order_items = getOrderItems($conn, $order['id']);
$page_title = "Order Details";
include '../includes/header.php';
?>

<section class="orders-section">
    <div class="container">
        <h2 class="section-title mb-4">Order #<?php echo htmlspecialchars($order['order_number']); ?></h2>
        
        <div class="row">
            <div class="col-lg-8">
                <div class="order-card">
                    <div class="order-header">
                        <span class="order-status bg-<?php echo getStatusClass($order['status']); ?>">
                            <?php echo ucfirst($order['status']); ?>
                        </span>
                        <small class="text-muted"><?php echo formatDate($order['created_at']); ?></small>
                    </div>
                    
                    <h5 class="mb-3">Order Items</h5>
                    <div class="order-items">
                        <?php foreach ($order_items as $item) { ?>
                        <div class="order-item">
                            <div>
                                <strong><?php echo htmlspecialchars($item['product_name']); ?></strong>
                                <br><small class="text-muted">₹<?php echo number_format($item['price'], 2); ?> x <?php echo $item['quantity']; ?></small>
                            </div>
                            <span><?php echo formatPrice($item['subtotal']); ?></span>
                        </div>
                        <?php } ?>
                    </div>
                    
                    <div class="order-total mt-3">
                        Total: <?php echo formatPrice($order['total_amount']); ?>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="order-card">
                    <h5 class="mb-3">Shipping Details</h5>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($order['name']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['phone']); ?></p>
                    <p><strong>Address:</strong> <?php echo nl2br(htmlspecialchars($order['address'])); ?></p>
                    <p><strong>City:</strong> <?php echo htmlspecialchars($order['city']); ?></p>
                </div>
                
                <div class="order-card mt-3">
                    <h5 class="mb-3">Payment</h5>
                    <p><strong>Method:</strong> <?php echo strtoupper($order['payment_method']); ?></p>
                </div>
            </div>
        </div>
        
        <a href="orders.php" class="btn btn-outline-primary mt-3">← Back to Orders</a>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
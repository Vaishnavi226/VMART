<?php
$page_title = "My Orders";
include '../includes/header.php';

if (!isLoggedIn()) {
    header('Location: login.php?redirect=orders');
    exit();
}

$orders = getUserOrders($conn, $_SESSION['user_id']);
?>


<section class="orders-section">
    <div class="container">
        <h2 class="section-title mb-4">My Orders</h2>
        
        <?php if (empty($orders)) { ?>
        <div class="alert alert-info text-center py-5">
            <i class="fas fa-box-open fa-3x mb-3"></i>
            <h4>No orders yet</h4>
            <p>Start shopping to see your orders here!</p>
            <a href="shop.php" class="btn btn-hero">Shop Now</a>
        </div>
        <?php } else { ?>
        <?php foreach ($orders as $order) { 
            $order_items = getOrderItems($conn, $order['id']);
        ?>
        <div class="order-card">
            <div class="order-header">
                <div>
                    <span class="order-number"><?php echo htmlspecialchars($order['order_number']); ?></span>
                    <small class="text-muted ms-2"><?php echo formatDate($order['created_at']); ?></small>
                </div>
                <span class="order-status bg-<?php echo getStatusClass($order['status']); ?>">
                    <?php echo ucfirst($order['status']); ?>
                </span>
            </div>
            
            <div class="order-items">
                <?php foreach ($order_items as $item) { ?>
                <div class="order-item">
                    <span><?php echo htmlspecialchars($item['product_name']); ?> x <?php echo $item['quantity']; ?></span>
                    <span><?php echo formatPrice($item['subtotal']); ?></span>
                </div>
                <?php } ?>
            </div>
            
            <div class="order-total mt-3">
                Total: <?php echo formatPrice($order['total_amount']); ?>
            </div>
            
            <div class="mt-3">
                <a href="order-detail.php?order=<?php echo $order['order_number']; ?>" class="btn btn-outline-primary btn-sm">View Details</a>
            </div>
        </div>
        <?php } ?>
        <?php } ?>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
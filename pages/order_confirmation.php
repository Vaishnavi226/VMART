<?php
$page_title = "Order Confirmation";
include '../includes/header.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$order_id = $_GET['id'] ?? 0;
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->execute([$order_id, $_SESSION['user_id']]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo '<div class="container py-5"><div class="alert alert-danger">Order not found!</div></div>';
    include '../includes/footer.php';
    exit();
}

$stmt = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
$stmt->execute([$order_id]);
$order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="alert alert-success text-center mb-4">
                    <i class="fas fa-check-circle" style="font-size: 50px;"></i>
                    <h3 class="mt-3">Order Placed Successfully!</h3>
                    <p class="mb-0">Your order #<?php echo $order['id']; ?> has been confirmed.</p>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3">Order Items</h5>
                        <?php foreach ($order_items as $item) { ?>
                        <div class="d-flex justify-content-between mb-2">
                            <span><?php echo htmlspecialchars($item['product_name']); ?> x <?php echo $item['quantity']; ?></span>
                            <span>₹<?php echo number_format($item['subtotal'], 2); ?></span>
                        </div>
                        <?php } ?>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold">
                            <span>Total</span>
                            <span>₹<?php echo number_format($order['total_amount'], 2); ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <a href="shop.php" class="btn btn-hero">Continue Shopping</a>
                    <a href="orders.php" class="btn btn-outline-primary ms-2">View Orders</a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>

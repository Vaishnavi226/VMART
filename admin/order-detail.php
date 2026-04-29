<?php
require_once '../config/db.php';
session_start();

if (!isAdmin()) {
    header('Location: login.php');
    exit();
}

$id = (int)$_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['status'])) {
    $status = sanitize($_POST['status']);
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$status, $id]);
    header("Location: order-detail.php?id=$id");
    exit();
}

$stmt = $conn->prepare("SELECT o.*, u.name as user_name, u.email as user_email FROM orders o LEFT JOIN users u ON o.user_id = u.id WHERE o.id = ?");
$stmt->execute([$id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo '<div class="container py-5"><div class="alert alert-danger">Order not found!</div></div>';
    exit();
}

$stmt = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
$stmt->execute([$id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Detail - VMART Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f8f9fa; }
        .sidebar { position: fixed; left: 0; top: 0; bottom: 0; width: 250px; background: #2c3e50; padding: 20px; color: white; }
        .sidebar-brand { font-size: 24px; font-weight: 700; margin-bottom: 40px; display: block; color: white; text-decoration: none; }
        .sidebar-menu a { color: rgba(255,255,255,0.8); text-decoration: none; padding: 12px 15px; display: block; border-radius: 8px; transition: 0.3s; margin-bottom: 5px; }
        .sidebar-menu a:hover, .sidebar-menu a.active { background: #2ecc71; color: white; }
        .main-content { margin-left: 250px; padding: 30px; }
        .card { background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); padding: 25px; }
    </style>
</head>
<body>
    <div class="sidebar">
        <a href="dashboard.php" class="sidebar-brand">VMART</a>
        <ul class="sidebar-menu">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="products.php">Products</a></li>
            <li><a href="categories.php">Categories</a></li>
            <li><a href="orders.php" class="active">Orders</a></li>
            <li><a href="users.php">Users</a></li>
            <li><a href="coupons.php">Coupons</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    
    <div class="main-content">
        <div class="d-flex justify-content-between mb-4">
            <h3>Order #<?php echo $order['order_number']; ?></h3>
            <a href="orders.php" class="btn btn-outline-primary">← Back</a>
        </div>
        
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <h5>Order Items</h5>
                    <table class="table">
                        <thead><tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr></thead>
                        <tbody>
                            <?php foreach ($items as $item) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                                <td>₹<?php echo number_format($item['price'], 2); ?></td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td>₹<?php echo number_format($item['subtotal'], 2); ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <h5 class="text-end">Total: ₹<?php echo number_format($order['total_amount'], 2); ?></h5>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card mb-4">
                    <h5>Customer</h5>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($order['user_name'] ?? 'Guest'); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['phone']); ?></p>
                    <p><strong>Address:</strong> <?php echo nl2br(htmlspecialchars($order['address'])); ?></p>
                    <p><strong>City:</strong> <?php echo htmlspecialchars($order['city']); ?></p>
                </div>
                
                <div class="card">
                    <h5>Update Status</h5>
                    <form method="POST">
                        <select name="status" class="form-select mb-3">
                            <option value="pending" <?php echo $order['status']=='pending'?'selected':''; ?>>Pending</option>
                            <option value="processing" <?php echo $order['status']=='processing'?'selected':''; ?>>Processing</option>
                            <option value="shipped" <?php echo $order['status']=='shipped'?'selected':''; ?>>Shipped</option>
                            <option value="delivered" <?php echo $order['status']=='delivered'?'selected':''; ?>>Delivered</option>
                            <option value="cancelled" <?php echo $order['status']=='cancelled'?'selected':''; ?>>Cancelled</option>
                        </select>
                        <button type="submit" class="btn btn-success w-100">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
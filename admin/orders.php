<?php
require_once '../config/db.php';
session_start();

if (!isAdmin()) {
    header('Location: login.php');
    exit();
}

// Update order status
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $id = (int)$_POST['order_id'];
    $status = sanitize($_POST['status']);
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$status, $id]);
}

$orders = $conn->query("SELECT o.*, u.name as user_name FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Orders - VMART Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f8f9fa; }
        .sidebar { position: fixed; left: 0; top: 0; bottom: 0; width: 250px; background: #2c3e50; padding: 20px; color: white; }
        .sidebar-brand { font-size: 24px; font-weight: 700; margin-bottom: 40px; display: block; color: white; text-decoration: none; }
        .sidebar-menu a { color: rgba(255,255,255,0.8); text-decoration: none; padding: 12px 15px; display: block; border-radius: 8px; transition: 0.3s; margin-bottom: 5px; }
        .sidebar-menu a:hover, .sidebar-menu a.active { background: #2ecc71; color: white; }
        .main-content { margin-left: 250px; padding: 30px; }
        .table-card { background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); overflow: hidden; }
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
        <h3 class="mb-4">Orders</h3>
        
        <div class="table-card">
            <table class="table mb-0">
                <thead class="table-light">
                    <tr><th>Order #</th><th>Customer</th><th>Amount</th><th>Payment</th><th>Status</th><th>Date</th><th>Action</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $o) { ?>
                    <tr>
                        <td><?php echo $o['order_number']; ?></td>
                        <td><?php echo htmlspecialchars($o['user_name'] ?? 'Guest'); ?></td>
                        <td>₹<?php echo number_format($o['total_amount'], 2); ?></td>
                        <td><?php echo strtoupper($o['payment_method']); ?></td>
                        <td>
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="order_id" value="<?php echo $o['id']; ?>">
                                <select name="status" class="form-select form-select-sm d-inline-block w-auto" onchange="this.form.submit()" name="update_status">
                                    <option value="pending" <?php echo $o['status']=='pending'?'selected':''; ?>>Pending</option>
                                    <option value="processing" <?php echo $o['status']=='processing'?'selected':''; ?>>Processing</option>
                                    <option value="shipped" <?php echo $o['status']=='shipped'?'selected':''; ?>>Shipped</option>
                                    <option value="delivered" <?php echo $o['status']=='delivered'?'selected':''; ?>>Delivered</option>
                                    <option value="cancelled" <?php echo $o['status']=='cancelled'?'selected':''; ?>>Cancelled</option>
                                </select>
                                <input type="hidden" name="update_status" value="1">
                            </form>
                        </td>
                        <td><?php echo date('d M Y', strtotime($o['created_at'])); ?></td>
                        <td><a href="order-detail.php?id=<?php echo $o['id']; ?>" class="btn btn-sm btn-outline-primary">View</a></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
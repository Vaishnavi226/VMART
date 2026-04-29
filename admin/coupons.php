<?php
require_once '../config/db.php';
session_start();

if (!isAdmin()) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_coupon'])) {
    $code = sanitize($_POST['code']);
    $discount_type = sanitize($_POST['discount_type']);
    $discount_value = (float)$_POST['discount_value'];
    $min_order_amount = (float)$_POST['min_order_amount'];
    $max_uses = !empty($_POST['max_uses']) ? (int)$_POST['max_uses'] : null;
    $valid_from = !empty($_POST['valid_from']) ? $_POST['valid_from'] : null;
    $valid_until = !empty($_POST['valid_until']) ? $_POST['valid_until'] : null;
    
    $stmt = $conn->prepare("INSERT INTO coupons (code, discount_type, discount_value, min_order_amount, max_uses, valid_from, valid_until, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'active')");
    $stmt->execute([$code, $discount_type, $discount_value, $min_order_amount, $max_uses, $valid_from, $valid_until]);
    $success = "Coupon created!";
}

$coupons = $conn->query("SELECT * FROM coupons ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Coupons - VMART Admin</title>
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
            <li><a href="orders.php">Orders</a></li>
            <li><a href="users.php">Users</a></li>
            <li><a href="coupons.php" class="active">Coupons</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    
    <div class="main-content">
        <div class="d-flex justify-content-between mb-4">
            <h3>Coupons</h3>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">Add Coupon</button>
        </div>
        
        <?php if (isset($success)) { ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
        <?php } ?>
        
        <div class="table-card">
            <table class="table mb-0">
                <thead class="table-light">
                    <tr><th>Code</th><th>Discount</th><th>Min Order</th><th>Uses</th><th>Valid Until</th><th>Status</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($coupons as $c) { ?>
                    <tr>
                        <td><code><?php echo htmlspecialchars($c['code']); ?></code></td>
                        <td><?php echo $c['discount_type'] == 'percentage' ? $c['discount_value'] . '%' : '₹' . $c['discount_value']; ?></td>
                        <td>₹<?php echo $c['min_order_amount']; ?></td>
                        <td><?php echo $c['used_count'] . '/' . ($c['max_uses'] ?? '∞'); ?></td>
                        <td><?php echo $c['valid_until'] ? date('d M Y', strtotime($c['valid_until'])) : 'Never'; ?></td>
                        <td><span class="badge bg-<?php echo $c['status']=='active'?'success':'secondary'; ?>"><?php echo $c['status']; ?></span></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="modal fade" id="addModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Add Coupon</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="mb-3"><label class="form-label">Code</label><input type="text" name="code" class="form-control" required></div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Discount Type</label>
                                <select name="discount_type" class="form-select">
                                    <option value="percentage">Percentage</option>
                                    <option value="fixed">Fixed Amount</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Discount Value</label>
                                <input type="number" name="discount_value" class="form-control" step="0.01" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Min Order Amount</label>
                                <input type="number" name="min_order_amount" class="form-control" step="0.01" value="0">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Max Uses</label>
                                <input type="number" name="max_uses" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Valid From</label>
                                <input type="date" name="valid_from" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Valid Until</label>
                                <input type="date" name="valid_until" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer"><button type="submit" name="add_coupon" class="btn btn-success">Create</button></div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
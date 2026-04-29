<?php
require_once '../config/db.php';
session_start();

if (!isAdmin()) {
    header('Location: login.php');
    exit();
}

$page_title = "Dashboard";
$stats = [];

// Total Users
$stmt = $conn->query("SELECT COUNT(*) as total FROM users WHERE role = 'user'");
$stats['users'] = $stmt->fetch()['total'];

// Total Orders
$stmt = $conn->query("SELECT COUNT(*) as total FROM orders");
$stats['orders'] = $stmt->fetch()['total'];

// Total Revenue
$stmt = $conn->query("SELECT SUM(total_amount) as total FROM orders WHERE status != 'cancelled'");
$stats['revenue'] = $stmt->fetch()['total'] ?? 0;

// Total Products
$stmt = $conn->query("SELECT COUNT(*) as total FROM products");
$stats['products'] = $stmt->fetch()['total'];

// Recent Orders
$stmt = $conn->query("SELECT o.*, u.name as user_name FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC LIMIT 5");
$recent_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Recent Products
$stmt = $conn->query("SELECT * FROM products WHERE status = 'active' ORDER BY id DESC LIMIT 8");
$recent_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - VMART Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f8f9fa; }
        .sidebar { position: fixed; left: 0; top: 0; bottom: 0; width: 250px; background: #2c3e50; padding: 20px; color: white; }
        .sidebar-brand { font-size: 24px; font-weight: 700; margin-bottom: 40px; display: block; color: white; text-decoration: none; }
        .sidebar-brand span { color: #2ecc71; }
        .sidebar-menu { list-style: none; padding: 0; }
        .sidebar-menu li { margin-bottom: 10px; }
        .sidebar-menu a { color: rgba(255,255,255,0.8); text-decoration: none; padding: 12px 15px; display: block; border-radius: 8px; transition: 0.3s; }
        .sidebar-menu a:hover, .sidebar-menu a.active { background: #2ecc71; color: white; }
        .sidebar-menu i { width: 25px; }
        .main-content { margin-left: 250px; padding: 30px; }
        .stat-card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .stat-icon { width: 60px; height: 60px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px; }
        .stat-value { font-size: 28px; font-weight: 700; }
        .table-card { background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); overflow: hidden; }
    </style>
</head>
<body>
    <div class="sidebar">
        <a href="dashboard.php" class="sidebar-brand">V<span>MART</span></a>
        <ul class="sidebar-menu">
            <li><a href="dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="products.php"><i class="fas fa-box"></i> Products</a></li>
            <li><a href="categories.php"><i class="fas fa-tags"></i> Categories</a></li>
            <li><a href="orders.php"><i class="fas fa-shopping-cart"></i> Orders</a></li>
            <li><a href="users.php"><i class="fas fa-users"></i> Users</a></li>
            <li><a href="coupons.php"><i class="fas fa-percent"></i> Coupons</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>
    
    <div class="main-content">
        <h3 class="mb-4">Dashboard</h3>
        
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted">Total Users</div>
                            <div class="stat-value"><?php echo $stats['users']; ?></div>
                        </div>
                        <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="fas fa-users"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted">Total Orders</div>
                            <div class="stat-value"><?php echo $stats['orders']; ?></div>
                        </div>
                        <div class="stat-icon bg-primary bg-opacity-10 text-primary"><i class="fas fa-shopping-cart"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted">Revenue</div>
                            <div class="stat-value">₹<?php echo number_format($stats['revenue'], 0); ?></div>
                        </div>
                        <div class="stat-icon bg-warning bg-opacity-10 text-warning"><i class="fas fa-rupee-sign"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted">Products</div>
                            <div class="stat-value"><?php echo $stats['products']; ?></div>
                        </div>
                        <div class="stat-icon bg-info bg-opacity-10 text-info"><i class="fas fa-box"></i></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="table-card mt-4">
            <div class="p-3 border-bottom">
                <h5 class="mb-0">Recent Orders</h5>
            </div>
            <table class="table mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_orders as $order) { ?>
                    <tr>
                        <td><?php echo $order['order_number']; ?></td>
                        <td><?php echo htmlspecialchars($order['user_name']); ?></td>
                        <td>₹<?php echo number_format($order['total_amount'], 2); ?></td>
                        <td><span class="badge bg-<?php echo getStatusClass($order['status']); ?>"><?php echo ucfirst($order['status']); ?></span></td>
                        <td><?php echo date('d M Y', strtotime($order['created_at'])); ?></td>
                        <td><a href="order-detail.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-outline-primary">View</a></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        
        <h5 class="mt-5 mb-3">Recent Products</h5>
        <div class="row g-3">
            <?php foreach ($recent_products as $product) { ?>
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="card h-100">
                    <div style="height: 150px; overflow: hidden; display: flex; align-items: center; justify-content: center; background: #f8f9fa;">
                        <?php if ($product['image']) { ?>
                        <img src="<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                        <?php } else { ?>
                        <i class="fas fa-box fa-3x text-muted"></i>
                        <?php } ?>
                    </div>
                    <div class="card-body">
                        <h6 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h6>
                        <p class="text-success fw-bold mb-0">₹<?php echo number_format($product['price'], 2); ?></p>
                        <small class="text-muted">Stock: <?php echo $product['stock']; ?></small>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>
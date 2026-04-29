<?php
require_once '../config/db.php';
session_start();

if (!isAdmin()) {
    header('Location: login.php');
    exit();
}

$users = $conn->query("SELECT * FROM users ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Users - VMART Admin</title>
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
            <li><a href="users.php" class="active">Users</a></li>
            <li><a href="coupons.php">Coupons</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    
    <div class="main-content">
        <h3 class="mb-4">Users</h3>
        
        <div class="table-card">
            <table class="table mb-0">
                <thead class="table-light">
                    <tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Joined</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u) { ?>
                    <tr>
                        <td><?php echo $u['id']; ?></td>
                        <td><?php echo htmlspecialchars($u['name']); ?></td>
                        <td><?php echo htmlspecialchars($u['email']); ?></td>
                        <td><span class="badge bg-<?php echo $u['role']=='admin'?'danger':'primary'; ?>"><?php echo $u['role']; ?></span></td>
                        <td><?php echo date('d M Y', strtotime($u['created_at'])); ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
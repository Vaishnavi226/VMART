<?php
require_once '../config/db.php';
session_start();

if (!isAdmin()) {
    header('Location: login.php');
    exit();
}

$page_title = "Products";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_product'])) {
        $name = sanitize($_POST['name']);
        $slug = slugify($name);
        $description = sanitize($_POST['description']);
        $price = (float)$_POST['price'];
        $original_price = !empty($_POST['original_price']) ? (float)$_POST['original_price'] : null;
        $category_id = (int)$_POST['category_id'];
        $stock = (int)$_POST['stock'];
        $featured = sanitize($_POST['featured']);
        
        $image = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $upload_dir = '../uploads/';
            $file_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (in_array($file_ext, $allowed)) {
                $filename = 'product_' . time() . '_' . rand(100, 999) . '.' . $file_ext;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $filename)) {
                    $image = '/VMART/uploads/' . $filename;
                }
            }
        }
        
        $stmt = $conn->prepare("INSERT INTO products (name, slug, description, price, original_price, category_id, stock, featured, status, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'active', ?)");
        $stmt->execute([$name, $slug, $description, $price, $original_price, $category_id, $stock, $featured, $image]);
        $success = "Product added successfully!";
    } elseif (isset($_POST['delete_product'])) {
        $id = (int)$_POST['product_id'];
        $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $success = "Product deleted successfully!";
    }
}

// Get products
$stmt = $conn->query("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get categories
$stmt = $conn->query("SELECT * FROM categories WHERE status = 'active' ORDER BY name");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products - VMART Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f8f9fa; }
        .sidebar { position: fixed; left: 0; top: 0; bottom: 0; width: 250px; background: #2c3e50; padding: 20px; color: white; }
        .sidebar-brand { font-size: 24px; font-weight: 700; margin-bottom: 40px; display: block; color: white; text-decoration: none; }
        .sidebar-menu { list-style: none; padding: 0; }
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
            <li><a href="products.php" class="active">Products</a></li>
            <li><a href="categories.php">Categories</a></li>
            <li><a href="orders.php">Orders</a></li>
            <li><a href="users.php">Users</a></li>
            <li><a href="coupons.php">Coupons</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Products</h3>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProductModal">Add Product</button>
        </div>
        
        <?php if (isset($success)) { ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
        <?php } ?>
        
             <div class="table-card">
                 <table class="table mb-0">
                     <thead class="table-light">
                         <tr>
                             <th>ID</th>
                             <th>Image</th>
                             <th>Name</th>
                             <th>Category</th>
                             <th>Price</th>
                             <th>Stock</th>
                             <th>Status</th>
                             <th>Actions</th>
                         </tr>
                     </thead>
                     <tbody>
                         <?php foreach ($products as $p) { ?>
                         <tr>
                             <td><?php echo $p['id']; ?></td>
                             <td>
                                 <?php if ($p['image']) { ?>
                                 <img src="../<?php echo $p['image']; ?>" alt="" style="width:50px;height:50px;object-fit:cover;border-radius:4px;">
                                 <?php } else { ?>
                                 <span class="text-muted">No image</span>
                                 <?php } ?>
                             </td>
                             <td><?php echo htmlspecialchars($p['name']); ?></td>
                             <td><?php echo htmlspecialchars($p['category_name']); ?></td>
                             <td>₹<?php echo number_format($p['price'], 2); ?></td>
                             <td><?php echo $p['stock']; ?></td>
                             <td><span class="badge bg-<?php echo $p['status'] == 'active' ? 'success' : 'secondary'; ?>"><?php echo $p['status']; ?></span></td>
                             <td>
                                 <form method="POST" class="d-inline" onsubmit="return confirm('Delete this product?');">
                                     <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
                                     <button type="submit" name="delete_product" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                 </form>
                             </td>
                         </tr>
                         <?php } ?>
                     </tbody>
                 </table>
             </div>
    </div>
    
    <div class="modal fade" id="addProductModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Product Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Product Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Price</label>
                                <input type="number" name="price" class="form-control" step="0.01" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Original Price</label>
                                <input type="number" name="original_price" class="form-control" step="0.01">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category</label>
                                <select name="category_id" class="form-select" required>
                                    <?php foreach ($categories as $c) { ?>
                                    <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['name']); ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Stock</label>
                                <input type="number" name="stock" class="form-control" value="0" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Featured</label>
                            <select name="featured" class="form-select">
                                <option value="no">No</option>
                                <option value="yes">Yes</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="add_product" class="btn btn-success">Add Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
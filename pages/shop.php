<?php
$page_title = "Shop";
include '../includes/header.php';

$category = $_GET['category'] ?? null;

$search = $_GET['search'] ?? null;
$page = $_GET['page'] ?? 1;
$limit = 12;
$offset = ($page - 1) * $limit;

$products = getAllProducts($conn, $category, $search, $limit, $offset);

$stmt = $conn->prepare("SELECT COUNT(*) as total FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.status = 'active'");
if ($category) {
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.status = 'active' AND c.slug = ?");
    $stmt->execute([$category]);
} elseif ($search) {
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.status = 'active' AND (p.name LIKE ? OR p.description LIKE ?)");
    $stmt->execute(["%$search%", "%$search%"]);
} else {
    $stmt->execute();
}
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$total_products = $result['total'];
$total_pages = ceil($total_products / $limit);
?>

<section class="shop-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="filter-sidebar">
                    <h4 class="filter-title">Filters</h4>
                    
                    <div class="filter-group">
                        <h5 class="filter-label">Categories</h5>
                        <?php
                        $categories = getCategories($conn);
                        foreach ($categories as $cat) {
                        ?>
                        <a href="?category=<?php echo $cat['slug']; ?>" class="filter-option d-block text-decoration-none <?php echo $category === $cat['slug'] ? 'text-primary' : 'text-dark'; ?>">
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </a>
                        <?php } ?>
                    </div>
                    
                    <div class="filter-group">
                        <h5 class="filter-label">Price Range</h5>
                        <input type="range" class="form-range" min="0" max="1000" step="50">
                        <div class="d-flex justify-content-between">
                            <span>₹0</span>
                            <span>₹1000+</span>
                        </div>
                    </div>
                    
                    <?php if ($category || $search) { ?>
                    <a href="shop.php" class="btn btn-outline-primary w-100">Clear Filters</a>
                    <?php } ?>
                </div>
            </div>
            
            <div class="col-lg-9">
                <?php if ($search) { ?>
                <div class="alert alert-info">
                    Showing results for "<strong><?php echo htmlspecialchars($search); ?></strong>"
                    (<?php echo $total_products; ?> products found)
                </div>
                <?php } ?>
                
                <div class="row g-4">
                    <?php if (empty($products)) { ?>
                    <div class="col-12">
                        <div class="alert alert-warning text-center">
                            No products found. <a href="shop.php">Browse all products</a>
                        </div>
                    </div>
                    <?php } ?>
                    
                    <?php foreach ($products as $product) { 
                        $discount = 0;
                        if ($product['original_price']) {
                            $discount = round(($product['original_price'] - $product['price']) / $product['original_price'] * 100);
                        }
                    ?>
                    <div class="col-sm-6 col-lg-4">
                        <div class="product-card">
                            <div class="product-image">
                                <?php if ($discount > 0) { ?>
                                <span class="product-badge"><?php echo $discount; ?>% OFF</span>
                                <?php } ?>
                                <div class="product-actions">
                                    <button class="product-action-btn btn-wishlist" data-id="<?php echo $product['id']; ?>">
                                        <i class="fas fa-heart"></i>
                                    </button>
                                </div>
                                <a href="product.php?slug=<?php echo $product['slug']; ?>">
                                    <img src="<?php echo getProductImage($product['image'], $product['name']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                </a>
                            </div>
                            <div class="product-info">
                                <span class="product-category"><?php echo htmlspecialchars($product['category_name']); ?></span>
                                <h4 class="product-name">
                                    <a href="product.php?slug=<?php echo $product['slug']; ?>"><?php echo htmlspecialchars($product['name']); ?></a>
                                </h4>
                                <div class="product-price">
                                    <span class="current-price"><?php echo formatPrice($product['price']); ?></span>
                                    <?php if ($product['original_price']) { ?>
                                    <span class="original-price"><?php echo formatPrice($product['original_price']); ?></span>
                                    <?php } ?>
                                </div>
                                <button class="btn btn-add-cart" data-id="<?php echo $product['id']; ?>">
                                    <i class="fas fa-shopping-cart me-2"></i> Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                
                <?php if ($total_pages > 1) { ?>
                <nav class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                    <a class="page-link <?php echo $i == $page ? 'active' : ''; ?>" href="?page=<?php echo $i; ?><?php echo $category ? '&category=' . $category : ''; ?><?php echo $search ? '&search=' . $search : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                    <?php } ?>
                </nav>
                <?php } ?>
            </div>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
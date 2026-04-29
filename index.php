<?php
require_once 'config/db.php';
require_once 'includes/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$page_title = "Home";
include 'includes/header.php';
?>


<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="hero-title">
                    Fresh <span class="highlight">Organic</span>Groceries<br>Delivered to Your Door
                </h1>
                <p class="hero-subtitle">
                    Discover the best quality fruits, vegetables, dairy products and more. 
                    Fresh from farms to your kitchen table.
                </p>
                <a href="pages/shop.php" class="btn btn-hero">Shop Now <i class="fas fa-arrow-right ms-2"></i></a>
            </div>
            <div class="col-lg-6">
                <div class="hero-image">
                    <img src="assets/images/hero-shopping.png" alt="Online Grocery Shopping" class="hero-img">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="category-section">
    <div class="container">
        <div class="text-center">
            <h2 class="section-title">Shop by Category</h2>
            <p class="section-subtitle">Browse our wide range of categories</p>
        </div>
        <div class="row g-4">
            <?php
            $categories = getCategories($conn);
            $icons = ['fa-apple-alt', 'fa-carrot', 'fa-cheese', 'fa-glass-water', 'fa-cookie', 'fa-seedling'];
            $i = 0;
            foreach ($categories as $cat) {
            ?>
            <div class="col-6 col-md-4 col-lg-2">
                <a href="pages/shop.php?category=<?php echo $cat['slug']; ?>" class="text-decoration-none">
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="fas <?php echo $icons[$i % count($icons)]; ?>"></i>
                        </div>
                        <h5 class="category-name"><?php echo htmlspecialchars($cat['name']); ?></h5>
                    </div>
                </a>
            </div>
            <?php $i++; } ?>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="products-section">
    <div class="container">
        <div class="text-center">
            <h2 class="section-title">Featured Products</h2>
            <p class="section-subtitle">Our most popular products</p>
        </div>
        <div class="row g-4">
            <?php
            $featured = getFeaturedProducts($conn, 8);
            foreach ($featured as $product) {
                $discount = 0;
                if ($product['original_price']) {
                    $discount = round(($product['original_price'] - $product['price']) / $product['original_price'] * 100);
                }
            ?>
            <div class="col-sm-6 col-lg-3">
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
                        <a href="pages/product.php?slug=<?php echo $product['slug']; ?>">
                            <img src="<?php echo getProductImage($product['image'], $product['name']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        </a>

                    </div>
                    <div class="product-info">
                        <span class="product-category"><?php echo htmlspecialchars($product['category_name']); ?></span>
                        <h4 class="product-name">
                            <a href="pages/product.php?slug=<?php echo $product['slug']; ?>"><?php echo htmlspecialchars($product['name']); ?></a>
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
        <div class="text-center mt-5">
            <a href="pages/shop.php" class="btn btn-hero">View All Products</a>
        </div>
    </div>
</section>

<!-- Banner Section -->
<section class="banner-section">
    <div class="container">
        <div class="banner-card">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <h2 class="banner-title">Get 20% Off Your First Order</h2>
                    <p class="banner-text">Use code FIRST20 at checkout to avail this offer.</p>
                    <a href="pages/shop.php" class="btn btn-banner">Shop Now</a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
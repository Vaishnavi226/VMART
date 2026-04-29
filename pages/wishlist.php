<?php
$page_title = "Wishlist";
include '../includes/header.php';

if (!isLoggedIn()) {
    header('Location: login.php?redirect=wishlist');
    exit();
}

$wishlist_items = getWishlistItems($conn, $_SESSION['user_id']);
?>


<section class="wishlist-section">
    <div class="container">
        <h2 class="section-title mb-4">My Wishlist</h2>
        
        <?php if (empty($wishlist_items)) { ?>
        <div class="alert alert-info text-center py-5">
            <i class="fas fa-heart fa-3x mb-3"></i>
            <h4>Your wishlist is empty</h4>
            <p>Save your favorite products here!</p>
            <a href="shop.php" class="btn btn-hero">Browse Products</a>
        </div>
        <?php } else { ?>
        <div class="row g-4">
            <?php foreach ($wishlist_items as $item) { ?>
            <div class="col-sm-6 col-lg-3">
                <div class="product-card">
                    <div class="product-image">
                        <div class="product-actions">
                            <button class="product-action-btn btn-wishlist wishlist-active" data-id="<?php echo $item['id']; ?>">
                                <i class="fas fa-heart"></i>
                            </button>
                        </div>
                        <a href="product.php?slug=<?php echo $item['slug']; ?>">
                            <img src="<?php echo getProductImage($item['image'], $item['name']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                        </a>
                    </div>
                    <div class="product-info">
                        <span class="product-category"><?php echo htmlspecialchars($item['category_name'] ?? ''); ?></span>
                        <h4 class="product-name">
                            <a href="product.php?slug=<?php echo $item['slug']; ?>"><?php echo htmlspecialchars($item['name']); ?></a>
                        </h4>
                        <div class="product-price">
                            <span class="current-price"><?php echo formatPrice($item['price']); ?></span>
                        </div>
                        <button class="btn btn-add-cart" data-id="<?php echo $item['id']; ?>">
                            <i class="fas fa-shopping-cart me-2"></i> Add to Cart
                        </button>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
        <?php } ?>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
<?php
$slug = $_GET['slug'] ?? '';
if (!$slug) {
    header('Location: shop.php');
    exit();
}

// Header already handles db and session
include '../includes/header.php';

$product = getProductBySlug($conn, $slug);

if (!$product) {
    echo '<div class="container py-5"><div class="alert alert-danger">Product not found!</div></div>';
    exit();
}

$page_title = $product['name'];
?>


<section class="product-detail-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="product-detail-image">
                    <img src="<?php echo getProductImage($product['image'], $product['name']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="product-detail-info">
                    <span class="product-category"><?php echo htmlspecialchars($product['category_name']); ?></span>
                    <h1><?php echo htmlspecialchars($product['name']); ?></h1>
                    
                    <div class="product-detail-price">
                        <?php echo formatPrice($product['price']); ?>
                        <?php if ($product['original_price']) { ?>
                        <span class="original-price"><?php echo formatPrice($product['original_price']); ?></span>
                        <?php } ?>
                    </div>
                    
                    <p class="product-description"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                    
                    <div class="quantity-selector">
                        <span>Quantity:</span>
                        <button class="quantity-btn" onclick="updateQty(-1)"><i class="fas fa-minus"></i></button>
                        <span class="quantity-value" id="quantity">1</span>
                        <button class="quantity-btn" onclick="updateQty(1)"><i class="fas fa-plus"></i></button>
                    </div>
                    
                    <div class="d-flex gap-3">
                        <button class="btn btn-hero btn-add-cart" data-id="<?php echo $product['id']; ?>">
                            <i class="fas fa-shopping-cart me-2"></i> Add to Cart
                        </button>
                        <button class="product-action-btn btn-wishlist" data-id="<?php echo $product['id']; ?>" style="width:auto;padding:12px 20px;border-radius:30px;">
                            <i class="fas fa-heart me-2"></i> Wishlist
                        </button>
                    </div>
                    
                    <div class="product-meta">
                        <div class="meta-item">
                            <span class="meta-label">Availability:</span>
                            <span><?php echo $product['stock'] > 0 ? '<span class="text-success">In Stock (' . $product['stock'] . ')</span>' : '<span class="text-danger">Out of Stock</span>'; ?></span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Category:</span>
                            <span><?php echo htmlspecialchars($product['category_name']); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function updateQty(change) {
    var qty = document.getElementById('quantity');
    var newQty = parseInt(qty.textContent) + change;
    if (newQty >= 1) qty.textContent = newQty;
}
</script>

<?php include '../includes/footer.php'; ?>
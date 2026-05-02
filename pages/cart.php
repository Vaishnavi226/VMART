<?php
$page_title = "Shopping Cart";
include '../includes/header.php';

if (!isLoggedIn()) {
    header('Location: login.php?redirect=cart');
    exit();
}

$cart_items = getCartItems($conn, $_SESSION['user_id']);

$cart_total = getCartTotal($conn, $_SESSION['user_id']);
?>

<section class="cart-section">
    <div class="container">
        <h2 class="section-title mb-4">Shopping Cart (<?php echo count($cart_items); ?> items)</h2>
        
        <?php if (empty($cart_items)) { ?>
        <div class="alert alert-info text-center py-5">
            <i class="fas fa-shopping-cart fa-3x mb-3"></i>
            <h4>Your cart is empty</h4>
            <p>Add some products to get started!</p>
            <a href="shop.php" class="btn btn-hero">Continue Shopping</a>
        </div>
        <?php } else { ?>
        <div class="row">
            <div class="col-lg-8">
                <div class="cart-table">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cart_items as $item) { ?>
                            <tr>
                                <td>
                                    <div class="cart-product-info">
                                        <img src="<?php echo getProductImage($item['image'], $item['name']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                        <div>
                                            <h5><?php echo htmlspecialchars($item['name']); ?></h5>
                                            <small class="text-muted">Stock: <?php echo $item['stock']; ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo formatPrice($item['price']); ?></td>
                                <td>
                                    <div class="quantity-selector">
                                        <button class="quantity-btn quantity-btn-minus" data-cart-id="<?php echo $item['cart_id']; ?>"><i class="fas fa-minus"></i></button>
                                        <span class="quantity-value"><?php echo $item['quantity']; ?></span>
                                        <button class="quantity-btn quantity-btn-plus" data-cart-id="<?php echo $item['cart_id']; ?>"><i class="fas fa-plus"></i></button>
                                    </div>
                                </td>
                                <td><?php echo formatPrice($item['price'] * $item['quantity']); ?></td>
                                <td>
                                    <button class="btn btn-remove-cart text-danger" data-id="<?php echo $item['cart_id']; ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="cart-summary">
                    <h4 class="summary-title">Order Summary</h4>
                    
                    <div class="coupon-form">
                        <input type="text" class="form-control" placeholder="Enter coupon code" id="coupon-code">
                        <button class="btn" id="apply-coupon">Apply</button>
                    </div>
                    
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span><?php echo formatPrice($cart_total); ?></span>
                    </div>
                    <div class="summary-row" id="discount-row" style="display:none;">
                        <span>Discount</span>
                        <span class="text-success" id="discount-amount">-₹0</span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping</span>
                        <span>Free</span>
                    </div>
                    <div class="summary-row total">
                        <span>Total</span>
                        <span id="final-total"><?php echo formatPrice($cart_total); ?></span>
                    </div>
                    
                    <?php $_SESSION['cart_total'] = $cart_total; ?>
                    <a href="checkout.php" class="btn btn-hero w-100 mt-3">Proceed to Checkout</a>
                    <a href="shop.php" class="btn btn-outline-primary w-100 mt-2">Continue Shopping</a>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</section>

<?php include '../includes/footer.php'; ?>

$(document).ready(function() {
    // Add to cart
    $('.btn-add-cart').on('click', function(e) {
        e.preventDefault();
        var productId = $(this).data('id');
        var quantity = 1;
        
        var btn = $(this);
        var qtyElement = btn.closest('.product-info').find('.quantity-value');
        if (qtyElement.length) {
            quantity = parseInt(qtyElement.text()) || 1;
        }

        $.ajax({
            url: '/VMART/actions/cart.php',
            type: 'POST',
            data: {
                action: 'add',
                product_id: productId,
                quantity: quantity
            },
            success: function(response) {
                try {
                    var data = typeof response === 'string' ? JSON.parse(response) : response;
                    if (data.success) {
                        showToast('Product added to cart!');
                        if (data.cart_count !== undefined) {
                            if ($('.cart-badge').length === 0) {
                                var badge = $('<span class="cart-badge"></span>');
                                $('.cart-icon').append(badge);
                            }
                            if (data.cart_count > 0) {
                                $('.cart-badge').text(data.cart_count).show();
                            } else {
                                $('.cart-badge').hide();
                            }
                        }
                    } else {
                        if (data.redirect) {
                            window.location.href = '/VMART/pages/login.php';
                        } else {
                            showToast(data.message, 'error');
                        }
                    }
                } catch(e) {
                    showToast('Error: ' + response, 'error');
                }
            },
            error: function(xhr, status, error) {
                alert('AJAX Error: ' + error);
            }
        });
    });

    // Update cart quantity
    $('.quantity-btn-minus, .quantity-btn-plus').on('click', function() {
        var input = $(this).siblings('.quantity-value');
        var currentQty = parseInt(input.text());
        var cartId = $(this).data('cart-id');
        var newQty = $(this).hasClass('quantity-btn-plus') ? currentQty + 1 : currentQty - 1;
        
        if (newQty < 1) return;
        
        $.ajax({
            url: '/VMART/actions/cart.php',
            type: 'POST',
            data: {
                action: 'update',
                cart_id: cartId,
                quantity: newQty
            },
            success: function(response) {
                var data = JSON.parse(response);
                if (data.success) {
                    location.reload();
                }
            }
        });
    });

    // Remove from cart
    $('.btn-remove-cart').on('click', function() {
        var cartId = $(this).data('id');
        
        if (!confirm('Are you sure you want to remove this item?')) return;
        
        $.ajax({
            url: '/VMART/actions/cart.php',
            type: 'POST',
            data: {
                action: 'remove',
                cart_id: cartId
            },
            success: function(response) {
                var data = JSON.parse(response);
                if (data.success) {
                    location.reload();
                }
            }
        });
    });

    // Add to wishlist
    $('.btn-wishlist').on('click', function(e) {
        e.preventDefault();
        var productId = $(this).data('id');
        var btn = $(this);
        
        $.ajax({
            url: '../actions/wishlist.php',
            type: 'POST',
            data: {
                action: 'toggle',
                product_id: productId
            },
            success: function(response) {
                try {
                    var data = typeof response === 'string' ? JSON.parse(response) : response;
                    if (data.success) {
                        btn.toggleClass('wishlist-active');
                        alert(data.message);
                    } else {
                        alert(data.message);
                        if (data.redirect) {
                            window.location.href = 'login.php';
                        }
                    }
                } catch(e) {
                    alert('Error: ' + response);
                }
            }
        });
    });

    // Apply coupon
    $('#apply-coupon').on('click', function() {
        var code = $('#coupon-code').val();
        
        $.ajax({
            url: '../actions/coupon.php',
            type: 'POST',
            data: {
                code: code
            },
            success: function(response) {
                var data = JSON.parse(response);
                if (data.success) {
                    $('#discount-row').show();
                    $('#discount-amount').text('₹' + data.discount);
                    $('#final-total').text('₹' + data.final_total);
                    alert('Coupon applied!');
                } else {
                    alert(data.message);
                }
            }
        });
    });

    // Image preview
    $('#product-image').on('change', function() {
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#image-preview').attr('src', e.target.result).show();
            }
            reader.readAsDataURL(this.files[0]);
        }
    });
    
    // Toast notification
    function showToast(message, type = 'success') {
        var bgClass = type === 'error' ? 'bg-danger' : 'bg-success';
        var toast = $('<div class="toast-notification ' + bgClass + '">' + message + '</div>');
        $('body').append(toast);
        toast.fadeIn(300).delay(2000).fadeOut(300, function() { $(this).remove(); });
    }
    
    // Navbar scroll effect
    $(window).on('scroll', function() {
        if ($(this).scrollTop() > 50) {
            $('.navbar').addClass('shadow-sm');
        } else {
            $('.navbar').removeClass('shadow-sm');
        }
    });
});
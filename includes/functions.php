<?php

function getCategories($conn) {
    $stmt = $conn->query("SELECT * FROM categories WHERE status = 'active' ORDER BY name");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getFeaturedProducts($conn, $limit = 8) {
    $stmt = $conn->query("SELECT p.*, c.name as category_name FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        WHERE p.status = 'active' AND p.featured = 'yes' 
        ORDER BY p.created_at DESC LIMIT " . (int)$limit);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllProducts($conn, $category = null, $search = null, $limit = 12, $offset = 0) {
    $query = "SELECT p.*, c.name as category_name FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        WHERE p.status = 'active'";
    $params = [];
    
    if ($category) {
        $query .= " AND c.slug = ?";
        $params[] = $category;
    }
    
    if ($search) {
        $query .= " AND (p.name LIKE ? OR p.description LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }
    
    $query .= " ORDER BY p.created_at DESC LIMIT " . (int)$limit . " OFFSET " . (int)$offset;
    
    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getProductBySlug($conn, $slug) {
    $stmt = $conn->prepare("SELECT p.*, c.name as category_name FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        WHERE p.slug = ? AND p.status = 'active'");
    $stmt->execute([$slug]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getCartItems($conn, $user_id) {
    $stmt = $conn->prepare("SELECT c.id as cart_id, c.quantity, p.id, p.name, p.price, p.image, p.stock 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getCartTotal($conn, $user_id) {
    $stmt = $conn->prepare("SELECT SUM(c.quantity * p.price) as total 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?");
    $stmt->execute([$user_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'] ? $result['total'] : 0;
}

function getUserOrders($conn, $user_id) {
    $stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getOrderItems($conn, $order_id) {
    $stmt = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
    $stmt->execute([$order_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getWishlistItems($conn, $user_id) {
    $stmt = $conn->prepare("SELECT p.*, w.id as wishlist_id FROM wishlist w 
        JOIN products p ON w.product_id = p.id 
        WHERE w.user_id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function isInWishlist($conn, $user_id, $product_id) {
    $stmt = $conn->prepare("SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
    return $stmt->fetch() ? true : false;
}

function applyCoupon($conn, $code, $total) {
    $stmt = $conn->prepare("SELECT * FROM coupons WHERE code = ? AND status = 'active' 
        AND (valid_from IS NULL OR valid_from <= CURDATE()) 
        AND (valid_until IS NULL OR valid_until >= CURDATE())");
    $stmt->execute([$code]);
    $coupon = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$coupon) {
        return ['success' => false, 'message' => 'Invalid coupon code'];
    }
    
    if ($coupon['max_uses'] && $coupon['used_count'] >= $coupon['max_uses']) {
        return ['success' => false, 'message' => 'Coupon usage limit reached'];
    }
    
    if ($total < $coupon['min_order_amount']) {
        return ['success' => false, 'message' => 'Minimum order amount required'];
    }
    
    $discount = 0;
    if ($coupon['discount_type'] == 'percentage') {
        $discount = ($total * $coupon['discount_value']) / 100;
    } else {
        $discount = $coupon['discount_value'];
    }
    
    return [
        'success' => true,
        'discount' => $discount,
        'coupon' => $coupon
    ];
}

function formatDate($date) {
    return date('d M Y', strtotime($date));
}

function getStatusClass($status) {
    $classes = [
        'pending' => 'warning',
        'processing' => 'info',
        'shipped' => 'primary',
        'delivered' => 'success',
        'cancelled' => 'danger'
    ];
    return $classes[$status] ?? 'secondary';
}

function getProductImage($image, $name = '') {
    if (empty($image)) {
        $name = str_replace(' ', '+', $name);
        return 'https://placehold.co/400x400/2ecc71/ffffff?text=' . $name;
    }
    // If it's just a filename, prepend the assets path
    if (!preg_match('/^http/', $image)) {
        $root = (strpos($_SERVER['PHP_SELF'], '/pages/') !== false || strpos($_SERVER['PHP_SELF'], '/actions/') !== false || strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../' : '';
        return $root . 'assets/images/' . $image;
    }
    return $image;
}


function formatPrice($price) {
    return '₹' . number_format($price, 2);
}
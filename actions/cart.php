<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

session_start();
header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Please login first', 'redirect' => true]);
    exit();
}

$action = $_POST['action'] ?? '';
$response = ['success' => false, 'message' => 'Invalid action'];

switch ($action) {
    case 'add':
        $product_id = (int)$_POST['product_id'];
        $quantity = (int)$_POST['quantity'];
        
        $stmt = $conn->prepare("SELECT id FROM products WHERE id = ? AND status = 'active'");
        $stmt->execute([$product_id]);
        if (!$stmt->fetch()) {
            $response = ['success' => false, 'message' => 'Product not found'];
            break;
        }
        
        $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$_SESSION['user_id'], $product_id]);
        $existing = $stmt->fetch();
        
        if ($existing) {
            $stmt = $conn->prepare("UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?");
            $stmt->execute([$quantity, $_SESSION['user_id'], $product_id]);
        } else {
            $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
            $stmt->execute([$_SESSION['user_id'], $product_id, $quantity]);
        }
        
        $cart_count = getCartCount($_SESSION['user_id'], $conn);
        $response = [
            'success' => true,
            'message' => 'Product added to cart',
            'cart_count' => $cart_count
        ];
        break;
        
    case 'update':
        $cart_id = (int)$_POST['cart_id'];
        $quantity = (int)$_POST['quantity'];
        
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$quantity, $cart_id, $_SESSION['user_id']]);
        
        $response = ['success' => true, 'message' => 'Cart updated'];
        break;
        
    case 'remove':
        $cart_id = (int)$_POST['cart_id'];
        
        $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
        $stmt->execute([$cart_id, $_SESSION['user_id']]);
        
        $cart_count = getCartCount($_SESSION['user_id'], $conn);
        $response = [
            'success' => true,
            'message' => 'Product removed from cart',
            'cart_count' => $cart_count
        ];
        break;
        
    case 'get':
        $cart_items = getCartItems($conn, $_SESSION['user_id']);
        $cart_total = getCartTotal($conn, $_SESSION['user_id']);
        $response = [
            'success' => true,
            'items' => $cart_items,
            'total' => $cart_total
        ];
        break;
}

echo json_encode($response);
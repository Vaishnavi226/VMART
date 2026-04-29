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
    case 'toggle':
        $product_id = (int)$_POST['product_id'];
        $user_id = $_SESSION['user_id'];
        
        $stmt = $conn->prepare("SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
        $existing = $stmt->fetch();
        
        if ($existing) {
            $stmt = $conn->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
            $stmt->execute([$user_id, $product_id]);
            $response = ['success' => true, 'message' => 'Removed from wishlist'];
        } else {
            $stmt = $conn->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)");
            $stmt->execute([$user_id, $product_id]);
            $response = ['success' => true, 'message' => 'Added to wishlist'];
        }
        break;
        
    case 'get':
        $wishlist_items = getWishlistItems($conn, $_SESSION['user_id']);
        $response = [
            'success' => true,
            'items' => $wishlist_items
        ];
        break;
}

echo json_encode($response);
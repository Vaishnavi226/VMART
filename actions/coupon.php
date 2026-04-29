<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

session_start();
header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit();
}

$code = sanitize($_POST['code'] ?? '');
$cart_total = $_SESSION['cart_total'] ?? 0;

if (!$code) {
    echo json_encode(['success' => false, 'message' => 'Please enter a coupon code']);
    exit();
}

$result = applyCoupon($conn, $code, $cart_total);

echo json_encode($result);
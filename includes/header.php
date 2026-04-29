<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Determine the root path
$root = (strpos($_SERVER['PHP_SELF'], '/pages/') !== false || strpos($_SERVER['PHP_SELF'], '/actions/') !== false || strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../' : '';

require_once $root . 'config/db.php';
require_once $root . 'includes/functions.php';

$user = getUser();
$cart_count = 0;
if ($user) {
    $cart_count = getCartCount($user['id'], $conn);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - VMART' : 'VMART - Fresh & Organic'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="<?php echo $root; ?>assets/css/style.css" rel="stylesheet">
</head>
<body>
    <?php include $root . 'includes/navbar.php'; ?>
    <main>

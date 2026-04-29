<?php 
$current_page = basename($_SERVER['PHP_SELF'], '.php');
$categories_stmt = $conn->query("SELECT id, name, slug FROM categories WHERE status = 'active'");
$categories = $categories_stmt->fetchAll(PDO::FETCH_ASSOC);

// Determine path prefix based on whether we are in a subfolder
$path_prefix = (strpos($_SERVER['PHP_SELF'], '/pages/') !== false || strpos($_SERVER['PHP_SELF'], '/actions/') !== false || strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '' : 'pages/';
$root_prefix = (strpos($_SERVER['PHP_SELF'], '/pages/') !== false || strpos($_SERVER['PHP_SELF'], '/actions/') !== false || strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../' : '';
?>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand" href="<?php echo $root_prefix; ?>index.php">
            <span class="brand-text">V<span class="brand-highlight">MART</span></span>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page == 'index' ? 'active' : ''; ?>" href="<?php echo $root_prefix; ?>index.php">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="categoryDropdown" role="button" data-bs-toggle="dropdown">
                        Categories
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="categoryDropdown">
                        <?php foreach ($categories as $cat) { ?>
                        <li><a class="dropdown-item" href="<?php echo $path_prefix; ?>shop.php?category=<?php echo $cat['slug']; ?>"><?php echo htmlspecialchars($cat['name']); ?></a></li>
                        <?php } ?>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page == 'shop' ? 'active' : ''; ?>" href="<?php echo $path_prefix; ?>shop.php">Shop</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page == 'contact' ? 'active' : ''; ?>" href="<?php echo $path_prefix; ?>contact.php">Contact</a>
                </li>
            </ul>
            
            <div class="d-flex align-items-center gap-3">
                <form class="search-form" action="<?php echo $path_prefix; ?>shop.php" method="GET">
                    <input type="text" name="search" class="form-control" placeholder="Search products...">
                    <button type="submit" class="btn-search"><i class="fas fa-search"></i></button>
                </form>
                
                <a href="<?php echo $path_prefix; ?>cart.php" class="cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                    <?php if ($cart_count > 0) { ?>
                    <span class="cart-badge"><?php echo $cart_count; ?></span>
                    <?php } else { ?>
                    <span class="cart-badge" style="display:none;"></span>
                    <?php } ?>
                </a>
                
                <?php if ($user) { ?>
                <div class="dropdown">
                    <button class="btn btn-user dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($user['name']); ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?php echo $path_prefix; ?>orders.php"><i class="fas fa-box"></i> My Orders</a></li>
                        <li><a class="dropdown-item" href="<?php echo $path_prefix; ?>wishlist.php"><i class="fas fa-heart"></i> Wishlist</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?php echo $root_prefix; ?>actions/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                    </ul>
                </div>
                <?php } else { ?>
                <a href="<?php echo $path_prefix; ?>login.php" class="btn btn-login">Login</a>
                <?php } ?>
            </div>
        </div>
    </div>
</nav>

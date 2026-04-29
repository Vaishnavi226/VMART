</main>
    
    <footer class="footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <h4 class="footer-title">VMART</h4>
                    <p class="footer-text">Your trusted online store for fresh groceries, organic fruits, vegetables, and quality dairy products. We deliver freshness to your doorstep.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h5 class="footer-heading">Quick Links</h5>
                    <ul class="footer-links">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="pages/shop.php">Shop</a></li>
                        <li><a href="pages/about.php">About Us</a></li>
                        <li><a href="pages/contact.php">Contact</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h5 class="footer-heading">Categories</h5>
                    <ul class="footer-links">
                        <?php 
                        $cat_stmt = $conn->query("SELECT name, slug FROM categories WHERE status = 'active' LIMIT 5");
                        while ($cat = $cat_stmt->fetch(PDO::FETCH_ASSOC)) { 
                        ?>
                        <li><a href="pages/shop.php?category=<?php echo $cat['slug']; ?>"><?php echo htmlspecialchars($cat['name']); ?></a></li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-6">
                    <h5 class="footer-heading">Contact Us</h5>
                    <ul class="footer-contact">
                        <li><i class="fas fa-map-marker-alt"></i> 123 Market Street, City</li>
                        <li><i class="fas fa-phone"></i> +91 9876543210</li>
                        <li><i class="fas fa-envelope"></i> support@vmart.com</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> VMART. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <?php 
    $js_path = (defined('IS_IN_PAGES') || isset($GLOBALS['in_pages']) || strpos($_SERVER['PHP_SELF'], '/pages/') !== false) ? '../assets/js/main.js' : 'assets/js/main.js';
    ?>
    <script src="<?php echo $js_path; ?>"></script>
</body>
</html>
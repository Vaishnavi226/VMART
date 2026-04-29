-- VMART eCommerce Database
CREATE DATABASE IF NOT EXISTS vmart;
USE vmart;

CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    city VARCHAR(100),
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    image VARCHAR(255),
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(200) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    original_price DECIMAL(10,2),
    image VARCHAR(255),
    category_id INT NOT NULL,
    stock INT DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    featured ENUM('yes', 'no') DEFAULT 'no',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS cart (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    order_number VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,
    city VARCHAR(100) NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    payment_method ENUM('cod', 'card') DEFAULT 'cod',
    status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    product_id INT,
    product_name VARCHAR(200) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS wishlist (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS coupons (
    id INT PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(50) NOT NULL UNIQUE,
    discount_type ENUM('percentage', 'fixed') NOT NULL,
    discount_value DECIMAL(10,2) NOT NULL,
    min_order_amount DECIMAL(10,2) DEFAULT 0,
    max_uses INT,
    used_count INT DEFAULT 0,
    valid_from DATE,
    valid_until DATE,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (name, email, password, role) VALUES 
('Admin', 'admin@vmart.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

INSERT INTO categories (name, slug, description, status) VALUES 
('Fruits', 'fruits', 'Fresh organic fruits', 'active'),
('Vegetables', 'vegetables', 'Fresh organic vegetables', 'active'),
('Dairy Products', 'dairy-products', 'Fresh dairy items', 'active'),
('Beverages', 'beverages', 'Drinks and beverages', 'active'),
('Snacks', 'snacks', 'Tasty snacks', 'active'),
('Grains', 'grains', 'Organic grains and cereals', 'active');

INSERT INTO products (name, slug, description, price, original_price, image, category_id, stock, status, featured) VALUES 
('Fresh Apples', 'fresh-apples', 'Organic fresh apples from local farms. Crisp and delicious.', 120.00, 150.00, 'apples.jpg', 1, 50, 'active', 'yes'),
('Organic Bananas', 'organic-bananas', 'Fresh organic bananas rich in potassium.', 60.00, 80.00, 'bananas.jpg', 1, 100, 'active', 'yes'),
('Fresh Spinach', 'fresh-spinach', 'Green fresh spinach leaves, rich in iron.', 40.00, 50.00, 'spinach.jpg', 2, 80, 'active', 'yes'),
('Tomatoes', 'tomatoes', 'Red fresh tomatoes for your kitchen.', 35.00, 45.00, 'tomatoes.jpg', 2, 120, 'active', 'no'),
('Fresh Milk', 'fresh-milk', 'Pure fresh cow milk 1 liter pack.', 55.00, 65.00, 'milk.jpg', 3, 60, 'active', 'yes'),
('Greek Yogurt', 'greek-yogurt', 'Creamy greek yogurt, rich in protein.', 80.00, 100.00, 'yogurt.jpg', 3, 40, 'active', 'no'),
('Orange Juice', 'orange-juice', 'Freshly squeezed orange juice 1L.', 90.00, 110.00, 'orange-juice.jpg', 4, 30, 'active', 'yes'),
('Green Tea', 'green-tea', 'Premium green tea bags 100 count.', 150.00, 180.00, 'green-tea.jpg', 4, 50, 'active', 'no'),
('Cashew Nuts', 'cashew-nuts', 'Roasted salted cashew nuts 200g.', 200.00, 250.00, 'cashews.jpg', 5, 25, 'active', 'yes'),
('Potato Chips', 'potato-chips', 'Crispy potato chips assorted flavors.', 30.00, 40.00, 'chips.jpg', 5, 200, 'active', 'no'),
('Basmati Rice', 'basmati-rice', 'Premium basmati rice 5kg bag.', 450.00, 500.00, 'rice.jpg', 6, 40, 'active', 'yes'),
('Whole Wheat', 'whole-wheat', 'Organic whole wheat 5kg pack.', 280.00, 320.00, 'wheat.jpg', 6, 35, 'active', 'no'),
('Fresh Strawberries', 'fresh-strawberries', 'Sweet organic strawberries 500g.', 180.00, 220.00, 'strawberries.jpg', 1, 25, 'active', 'yes'),
('Cucumber', 'cucumber', 'Fresh green cucumbers 1kg.', 30.00, 40.00, 'cucumber.jpg', 2, 90, 'active', 'no'),
('Cheddar Cheese', 'cheddar-cheese', 'Premium cheddar cheese 400g.', 180.00, 200.00, 'cheese.jpg', 3, 20, 'active', 'no'),
('Mango Juice', 'mango-juice', 'Natural mango juice 1L pack.', 100.00, 120.00, 'mango-juice.jpg', 4, 35, 'active', 'no');
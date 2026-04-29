# 🛒 VMART - Online Grocery Store

A modern, responsive e-commerce platform for purchasing groceries online. Built with PHP, MySQL, and Bootstrap, VMART provides a seamless shopping experience for customers and powerful management tools for administrators.

---

## ✨ Features

### 👥 **Customer Features**

- ✅ User registration and authentication
- ✅ Browse products by categories
- ✅ Search and filter products
- ✅ Add items to cart and wishlist
- ✅ Checkout with order confirmation
- ✅ Order history and tracking
- ✅ Apply coupon codes for discounts
- ✅ Responsive design for mobile & desktop

### 🔧 **Admin Features**

- ✅ Dashboard with sales overview
- ✅ Product management (CRUD operations)
- ✅ Category management
- ✅ User management
- ✅ Order processing and tracking
- ✅ Coupon code management
- ✅ Sales reports

### 🔐 **Security**

- ✅ Password hashing with bcrypt
- ✅ Environment-based configuration
- ✅ PDO prepared statements for SQL injection prevention
- ✅ Session-based authentication
- ✅ Role-based access control (user/admin)

---

## 🛠️ Tech Stack

| Component       | Technology                   |
| --------------- | ---------------------------- |
| **Backend**     | PHP 7+                       |
| **Database**    | MySQL 5.7+                   |
| **Frontend**    | HTML5, CSS3, Bootstrap 5.3.0 |
| **Server**      | Apache (XAMPP)               |
| **Frontend JS** | jQuery, AJAX                 |
| **Icons**       | FontAwesome 6.4.0            |

---

## 📋 Prerequisites

Before installing VMART, ensure you have:

- **XAMPP** (or Apache + PHP + MySQL)
- **PHP 7.0+** with PDO extension
- **MySQL 5.7+**
- **Web Browser** (Chrome, Firefox, Edge, etc.)

---

## 🚀 Installation & Setup

### Step 1: Download/Clone the Project

```bash
# If using Git
git clone https://github.com/yourusername/VMART.git
cd VMART

# Or download the ZIP file and extract to:
# C:\xampp\htdocs\VMART
```

### Step 2: Configure Environment Variables

```bash
# Copy the example environment file
cp .env.example .env

# Edit .env with your local settings (usually no changes needed for local XAMPP)
DB_HOST=127.0.0.1
DB_USER=root
DB_PASSWORD=
DB_NAME=vmart
```

For detailed environment configuration, see [ENV_GUIDE.md](ENV_GUIDE.md)

### Step 3: Start XAMPP Services

1. Open **XAMPP Control Panel**
2. Start **Apache** and **MySQL** services
3. Verify: Open http://localhost/phpmyadmin in your browser

### Step 4: Initialize Database

1. Open your browser and navigate to:
   ```
   http://localhost/VMART/import_db.php
   ```
2. Click **Import Database** button
3. The database will be created with 6 categories and 16 seeded products

### Step 5: Access the Application

- **Customer Site**: http://localhost/VMART/index.php
- **Admin Panel**: http://localhost/VMART/admin/login.php

---

## 👤 Default Credentials

### Admin Account

```
Email: admin@vmart.com
Password: admin123
```

### Test User Account

```
Email: user@vmart.com
Password: password123
```

---

## 📁 Project Structure

```
VMART/
├── index.php                 # Homepage
├── import_db.php             # Database initialization
├── .env                      # Environment variables (local only)
├── .env.example              # Template for .env
├── .gitignore                # Git ignore rules
├── ENV_GUIDE.md              # Environment configuration guide
├── README.md                 # This file
│
├── config/
│   ├── db.php                # Database connection
│   └── env.php               # Environment variable loader
│
├── includes/
│   ├── header.php            # HTML header, CSS/JS loading
│   ├── footer.php            # HTML footer
│   ├── navbar.php            # Navigation menu
│   └── functions.php         # Helper functions
│
├── pages/                    # Customer pages
│   ├── shop.php              # Product listing
│   ├── product.php           # Product details
│   ├── cart.php              # Shopping cart
│   ├── checkout.php          # Order placement
│   ├── order_confirmation.php # Order success page
│   ├── orders.php            # Order history
│   ├── order-detail.php      # Order details
│   ├── wishlist.php          # Saved items
│   ├── login.php             # User login
│   ├── register.php          # User registration
│   ├── about.php             # About us page
│   └── contact.php           # Contact page
│
├── admin/                    # Admin pages
│   ├── login.php             # Admin login
│   ├── dashboard.php         # Admin dashboard
│   ├── products.php          # Product management
│   ├── categories.php        # Category management
│   ├── users.php             # User management
│   ├── orders.php            # Order management
│   ├── order-detail.php      # Order details
│   ├── coupons.php           # Coupon management
│   └── logout.php            # Admin logout
│
├── actions/                  # AJAX handlers
│   ├── cart.php              # Cart operations
│   ├── wishlist.php          # Wishlist operations
│   ├── coupon.php            # Coupon validation
│   └── logout.php            # Session logout
│
├── assets/
│   ├── css/
│   │   └── style.css         # Main stylesheet
│   ├── js/
│   │   └── main.js           # Main JavaScript (AJAX)
│   └── images/               # Product images
│
├── uploads/                  # Uploaded files directory
└── database_new.sql          # Database schema & seed data
```

---

## 💡 Usage Guide

### For Customers

1. **Register a new account** at http://localhost/VMART/pages/register.php
2. **Login** with your credentials
3. **Browse products** in the Shop
4. **Add items to cart** or **Wishlist**
5. **Checkout** and place order
6. **View order history** in your account

### For Administrators

1. **Login** at http://localhost/VMART/admin/login.php
2. Use admin credentials above
3. **Manage Products** - Add, edit, delete products
4. **Manage Categories** - Create categories
5. **View Orders** - Process customer orders
6. **Manage Coupons** - Create discount codes
7. **View Users** - Manage customer accounts

---

## 🗄️ Database Schema

### Tables

| Table         | Purpose                                       |
| ------------- | --------------------------------------------- |
| `users`       | Customer and admin accounts                   |
| `categories`  | Product categories (Fruits, Vegetables, etc.) |
| `products`    | Product catalog with prices and stock         |
| `cart`        | Shopping cart items                           |
| `orders`      | Customer orders                               |
| `order_items` | Items in each order                           |
| `wishlist`    | Saved items by customers                      |
| `coupons`     | Discount codes                                |

---

## 🔄 Workflow

### Customer Purchase Flow

```
Register/Login
    ↓
Browse Products
    ↓
Add to Cart
    ↓
Checkout
    ↓
Place Order
    ↓
Order Confirmation
    ↓
View Order History
```

### Admin Management Flow

```
Admin Login
    ↓
Dashboard (View Statistics)
    ↓
Manage Products/Categories/Users/Orders
    ↓
Process Orders
    ↓
Manage Coupons
```

---

## 🔧 API Endpoints (AJAX)

### Cart Operations

- `POST /actions/cart.php` - Add/Update/Remove cart items

### Wishlist Operations

- `POST /actions/wishlist.php` - Toggle wishlist items

### Coupon Validation

- `POST /actions/coupon.php` - Validate and apply coupons

### Logout

- `GET /actions/logout.php` - Destroy session

---

## 📝 Important Files

- **ENV_GUIDE.md** - Detailed environment configuration documentation
- **database_new.sql** - Complete database schema and seed data
- **import_db.php** - Database initialization script

---

## 🐛 Troubleshooting

### Database Connection Error

- Check `.env` file has correct database credentials
- Ensure MySQL is running in XAMPP
- Verify database name is `vmart`

### Pages Not Loading

- Clear browser cache (Ctrl+Shift+Delete)
- Restart Apache in XAMPP
- Check file permissions

### Images Not Displaying

- Ensure image files exist in `assets/images/`
- Check image file names match database

### Session Not Working

- Verify PHP session is enabled
- Check session storage directory permissions
- Ensure cookies are enabled in browser

For more issues, check the [ENV_GUIDE.md](ENV_GUIDE.md)

---

## 🚀 Deployment

To deploy VMART to production:

1. **Edit .env** with production credentials
2. **Never commit .env** to version control (use .env.example)
3. **Set APP_ENV=production** in .env
4. **Set APP_DEBUG=false** in .env
5. **Upload** to web server
6. **Run import_db.php** on production server to create database
7. See [ENV_GUIDE.md](ENV_GUIDE.md) for security best practices

---

## 🛡️ Security Considerations

- ✅ Passwords are hashed with bcrypt
- ✅ SQL injection prevention using PDO prepared statements
- ✅ Credentials managed via environment variables
- ✅ Session-based authentication
- ✅ Role-based access control

For production deployment, also:

- Use HTTPS/SSL certificates
- Set strong encryption keys
- Implement rate limiting
- Regular security audits
- Keep dependencies updated

---

## 📦 Installed Packages

- **Bootstrap 5.3.0** - CSS Framework
- **jQuery** - JavaScript library
- **FontAwesome 6.4.0** - Icon library

---

## 📄 License

This project is provided as-is for educational and commercial use.

---

## 🤝 Contributing

Contributions are welcome! Please:

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Submit a pull request

---

## 📧 Support

For questions or issues:

- Check the [ENV_GUIDE.md](ENV_GUIDE.md)
- Review the troubleshooting section above
- Check existing issues on GitHub

---

## 📅 Version

**Current Version:** 1.0.0

**Last Updated:** April 29, 2026

---

## 🎯 Roadmap

### Future Features

- [ ] Email notifications for orders
- [ ] Payment gateway integration (Razorpay, Stripe)
- [ ] Product reviews and ratings
- [ ] Advanced search with filters
- [ ] Inventory management alerts
- [ ] Customer support chat
- [ ] Mobile app

---

**Happy Shopping! 🛒**

Built with ❤️ for online grocery shopping

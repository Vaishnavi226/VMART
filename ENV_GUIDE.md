# 🔐 Environment Configuration Guide - VMART

This document explains how to configure environment variables for VMART, especially when preparing for GitHub upload.

## 📋 Overview

VMART uses environment variables to manage sensitive configuration data like database credentials, API keys, and security settings. This approach ensures that:

- ✅ Credentials are never hardcoded in the repository
- ✅ Different environments (local, staging, production) can have different configurations
- ✅ The project is safe to push to GitHub

---

## 🚀 Quick Start

### 1. **For Local Development**

When you first clone or set up the project locally:

```bash
# The .env file already exists with default local values
# Database defaults:
DB_HOST=127.0.0.1
DB_USER=root
DB_PASSWORD=
DB_NAME=vmart
```

**No action needed** if you're using the default XAMPP setup with an empty MySQL password.

### 2. **For Production/GitHub Upload**

Before pushing to GitHub:

1. ✅ `.env` file is in `.gitignore` (already configured)
2. ✅ Only `.env.example` is committed (template file)
3. ✅ Users cloning from GitHub will use `.env.example` as a template

---

## 📁 Files Explained

| File             | Purpose                             | Commit to Git?        |
| ---------------- | ----------------------------------- | --------------------- |
| `.env`           | Actual credentials (LOCAL USE ONLY) | ❌ NO (in .gitignore) |
| `.env.example`   | Template for developers             | ✅ YES                |
| `.gitignore`     | Excludes sensitive files            | ✅ YES                |
| `config/env.php` | Helper function to load .env        | ✅ YES                |
| `config/db.php`  | Updated to use env variables        | ✅ YES                |

---

## 🔑 Environment Variables Reference

### Database Configuration

```env
DB_HOST=127.0.0.1          # MySQL host
DB_USER=root               # MySQL user
DB_PASSWORD=               # MySQL password (empty for local XAMPP)
DB_NAME=vmart              # Database name
```

### Application Settings

```env
APP_ENV=development        # development | production | testing
APP_DEBUG=true             # Enable/disable debug mode
APP_URL=http://localhost/VMART  # Application URL
```

### Session Configuration

```env
SESSION_LIFETIME=3600      # Session timeout in seconds
```

### Security Keys (For Future Use)

```env
APP_KEY=your-secret-key-here
ENCRYPTION_KEY=your-encryption-key-here
```

### Email Configuration (For Future Use)

```env
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_FROM=noreply@vmart.com
MAIL_FROM_NAME=VMART Grocery Store
```

### Payment Gateway (For Future Integration)

```env
RAZORPAY_KEY=your-key
RAZORPAY_SECRET=your-secret
STRIPE_PUBLIC_KEY=your-key
STRIPE_SECRET_KEY=your-secret
```

---

## 🔄 For Users Cloning from GitHub

After cloning the repository:

```bash
# 1. Copy the example file
cp .env.example .env

# 2. Edit .env with your local settings
# For local XAMPP: Usually no changes needed if using defaults

# 3. Run the import script
# Visit: http://localhost/VMART/import_db.php

# 4. Access the application
# Visit: http://localhost/VMART/index.php
```

---

## 🛡️ Security Best Practices

1. **ALWAYS** add `.env` to `.gitignore`
2. **NEVER** commit `.env` to any repository
3. **NEVER** expose `APP_KEY`, `ENCRYPTION_KEY`, or API secrets
4. Use strong passwords in production
5. Rotate API keys regularly
6. Use environment-specific configurations for different servers

---

## 📝 Examples

### Local Development (.env)

```env
DB_HOST=127.0.0.1
DB_USER=root
DB_PASSWORD=
DB_NAME=vmart
APP_ENV=development
APP_DEBUG=true
```

### Production (.env - NEVER COMMIT)

```env
DB_HOST=db.example.com
DB_USER=vmart_user
DB_PASSWORD=strong-password-here
DB_NAME=vmart_production
APP_ENV=production
APP_DEBUG=false
APP_URL=https://vmart.example.com
```

---

## ✅ Checklist Before GitHub Upload

- [ ] `.env` file exists locally (for development)
- [ ] `.env` is listed in `.gitignore`
- [ ] `.env.example` is committed (without real credentials)
- [ ] `config/env.php` is committed
- [ ] `config/db.php` uses `getEnv()` function
- [ ] No hardcoded passwords in any PHP files
- [ ] All sensitive data moved to environment variables

---

## 🆘 Troubleshooting

### "Connection failed" error

- Check `.env` file exists in project root
- Verify `DB_HOST`, `DB_USER`, `DB_PASSWORD`, `DB_NAME` are correct
- Ensure MySQL server is running

### Environment variables not loading

- Verify `.env` file is in the correct location: `C:\xampp\htdocs\VMART\.env`
- Check file permissions (should be readable)
- Clear browser cache and restart Apache

### "File not found" for env.php

- Ensure `config/env.php` exists
- Check `require_once __DIR__ . '/env.php';` path is correct in `config/db.php`

---

## 📚 Additional Resources

- [PHP Environment Variables](https://www.php.net/manual/en/function.getenv.php)
- [12 Factor App - Config](https://12factor.net/config)
- [GitHub - How to handle secrets](https://docs.github.com/en/actions/security-guides/encrypted-secrets)

---

**Created:** April 29, 2026
**For:** VMART E-Commerce Platform
**Version:** 1.0

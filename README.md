# 🐾 Z-Pets Store

> **Modern, production-ready e-commerce platform for pet products in Egypt**  
> Built with Laravel 13, Blade, Tailwind CSS v4, and MySQL. Features WhatsApp checkout, admin dashboard, and fully responsive design.

<div align="center">

[![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3+-777BB4?logo=php&logoColor=white)](https://www.php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-005C87?logo=mysql&logoColor=white)](https://www.mysql.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-4.x-06B6D4?logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

[🌐 Live Demo](https://z-pets-store-production.up.railway.app/) • [📖 Documentation](#documentation) • [🚀 Quick Start](#quick-start)

</div>

---

## 📋 Table of Contents

- [About](#about-the-project)
- [Features](#-features)
- [Tech Stack](#-tech-stack)
- [Project Structure](#-project-structure)
- [Quick Start](#-quick-start)
- [Configuration](#-configuration)
- [Database Schema](#-database-schema)
- [API Endpoints](#-api-endpoints)
- [Admin Dashboard](#-admin-dashboard)
- [Deployment](#-deployment)
- [Development](#-development)
- [Testing](#-testing)
- [Security](#-security)
- [Contributing](#-contributing)
- [License](#-license)
- [Support](#-support)

---

## 🎯 About the Project

**Z-Pets Store** is a comprehensive e-commerce solution designed specifically for Egypt's pet market. It provides pet owners with an intuitive, mobile-first shopping experience while offering sellers a powerful admin dashboard to manage their inventory, orders, and store settings.

### 🎨 Key Highlights

- **66.5% Blade Templates** — Server-side rendering for optimal SEO and performance
- **31.8% PHP (Laravel)** — Production-grade backend with modern patterns
- **Mobile-First Design** — Responsive across all devices and screen sizes
- **WhatsApp Integration** — Seamless order checkout via WhatsApp
- **Admin Dashboard** — Complete store management interface
- **Dark Green Theme** — Professional, modern aesthetic aligned with the brand

### 👥 Target Audience

- Pet owners shopping for supplies and accessories
- Pet store owners managing multiple product categories
- Admin users managing orders and inventory

---

## ✨ Features

### 🛍️ Customer-Facing Features

| Feature | Description |
|---------|-------------|
| **Product Browsing** | Browse products by category with advanced filtering and search |
| **Product Details** | Full product information with multiple high-quality images |
| **Shopping Cart** | Session-based cart with real-time updates and quantity management |
| **WhatsApp Checkout** | One-click order submission via WhatsApp with auto-generated messages |
| **Order Receipts** | Downloadable PDF receipts for every order |
| **Responsive Design** | Optimized for mobile (📱), tablet (📋), and desktop (🖥️) |
| **SEO Optimization** | Meta tags, sitemap, canonical URLs, and structured data |
| **Performance** | Fast page loads with optimized images and caching |

### 🔧 Admin Dashboard Features

| Feature | Description |
|---------|-------------|
| **Dashboard Analytics** | Real-time metrics, sales overview, recent orders |
| **Product Management** | Create, edit, delete products with bulk operations |
| **Category Management** | Organize products with parent/child category hierarchies |
| **Image Management** | Upload multiple images per product with drag-and-drop |
| **Banner Management** | Create and manage hero slider banners |
| **Order Management** | View, filter, and update order status |
| **Settings Panel** | Configure WhatsApp number, store address, social links, etc. |
| **Role-Based Access** | Admin-only routes with middleware protection |
| **Activity Logging** | Track changes and maintain audit trails |

---

## 🛠️ Tech Stack

### Backend
| Technology | Purpose | Version |
|-----------|---------|---------|
| **Laravel** | Web framework | 13.x |
| **PHP** | Server language | 8.3+ |
| **MySQL** | Database | 8.0+ |
| **Eloquent ORM** | Database abstraction | Built-in |
| **Laravel Breeze** | Authentication scaffolding | Latest |
| **Laravel Dompdf** | PDF generation | Latest |
| **PestPHP** | Testing framework | Latest |

### Frontend
| Technology | Purpose | Version |
|-----------|---------|---------|
| **Blade Templates** | Templating engine | 13.x |
| **Tailwind CSS** | Styling framework | 4.x |
| **Vite** | Build tool | 6.x |
| **Alpine.js** | Lightweight interactivity | 3.x |
| **Font Awesome** | Icon library | 6.x |
| **Plus Jakarta Sans** | Custom font | Google Fonts |

### DevOps & Tools
| Tool | Purpose |
|------|---------|
| **Railway.app** | Production hosting |
| **GitHub Actions** | CI/CD pipeline |
| **Composer** | PHP package manager |
| **npm** | JavaScript package manager |
| **Laravel Tinker** | Interactive shell |
| **Laravel Pail** | Real-time logs |

---

## 📁 Project Structure

```
z-pets-store/
├── app/
│   ├── Actions/                          # Reusable domain actions
│   │   ├── CreateProductAction.php
│   │   └── ProcessOrderAction.php
│   ├── Console/
│   │   └── Commands/                     # Custom Artisan commands
│   ├── Helpers/
│   │   └── Cart.php                      # Shopping cart logic
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Public/
│   │   │   │   ├── HomeController.php
│   │   │   │   ├── ProductController.php
│   │   │   │   ├── CategoryController.php
│   │   │   │   ├── CartController.php
│   │   │   │   ├── CheckoutController.php
│   │   │   │   └── SearchController.php
│   │   │   └── Admin/                   # Dashboard controllers
│   │   │       ├── DashboardController.php
│   │   │       ├── ProductController.php
│   │   │       ├── CategoryController.php
│   │   │       ├── BannerController.php
│   │   │       ├── OrderController.php
│   │   │       └── SettingController.php
│   │   ├── Middleware/
│   │   │   ├── IsAdmin.php               # Admin route protection
│   │   │   └── SecurityHeaders.php
│   │   ├── Requests/                    # Form validation requests
│   │   │   ├── StoreProductRequest.php
│   │   │   ├── UpdateProductRequest.php
│   │   │   └── StoreCategoryRequest.php
│   │   └── View/Composers/
│   │       └── NavigationComposer.php    # Global view data
│   ├── Models/
│   │   ├── User.php
│   │   ├── Product.php
│   │   ├── Category.php
│   │   ├── ProductImage.php
│   │   ├── Order.php
│   │   ├── OrderItem.php
│   │   ├── Banner.php
│   │   ├── Setting.php
│   │   └── Tag.php
│   ├── Providers/
│   │   ├── AppServiceProvider.php
│   │   └── AuthServiceProvider.php
│   └── View/Components/
│       ├── ProductCard.php
│       ├── CategoryCard.php
│       └── BannerSlider.php
│
├── bootstrap/
│   └── app.php
│
├── config/                               # Configuration files
│   ├── app.php
│   ├── database.php
│   ├── filesystems.php
│   └── ...
│
├── database/
│   ├── factories/
│   │   ├── UserFactory.php
│   │   ├── ProductFactory.php
│   │   └── CategoryFactory.php
│   ├── migrations/
│   │   ├── 2024_01_01_000000_create_users_table.php
│   │   ├── 2024_01_01_000001_create_categories_table.php
│   │   ├── 2024_01_01_000002_create_products_table.php
│   │   ├── 2024_01_01_000003_create_product_images_table.php
│   │   ├── 2024_01_01_000004_create_orders_table.php
│   │   ├── 2024_01_01_000005_create_order_items_table.php
│   │   ├── 2024_01_01_000006_create_banners_table.php
│   │   └── 2024_01_01_000007_create_settings_table.php
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── AdminUserSeeder.php
│       ├── CategorySeeder.php
│       ├── ProductSeeder.php
│       ├── BannerSeeder.php
│       └── SettingSeeder.php
│
├── public/
│   ├── index.php
│   ├── storage/                         # Symlink to storage/app/public
│   └── build/                           # Compiled frontend assets
│
├── resources/
│   ├── css/
│   │   └── app.css                      # Tailwind CSS entry point
│   ├── js/
│   │   └── app.js                       # Main JS entry point
│   └── views/
│       ├── auth/
│       │   ├── login.blade.php
│       │   └── register.blade.php
│       ├── components/
│       │   ├── product-card.blade.php
│       │   ├── category-card.blade.php
│       │   ├── banner-slider.blade.php
│       │   ├── cart-icon.blade.php
│       │   ├── alert.blade.php
│       │   └── breadcrumb.blade.php
│       ├── dashboard/
│       │   ├── index.blade.php
│       │   ├── products/
│       │   │   ├── index.blade.php
│       │   │   ├── create.blade.php
│       │   │   └── edit.blade.php
│       │   ├── categories/
│       │   │   ├── index.blade.php
│       │   │   ├── create.blade.php
│       │   │   └── edit.blade.php
│       │   ├── banners/index.blade.php
│       │   ├── orders/index.blade.php
│       │   └── settings/index.blade.php
│       ├── errors/
│       │   ├── 404.blade.php
│       │   └── 500.blade.php
│       ├── layouts/
│       │   ├── app.blade.php             # Public layout
│       │   ├── dashboard.blade.php       # Admin layout
│       │   └── guest.blade.php           # Auth layout
│       └── pages/
│           ├── home.blade.php
│           ├── products/
│           │   ├── index.blade.php
│           │   ├── show.blade.php
│           │   └── search.blade.php
│           ├── categories/show.blade.php
│           ├── cart/index.blade.php
│           └── checkout/
│               ├── index.blade.php
│               └── confirm.blade.php
│
├── routes/
│   ├── web.php                          # Public routes
│   ├── admin.php                        # Admin routes
│   ├── auth.php                         # Auth routes (Laravel Breeze)
│   └── console.php                      # Artisan commands
│
├── storage/
│   ├── app/
│   │   └── public/                      # Uploaded images & files
│   ├── logs/                            # Application logs
│   └── framework/                       # Laravel framework files
│
├── tests/
│   ├── Feature/
│   │   ├── ProductTest.php
│   │   ├── CartTest.php
│   │   ├── OrderTest.php
│   │   └── AuthTest.php
│   └── Unit/
│       ├── CartHelperTest.php
│       └── ProductModelTest.php
│
├── .env.example                         # Environment template
├── .gitignore
├── composer.json
├── package.json
├── tailwind.config.js
├── vite.config.js
├── phpunit.xml
└── README.md                            # This file

```

---

## 🚀 Quick Start

### Prerequisites

Ensure you have the following installed:

- **PHP** 8.3 or higher ([Download](https://www.php.net/downloads))
- **Composer** 2.0+ ([Download](https://getcomposer.org))
- **Node.js** 20+ ([Download](https://nodejs.org))
- **MySQL** 8.0+ ([Download](https://www.mysql.com/downloads/mysql/))
- **Git** ([Download](https://git-scm.com))

### Installation Steps

#### 1. Clone the Repository

```bash
git clone https://github.com/Alshabasy/z-pets-store.git
cd z-pets-store
```

#### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install
```

#### 3. Configure Environment

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

#### 4. Database Setup

Edit `.env` with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=zpets
DB_USERNAME=root
DB_PASSWORD=your_password
```

Then run migrations and seeders:

```bash
php artisan migrate --seed
```

#### 5. Link Storage

```bash
php artisan storage:link
```

This creates a symbolic link from `storage/app/public` to `public/storage` for image access.

#### 6. Build Assets

```bash
# Development build
npm run dev

# Production build
npm run build
```

#### 7. Start Development Server

```bash
# Terminal 1: Start Laravel server
php artisan serve

# Terminal 2: Start Vite dev server
npm run dev
```

Visit `http://localhost:8000` in your browser.

### Default Admin Credentials

After seeding, use these credentials to access the admin dashboard:

```
Email:    admin@zpets.com
Password: password
```

⚠️ **Important:** Change these credentials immediately in production!

---

## ⚙️ Configuration

### Environment Variables

Key configuration variables in `.env`:

```env
# Application
APP_NAME=Z-Pets-Store
APP_ENV=local          # Change to 'production' for deployment
APP_DEBUG=true         # Set to false in production
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=zpets
DB_USERNAME=root
DB_PASSWORD=

# Mail (for notifications)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=
MAIL_PASSWORD=

# WhatsApp Integration
WHATSAPP_PHONE_NUMBER=+201234567890  # Store's WhatsApp number

# File Storage
FILESYSTEM_DISK=public
FILESYSTEM_VISIBILITY_PUBLIC=public

# Cache
CACHE_STORE=file

# Session
SESSION_DRIVER=cookie
SESSION_LIFETIME=120
```

### Tailwind CSS Configuration

Customize brand colors in `tailwind.config.js`:

```javascript
module.exports = {
  theme: {
    extend: {
      colors: {
        brand: {
          green: '#2D6A2D',
          'green-light': '#4CAF50',
          'green-dark': '#1B4D1B',
        },
      },
    },
  },
};
```

---

## 💾 Database Schema

### Users Table
```sql
CREATE TABLE users (
  id BIGINT PRIMARY KEY,
  name VARCHAR(255),
  email VARCHAR(255) UNIQUE,
  email_verified_at TIMESTAMP NULL,
  password VARCHAR(255),
  role ENUM('admin', 'customer') DEFAULT 'customer',
  remember_token VARCHAR(100) NULL,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

### Categories Table
```sql
CREATE TABLE categories (
  id BIGINT PRIMARY KEY,
  name VARCHAR(255),
  slug VARCHAR(255) UNIQUE,
  description TEXT NULL,
  image VARCHAR(255) NULL,
  parent_id BIGINT NULL (self-referencing for subcategories),
  sort_order INT DEFAULT 0,
  is_active BOOLEAN DEFAULT true,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

### Products Table
```sql
CREATE TABLE products (
  id BIGINT PRIMARY KEY,
  name VARCHAR(255),
  slug VARCHAR(255) UNIQUE,
  description TEXT,
  short_description VARCHAR(500) NULL,
  price DECIMAL(10,2),
  sale_price DECIMAL(10,2) NULL,
  category_id BIGINT FK,
  in_stock BOOLEAN DEFAULT true,
  is_featured BOOLEAN DEFAULT false,
  is_active BOOLEAN DEFAULT true,
  sku VARCHAR(100) UNIQUE NULL,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

### Product Images Table
```sql
CREATE TABLE product_images (
  id BIGINT PRIMARY KEY,
  product_id BIGINT FK,
  image_path VARCHAR(255),
  is_primary BOOLEAN DEFAULT false,
  sort_order INT DEFAULT 0,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

### Orders Table
```sql
CREATE TABLE orders (
  id BIGINT PRIMARY KEY,
  customer_name VARCHAR(255),
  customer_email VARCHAR(255),
  customer_phone VARCHAR(20),
  total_price DECIMAL(10,2),
  status ENUM('pending', 'confirmed', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
  notes TEXT NULL,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

### Order Items Table
```sql
CREATE TABLE order_items (
  id BIGINT PRIMARY KEY,
  order_id BIGINT FK,
  product_id BIGINT FK,
  quantity INT,
  unit_price DECIMAL(10,2),
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

### Banners Table
```sql
CREATE TABLE banners (
  id BIGINT PRIMARY KEY,
  title VARCHAR(255),
  image_path VARCHAR(255),
  link VARCHAR(255) NULL,
  is_active BOOLEAN DEFAULT true,
  sort_order INT DEFAULT 0,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

### Settings Table
```sql
CREATE TABLE settings (
  id BIGINT PRIMARY KEY,
  key VARCHAR(255) UNIQUE,
  value TEXT,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

---

## 🔗 API Endpoints

### Public Routes

#### Products
```
GET    /products              - List all products (with pagination)
GET    /products/{slug}       - Show product details
GET    /products/search       - Search products
GET    /categories/{slug}     - Show category with products
```

#### Cart
```
POST   /cart/add              - Add item to cart
POST   /cart/update/{item}    - Update cart item quantity
DELETE /cart/remove/{item}    - Remove item from cart
GET    /cart                  - View cart
```

#### Checkout
```
POST   /checkout              - Process order via WhatsApp
GET    /order/{id}/receipt    - Download order receipt (PDF)
```

### Admin Routes (Requires Authentication & Admin Role)

#### Dashboard
```
GET    /dashboard             - Dashboard overview
```

#### Products
```
GET    /dashboard/products              - List products
GET    /dashboard/products/create       - Create form
POST   /dashboard/products              - Store product
GET    /dashboard/products/{id}/edit    - Edit form
PUT    /dashboard/products/{id}         - Update product
DELETE /dashboard/products/{id}         - Delete product
```

#### Categories
```
GET    /dashboard/categories              - List categories
GET    /dashboard/categories/create       - Create form
POST   /dashboard/categories              - Store category
GET    /dashboard/categories/{id}/edit    - Edit form
PUT    /dashboard/categories/{id}         - Update category
DELETE /dashboard/categories/{id}         - Delete category
```

#### Orders
```
GET    /dashboard/orders              - List orders
GET    /dashboard/orders/{id}         - Show order details
PUT    /dashboard/orders/{id}/status  - Update order status
```

#### Settings
```
GET    /dashboard/settings            - Settings form
PUT    /dashboard/settings            - Update settings
```

---

## 🎛️ Admin Dashboard

### Dashboard Features

1. **Overview Card** - Quick stats (total products, categories, orders, revenue)
2. **Recent Orders** - Latest customer orders with status
3. **Product Management** - CRUD operations for products
4. **Category Management** - Parent/child category hierarchy
5. **Banner Management** - Hero slider content
6. **Order Tracking** - Real-time order status updates
7. **Settings Panel** - Store configuration

### Accessing the Dashboard

1. Log in with admin credentials
2. Click "Dashboard" in the navbar
3. All features protected by `IsAdmin` middleware

---

## 🚀 Deployment

### Railway.app (Recommended)

Railway.app provides the easiest path to production with MySQL hosting and automatic deployments.

#### Setup Steps

1. **Create Railway Account**
   - Visit [railway.app](https://railway.app)
   - Sign up with GitHub

2. **Create New Project**
   - Click "New Project"
   - Select "Deploy from GitHub"
   - Connect your GitHub repository

3. **Add MySQL Database**
   - Click "Add Service"
   - Select "MySQL"
   - Railway creates the database automatically

4. **Add Storage Volume**
   - Create a volume with mount path `/app/storage/app/public`
   - This persists uploaded images

5. **Configure Environment Variables**
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://your-project.up.railway.app
   CACHE_STORE=array
   SESSION_DRIVER=cookie
   SESSION_ENCRYPT=true
   FILESYSTEM_DISK=public
   ```

6. **Deploy**
   - Push code to GitHub
   - Railway automatically builds and deploys
   - View logs in Railway dashboard

#### Production Environment Configuration

```env
# Security
APP_ENV=production
APP_DEBUG=false
APP_KEY=<generated-key>

# Database (provided by Railway)
DB_CONNECTION=mysql
DB_HOST=${{MYSQL_HOST}}
DB_PORT=${{MYSQL_PORT}}
DB_DATABASE=${{MYSQL_DATABASE}}
DB_USERNAME=${{MYSQL_USER}}
DB_PASSWORD=${{MYSQL_PASSWORD}}

# Cache & Session
CACHE_STORE=array
SESSION_DRIVER=cookie
SESSION_ENCRYPT=true

# Mail
MAIL_MAILER=smtp
MAIL_HOST=<your-smtp-host>
MAIL_PORT=465
MAIL_USERNAME=<your-email>
MAIL_PASSWORD=<your-app-password>

# Storage
FILESYSTEM_DISK=public
```

### Manual Deployment

For other hosting providers (AWS, DigitalOcean, etc.):

1. SSH into server
2. Clone repository
3. Install dependencies
4. Configure `.env`
5. Run `php artisan migrate`
6. Run `npm run build`
7. Set web root to `public/`
8. Configure SSL certificate
9. Set up PHP-FPM and Nginx/Apache

---

## 💻 Development

### Development Workflow

```bash
# Start development servers
npm run dev        # Terminal 1: Vite dev server (hot reload)
php artisan serve  # Terminal 2: Laravel server

# Watch logs
php artisan pail   # Terminal 3: Real-time logs

# Tinker REPL
php artisan tinker
```

### Code Style

- Follow [PSR-12](https://www.php-fig.org/psr/psr-12/) for PHP
- Use [Prettier](https://prettier.io/) for JavaScript
- Use [Laravel Pint](https://github.com/laravel/pint) for formatting

```bash
# Format code
./vendor/bin/pint

# Check code
php artisan pint --test
```

### Database Migrations

Create new migration:

```bash
php artisan make:migration create_table_name
php artisan make:migration add_column_to_table_name
```

Run migrations:

```bash
php artisan migrate              # Run all pending
php artisan migrate:rollback     # Rollback last batch
php artisan migrate:refresh      # Reset and re-run all
php artisan migrate:fresh --seed # Fresh install with seeds
```

### Tinker Usage

```bash
# Start interactive shell
php artisan tinker

# Create test product
$product = Product::factory()->create();

# Query products
Product::all();
Product::where('is_active', true)->get();

# Update product
$product->update(['price' => 150]);

# Delete product
$product->delete();
```

---

## 🧪 Testing

### Running Tests

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/ProductTest.php

# Run with coverage
php artisan test --coverage

# Run PestPHP tests
./vendor/bin/pest
```

### Example Test

```php
// tests/Feature/CartTest.php
<?php

use App\Models\Product;
use Tests\TestCase;

class CartTest extends TestCase
{
    public function test_can_add_item_to_cart()
    {
        $product = Product::factory()->create();
        
        $response = $this->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);
        
        $this->assertEquals(session('cart.' . $product->id), 2);
        $response->assertRedirect('/cart');
    }
}
```

### Test Database

Tests use a separate in-memory SQLite database. Configuration in `phpunit.xml`:

```xml
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
```

---

## 🔒 Security

### Key Security Features

- **CSRF Protection** - All forms protected with `@csrf`
- **XSS Prevention** - Blade escaping with `{{ }}` syntax
- **SQL Injection** - Eloquent ORM parameterized queries
- **Authentication** - Laravel Breeze with secure hashing
- **Authorization** - Role-based middleware (`IsAdmin`)
- **Rate Limiting** - Prevent brute force attacks
- **Secure Headers** - Custom middleware for HTTP security headers
- **File Upload Validation** - Extension and MIME type checking
- **Password Hashing** - bcrypt with configurable rounds

### Best Practices

1. **Environment Variables**
   - Never commit `.env` file
   - Use `.env.example` as template
   - Keep secrets in `.env` (file storage) or environment config

2. **Database**
   - Always use parameterized queries (Eloquent)
   - Implement proper access control
   - Regular backups (Railway handles this)

3. **File Uploads**
   ```php
   $request->validate([
       'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
   ]);
   ```

4. **Authentication**
   - Store sensitive data server-side
   - Use HTTPS in production
   - Implement session timeout

5. **API Security**
   - Validate all inputs
   - Sanitize outputs
   - Implement rate limiting

---

## 📊 Performance Optimization

### Caching Strategies

```php
// Cache product listings
$products = Cache::remember('products.all', 3600, function () {
    return Product::with('images')->get();
});

// Invalidate on update
Cache::forget('products.all');
```

### Database Optimization

```php
// Use eager loading to prevent N+1 queries
$products = Product::with('images', 'category')->get();

// Add indexes to frequently queried columns
Schema::table('products', function (Blueprint $table) {
    $table->index('category_id');
    $table->index('slug');
});
```

### Asset Optimization

- Vite automatically minifies CSS and JavaScript
- Images optimized with compression
- Lazy loading for product images
- CDN ready for static assets

---

## 🐛 Troubleshooting

### Common Issues

**Issue:** `Class 'App\Models\Product' not found`
```bash
# Solution: Clear autoloader cache
composer dump-autoload
```

**Issue:** Storage link not working
```bash
# Solution: Re-create storage link
php artisan storage:link
```

**Issue:** Migration fails
```bash
# Solution: Check database connection in .env
php artisan migrate --verbose
```

**Issue:** Assets not loading
```bash
# Solution: Rebuild frontend assets
npm run build
# or
npm run dev
```

**Issue:** White screen of death
```bash
# Solution: Check logs
tail -f storage/logs/laravel.log
php artisan pail
```

---

## 📚 Additional Resources

### Documentation

- [Laravel Docs](https://laravel.com/docs/13.x)
- [Blade Template Docs](https://laravel.com/docs/13.x/blade)
- [Tailwind CSS](https://tailwindcss.com/docs)
- [Laravel Eloquent](https://laravel.com/docs/13.x/eloquent)
- [Vite Documentation](https://vitejs.dev)

### Useful Commands

```bash
# Generate model with migration and factory
php artisan make:model Product -mf

# Generate controller with resourceful routes
php artisan make:controller ProductController --resource

# Create custom Artisan command
php artisan make:command SyncProducts

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Generate API documentation
php artisan scribe:generate
```

---

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. **Fork** the repository
2. **Create** a feature branch (`git checkout -b feature/amazing-feature`)
3. **Commit** your changes (`git commit -m 'Add amazing feature'`)
4. **Push** to the branch (`git push origin feature/amazing-feature`)
5. **Open** a Pull Request

### Contribution Guidelines

- Follow the existing code style (PSR-12)
- Write tests for new features
- Update documentation
- Keep commits atomic and descriptive
- Add meaningful PR descriptions

---

## 📝 License

Z-Pets Store is open-sourced software licensed under the [MIT License](LICENSE).

```
MIT License

Copyright (c) 2024 Amr Alshabasy

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.
```

---

## 💬 Support

Need help? Here are several ways to get support:

- **Issues**: [GitHub Issues](https://github.com/Alshabasy/z-pets-store/issues) - Report bugs and request features
- **Documentation**: Check this README and [Laravel docs](https://laravel.com/docs)
- **Discussions**: [GitHub Discussions](https://github.com/Alshabasy/z-pets-store/discussions) - Ask questions and share ideas
- **Email**: Contact the maintainers

---

## 👨‍💻 Author

**Amr Alshabasy**

- GitHub: [@Alshabasy](https://github.com/Alshabasy)
- LinkedIn: [Connect](https://linkedin.com/in/alshabasy)
- Email: [your-email@example.com]

---

## 🙏 Acknowledgments

- Laravel community for the amazing framework
- Tailwind CSS team for the utility-first CSS framework
- Railway.app for seamless deployment
- All contributors and supporters

---

<div align="center">

**[⬆ back to top](#-z-pets-store)**

Made with ❤️ by Amr Alshabasy

</div>

# Z-Pets Store


### [live Demo](https://z-pets-store-production.up.railway.app/)
<!-- <p align="center">
  <img src="https://coresg-normal.trae.ai/api/ide/v1/text-to-image?prompt=A%20cute%2C%20modern%20pet%20store%20logo%20with%20a%20green%20color%20scheme%20and%20a%20paw%20print&image_size=square" alt="Z-Pets Store Logo" width="200" style="border-radius: 12px;"/>
</p> -->

A full-featured, modern pet store built with **Laravel 13**, **Blade**, **Tailwind CSS v4**, and **MySQL**. Designed for Egypt's pet market, with WhatsApp checkout and a professional admin dashboard.


## Table of Contents
1. [About](#about)
2. [Tech Stack](#tech-stack)
3. [Features](#features)
4. [Quick Start](#quick-start)
5. [Deployment](#deployment)
6. [Project Structure](#project-structure)
7. [Contributing](#contributing)
8. [License](#license)


## About

Z-Pets Store is an e-commerce platform dedicated to pet owners in Egypt. It provides an intuitive, mobile-friendly shopping experience for pet products, with seamless WhatsApp integration for order management. The admin panel makes it easy to manage products, categories, orders, and store settings.


## Tech Stack

### Backend
- [Laravel 13.7](https://laravel.com/docs/13.x) - Modern PHP framework
- [PHP 8.3](https://www.php.net/releases/8.3/en.php) - Server-side language
- [MySQL](https://www.mysql.com/) - Relational database
- [PestPHP](https://pestphp.com/) - Testing framework
- [Barryvdh Laravel DomPDF](https://github.com/barryvdh/laravel-dompdf) - PDF receipt generation

### Frontend
- [Tailwind CSS v4](https://tailwindcss.com/docs/v4) - Utility-first CSS framework
- [Blade Templates](https://laravel.com/docs/13.x/blade) - Laravel templating engine
- [Vite 6](https://vitejs.dev/) - Fast frontend build tool
- [Font Awesome](https://fontawesome.com/) - Icon library
- [Google Fonts (Plus Jakarta Sans)](https://fonts.google.com/specimen/Plus+Jakarta+Sans) - Typography

### DevOps & Tools
- [Railway.app](https://railway.app/) - Production deployment platform
- [GitHub Actions](https://docs.github.com/en/actions) - CI/CD
- [Laravel Tinker](https://laravel.com/docs/13.x/artisan#tinker) - Interactive shell
- [Laravel Pail](https://laravel.com/docs/13.x/pail) - Real-time log viewer


## Features

### Public Store
- 🛒 **Product Listings** - Browse by categories, search, filter
- 📸 **Product Images** - Multiple images with a main featured image
- 🛒 **Cart System** - Session-based cart with update/remove functionality
- 📱 **WhatsApp Checkout** - Order via WhatsApp with auto-generated message
- 📄 **PDF Receipts** - Downloadable order receipts
- 🔍 **SEO Optimized** - Meta tags, sitemap, canonical URLs
- 📱 **Responsive Design** - Works perfectly on mobile, tablet, and desktop

### Admin Dashboard
- 📊 **Dashboard Overview** - Key metrics and recent orders
- 📦 **Product Management** - Create, edit, delete products
- 📂 **Category Management** - Parent/child categories
- 🖼️ **Banner Management** - Hero slider banners
- 📋 **Order Management** - View, update order status
- ⚙️ **Store Settings** - Customize WhatsApp, address, social links, etc.
- 🔐 **Role-Based Access** - Only admins can access the dashboard


## Quick Start

### Prerequisites
- PHP 8.3 or higher
- Composer 2
- Node.js 20 or higher
- MySQL 8 or higher

### Installation

1. **Clone or download the project**
   ```bash
   cd /path/to/zpets
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Setup environment file**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure database**
   Update the following in your `.env`:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=zpets
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Migrate and seed**
   ```bash
   php artisan migrate --seed
   ```

6. **Link storage**
   ```bash
   php artisan storage:link
   ```

7. **Run the app**
   ```bash
   # For development (starts Vite dev server and Laravel server)
   npm run dev

   # In another terminal
   php artisan serve
   ```

8. **Visit your app**
   Open your browser and go to `http://localhost:8000`

### Default Admin Account
```
Email: admin@zpets.com
Password: password
```

## Deployment

### Railway.app (Recommended)

1. **Sign up for Railway.app** and create a new project.
2. **Add a MySQL database** to your project.
3. **Add a Volume** with mount path `/app/storage/app/public` to persist uploaded images.
4. **Set environment variables** in Railway (see `.env.example` for all required vars).
5. **Push your code to GitHub** and connect it to Railway.
6. **Deploy!** Railway will automatically build and launch the app using `railway.json` and `nixpacks.toml`.

**Important Env Vars for Railway:**
```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-railway-url.up.railway.app
CACHE_STORE=array
SESSION_DRIVER=cookie
SESSION_ENCRYPT=true
FILESYSTEM_DISK=public
```


## Project Structure

```
zpets/
├── app/
│   ├── Actions/               # Reusable application actions
│   ├── Console/
│   │   └── Commands/          # Artisan commands
│   ├── Helpers/               # Helper classes (Cart)
│   ├── Http/
│   │   ├── Controllers/       # Public & Admin controllers
│   │   ├── Middleware/        # Custom middleware (IsAdmin, SecurityHeaders)
│   │   ├── Requests/          # Form request validation
│   │   └── View/Composers/    # View composers (Navigation)
│   ├── Models/                # Eloquent models
│   ├── Providers/             # Service providers
│   └── View/Components/       # Blade components
├── bootstrap/                 # Laravel bootstrap files
├── config/                    # Configuration files
├── database/
│   ├── factories/             # Model factories
│   ├── migrations/            # Database migrations
│   └── seeders/               # Database seeders
├── public/                    # Public assets (index.php, build files, storage link)
├── resources/
│   ├── css/
│   │   └── app.css            # Main Tailwind CSS file
│   ├── js/
│   │   └── app.js             # Main JS file
│   └── views/
│       ├── auth/              # Authentication views
│       ├── components/        # Reusable Blade components
│       ├── dashboard/         # Admin dashboard views
│       ├── errors/            # Error pages
│       ├── layouts/           # Master layouts (app, dashboard, guest)
│       ├── pages/             # Public views (home, products, cart, etc.)
│       └── pdf/               # PDF receipt views
├── routes/
│   ├── admin.php              # Admin-only routes
│   ├── auth.php               # Authentication routes (Breeze)
│   ├── console.php            # Artisan command routes
│   └── web.php                # Public web routes
├── storage/                   # Storage directory (logs, cache, uploads)
├── tests/                     # PestPHP tests (Feature & Unit)
└── ...                        # Config files (composer.json, package.json, etc.)
```


## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.


## License

Z-Pets Store is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

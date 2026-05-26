# Z-Pets Store — Laravel Full-Stack Project

> **AI Agent Instructions**: Read this file completely before writing any code.
> Follow every convention here. Do not deviate without being asked.

---

## 1. Project Identity

| Field | Value |
|---|---|
| **Project name** | Z-Pets Store |
| **Framework** | Laravel 11 (latest stable) |
| **Stack** | Full-stack — Blade + TailwindCSS (no React/Vue) |
| **Database** | MySQL |
| **Auth** | Laravel Breeze (Blade variant) |
| **Image storage** | Laravel Storage (public disk, `storage/app/public`) |
| **Language** | English only |
| **Purpose** | Online pet store for Egypt — customers browse and order via WhatsApp |

---

## 2. Brand & Design System

### Color Palette
```
Primary Green   : #2D6A2D  (buttons, accents, active states)
Light Green     : #4CAF50  (hover states, badges)
Dark Green      : #1B4D1B  (navbar background, footer)
White           : #FFFFFF  (page backgrounds, cards)
Off-White       : #F8FAF8  (section backgrounds)
Black           : #1A1A1A  (body text, headings)
Mid Gray        : #6B7280  (secondary text, labels)
Border Gray     : #E5E7EB  (dividers, input borders)
Danger Red      : #DC2626  (delete buttons, errors, out-of-stock)
Warning Amber   : #D97706  (sale badges, warnings)
```

### Typography
- **Font**: `Inter` from Google Fonts (import in app layout)
- **Headings**: `font-bold`, dark green or black
- **Body**: `font-normal`, black `#1A1A1A`, `text-base`
- **Labels / meta**: `text-sm`, gray

### TailwindCSS Config (extend in `tailwind.config.js`)
```js
colors: {
  brand: {
    green:      '#2D6A2D',
    'green-light': '#4CAF50',
    'green-dark':  '#1B4D1B',
  }
}
```

### Design Principles
- Clean, spacious, modern — inspired by petsegypt.com
- Cards with subtle shadows: `shadow-sm hover:shadow-md`
- Rounded corners: `rounded-xl` for cards, `rounded-lg` for buttons
- Full mobile responsiveness — mobile-first TailwindCSS classes
- Smooth transitions: `transition-all duration-200`
- Product images: always `object-cover` with a fixed aspect ratio (4:3)

---

## 3. Directory & File Conventions

```
app/
  Http/
    Controllers/
      Public/          ← customer-facing controllers
        HomeController.php
        ProductController.php
        CategoryController.php
        CartController.php
        CheckoutController.php
        SearchController.php
      Dashboard/       ← seller admin controllers
        DashboardController.php
        ProductController.php   (Admin\ProductController)
        CategoryController.php  (Admin\CategoryController)
        BannerController.php
        OrderController.php
        SettingController.php
    Middleware/
      IsAdmin.php      ← protects all /dashboard routes
    Requests/
      StoreProductRequest.php
      UpdateProductRequest.php
      StoreCategoryRequest.php
      StoreOrderRequest.php

  Models/
    User.php
    Product.php
    Category.php
    ProductImage.php
    Tag.php
    Order.php
    OrderItem.php
    Banner.php
    Setting.php

  Helpers/
    Cart.php           ← Cart session logic helper class

resources/
  views/
    layouts/
      app.blade.php         ← public layout (navbar + footer)
      dashboard.blade.php   ← seller dashboard layout (sidebar)
    components/
      product-card.blade.php
      category-card.blade.php
      banner-slider.blade.php
      cart-icon.blade.php
      alert.blade.php
      breadcrumb.blade.php
    pages/                  ← public pages
      home.blade.php
      products/
        index.blade.php
        show.blade.php
        search.blade.php
      categories/
        show.blade.php
      cart/
        index.blade.php
      checkout/
        index.blade.php
        confirm.blade.php
    dashboard/              ← seller pages
      index.blade.php
      products/
        index.blade.php
        create.blade.php
        edit.blade.php
      categories/
        index.blade.php
        create.blade.php
        edit.blade.php
      banners/
        index.blade.php
      orders/
        index.blade.php
      settings/
        index.blade.php

routes/
  web.php     ← all public routes
  admin.php   ← all dashboard routes (imported in RouteServiceProvider)

database/
  migrations/
  seeders/
    DatabaseSeeder.php
    AdminUserSeeder.php
    CategorySeeder.php
    ProductSeeder.php
    TagSeeder.php
    BannerSeeder.php
    SettingSeeder.php
```

---

## 4. Database Schema (Complete)

### `users`
```
id, name, email, email_verified_at, password, role (enum: admin|customer, default: customer),
remember_token, timestamps
```

### `categories`
```
id, name, slug (unique), description (nullable), image (nullable),
parent_id (FK → categories.id, nullable, for subcategories),
sort_order (int, default: 0), is_active (boolean, default: true), timestamps
```

### `products`
```
id, name, slug (unique), description (text), short_description (nullable),
price (decimal 10,2), sale_price (decimal 10,2, nullable),
category_id (FK → categories.id), in_stock (boolean, default: true),
is_featured (boolean, default: false), is_active (boolean, default: true),
sort_order (int, default: 0), timestamps
```

### `product_images`
```
id, product_id (FK → products.id, cascade delete),
path (string — storage path), is_main (boolean, default: false),
sort_order (int, default: 0), timestamps
```

### `tags`
```
id, name, slug, color (hex string, default: '#2D6A2D'), timestamps
```

### `product_tag` (pivot)
```
product_id (FK → products.id), tag_id (FK → tags.id)
Primary key: [product_id, tag_id]
```

### `banners`
```
id, title (nullable), image (string), link_url (nullable),
sort_order (int, default: 0), is_active (boolean, default: true), timestamps
```

### `orders`
```
id, customer_name (string), customer_phone (string),
items_snapshot (JSON — array of {name, qty, price}),
subtotal (decimal 10,2), total (decimal 10,2),
notes (text, nullable), status (enum: new|seen|confirmed|completed, default: new),
whatsapp_sent_at (timestamp, nullable), timestamps
```

### `settings`
```
id, key (string, unique), value (text, nullable), timestamps

Default rows to seed:
  whatsapp_number     → '201XXXXXXXXX'
  store_name          → 'Z-Pets Store'
  store_tagline       → 'Egypt\'s Favourite Pet Store'
  free_delivery_above → '2000'
  delivery_note       → 'Free delivery on orders over 2000 EGP'
  shop_address        — physical store address, shown in footer
  social_facebook     — full Facebook page URL
  social_instagram    — full Instagram profile URL
  social_tiktok       — full TikTok profile URL
```

---

## 5. Model Relationships (Summary)

```php
// Category
belongsTo(Category::class, 'parent_id')     // parent category
hasMany(Category::class, 'parent_id')        // subcategories
hasMany(Product::class)

// Product
belongsTo(Category::class)
hasMany(ProductImage::class)
hasOne(ProductImage::class)->where('is_main', true)  // mainImage()
belongsToMany(Tag::class)

// ProductImage
belongsTo(Product::class)

// Tag
belongsToMany(Product::class)

// Order
// standalone — no FK relationships (snapshot model)
```

---

## 6. Routing Structure

### Public Routes (`routes/web.php`)
```
GET  /                          → HomeController@index
GET  /products                  → Public\ProductController@index
GET  /products/{slug}           → Public\ProductController@show
GET  /category/{slug}           → Public\CategoryController@show
GET  /search                    → SearchController@index
GET  /cart                      → CartController@index
POST /cart/add                  → CartController@add
POST /cart/update               → CartController@update
POST /cart/remove               → CartController@remove
POST /cart/clear                → CartController@clear
GET  /checkout                  → CheckoutController@index
POST /checkout                  → CheckoutController@store
GET  /checkout/confirm          → CheckoutController@confirm
```

### Dashboard Routes (`routes/admin.php`) — middleware: auth, isAdmin
```
GET  /dashboard                          → DashboardController@index
GET  /dashboard/products                 → Dashboard\ProductController@index
GET  /dashboard/products/create          → Dashboard\ProductController@create
POST /dashboard/products                 → Dashboard\ProductController@store
GET  /dashboard/products/{id}/edit       → Dashboard\ProductController@edit
PUT  /dashboard/products/{id}            → Dashboard\ProductController@update
DELETE /dashboard/products/{id}          → Dashboard\ProductController@destroy

GET  /dashboard/categories               → Dashboard\CategoryController@index
GET  /dashboard/categories/create        → Dashboard\CategoryController@create
POST /dashboard/categories               → Dashboard\CategoryController@store
GET  /dashboard/categories/{id}/edit     → Dashboard\CategoryController@edit
PUT  /dashboard/categories/{id}          → Dashboard\CategoryController@update
DELETE /dashboard/categories/{id}        → Dashboard\CategoryController@destroy

GET  /dashboard/banners                  → BannerController@index
POST /dashboard/banners                  → BannerController@store
PUT  /dashboard/banners/{id}             → BannerController@update
DELETE /dashboard/banners/{id}           → BannerController@destroy

GET  /dashboard/orders                   → OrderController@index
PUT  /dashboard/orders/{id}/status       → OrderController@updateStatus
DELETE /dashboard/orders/{id}             → OrderController@destroy

GET  /dashboard/settings                 → SettingController@index
POST /dashboard/settings                 → SettingController@update
```

---

## 7. Cart System

The cart is **session-based** — no database table, no login required.

### Cart Session Structure
```php
session('cart') = [
  'product_slug' => [
    'id'       => 1,
    'name'     => 'Royal Canin Adult Cat',
    'price'    => 350.00,
    'quantity' => 2,
    'image'    => 'storage/products/rc-adult.jpg',
    'slug'     => 'royal-canin-adult-cat',
  ],
  ...
]
```

### `app/Helpers/Cart.php` must implement:
- `Cart::add(Product $product, int $qty = 1)`
- `Cart::remove(string $slug)`
- `Cart::update(string $slug, int $qty)`
- `Cart::clear()`
- `Cart::items(): array`
- `Cart::count(): int`
- `Cart::subtotal(): float`
- `Cart::total(): float` (same as subtotal — no tax in this version)

Register in `config/app.php` aliases as `'Cart' => App\Helpers\Cart::class`

---

## 8. WhatsApp Order Flow

### How It Works
1. Customer fills cart → visits `/checkout`
2. Checkout form collects: `customer_name`, `customer_phone`, `notes` (optional)
3. On form submit (`POST /checkout`):
   a. Validate form fields
   b. Build cart items summary string
   c. Save order to `orders` table (status: new)
   d. Build WhatsApp URL:
      ```
      https://wa.me/{whatsapp_number}?text={urlencoded_message}
      ```
   e. Message format:
      ```
      🐾 New Order — Z-Pets Store

      Customer: {name}
      Phone: {phone}

      Items:
      • {qty}x {product name} — EGP {price}
      • {qty}x {product name} — EGP {price}

      Total: EGP {total}

      Notes: {notes or 'None'}
      ```
   f. Clear cart session
   g. Redirect to `/checkout/confirm` with success message

### WhatsApp Number Source
Always read from `settings` table: `Setting::getValue('whatsapp_number')`

### `Setting::getValue($key)` helper method
```php
public static function getValue(string $key, $default = null)
{
    return static::where('key', $key)->value('value') ?? $default;
}
```

---

## 9. Image Upload Rules

- Use Laravel Storage public disk: `Storage::disk('public')`
- Store products in: `products/{product_id}/`
- Store categories in: `categories/`
- Store banners in: `banners/`
- Run `php artisan storage:link` after setup
- Always validate: `mimes:jpeg,jpg,png,webp|max:2048`
- On product create: first uploaded image is automatically set as `is_main = true`
- On product delete: delete all associated images from storage
- In views: use `asset(Storage::url($path))` or `asset('storage/' . $path)`

---

## 10. Dashboard (Seller Panel)

### Layout (`layouts/dashboard.blade.php`)
- Fixed left sidebar (collapsible on mobile)
- Sidebar links with active state highlighting
- Top bar: store name, seller name, logout button
- Main content area with breadcrumb

### Sidebar Navigation Items
```
Dashboard (overview)
├── Products
│   ├── All Products
│   └── Add Product
├── Categories
│   ├── All Categories
│   └── Add Category
├── Banners
├── Orders
└── Settings
```

### Dashboard Index Cards (overview)
```
Total Products  |  Total Categories  |  New Orders  |  Total Orders
```
Plus a "Recent Orders" table showing the last 10 orders.

### Product Form Fields
```
Name*               → text input (slug auto-generated from name via JS)
Slug*               → text input (auto-filled, editable)
Category*           → select (all active categories with parent labels)
Short Description   → textarea (1–2 lines, shown on product cards)
Full Description*   → textarea (shown on product detail page)
Price*              → number input (EGP)
Sale Price          → number input (leave empty = no sale)
Images*             → multiple file input (first = main image)
Stock Status        → toggle: In Stock / Out of Stock
Featured            → checkbox (shows on homepage featured section)
Active              → checkbox (hides from public if unchecked)
Tags                → multi-select checkboxes
```

### Category Form Fields
```
Name*            → text
Slug*            → text (auto-generated)
Parent Category  → select (nullable — for subcategories)
Description      → textarea
Image            → file input
Sort Order       → number
Active           → checkbox
```

---

## 11. Public Frontend Pages

### Homepage (`/`)
Sections in order:
1. Hero banner slider — from `banners` table (active only, sorted by sort_order)
2. Pet category quick-links — icon cards for main categories (parent_id IS NULL)
3. Featured products grid — `is_featured = true`, `is_active = true`, max 8
4. "New Arrivals" strip — latest 8 products by `created_at`
5. Promotional banner (static or from settings)
6. "Shop by Pet Type" — large image cards per main category
7. Footer

### Product Listing Page (`/products`, `/category/{slug}`)
- Filter sidebar: price range, subcategories, tags, in-stock only toggle
- Sort dropdown: newest, price low→high, price high→low, name A-Z
- Pagination: 16 products per page
- Breadcrumb: Home › Category › Subcategory
- Out-of-stock products shown last, with badge

### Product Detail Page (`/products/{slug}`)
- Image gallery (main + thumbnails)
- Name, price, sale price (with % off badge if on sale)
- Short description
- Full description tab
- Add to cart button (disabled if out of stock)
- Tags displayed as badges
- Related products (same category, max 4)
- Breadcrumb

### Search Page (`/search?q=...`)
- Query from `?q=` param
- Search: `products.name`, `products.description`, `categories.name`
- Results grid (same product card component)
- "No results" state with suggestion

### Cart Page (`/cart`)
- Line items: image, name, price, quantity stepper, remove button
- Order summary sidebar: subtotal, total, "Proceed to Order" button
- Empty cart state with CTA to homepage

### Checkout Page (`/checkout`)
- Form: Customer Name*, Customer Phone*, Notes (optional)
- Order summary (read-only cart items)
- "Send Order via WhatsApp" button (green, with WhatsApp icon)
- Note: "We will contact you to confirm delivery"

### Confirmation Page (`/checkout/confirm`)
- Success message with paw print icon
- Order summary recap
- WhatsApp link (in case redirect failed)
- "Continue Shopping" button

---

## 12. Blade Components

Create these as reusable components:

### `<x-product-card :product="$product" />`
- Product image (main image or placeholder)
- Category name (small, green)
- Product name
- Price + sale price (strikethrough if on sale)
- Sale % badge (amber)
- "Out of Stock" badge (red, if `in_stock = false`)
- "Add to Cart" button (AJAX via fetch, updates cart count in navbar)

### `<x-category-card :category="$category" />`
- Category image with green overlay on hover
- Category name

### `<x-banner-slider :banners="$banners" />`
- Auto-playing slider using Alpine.js (included with Breeze) or plain JS
- Dots navigation
- Responsive: full-width images

### `<x-alert type="success|error|warning" :message="$message" />`
- Dismissible flash message
- Color-coded by type

### `<x-breadcrumb :items="$items" />`
- `$items` = array of `['label' => '', 'url' => '']`
- Last item is current (no link)

---

## 13. Validation Rules

### Product (StoreProductRequest / UpdateProductRequest)
```php
'name'              => 'required|string|max:255',
'slug'              => 'required|string|unique:products,slug,'.$id,
'category_id'       => 'required|exists:categories,id',
'description'       => 'required|string',
'short_description' => 'nullable|string|max:500',
'price'             => 'required|numeric|min:0',
'sale_price'        => 'nullable|numeric|lt:price',
'in_stock'          => 'boolean',
'is_featured'       => 'boolean',
'is_active'         => 'boolean',
'images'            => 'nullable|array',
'images.*'          => 'image|mimes:jpeg,jpg,png,webp|max:2048',
'tags'              => 'nullable|array',
'tags.*'            => 'exists:tags,id',
```

### Checkout (StoreOrderRequest)
```php
'customer_name'  => 'required|string|max:255',
'customer_phone' => 'required|string|max:20',
'notes'          => 'nullable|string|max:1000',
```

### Dashboard — Store Settings (`SettingController@update`)
```php
'store_name'          => 'required|string|max:255',
'store_tagline'       => 'nullable|string|max:255',
'whatsapp_number'     => 'required|string|max:20',
'free_delivery_above' => 'nullable|numeric|min:0',
'delivery_note'       => 'nullable|string|max:500',
'shop_address'        => 'nullable|string|max:500',
'social_facebook'     => 'nullable|url|max:255',
'social_instagram'    => 'nullable|url|max:255',
'social_tiktok'       => 'nullable|url|max:255',
```

---

## 14. Security Requirements

- All dashboard routes protected by `auth` + `IsAdmin` middleware
- `IsAdmin` middleware: checks `auth()->user()->role === 'admin'`, aborts 403 otherwise
- All forms include `@csrf`
- All user input sanitized — use Laravel's built-in escaping (`{{ }}` not `{!! !!}`)
- Image uploads: validate MIME type server-side (not just extension)
- Slugs: always auto-generate using `Str::slug()`, never trust user input directly
- Product delete: soft-confirm with JavaScript `confirm()` dialog before submitting

---

## 15. SEO Basics

- Each page sets `<title>` dynamically via `@section('title', '...')`
- Product pages: `<meta name="description">` from `short_description`
- Category pages: `<meta name="description">` from `description`
- All product images have meaningful `alt` attributes (product name)
- Canonical URLs on product and category pages
- `<link rel="canonical" href="{{ url()->current() }}">`

---

## 16. Seeder Data (for development)

### Admin User
```
name:     Z-Pets Admin
email:    admin@zpets.com
password: password
role:     admin
```

### Categories (main)
```
Dogs, Cats, Birds, Small Pets, Fish & Aquatic
```

### Subcategories (sample)
```
Dogs: Food, Accessories, Grooming, Health
Cats: Food, Litter, Accessories, Grooming
```

### Tags
```
New Arrival (#2D6A2D), Sale (#D97706), Popular (#1B4D1B), Staff Pick (#185FA5)
```

### Products (min 20 — 4 per main category)
```
Each with: realistic name, price (50–1000 EGP), is_featured on 6 of them,
sale_price on 4 of them, placeholder image path.
```

### Settings
```
whatsapp_number     → '201000000000'
store_name          → 'Z-Pets Store'
store_tagline       → 'Egypt\'s Favourite Pet Store'
free_delivery_above → '2000'
delivery_note       → 'Free delivery on orders over 2000 EGP (Cairo & Giza)'
shop_address        → '15 El-Nile Street, Maadi, Cairo, Egypt'
social_facebook     → ''
social_instagram    → ''
social_tiktok       → ''
```

---

## 17. Artisan Commands Cheatsheet

```bash
# Fresh install
composer install
cp .env.example .env
php artisan key:generate

# Database
php artisan migrate:fresh --seed

# Storage
php artisan storage:link

# Run dev server
php artisan serve
npm run dev

# Make new items
php artisan make:model ModelName -m        # model + migration
php artisan make:controller Name --resource
php artisan make:request Name
php artisan make:middleware Name
php artisan make:seeder Name

# Create new admin account (secure — terminal only)
php artisan admin:create
```

---

## 18. Coding Standards

- Controllers: thin — delegate logic to models or helper classes
- Use resource controllers where possible (`--resource`)
- Use named routes: `route('products.show', $product->slug)`
- Flash messages: `session()->flash('success', '...')` and `session()->flash('error', '...')`
- Always eager-load relationships to avoid N+1: `Product::with(['category', 'images', 'tags'])`
- Paginate all listing pages: `->paginate(16)`
- Use `$product->mainImage->path ?? 'images/placeholder.jpg'` pattern for safe image access
- No raw SQL — use Eloquent exclusively
- All money values stored as `decimal(10,2)` — display with `number_format($price, 2)`

---

## 19. Environment Variables Needed (`.env`)

```env
APP_NAME="Z-Pets Store"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=zpets
DB_USERNAME=root
DB_PASSWORD=

FILESYSTEM_DISK=public
```

---

## 20. Do Not Do (Agent Restrictions)

- Do NOT use Vue.js, React, or Inertia.js — Blade + Alpine.js only
- Do NOT use Livewire — keep it simple with standard form submissions
- Do NOT use payment gateways — WhatsApp is the checkout method
- Do NOT create customer login/registration — no customer accounts needed
- Do NOT use API routes — everything is web routes returning Blade views
- Do NOT hardcode the WhatsApp number — always read from `settings` table
- Do NOT skip `@csrf` on any form
- Do NOT use `{!! !!}` unless rendering known-safe HTML (e.g. nl2br on description)
- Do NOT leave `dd()` or `dump()` in production code

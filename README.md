# PHP_Laravel12_Cache_Query

## Introduction

PHP_Laravel12_Cache_Query is a Laravel 12-based web application designed to demonstrate efficient database query caching using Laravel’s built-in caching system and/or advanced caching techniques.

The project focuses on improving application performance by reducing repetitive database queries and storing frequently accessed data in cache. This significantly enhances response time and reduces database load, making the application scalable and efficient.

The system follows modern Laravel architecture (MVC) and provides a clean and professional UI to visualize cache behavior in real time.

---

## Project Overview

This project demonstrates a real-world implementation of caching in Laravel by building a simple Product Management System with caching support.

It includes features to:
- Store and retrieve product data
- Cache query results to improve performance
- Automatically clear cache when data changes
- Display whether data is fetched from cache or database

The application helps understand:
- How Laravel caching works
- How to optimize database queries
- How to implement performance improvements in web applications

This project is ideal for learning:
- Backend optimization techniques
- Laravel best practices
- Real-world caching strategies used in production systems

---

## Tech Stack

* Laravel 12
* PHP 8+
* MySQL
* Blade Template Engine
* Laravel Cache (File / Database / Redis)

---

## Project Setup (Step-by-Step)

## Step 1: Create Laravel Project

```bash
composer create-project laravel/laravel PHP_Laravel12_Cache_Query "12.*"
cd PHP_Laravel12_Cache_Query
```

---

## Step 2: Configure Environment

Update `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cache_query_db
DB_USERNAME=root
DB_PASSWORD=

CACHE_STORE=file
```

Run Migration Command:

```bash
php artisan migrate
```

---

## Step 3: Install CacheQuery Package

Run:

```bash
composer require laragear/cache-query
```

---

## Step 4: Publish Config 

```bash
php artisan vendor:publish --provider="Laragear\CacheQuery\CacheQueryServiceProvider" --tag="config"
```

Open:

config/cache-query.php

Update:

```php
 'store' => env('CACHE_STORE'),
 'commutative' => true,
```

---

## Step 5: Create Model & Migration

```bash
php artisan make:model Product -m
```

### Migration File

File: `database/migrations/xxxx_create_products_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
```

Run:

```bash
php artisan migrate
```

### Model

File: `app/Models/Product.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'price'];
}
```

---

## Step 6: Seeder (Optional) 

```bash
php artisan make:seeder ProductSeeder
```

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        Product::create(['name' => 'Laptop', 'price' => 50000]);
        Product::create(['name' => 'Mobile', 'price' => 20000]);
        Product::create(['name' => 'Headphones', 'price' => 3000]);
    }
}
```

Run:

```bash
php artisan db:seed --class=ProductSeeder
```

---

## Step 7: Create Controller

```bash
php artisan make:controller ProductController
```

### Controller File

File: `app/Http/Controllers/ProductController.php`

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    // Show products (with cache)
    public function index()
    {
        // Check if data is from cache
        $fromCache = Cache::has('products_list');

        // Get from cache or database
        $products = Cache::remember('products_list', 300, function () {
            return Product::all();
        });

        return view('products.index', compact('products', 'fromCache'));
    }

    // Store product
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'price' => 'required|numeric'
        ]);

        // Save to database
        Product::create([
            'name'  => $request->name,
            'price' => $request->price
        ]);

        // Clear cache after insert
        Cache::forget('products_list');

        return redirect()->back()->with('success', 'Product added successfully!');
    }
}
```

---

## Step 8: Define Routes

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

// Show products (with cache)
Route::get('/', [ProductController::class, 'index']);

// Add product
Route::post('/add-product', [ProductController::class, 'store']);
```

---

## Step 9: Create Blade View

File: `resources/views/products/index.blade.php`

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Dashboard</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #eef2f7, #f8fbff);
            padding: 40px;
        }

        .container {
            max-width: 900px;
            margin: auto;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #2c3e50;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.05);
        }

        .form-group {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        input {
            flex: 1;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ddd;
            outline: none;
            transition: 0.3s;
        }

        input:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0,123,255,0.2);
        }

        button {
            padding: 10px 18px;
            border: none;
            border-radius: 8px;
            background: #007bff;
            color: white;
            font-weight: 500;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #0056b3;
        }

        .product-card {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-radius: 10px;
            background: #f9fbff;
            margin-bottom: 10px;
            border: 1px solid #eaeaea;
            transition: 0.3s;
        }

        .product-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .price {
            font-weight: 600;
            color: #28a745;
        }

        .success {
            background: #e6ffed;
            color: #1e7e34;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .empty {
            text-align: center;
            color: #888;
            padding: 20px;
        }

        .footer-note {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 14px;
        }

        /* 🔥 NEW: Cache Status Box */
        .cache-box {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-weight: 500;
        }

        .cache {
            background: #e6f7ff;
            color: #007bff;
        }

        .database {
            background: #fff3cd;
            color: #856404;
        }
    </style>
</head>
<body>

<div class="container">

    <h1>🚀 Product Dashboard</h1>

    <!-- Success Message -->
    @if(session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Cache Status -->
    @if(isset($fromCache))
        @if($fromCache)
            <div class="cache-box cache">
                ⚡ Data loaded from CACHE
            </div>
        @else
            <div class="cache-box database">
                🔥 Data loaded from DATABASE
            </div>
        @endif
    @endif

    <!-- Add Product -->
    <div class="card">
        <h3 style="margin-bottom: 15px;">Add New Product</h3>

        <form method="POST" action="/add-product">
            @csrf

            <div class="form-group">
                <input type="text" name="name" placeholder="Product Name" required>
                <input type="number" name="price" placeholder="Price (₹)" required>
                <button type="submit">Add</button>
            </div>
        </form>
    </div>

    <!-- Product List -->
    <div class="card">
        <h3 style="margin-bottom: 15px;">Product List (CacheQuery)</h3>

        @forelse($products as $product)
            <div class="product-card">
                <div>
                    <strong>{{ $product->name }}</strong>
                </div>
                <div class="price">
                    ₹{{ number_format($product->price) }}
                </div>
            </div>
        @empty
            <div class="empty">
                No products available
            </div>
        @endforelse
    </div>

    <div class="footer-note">
        ⚡ Cached data for 5 minutes for better performance
    </div>

</div>

</body>
</html>
```
---

## Step 10: Start the Project

Run:

```bash
php artisan serve
```
Open in browser:

```bash
http://127.0.0.1:8000
```

---

## Step 11: Test Cache Working

1. Open homepage
   → Data from DATABASE

2. Refresh page
   → Data from CACHE

3. Add product
   → Cache cleared

4. Refresh again
   → Cache again

---

## Output

<img src="screenshots/Screenshot 2026-03-24 174001.png" width="1000">

<img src="screenshots/Screenshot 2026-03-24 174019.png" width="1000">

<img src="screenshots/Screenshot 2026-03-24 174041.png" width="1000">


---

## Project Structure

```
PHP_Laravel12_Cache_Query/
│
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── ProductController.php   # Handles logic (cache + CRUD)
│   │
│   ├── Models/
│   │   └── Product.php                # Product model
│
├── bootstrap/
│   └── app.php                        # Laravel bootstrap (auto package loading)
│
├── config/
│   ├── app.php
│   ├── cache.php                      # Cache configuration
│   ├── cache-query.php  
│   └── database.php
│
├── database/
│   ├── migrations/
│   │   └── xxxx_create_products_table.php   # Products table
│   │
│   ├── seeders/
│   │   └── ProductSeeder.php          # Optional sample data
│
├── public/
│   └── index.php                      # Entry point
│
├── resources/
│   └── views/
│       └── products/
│           └── index.blade.php        # UI (dashboard + cache status)
│
├── routes/
│   └── web.php                        # Routes (/, add-product)
│
├── storage/
│   ├── framework/
│   │   ├── cache/                     # Cached data stored here (if file driver)
│   │   └── sessions/
│
├── .env                               # Environment config (DB + CACHE)
├── artisan                            # Laravel CLI
├── composer.json
└── package.json
```

---

Your PHP_Laravel12_Cache_Query Project is now ready!



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
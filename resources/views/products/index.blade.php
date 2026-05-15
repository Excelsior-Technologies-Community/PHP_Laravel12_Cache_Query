<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cache Query Dashboard</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            background: linear-gradient(to right, #0f172a, #1e293b);
        }

        .glass {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>

<body class="min-h-screen text-white">

    <div class="max-w-6xl mx-auto py-10 px-5">

        <!-- Heading -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-4xl font-bold">
                    🚀 Cache Query Dashboard
                </h1>

                <p class="text-slate-300 mt-2">
                    Laravel 12 + Cache Query Package
                </p>
            </div>

            <!-- Cache Status -->
            @if($fromCache)
                <div class="bg-green-500/20 text-green-300 px-4 py-2 rounded-xl">
                    ⚡ Loaded From Cache
                </div>
            @else
                <div class="bg-yellow-500/20 text-yellow-300 px-4 py-2 rounded-xl">
                    🔥 Loaded From Database
                </div>
            @endif
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="bg-green-500/20 border border-green-500 text-green-300 p-4 rounded-xl mb-5">
                {{ session('success') }}
            </div>
        @endif

        @if(session('delete'))
            <div class="bg-red-500/20 border border-red-500 text-red-300 p-4 rounded-xl mb-5">
                {{ session('delete') }}
            </div>
        @endif

        <!-- Dashboard Cards -->
        <div class="grid md:grid-cols-3 gap-5 mb-8">

            <div class="glass p-6 rounded-2xl">
                <h2 class="text-slate-300 text-sm mb-2">
                    Total Products
                </h2>

                <h1 class="text-3xl font-bold">
                    {{ $products->total() }}
                </h1>
            </div>

            <div class="glass p-6 rounded-2xl">
                <h2 class="text-slate-300 text-sm mb-2">
                    Cache Time
                </h2>

                <h1 class="text-3xl font-bold">
                    5 Min
                </h1>
            </div>

            <div class="glass p-6 rounded-2xl">
                <h2 class="text-slate-300 text-sm mb-2">
                    Search Results
                </h2>

                <h1 class="text-3xl font-bold">
                    {{ $products->count() }}
                </h1>
            </div>

        </div>

        <!-- Add Product -->
        <div class="glass p-6 rounded-2xl mb-8">

            <h2 class="text-2xl font-semibold mb-5">
                ➕ Add Product
            </h2>

            <form action="/add-product" method="POST">
                @csrf

                <div class="grid md:grid-cols-3 gap-4">

                    <input type="text" name="name" placeholder="Product Name"
                        class="bg-slate-800 border border-slate-700 rounded-xl p-3 outline-none">

                    <input type="number" name="price" placeholder="Price"
                        class="bg-slate-800 border border-slate-700 rounded-xl p-3 outline-none">

                    <button class="bg-indigo-600 hover:bg-indigo-700 rounded-xl px-5 py-3 font-semibold">
                        Add Product
                    </button>

                </div>
            </form>

        </div>

        <!-- Search -->
        <div class="glass p-6 rounded-2xl mb-8">

            <form method="GET">

                <div class="flex gap-4">

                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..."
                        class="w-full bg-slate-800 border border-slate-700 rounded-xl p-3 outline-none">

                    <button class="bg-cyan-600 hover:bg-cyan-700 px-6 rounded-xl">
                        Search
                    </button>

                </div>

            </form>

        </div>

        <!-- Product Table -->
        <div class="glass rounded-2xl overflow-hidden">

            <table class="w-full">

                <thead class="bg-slate-800">
                    <tr>
                        <th class="p-4 text-left">#</th>
                        <th class="p-4 text-left">Product</th>
                        <th class="p-4 text-left">Price</th>
                        <th class="p-4 text-left">Created</th>
                        <th class="p-4 text-center">Action</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($products as $product)

                        <tr class="border-b border-slate-700 hover:bg-slate-800/40">

                            <td class="p-4">
                                {{ $product->id }}
                            </td>

                            <td class="p-4 font-semibold">
                                {{ $product->name }}
                            </td>

                            <td class="p-4 text-green-400">
                                ₹{{ number_format($product->price) }}
                            </td>

                            <td class="p-4 text-slate-300">
                                {{ $product->created_at->diffForHumans() }}
                            </td>

                            <td class="p-4 text-center">

                                <form action="/delete-product/{{ $product->id }}" method="POST">

                                    @csrf
                                    @method('DELETE')

                                    <button onclick="return confirm('Delete Product?')"
                                        class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg">
                                        Delete
                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="text-center p-8 text-slate-400">
                                No Products Found
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $products->links() }}
        </div>

    </div>

</body>

</html>
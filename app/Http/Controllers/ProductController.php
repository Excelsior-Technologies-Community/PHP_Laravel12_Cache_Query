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
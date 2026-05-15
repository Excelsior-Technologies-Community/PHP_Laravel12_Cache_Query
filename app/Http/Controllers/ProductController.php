<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    // Show Products
   public function index(Request $request)
{
    $search = $request->search ?? '';
    $page = $request->page ?? 1;

    $cacheKey = 'products_' . md5($search . '_' . $page);

    $fromCache = Cache::has($cacheKey);

    $products = Cache::remember($cacheKey, 300, function () use ($search) {

        return Product::when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('price', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'asc')
            ->paginate(4);

    });

    return view('products.index', compact('products', 'fromCache'));
}
    // Store Product
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'price' => 'required|numeric'
        ]);

        Product::create([
            'name' => $request->name,
            'price' => $request->price
        ]);

        // Clear cache
        Cache::flush();

        return redirect()->back()
            ->with('success', 'Product added successfully!');
    }

    // Delete Product
    public function destroy($id)
    {
        Product::findOrFail($id)->delete();

        // Clear cache
        Cache::flush();

        return redirect()->back()
            ->with('delete', 'Product deleted successfully!');
    }
}
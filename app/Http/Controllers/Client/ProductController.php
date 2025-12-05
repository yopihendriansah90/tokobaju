<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('q');
        $categorySlug = $request->query('category');
        $sort = $request->query('sort', 'latest');

        $categories = Category::orderBy('name')->get();

        $productsQuery = Product::with('category')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($nested) use ($search) {
                    $nested->where('name', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%');
                });
            })
            ->when($categorySlug, function ($query) use ($categorySlug) {
                $query->whereHas('category', function ($categoryQuery) use ($categorySlug) {
                    $categoryQuery->where('slug', $categorySlug);
                });
            });

        $productsQuery = match ($sort) {
            'price_asc' => $productsQuery->orderBy('price'),
            'price_desc' => $productsQuery->orderByDesc('price'),
            default => $productsQuery->latest(),
        };

        $products = $productsQuery->paginate(12)->withQueryString();

        return view('client.products.index', [
            'products' => $products,
            'categories' => $categories,
            'search' => $search,
            'categorySlug' => $categorySlug,
            'sort' => $sort,
        ]);
    }

    public function show(Product $product)
    {
        $product->load('category');

        $relatedProducts = Product::with('category')
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->latest()
            ->take(4)
            ->get();

        return view('client.products.show', compact('product', 'relatedProducts'));
    }
}

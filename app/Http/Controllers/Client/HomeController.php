<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('q');
        $categorySlug = $request->query('category');
        $sort = $request->query('sort', 'latest');

        $banners = Banner::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $categories = Category::query()
            ->with('media')
            ->orderBy('name')
            ->get();

        $featuredQuery = Product::query()
            ->with('category')
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

        $featuredQuery = match ($sort) {
            'price_asc' => $featuredQuery->orderBy('price'),
            'price_desc' => $featuredQuery->orderByDesc('price'),
            default => $featuredQuery->latest(),
        };

        $featuredProducts = (clone $featuredQuery)
            ->where('is_featured', true)
            ->take(6)
            ->get();

        if ($featuredProducts->isEmpty()) {
            $featuredProducts = (clone $featuredQuery)->take(6)->get();
        }

        $products = $featuredQuery->paginate(8)->withQueryString();

        return view('client.home', [
            'banners' => $banners,
            'categories' => $categories,
            'featuredProducts' => $featuredProducts,
            'products' => $products,
            'search' => $search,
            'categorySlug' => $categorySlug,
            'sort' => $sort,
        ]);
    }
}

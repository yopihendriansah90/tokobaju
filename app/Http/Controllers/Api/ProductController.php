<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('q');
        $categorySlug = $request->query('category');
        $sort = $request->query('sort', 'latest');
        $featured = $request->query('featured');
        $inStock = $request->query('in_stock');
        $perPage = $this->sanitizePerPage($request->query('per_page', 12));

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
            })
            ->when($featured !== null, function ($query) use ($featured) {
                $query->where('is_featured', (bool) $featured);
            })
            ->when($inStock !== null, function ($query) use ($inStock) {
                $query->where('stock', (bool) $inStock ? '>' : '=', 0);
            });

        $productsQuery = match ($sort) {
            'price_asc' => $productsQuery->orderBy('price'),
            'price_desc' => $productsQuery->orderByDesc('price'),
            default => $productsQuery->latest(),
        };

        $products = $productsQuery->paginate($perPage)->withQueryString();

        return response()->json([
            'data' => $products->getCollection()->map(function (Product $product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'price' => (string) $product->price,
                    'stock' => $product->stock,
                    'is_featured' => (bool) $product->is_featured,
                    'thumbnail_url' => $product->getFirstMediaUrl('products') ?: null,
                    'category' => $product->category ? [
                        'id' => $product->category->id,
                        'name' => $product->category->name,
                        'slug' => $product->category->slug,
                    ] : null,
                ];
            }),
            'meta' => [
                'current_page' => $products->currentPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ],
            'links' => [
                'next' => $products->nextPageUrl(),
                'prev' => $products->previousPageUrl(),
            ],
        ]);
    }

    public function show(int $id)
    {
        $product = Product::with('category')->findOrFail($id);

        $highlights = $this->splitHighlights($product->highlights);

        return response()->json([
            'data' => [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'description' => $product->description,
                'highlights' => $highlights,
                'price' => (string) $product->price,
                'stock' => $product->stock,
                'is_featured' => (bool) $product->is_featured,
                'images' => $product->getMedia('products')->map(fn ($media) => $media->getUrl())->values(),
                'category' => $product->category ? [
                    'id' => $product->category->id,
                    'name' => $product->category->name,
                    'slug' => $product->category->slug,
                ] : null,
            ],
        ]);
    }

    private function splitHighlights(?string $highlights): array
    {
        if (!$highlights) {
            return [];
        }

        return collect(preg_split('/\r\n|\r|\n/', trim($highlights)))
            ->map(fn ($item) => trim($item))
            ->filter()
            ->values()
            ->all();
    }

    private function sanitizePerPage(mixed $value): int
    {
        $perPage = (int) $value;
        if ($perPage <= 0) {
            return 12;
        }

        return min($perPage, 100);
    }
}

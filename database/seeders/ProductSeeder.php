<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Seed products from image filenames in /image.
     * This seeder only inserts product data and category relation (no media upload).
     */
    public function run(): void
    {
        $categoryIdBySlug = Category::query()
            ->pluck('id', 'slug');

        $imageFiles = collect(File::files(base_path('image')))
            ->map(fn ($file) => $file->getFilename())
            ->filter(fn (string $filename) => preg_match('/\.(jpg|jpeg|png|webp)$/i', $filename) === 1)
            ->reject(fn (string $filename) => Str::startsWith($filename, 'icon-'))
            ->sort()
            ->values();

        foreach ($imageFiles as $filename) {
            $baseName = pathinfo($filename, PATHINFO_FILENAME);
            $categorySlug = $this->resolveCategorySlug($baseName);
            $categoryId = $categoryIdBySlug->get($categorySlug);

            if (! $categoryId) {
                continue;
            }

            Product::updateOrCreate(
                ['slug' => Str::slug($baseName)],
                [
                    'category_id' => $categoryId,
                    'name' => (string) Str::of($baseName)->replace('-', ' ')->title(),
                    'slug' => Str::slug($baseName),
                    'description' => "Produk seed dari file gambar: {$filename}",
                    'highlights' => null,
                    'price' => $this->resolvePriceByCategory($categorySlug),
                    'stock' => 50,
                    'is_featured' => false,
                ]
            );
        }
    }

    private function resolveCategorySlug(string $baseName): string
    {
        $fashionKeywords = ['baju', 'koko', 'cadar', 'gamis', 'kaos', 'kerudung'];

        if (Str::contains($baseName, $fashionKeywords)) {
            return 'fashion-muslim';
        }

        if (Str::contains($baseName, ['pita'])) {
            return 'craft-aksesoris';
        }

        return 'makanan-minuman';
    }

    private function resolvePriceByCategory(string $categorySlug): int
    {
        return match ($categorySlug) {
            'fashion-muslim' => 149000,
            'craft-aksesoris' => 25000,
            default => 22000,
        };
    }
}

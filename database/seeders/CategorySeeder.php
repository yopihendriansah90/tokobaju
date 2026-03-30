<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Fashion Muslim',
                'slug' => 'fashion-muslim',
            ],
            [
                'name' => 'Disty Cell',
                'slug' => 'disty-cell',
            ],
            [
                'name' => 'Kosmetik & Skincare',
                'slug' => 'kosmetik-skincare',
            ],
            [
                'name' => 'Makanan & Minuman',
                'slug' => 'makanan-minuman',
            ],
            [
                'name' => 'Craft & Aksesoris',
                'slug' => 'craft-aksesoris',
            ],
            [
                'name' => 'Jasa Jahit',
                'slug' => 'jasa-jahit',
            ],
            [
                'name' => 'Laundry Pakaian',
                'slug' => 'laundry-pakaian',
            ],
        ];

        foreach ($categories as $item) {
            Category::updateOrCreate(
                ['slug' => $item['slug']],
                [
                    'name' => $item['name'],
                    'slug' => $item['slug'],
                ]
            );
        }
    }
}

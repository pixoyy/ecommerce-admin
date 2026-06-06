<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\FileStorage;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $images = FileStorage::pluck('id');

        $categories = [
            ['name' => 'Pakaian Wanita', 'slug' => 'pakaian-wanita', 'sort_order' => 1],
            ['name' => 'Pakaian Pria', 'slug' => 'pakaian-pria', 'sort_order' => 2],
            ['name' => 'Baju Muslim', 'slug' => 'baju-muslim', 'sort_order' => 3],
            ['name' => 'Tas & Dompet', 'slug' => 'tas-dompet', 'sort_order' => 4],
            ['name' => 'Sepatu', 'slug' => 'sepatu', 'sort_order' => 5],
            ['name' => 'Aksesoris', 'slug' => 'aksesoris', 'sort_order' => 6],
            ['name' => 'Kesehatan & Kecantikan', 'slug' => 'kesehatan-kecantikan', 'sort_order' => 7],
            ['name' => 'Peralatan Olahraga', 'slug' => 'peralatan-olahraga', 'sort_order' => 8],
        ];

        foreach ($categories as $i => $cat) {
            Category::create([
                'image' => $images->get($i),
                'name' => $cat['name'],
                'slug' => $cat['slug'],
                'sort_order' => $cat['sort_order'],
                'is_active' => 1,
            ]);
        }
    }
}

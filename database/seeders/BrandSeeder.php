<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            ['name' => 'Erigo', 'slug' => 'erigo'],
            ['name' => '3Second', 'slug' => '3second'],
            ['name' => 'Batik Keris', 'slug' => 'batik-keris'],
            ['name' => 'EIGER', 'slug' => 'eiger'],
            ['name' => 'Adidas', 'slug' => 'adidas'],
            ['name' => 'Nike', 'slug' => 'nike'],
            ['name' => 'Converse', 'slug' => 'converse'],
            ['name' => 'Wardah', 'slug' => 'wardah'],
            ['name' => 'Somethinc', 'slug' => 'somethinc'],
            ['name' => 'Vans', 'slug' => 'vans'],
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }
    }
}

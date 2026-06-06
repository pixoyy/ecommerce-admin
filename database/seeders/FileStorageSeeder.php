<?php

namespace Database\Seeders;

use App\Models\FileStorage;
use Illuminate\Database\Seeder;

class FileStorageSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 70; $i++) {
            FileStorage::create([
                'link' => "https://placehold.co/800x800/EEE/999?text=Image+{$i}",
            ]);
        }
    }
}

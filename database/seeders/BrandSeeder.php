<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    public function run()
    {
        Brand::create([
            'name' => 'Nike',
        ]);
        Brand::create([
            'name' => 'Adidas',
        ]);
        Brand::create([
            'name' => 'Puma',
        ]);

    }
}

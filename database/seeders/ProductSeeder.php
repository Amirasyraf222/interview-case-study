<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        Product::create([
            'name'              => 'Nike Court Vision',
            'sku'               => '876845945',
            'description'       => 'For men',
            'price'             => '100',
            'total_stock'       => '50',
            'category_id'       => 1,
            'brand_id'          => 1,
            'imagePath'         => '/images/nikecourtvision.png'
        ]);
        Product::create([
            'name'              => 'Nike Waffle Debut',
            'sku'               => '876868945',
            'description'       => 'For women',
            'price'             => '123',
            'total_stock'       => '50',
            'category_id'       => 2,
            'brand_id'          => 1,
            'imagePath'         => '/images/nikewaffledebut.png'

        ]);
        Product::create([
            'name'              => 'Adidas Stan Smith',
            'sku'               => '768794',
            'description'       => 'For lifestyle',
            'price'             => '200',
            'total_stock'          => '50',
            'category_id'       => 3,
            'brand_id'          => 2,
            'imagePath'         => '/images/adidasstansmith.png'

        ]);

    }
}

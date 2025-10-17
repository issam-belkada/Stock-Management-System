<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {

        Product::firstOrCreate([
            'name' => 'iPhone 14',
            'sku' => 'SKU-0002',
            'price' => '125000',
            'category_id' => 2
        ]);
    }
}

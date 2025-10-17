<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::firstOrCreate(['name' => 'Laptops']);
        Category::firstOrCreate(['name' => 'Phones']);
        Category::firstOrCreate(['name' => 'Accessories']);
        
    }
}

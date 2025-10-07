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
        Category::create([
            'name' => 'دسته 1',
            'super_category_id' => 1
        ]);
        Category::create([
            'name' => 'دسته 2',
            'super_category_id' => 2
        ]);
        Category::create([
            'name' => 'زیر دسته 1',
            'super_category_id' => 1
        ]);
        Category::create([
            'name' => 'زیر دسته 2',
            'super_category_id' => 1
        ]);
        Category::create([
            'name' => 'زیر دسته 3',
            'super_category_id' => 1
        ]);
        Category::create([
            'name' => 'زیر دسته جدید',
            'super_category_id' => 2
        ]);
        Category::create([
            'name' => 'زیر دسته جدید بعدی',
            'super_category_id' => 2
        ]);
    }
}

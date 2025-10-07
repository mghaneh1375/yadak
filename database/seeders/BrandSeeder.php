<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Brand::create([
            'name' => 'برند 1',
            'category_id' => 1
        ]);
        Brand::create([
            'name' => 'برند 2',
            'category_id' => 1
        ]);
        Brand::create([
            'name' => 'برند 3',
            'category_id' => 1
        ]);
        Brand::create([
            'name' => 'برند 4',
            'category_id' => 2
        ]);
        Brand::create([
            'name' => 'برند 5',
            'category_id' => 2
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\SuperCategory;
use Illuminate\Database\Seeder;

class SuperCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SuperCategory::create([
            'name' => 'دسته کلی 1'
        ]);
        SuperCategory::create([
            'name' => 'دسته کلی 2'
        ]);
    }
}

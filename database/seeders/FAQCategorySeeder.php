<?php

namespace Database\Seeders;

use App\Models\FAQCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FAQCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FAQCategory::create([
            'name' => 'دسته اول'
        ]);
        
        FAQCategory::create([
            'name' => 'دسته دوم'
        ]);
        
        FAQCategory::create([
            'name' => 'دسته سوم'
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\CategoryItem;
use Illuminate\Database\Seeder;

class CategoryItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CategoryItem::create([
            'name' => 'آیتم 1',
            'category_id' => 1,
            'base_item_id' => 0
        ]);
        CategoryItem::create([
            'name' => 'آیتم 2',
            'category_id' => 1,
            'base_item_id' => 1
        ]);
        CategoryItem::create([
            'name' => 'آیتم 3',
            'category_id' => 1,
            'base_item_id' => 1
        ]);
        CategoryItem::create([
            'name' => 'آیتم 4',
            'category_id' => 1,
            'base_item_id' => 1
        ]);
        CategoryItem::create([
            'name' => 'آیتم 5',
            'category_id' => 2,
            'base_item_id' => 0
        ]);
        CategoryItem::create([
            'name' => 'آیتم 6',
            'category_id' => 2,
            'base_item_id' => 5
        ]);
        CategoryItem::create([
            'name' => 'آیتم 7',
            'category_id' => 2,
            'base_item_id' => 5
        ]);
        CategoryItem::create([
            'name' => 'آیتم 8',
            'category_id' => 2,
            'base_item_id' => 5
        ]);
    }
}

<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(SuperCategorySeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(BrandSeeder::class);
        $this->call(ConfigSeeder::class);
        $this->call(FAQCategorySeeder::class);
        $this->call(CommonQuestionSeeder::class);
        $this->call(CategoryItemSeeder::class);
    }
}

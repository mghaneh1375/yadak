<?php

namespace Database\Seeders;

use App\Models\CommonQuestion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommonQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CommonQuestion::create([
            'question' => 'سوال 1',
            'answer' => 'پاسخ 1',
            'category_id' => '1'
        ]);
        CommonQuestion::create([
            'question' => 'سوال 2',
            'answer' => 'پاسخ 2',
            'category_id' => '1'
        ]);
        CommonQuestion::create([
            'question' => 'سوال جدید',
            'answer' => 'پاسخ جدید',
            'category_id' => '2'
        ]);
        CommonQuestion::create([
            'question' => 'سوال ',
            'answer' => 'پاسخ ',
            'category_id' => '3'
        ]);
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('common_question', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('answer');
            $table->longText('question');
            $table->unsignedInteger('category_id');
            $table->foreign('category_id')->references('id')->on('faq_category')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('common_question');
    }
};

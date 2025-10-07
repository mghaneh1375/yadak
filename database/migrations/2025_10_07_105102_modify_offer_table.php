<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('offer', function($table) {
            $table->string('code')->nullable();
            $table->unsignedInteger('amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offer', function($table) {
            $table->dropColumn(['code', 'amount']);
        });
    }
};

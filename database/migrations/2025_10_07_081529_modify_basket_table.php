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
        Schema::table('basket', function($table) {
            $table->string('confirm_date')->nullable();
            $table->string('arrival_date')->nullable();
            $table->longtext('description')->nullable();
            $table->tinyInteger('validate_offcode')->default(0);
            $table->tinyInteger('decease_from_warehouse')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('basket', function($table) {
            $table->dropColumn(['confirm_date', 'validate_offcode', 'decease_from_warehouse']);
        });
    }
};

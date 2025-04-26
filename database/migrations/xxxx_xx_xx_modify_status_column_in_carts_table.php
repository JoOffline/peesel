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
        Schema::table('carts', function (Blueprint $table) {
            // Hapus kolom status yang lama
            $table->dropColumn('status');
        });

        Schema::table('carts', function (Blueprint $table) {
            // Buat kolom status baru dengan tipe enum
            $table->enum('status', ['cart', 'pending', 'checkout'])->default('cart');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->string('status', 20)->default('cart');
        });
    }
};
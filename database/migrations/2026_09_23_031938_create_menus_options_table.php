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
        Schema::create('menus_options', function (Blueprint $table) {
            // $table->id();
            $table->id('menu_option_id'); // menu_option_id (PK)
            $table->string('name');
            $table->string('key')->unique(); // Clave evaluada por el Middleware (ej: news_Administrator)
            // $table->string('path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus_options');
    }
};

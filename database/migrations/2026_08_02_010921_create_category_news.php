<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('category_news', function (Blueprint $table) {
            // -----------------TABLA MUCHOS A MUHCOS ------------------
            $table->id('category_news_id');
            // // Columnas de la relación Muchos a Muchos
            // $table->foreignId('news_id')->nullable()->constrained('news')->onDelete('cascade');

            $table->foreignId('news_id')
                ->constrained('news')
                ->references('news_id') // <-- AQUÍ pones el nombre real de la columna ID en tu tabla news
                ->onDelete('cascade');

            $table->foreignId('catalog_detail_id')
                ->constrained('catalog_details')
                ->references('catalog_detail_id') // <-- AQUÍ pones el nombre real de la columna ID en tu tabla news
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_news');
    }
};

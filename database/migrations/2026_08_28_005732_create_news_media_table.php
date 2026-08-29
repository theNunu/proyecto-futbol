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
        Schema::create('news_media', function (Blueprint $table) {
            $table->uuid('news_media_id')->primary(); 
            // $table->string('new_id');
            // $table->string('file_id')->nullable(); // ← IMPORTANTE;
            $table->string('type')->nullable();
            
            $table->string('url_externo')->nullable(); // ← para videos externos

            // $table->foreign('new_id')->references('news_id')->on('news')->cascadeOnDelete();
            // $table->foreign('file_id')->references('file_id')->on('files')->cascadeOnDelete();

            $table->foreignId('new_id')
                ->constrained('news')
                ->references('news_id') // <-- AQUÍ pones el nombre real de la columna ID en tu tabla news
                ->onDelete('cascade');

            $table->foreignId('file_id')
                ->constrained('files')
                ->references('file_id') // <-- AQUÍ pones el nombre real de la columna ID en tu tabla news
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_media');
    }
};

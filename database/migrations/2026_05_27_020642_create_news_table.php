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
        Schema::create('news', function (Blueprint $table) {
            $table->id('news_id');
            $table->string('title', 10);
            $table->string('summary', 20)->nullable();;
            $table->string('description', 35);
             // 1. Fecha exacta (Solo YYYY-MM-DD)
            $table->date('begin_date');
            $table->date('end_date');
            //  $table->string('codigo', 10);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};

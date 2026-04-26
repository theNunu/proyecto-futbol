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
        Schema::create('menus', function (Blueprint $table) {
            // $table->id();
            $table->id('menu_id');
            // El parent_id permite la recursividad. Si es null, es un ítem raíz.
            $table->foreignId('parent_id')->references('menu_id')->on('menus')
                ->nullable()
                ->constrained('menus')
                ->onDelete('cascade');

            $table->string('name'); // Ejemplo: "Torneos Ecuador"
            $table->string('slug'); // Ejemplo: "torneos-ecuador" slug deb eser unico
            $table->string('url')->nullable(); // La URL administrable
            $table->integer('order'); // Para ordenar qué va primero
            $table->boolean('is_active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};

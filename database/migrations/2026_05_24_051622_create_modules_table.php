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
        Schema::create('modules', function (Blueprint $table) {
            $table->id('module_id');
            $table->string('name');         // Ejemplo: 'Gestión Administrativa' o 'Menú-FEF'
            $table->string('route')->nullable(); // Ruta de Angular a la que redirige (ej: '/admin/usuarios')
            $table->string('icon')->nullable();  // Icono para el menú (ej: 'fa-shield', 'settings')
            $table->unsignedBigInteger('parent_id')->nullable(); // El truco está aquí
            $table->integer('order'); // Para ordenar cuál va primero, segundo, etc.
            $table->boolean('is_active');
            $table->timestamps();
            // Llave foránea que apunta a la misma tabla
            $table->foreign('parent_id')->references('module_id')->on('modules')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};

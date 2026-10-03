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
        Schema::create('menus_options_roles', function (Blueprint $table) {
            $table->id('menu_option_role_id');

            // Relaciones
            $table->foreignId('role_id')->constrained('roles', 'role_id')->cascadeOnDelete();
            $table->foreignId('menu_option_id')->constrained('menus_options', 'menu_option_id')->cascadeOnDelete();

            // $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            // $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus_options_roles');
    }
};

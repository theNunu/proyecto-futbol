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
        Schema::create('persons', function (Blueprint $table) {
            // $table->id();
            $table->uuid('person_id')->primary();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('identification_type', 100);
            $table->string('identification_number', 100);
            $table->string('image_url', 300);
            $table->string('email', 100)->unique();
            $table->date('birth_date')->nullable();
            $table->string('phone_number', 20)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persons');
    }
};

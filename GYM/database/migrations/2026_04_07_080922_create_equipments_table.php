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
    Schema::create('equipments', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('category')->nullable();    // e.g. Cardio, Strength, Flexibility
        $table->text('description')->nullable();
        $table->string('brand')->nullable();
        $table->integer('quantity')->default(1);
        $table->enum('condition', ['excellent', 'good', 'fair', 'maintenance'])->default('good');
        $table->boolean('is_available')->default(true);
        $table->string('image')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipments');
    }
};

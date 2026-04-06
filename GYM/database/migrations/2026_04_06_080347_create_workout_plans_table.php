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
    Schema::create('workout_plans', function (Blueprint $table) {
        $table->id();
        $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
        $table->string('name');                    // e.g. Upper Body Push Day
        $table->string('frequency')->nullable();   // e.g. 4 Days / Week
        $table->string('duration')->nullable();    // e.g. 50 minutes
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workout_plans');
    }
};

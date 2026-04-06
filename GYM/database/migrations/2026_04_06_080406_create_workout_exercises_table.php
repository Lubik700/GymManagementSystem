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
    Schema::create('workout_exercises', function (Blueprint $table) {
        $table->id();
        $table->foreignId('workout_plan_id')->constrained('workout_plans')->onDelete('cascade');
        $table->string('name');                    // e.g. Bench Press
        $table->string('equipment')->nullable();   // e.g. Barbell
        $table->string('position')->nullable();    // e.g. Flat Bench
        $table->integer('sets')->nullable();       // e.g. 4
        $table->string('reps')->nullable();        // e.g. 8-12
        $table->string('rest')->nullable();        // e.g. 90 sec
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workout_exercises');
    }
};

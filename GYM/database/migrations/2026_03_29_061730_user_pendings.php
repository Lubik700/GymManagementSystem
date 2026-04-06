<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  // database/migrations/create_user_pendings_table.php
public function up(): void
{
    Schema::create('user_pendings', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->string('contact');
        $table->date('dob');
        $table->text('address');
        $table->enum('gender', ['male', 'female', 'other']);
        $table->string('password');
        $table->string('profile_picture')->nullable();
        $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

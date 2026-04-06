<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
// database/migrations/create_clients_table.php
public function up(): void
{
    Schema::create('clients', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_pending_id')->constrained('user_pendings');
        $table->string('name');
        $table->string('email')->unique();
        $table->string('contact');
        $table->date('dob');
        $table->text('address');
        $table->enum('gender', ['male', 'female', 'other']);
        $table->string('password');
        $table->string('profile_picture')->nullable();
        $table->enum('status', ['client', 'active', 'inactive'])->default('client');
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

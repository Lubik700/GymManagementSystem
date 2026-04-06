<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  // database/migrations/create_subscriptions_table.php
public function up(): void
{
    Schema::create('subscriptions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('client_id')->constrained('clients');
        $table->string('plan_name');
        $table->decimal('amount', 8, 2);
        $table->date('start_date');
        $table->date('end_date');
        $table->enum('status', ['active', 'expired', 'cancelled'])->default('active');
        $table->text('notes')->nullable();
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

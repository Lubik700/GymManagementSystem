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
    Schema::table('subscriptions', function (Blueprint $table) {
        $table->string('duration')->nullable()->after('plan_name'); // e.g. "1 Month", "3 Months"
    });
}

public function down(): void
{
    Schema::table('subscriptions', function (Blueprint $table) {
        $table->dropColumn('duration');
    });
}
};

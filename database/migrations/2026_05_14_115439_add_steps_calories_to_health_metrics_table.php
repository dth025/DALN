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
        Schema::table('health_metrics', function (Blueprint $table) {
            $table->integer('steps')->nullable();
            $table->integer('calories')->nullable(); // Nạp vào
            $table->integer('burned')->nullable();   // Tiêu thụ
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('health_metrics', function (Blueprint $table) {
            $table->dropColumn(['steps', 'calories', 'burned']);
        });
    }
};

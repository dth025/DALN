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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable();
            $table->date('dob')->nullable();
            $table->string('gender')->nullable();
            $table->integer('height')->nullable(); // in cm
            $table->integer('weight')->nullable(); // in kg
            $table->string('blood_type')->nullable();
            $table->string('address')->nullable();
            $table->string('job')->nullable();
            $table->text('health_goals')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'dob', 'gender', 'height', 'weight', 'blood_type', 'address', 'job', 'health_goals']);
        });
    }
};

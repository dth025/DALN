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
        Schema::table('appointments', function (Blueprint $table) {
            if (!Schema::hasColumn('appointments', 'proposed_date')) {
                $table->dateTime('proposed_date')->nullable()->after('appointment_date');
            }
            // Change status enum to string to support more statuses like 'rescheduled_pending'
            $table->string('status', 50)->default('scheduled')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            if (Schema::hasColumn('appointments', 'proposed_date')) {
                $table->dropColumn('proposed_date');
            }
            // Change back to enum is database specific, we can change to string but with fewer options
            $table->string('status', 50)->default('scheduled')->change();
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add indexes to appointments table
        Schema::table('appointments', function (Blueprint $table) {
            try {
                if ($this->indexNotExists('appointments', 'appointments_user_id_index')) {
                    $table->index('user_id');
                }
            } catch (\Exception $e) {
                // Index already exists
            }
            
            try {
                if ($this->indexNotExists('appointments', 'appointments_appointment_date_index')) {
                    $table->index('appointment_date');
                }
            } catch (\Exception $e) {
                // Index already exists
            }
            
            try {
                if ($this->indexNotExists('appointments', 'appointments_status_index')) {
                    $table->index('status');
                }
            } catch (\Exception $e) {
                // Index already exists
            }
        });

        // Add indexes to doctors table
        Schema::table('doctors', function (Blueprint $table) {
            try {
                if ($this->indexNotExists('doctors', 'doctors_status_index')) {
                    $table->index('status');
                }
            } catch (\Exception $e) {
                // Index already exists
            }
            
            try {
                if ($this->indexNotExists('doctors', 'doctors_email_index')) {
                    $table->index('email');
                }
            } catch (\Exception $e) {
                // Index already exists
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            try {
                $table->dropIndex(['user_id']);
            } catch (\Exception $e) {
                // Index doesn't exist
            }
            try {
                $table->dropIndex(['appointment_date']);
            } catch (\Exception $e) {
                // Index doesn't exist
            }
            try {
                $table->dropIndex(['status']);
            } catch (\Exception $e) {
                // Index doesn't exist
            }
        });

        Schema::table('doctors', function (Blueprint $table) {
            try {
                $table->dropIndex(['status']);
            } catch (\Exception $e) {
                // Index doesn't exist
            }
            try {
                $table->dropIndex(['email']);
            } catch (\Exception $e) {
                // Index doesn't exist
            }
        });
    }

    private function indexNotExists(string $table, string $index): bool
    {
        $result = DB::select("SELECT DISTINCT INDEX_NAME FROM INFORMATION_SCHEMA.STATISTICS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND INDEX_NAME = ?", 
            [DB::getDatabaseName(), $table, $index]
        );
        return empty($result);
    }
};

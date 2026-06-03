<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HealthMetricsSeeder extends Seeder
{
    public function run(): void
    {
        // Seed for all users
        $users = User::all();

        foreach ($users as $user) {
            // Remove existing metrics for this user
            DB::table('health_metrics')->where('user_id', $user->id)->delete();

            $baseWeight = rand(62, 75);

            for ($i = 60; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('Y-m-d');

                // Natural variation with slight trends
                $weight     = round($baseWeight + (rand(-30, 30) / 100), 2);
                $heartRate  = rand(58, 90);
                $spo2       = rand(95, 100);
                $water      = round(rand(15, 30) / 10, 1); // 1.5 – 3.0 L
                $sleep      = round(rand(55, 90) / 10, 1); // 5.5 – 9.0 h
                $steps      = rand(4500, 14000);
                $calories   = rand(1400, 2800);
                $burned     = rand(200, 800);

                DB::table('health_metrics')->insert([
                    'user_id'      => $user->id,
                    'heart_rate'   => $heartRate,
                    'spo2'         => $spo2,
                    'weight'       => $weight,
                    'water_intake' => $water,
                    'sleep_hours'  => $sleep,
                    'steps'        => $steps,
                    'calories'     => $calories,
                    'burned'       => $burned,
                    'recorded_at'  => $date,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);

                // Slowly drift weight over time
                $baseWeight += (rand(-5, 3) / 100);
            }
        }

        $this->command->info('Health metrics seeded: 61 days × ' . $users->count() . ' users ✓');
    }
}

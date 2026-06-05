<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class WorkoutsSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $types = ['HIIT Cardio Blast', 'Yoga buổi sáng', 'Tăng cơ toàn thân', 'Core & Plank'];

        foreach ($users as $user) {
            DB::table('workouts')->where('user_id', $user->id)->delete();

            // Seed 3-5 workouts in the current week
            $numWorkouts = rand(3, 5);
            for ($i = 0; $i < $numWorkouts; $i++) {
                $type = $types[array_rand($types)];
                $duration = rand(15, 60);
                $calories = $duration * rand(6, 9);
                $daysAgo = rand(0, 5);
                $startedAt = now()->subDays($daysAgo)->subHours(rand(1, 10));

                DB::table('workouts')->insert([
                    'user_id' => $user->id,
                    'type' => $type,
                    'duration_minutes' => $duration,
                    'calories_burned' => $calories,
                    'started_at' => $startedAt,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Also add corresponding calories burned to health metrics of that day
                $dateStr = $startedAt->toDateString();
                $metric = DB::table('health_metrics')
                    ->where('user_id', $user->id)
                    ->whereDate('recorded_at', $dateStr)
                    ->first();
                if ($metric) {
                    DB::table('health_metrics')
                        ->where('id', $metric->id)
                        ->update(['burned' => ($metric->burned ?: 2000) + $calories]);
                }
            }
        }
    }
}

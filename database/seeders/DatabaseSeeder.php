<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456'),
            'role' => 'admin',
            'plan' => 'Premium',
            'status' => 'active',
        ]);

        // Create Doctor User (registered as User model for Laravel Auth)
        User::factory()->create([
            'name' => 'BS. Demo Doctor',
            'email' => 'doctor.demo@healthsync.vn',
            'password' => bcrypt('123456'),
            'role' => 'doctor',
            'plan' => 'Premium',
            'status' => 'active',
            'phone' => '0912345678',
        ]);

        // Create Regular Users
        User::factory()->create([
            'name' => 'Test User 1',
            'email' => 'user1@example.com',
            'password' => bcrypt('123456'),
            'role' => 'user',
            'plan' => 'Free',
            'status' => 'active',
            'phone' => '0901234567',
        ]);

        User::factory()->create([
            'name' => 'Test User 2',
            'email' => 'user2@example.com',
            'password' => bcrypt('123456'),
            'role' => 'user',
            'plan' => 'Pro',
            'status' => 'active',
            'phone' => '0909876543',
        ]);

        // Call seeders
        $this->call([
            DoctorSeeder::class,
            HealthMetricsSeeder::class,
            AppointmentSeeder::class,
            MealPlanSeeder::class,
            WorkoutsSeeder::class,
        ]);
    }
}

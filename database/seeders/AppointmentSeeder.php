<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get users (skip admin)
        $users = User::where('role', 'user')->take(2)->get();
        
        if ($users->count() === 0) {
            return; // No users to create appointments for
        }

        // Get doctors
        $doctors = Doctor::where('status', 'active')->get();
        
        if ($doctors->count() === 0) {
            return; // No doctors available
        }

        $appointments = [];
        $now = Carbon::now();
        
        // Create appointments for each user
        foreach ($users as $index => $user) {
            $doctor = $doctors[$index % $doctors->count()];
            
            // Appointment 1: Past appointment (completed)
            $appointments[] = [
                'user_id' => $user->id,
                'doctor_id' => $doctor->id,
                'doctor_name' => $doctor->name,
                'specialty' => $doctor->specialty,
                'appointment_date' => $now->clone()->subDays(7)->setTime(10, 30),
                'status' => 'completed',
            ];
            
            // Appointment 2: Upcoming appointment
            $doctor2 = $doctors[($index + 1) % $doctors->count()];
            $appointments[] = [
                'user_id' => $user->id,
                'doctor_id' => $doctor2->id,
                'doctor_name' => $doctor2->name,
                'specialty' => $doctor2->specialty,
                'appointment_date' => $now->clone()->addDays(3)->setTime(14, 0),
                'status' => 'scheduled',
            ];

            // Appointment 3: Another upcoming
            $doctor3 = $doctors[($index + 2) % $doctors->count()];
            $appointments[] = [
                'user_id' => $user->id,
                'doctor_id' => $doctor3->id,
                'doctor_name' => $doctor3->name,
                'specialty' => $doctor3->specialty,
                'appointment_date' => $now->clone()->addDays(10)->setTime(9, 0),
                'status' => 'scheduled',
            ];
        }

        foreach ($appointments as $appointment) {
            Appointment::firstOrCreate(
                [
                    'user_id' => $appointment['user_id'],
                    'doctor_id' => $appointment['doctor_id'],
                    'appointment_date' => $appointment['appointment_date'],
                ],
                $appointment
            );
        }
    }
}

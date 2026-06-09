<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'specialty', 'email', 'phone', 'avatar', 'place', 'status', 'password', 'address'])]
class Doctor extends Model
{
    use HasFactory;

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }

    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function mealPlans()
    {
        return $this->hasMany(MealPlan::class);
    }

    public function recommendations()
    {
        return $this->hasMany(DoctorRecommendation::class);
    }

    public function getAvatarUrlAttribute()
    {
        if (!$this->avatar) {
            return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=0284c7&color=fff';
        }
        if (str_starts_with($this->avatar, 'http')) {
            return $this->avatar;
        }
        return asset('storage/' . $this->avatar);
    }
}

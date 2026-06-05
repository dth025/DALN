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
}

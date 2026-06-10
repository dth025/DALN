<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['user_id', 'doctor_id', 'doctor_name', 'specialty', 'appointment_date', 'status', 'proposed_date'])]
class Appointment extends Model
{
    use HasFactory;

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

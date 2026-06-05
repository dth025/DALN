<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'calories',
        'tags',
        'doctor_id',
        'patient_id',
        'is_template',
        'days',
    ];

    protected $casts = [
        'tags' => 'array',
        'days' => 'array',
        'is_template' => 'boolean',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
}

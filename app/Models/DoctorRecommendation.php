<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DoctorRecommendation extends Model
{
    use HasFactory;

    protected $table = 'doctor_recommendations';

    protected $fillable = [
        'doctor_id',
        'user_id',
        'advice',
        'meals', // JSON
    ];

    protected $casts = [
        'meals' => 'array',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HealthMetric extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'heart_rate', 'spo2', 'weight', 'water_intake', 'sleep_hours', 'steps', 'calories', 'burned', 'recorded_at'
    ];

    protected $casts = [
        'recorded_at' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

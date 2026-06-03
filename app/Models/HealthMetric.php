<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthMetric extends Model
{
    protected $fillable = [
        'user_id', 'heart_rate', 'spo2', 'weight', 'water_intake', 'sleep_hours', 'steps', 'calories', 'burned', 'recorded_at'
    ];
}

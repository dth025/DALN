<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Lấy dữ liệu mới nhất (Ngày cuối cùng có dữ liệu, không vượt quá hôm nay)
        $latest = \App\Models\HealthMetric::where('user_id', $user->id)
            ->where('recorded_at', '<=', now()->toDateString())
            ->orderBy('recorded_at', 'desc')
            ->first();
            
        // Lấy dữ liệu của ngày liền kề trước đó để so sánh
        $previous = null;
        if ($latest) {
            $previous = \App\Models\HealthMetric::where('user_id', $user->id)
                ->where('recorded_at', '<', $latest->recorded_at)
                ->orderBy('recorded_at', 'desc')
                ->first();
        }

        // Lấy toàn bộ lịch sử để vẽ biểu đồ tổng quan
        $history = \App\Models\HealthMetric::where('user_id', $user->id)
            ->where('recorded_at', '>=', now()->subYear()->toDateString())
            ->orderBy('recorded_at', 'asc')
            ->get();

        // Đồng bộ hóa dữ liệu của hôm nay từ model User vào lịch sử biểu đồ nếu chưa có hoặc cập nhật mới nhất
        $todayStr = now()->toDateString();
        $hasToday = $history->contains(function ($item) use ($todayStr) {
            return substr($item->recorded_at, 0, 10) === $todayStr;
        });

        if (!$hasToday) {
            $todayMetric = new \App\Models\HealthMetric([
                'user_id' => $user->id,
                'recorded_at' => $todayStr,
                'heart_rate' => $user->heart_rate,
                'spo2' => $user->spo2,
                'water_intake' => $user->water_intake,
                'sleep_hours' => $user->sleep_hours,
                'weight' => $user->weight,
                'steps' => $user->steps,
                'calories' => $user->calories,
                'burned' => 2000
            ]);
            $history->push($todayMetric);
        } else {
            $todayMetric = $history->first(function ($item) use ($todayStr) {
                return substr($item->recorded_at, 0, 10) === $todayStr;
            });
            $todayMetric->heart_rate = $user->heart_rate !== null ? $user->heart_rate : $todayMetric->heart_rate;
            $todayMetric->spo2 = $user->spo2 !== null ? $user->spo2 : $todayMetric->spo2;
            $todayMetric->water_intake = $user->water_intake !== null ? $user->water_intake : $todayMetric->water_intake;
            $todayMetric->sleep_hours = $user->sleep_hours !== null ? $user->sleep_hours : $todayMetric->sleep_hours;
            $todayMetric->weight = $user->weight !== null ? $user->weight : $todayMetric->weight;
            $todayMetric->steps = $user->steps !== null ? $user->steps : $todayMetric->steps;
            $todayMetric->calories = $user->calories !== null ? $user->calories : $todayMetric->calories;
        }

        $whoScore = $this->calculateWhoScore($user);
        $streak = $this->calculateStreak($user);

        return view('dashboard', [
            'user' => $user,
            'latest' => $latest,
            'previous' => $previous,
            'whoScore' => $whoScore,
            'streak' => $streak,
            'history' => $history
        ]);
    }

    private function calculateWhoScore($user) {
        $steps = floatval($user->steps ?: 0);
        $sleep = floatval($user->sleep_hours ?: 0);
        $hr = intval($user->heart_rate ?: 75);
        $weight = floatval($user->weight ?: 0);
        $height = floatval($user->height ?: 0);
        if ($height > 250) {
            $height = $height / 10;
        }

        // 1. Vận động (Activity Score - Max 100)
        $activityScore = min(100, ($steps / 8000) * 100);

        // 2. Giấc ngủ (Sleep Score - Max 100)
        $durationScore = 0;
        if ($sleep >= 7.5 && $sleep <= 8.5) $durationScore = 60;
        elseif ($sleep >= 6 && $sleep < 7.5) $durationScore = 45;
        elseif ($sleep > 8.5 && $sleep <= 9.5) $durationScore = 45;
        else $durationScore = 30;

        $recoveryScore = 0;
        if ($hr <= 65) $recoveryScore = 40;
        elseif ($hr <= 75) $recoveryScore = 30;
        elseif ($hr <= 85) $recoveryScore = 20;
        else $recoveryScore = 10;
        
        $sleepScore = $durationScore + $recoveryScore;

        // 3. BMI (Max 100)
        $bmiScore = 50;
        if ($weight > 0 && $height > 0) {
            $bmi = $weight / (($height/100) * ($height/100));
            if ($bmi >= 18.5 && $bmi <= 24.9) $bmiScore = 100;
            elseif ($bmi >= 25 && $bmi <= 29.9) $bmiScore = 80;
        }

        // Final weighted average (JS: Activity 40%, Sleep 40%, BMI 20%)
        return round(($activityScore * 0.4) + ($sleepScore * 0.4) + ($bmiScore * 0.2));
    }

    private function calculateStreak($user) {
        $metrics = \App\Models\HealthMetric::where('user_id', $user->id)
            ->orderBy('recorded_at', 'desc')
            ->get();
            
        $streak = 0;
        $today = now()->startOfDay();
        $expectedDate = $today;

        foreach ($metrics as $m) {
            $recordedAt = \Carbon\Carbon::parse($m->recorded_at)->startOfDay();
            $createdAt = \Carbon\Carbon::parse($m->created_at)->startOfDay();

            // Rule: Phải liên tiếp
            if ($recordedAt->equalTo($expectedDate)) {
                // Rule: Cập nhật đúng ngày (không cập nhật bù cho quá khứ)
                if ($createdAt->equalTo($recordedAt)) {
                    $streak++;
                    $expectedDate->subDay();
                } else {
                    // Cập nhật bù cho quá khứ làm mất chuỗi
                    break;
                }
            } else if ($recordedAt->lt($expectedDate)) {
                // Có ngày bị bỏ lỡ
                break;
            }
        }
        
        return $streak;
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Workout;
use Carbon\Carbon;

class WorkoutController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // 1. Tính toán lịch sử tuần này (Thứ 2 đến Chủ nhật)
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $workoutsThisWeek = Workout::where('user_id', $user->id)
            ->whereBetween('started_at', [$startOfWeek, $endOfWeek])
            ->orderBy('started_at', 'asc')
            ->get();

        $weeklyCalories = $workoutsThisWeek->sum('calories_burned');
        $weeklyDuration = $workoutsThisWeek->sum('duration_minutes');

        $hours = floor($weeklyDuration / 60);
        $minutes = $weeklyDuration % 60;
        $weeklyDurationStr = ($hours > 0 ? "{$hours}h " : "") . "{$minutes}m";

        // 2. Tính toán Streak (số ngày tập luyện liên tiếp gần đây nhất bao gồm hôm nay/hôm qua)
        $allWorkouts = Workout::where('user_id', $user->id)
            ->orderBy('started_at', 'desc')
            ->get();

        $workoutDates = $allWorkouts->map(fn($w) => Carbon::parse($w->started_at)->toDateString())->unique()->values();
        $streak = 0;

        if ($workoutDates->isNotEmpty()) {
            $today = Carbon::now()->toDateString();
            $yesterday = Carbon::now()->subDay()->toDateString();

            $currentDate = null;
            if ($workoutDates->contains($today)) {
                $currentDate = Carbon::now();
            } elseif ($workoutDates->contains($yesterday)) {
                $currentDate = Carbon::now()->subDay();
            }

            if ($currentDate) {
                while ($workoutDates->contains($currentDate->toDateString())) {
                    $streak++;
                    $currentDate->subDay();
                }
            }
        }

        // 3. Lịch tập tuần này (T2 - CN)
        $weeklySchedule = [];
        $days = ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN'];
        for ($i = 0; $i < 7; $i++) {
            $dayDate = Carbon::now()->startOfWeek()->addDays($i)->toDateString();
            $hasWorkout = $workoutsThisWeek->contains(fn($w) => Carbon::parse($w->started_at)->toDateString() === $dayDate);
            $weeklySchedule[] = [
                'label' => $days[$i],
                'done' => $hasWorkout
            ];
        }
        $doneDaysCount = collect($weeklySchedule)->where('done', true)->count();

        // 4. AI Đề xuất bài tập dựa trên chỉ số cơ thể thực tế
        $latestBmi = 0;
        $weight = $user->weight ?: 0;
        $height = $user->height ?: 0;
        if ($height > 250) {
            $height = $height / 10;
        }
        if ($weight > 0 && $height > 0) {
            $latestBmi = $weight / (($height / 100) ** 2);
        }

        $heartRate = $user->heart_rate ?: 75;
        $steps = $user->steps ?: 0;

        // Xây dựng đề xuất AI dựa trên chỉ số sức khỏe thực tế
        $aiAnalysis = '';
        $recommendedWorkouts = [];

        if ($latestBmi >= 25) {
            $aiAnalysis = 'Chỉ số BMI của bạn đang ở mức thừa cân (' . round($latestBmi, 1) . '). AI khuyến nghị tập trung vào các bài tập Cardio cường độ cao ngắt quãng (HIIT) kết hợp cơ trọng tâm (Core) để thúc đẩy quá trình đốt cháy chất béo và nâng cao thể lực.';
            $recommendedWorkouts = [
                [
                    'name' => 'HIIT Cardio Blast',
                    'duration' => '20 phút',
                    'kcal' => 280,
                    'level' => 'Trung bình',
                    'img' => 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?w=600&q=80',
                    'color' => 'from-rose-500 to-orange-400',
                    'type' => 'HIIT'
                ],
                [
                    'name' => 'Core & Plank',
                    'duration' => '15 phút',
                    'kcal' => 120,
                    'level' => 'Trung bình',
                    'img' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=600&q=80',
                    'color' => 'from-emerald-500 to-teal-400',
                    'type' => 'Core'
                ]
            ];
        } elseif ($latestBmi > 0 && $latestBmi < 18.5) {
            $aiAnalysis = 'Chỉ số BMI của bạn ở mức nhẹ cân (' . round($latestBmi, 1) . '). AI khuyến nghị các bài tập kháng lực cường độ trung bình đến cao (Strength Training) nhằm xây dựng khối lượng cơ bắp và nâng cao sức mạnh tổng thể.';
            $recommendedWorkouts = [
                [
                    'name' => 'Tăng cơ toàn thân',
                    'duration' => '45 phút',
                    'kcal' => 380,
                    'level' => 'Khó',
                    'img' => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=600&q=80',
                    'color' => 'from-blue-500 to-cyan-400',
                    'type' => 'Strength'
                ],
                [
                    'name' => 'Core & Plank',
                    'duration' => '15 phút',
                    'kcal' => 120,
                    'level' => 'Trung bình',
                    'img' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=600&q=80',
                    'color' => 'from-emerald-500 to-teal-400',
                    'type' => 'Core'
                ]
            ];
        } elseif ($heartRate > 85) {
            $aiAnalysis = 'Nhịp tim nghỉ ngơi của bạn hiện hơi cao (' . $heartRate . ' bpm). AI đề xuất các bài tập phục hồi nhịp tim, các tư thế Yoga kéo giãn cơ thể để xoa dịu hệ thần kinh và phục hồi cơ xương khớp.';
            $recommendedWorkouts = [
                [
                    'name' => 'Yoga buổi sáng',
                    'duration' => '30 phút',
                    'kcal' => 150,
                    'level' => 'Dễ',
                    'img' => 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=600&q=80',
                    'color' => 'from-violet-500 to-purple-400',
                    'type' => 'Yoga'
                ],
                [
                    'name' => 'Core & Plank',
                    'duration' => '15 phút',
                    'kcal' => 120,
                    'level' => 'Trung bình',
                    'img' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=600&q=80',
                    'color' => 'from-emerald-500 to-teal-400',
                    'type' => 'Core'
                ]
            ];
        } else {
            $aiAnalysis = 'Chỉ số cơ thể của bạn đang ở mức lý tưởng! AI đề xuất chế độ tập luyện cân bằng kết hợp tăng cơ (Strength) và dẻo dai tim mạch (Cardio/Yoga) để duy trì trạng thái tối ưu.';
            $recommendedWorkouts = [
                [
                    'name' => 'HIIT Cardio Blast',
                    'duration' => '20 phút',
                    'kcal' => 280,
                    'level' => 'Trung bình',
                    'img' => 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?w=600&q=80',
                    'color' => 'from-rose-500 to-orange-400',
                    'type' => 'HIIT'
                ],
                [
                    'name' => 'Tăng cơ toàn thân',
                    'duration' => '45 phút',
                    'kcal' => 380,
                    'level' => 'Khó',
                    'img' => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=600&q=80',
                    'color' => 'from-blue-500 to-cyan-400',
                    'type' => 'Strength'
                ]
            ];
        }

        // Toàn bộ danh sách bài tập thư viện để người dùng chọn tập
        $allPresetWorkouts = [
            ['name' => 'HIIT Cardio Blast', 'duration' => '20 phút', 'kcal' => 280, 'level' => 'Trung bình', 'img' => 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?w=600&q=80', 'color' => 'from-rose-500 to-orange-400', 'type' => 'HIIT'],
            ['name' => 'Yoga buổi sáng', 'duration' => '30 phút', 'kcal' => 150, 'level' => 'Dễ', 'img' => 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=600&q=80', 'color' => 'from-violet-500 to-purple-400', 'type' => 'Yoga'],
            ['name' => 'Tăng cơ toàn thân', 'duration' => '45 phút', 'kcal' => 380, 'level' => 'Khó', 'img' => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=600&q=80', 'color' => 'from-blue-500 to-cyan-400', 'type' => 'Strength'],
            ['name' => 'Core & Plank', 'duration' => '15 phút', 'kcal' => 120, 'level' => 'Trung bình', 'img' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=600&q=80', 'color' => 'from-emerald-500 to-teal-400', 'type' => 'Core'],
        ];

        return view('workout', [
            'weeklyCalories' => $weeklyCalories,
            'weeklyDurationStr' => $weeklyDurationStr,
            'streak' => $streak,
            'weeklySchedule' => $weeklySchedule,
            'doneDaysCount' => $doneDaysCount,
            'aiAnalysis' => $aiAnalysis,
            'recommendedWorkouts' => $recommendedWorkouts,
            'allPresetWorkouts' => $allPresetWorkouts,
        ]);
    }

    public function logWorkout(Request $request)
    {
        $request->validate([
            'type' => 'required|string|max:255',
            'duration_minutes' => 'required|integer|min:1',
            'calories_burned' => 'required|integer|min:0',
            'started_at' => 'nullable|date',
        ]);

        $workout = Workout::create([
            'user_id' => auth()->id(),
            'type' => $request->type,
            'duration_minutes' => $request->duration_minutes,
            'calories_burned' => $request->calories_burned,
            'started_at' => $request->started_at ? Carbon::parse($request->started_at) : Carbon::now(),
        ]);

        // Cập nhật Calories nạp/tiêu hao vào bảng users nếu có (tùy chỉnh logic dự án)
        $user = auth()->user();
        if ($workout->started_at->toDateString() === Carbon::now()->toDateString()) {
            // Cộng dồn calories đốt vào trường user nếu có, hoặc để HealthMetric xử lý. 
            // Ở đây, để giữ đúng thiết kế và dữ liệu của dashboard:
            // Ta đồng bộ hoá qua HealthMetric cho ngày hôm đó.
            $todayStr = Carbon::now()->toDateString();
            $metric = \App\Models\HealthMetric::firstOrCreate(
                ['user_id' => $user->id, 'recorded_at' => $todayStr]
            );
            $metric->burned = ($metric->burned ?: 2000) + $workout->calories_burned;
            $metric->save();
        }

        if ($request->ajax()) {
            return response()->json([
                'message' => 'Lưu bài tập thành công!',
                'workout' => $workout
            ]);
        }

        return back()->with('status', 'workout-logged');
    }
}

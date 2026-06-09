<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        [$aiSuggestedPlan, $doctorRecommendedPlan] = $this->generateHealthBasedPlans($user);

        // Query doctors with index optimization
        $doctors = Doctor::where('status', 'active')->orderBy('name')->select(['id', 'name', 'specialty', 'avatar', 'place', 'phone', 'status'])->get();
        
        $selectedDoctor = null;
        if ($request->query('selected_doctor')) {
            $selectedDoctor = $doctors->firstWhere('id', (int) $request->query('selected_doctor'));
        }

        if ($selectedDoctor) {
            $doctorRecommendedPlan['doctor'] = $selectedDoctor->name;
            if (empty($doctorRecommendedPlan['advice'])) {
                $doctorRecommendedPlan['advice'] = 'Bạn đã chọn bác sĩ ' . $selectedDoctor->name . '. Đề xuất thực đơn sẽ được cập nhật khi bác sĩ gửi phản hồi.';
            }
        }

        $macros = $this->calculateMacros($user);

        // Derive meal suggestions from the AI plan so there's no hardcoded list
        $aiMeals = $aiSuggestedPlan['meals'] ?? [];
        $meals = array_map(function ($m, $i) {
            $times = ['07:00', '12:30', '18:30', '21:00'];
            $imgs  = [
                'https://images.unsplash.com/photo-1517673400267-0251440c45dc?w=600&q=80',
                'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=600&q=80',
                'https://images.unsplash.com/photo-1467003909585-2f8a72700288?w=600&q=80',
                'https://images.unsplash.com/photo-1488477181946-6428a0291777?w=600&q=80',
            ];
            return [
                'title' => $m['label'] ?? ('Bữa ' . ($i + 1)),
                'time'  => $times[$i] ?? '12:00',
                'kcal'  => $m['kcal'] ?? 0,
                'name'  => $m['name'] ?? '',
                'img'   => $imgs[$i] ?? $imgs[0],
                'tags'  => [],
            ];
        }, $aiMeals, array_keys($aiMeals));

        return view('menu', [
            'macros' => $macros,
            'meals'  => $meals,
            'aiSuggestedPlan' => $aiSuggestedPlan,
            'doctorRecommendedPlan' => $doctorRecommendedPlan,
            'doctors' => $doctors,
            'selectedDoctor' => $selectedDoctor,
        ]);
    }

    private function calculateMacros($user): array
    {
        $weight = floatval($user->weight ?: 0);
        $height = floatval($user->height ?: 0);
        if ($height > 250) $height /= 10;

        // Harris-Benedict TDEE estimate (moderate activity)
        $age = $user->dob ? \Carbon\Carbon::parse($user->dob)->age : 25;
        $gender = strtolower($user->gender ?? 'male');
        if ($weight > 0 && $height > 0) {
            if ($gender === 'female' || $gender === 'nữ') {
                $bmr = 655 + (9.6 * $weight) + (1.8 * $height) - (4.7 * $age);
            } else {
                $bmr = 66 + (13.7 * $weight) + (5 * $height) - (6.8 * $age);
            }
            $tdee = (int) round($bmr * 1.55);
        } else {
            $tdee = 2000;
        }

        // Adjust by goal
        $goalText = strtolower($user->health_goals ?? '');
        if (str_contains($goalText, 'giảm') || ($weight > 0 && $height > 0 && $weight / (($height / 100) ** 2) >= 25)) {
            $targetCal = (int) round($tdee * 0.85);
        } elseif (str_contains($goalText, 'tăng') || ($weight > 0 && $height > 0 && $weight / (($height / 100) ** 2) < 18.5)) {
            $targetCal = (int) round($tdee * 1.10);
        } else {
            $targetCal = $tdee;
        }

        // Actual calories consumed today from HealthMetric (if logged)
        $todayMetric = \App\Models\HealthMetric::where('user_id', $user->id)
            ->where('recorded_at', now()->toDateString())
            ->first();
        $actualCal = $todayMetric?->calories ?? $user->calories ?? 0;

        // Macro targets (protein 30%, carbs 45%, fat 25%)
        $proteinTarget = (int) round($targetCal * 0.30 / 4);
        $carbTarget    = (int) round($targetCal * 0.45 / 4);
        $fatTarget     = (int) round($targetCal * 0.25 / 9);

        // Estimate actual macros from calories ratio (approximation if no detailed tracking)
        $calRatio      = $targetCal > 0 ? min(1, $actualCal / $targetCal) : 0;
        $actualProtein = (int) round($proteinTarget * $calRatio);
        $actualCarb    = (int) round($carbTarget * $calRatio);
        $actualFat     = (int) round($fatTarget * $calRatio);

        $calPct     = $targetCal > 0 ? min(100, round($actualCal / $targetCal * 100)) : 0;
        $proteinPct = $proteinTarget > 0 ? min(100, round($actualProtein / $proteinTarget * 100)) : 0;
        $carbPct    = $carbTarget > 0 ? min(100, round($actualCarb / $carbTarget * 100)) : 0;
        $fatPct     = $fatTarget > 0 ? min(100, round($actualFat / $fatTarget * 100)) : 0;

        return [
            ['label' => 'Calories', 'value' => number_format($actualCal), 'target' => number_format($targetCal), 'color' => 'from-orange-500 to-amber-400', 'width' => $calPct . '%'],
            ['label' => 'Protein',  'value' => $actualProtein . 'g',      'target' => $proteinTarget . 'g',      'color' => 'from-rose-500 to-pink-400',    'width' => $proteinPct . '%'],
            ['label' => 'Carbs',    'value' => $actualCarb . 'g',         'target' => $carbTarget . 'g',         'color' => 'from-blue-500 to-cyan-400',    'width' => $carbPct . '%'],
            ['label' => 'Fat',      'value' => $actualFat . 'g',          'target' => $fatTarget . 'g',          'color' => 'from-emerald-500 to-teal-400', 'width' => $fatPct . '%'],
        ];
    }

    public function aiRecommendation()
    {
        $user = auth()->user();
        [$aiSuggestedPlan, $doctorRecommendedPlan] = $this->generateHealthBasedPlans($user);

        return response()->json([
            'aiSuggestedPlan' => $aiSuggestedPlan,
            'doctorRecommendedPlan' => $doctorRecommendedPlan,
            'updated_at' => now()->format('H:i:s'),
        ]);
    }

    private function generateHealthBasedPlans($user)
    {
        $weight = floatval($user->weight ?: 0);
        $height = floatval($user->height ?: 0);
        if ($height > 250) {
            $height = $height / 10;
        }
        $bmi = ($weight > 0 && $height > 0) ? $weight / (($height / 100) ** 2) : 0;

        $heartRate = intval($user->heart_rate ?: 75);
        $spo2 = intval($user->spo2 ?: 98);
        $sleep = floatval($user->sleep_hours ?: 7);
        $steps = intval($user->steps ?: 0);

        $goalText = strtolower($user->health_goals ?? '');
        if (str_contains($goalText, 'giảm')) {
            $goal = 'giảm cân';
        } elseif (str_contains($goalText, 'tăng')) {
            $goal = 'tăng cơ';
        } elseif ($bmi >= 25) {
            $goal = 'giảm cân';
        } elseif ($bmi < 18.5) {
            $goal = 'tăng cơ';
        } else {
            $goal = 'cân bằng';
        }

        $aiDescription = 'AI phân tích chỉ số sức khỏe hiện tại của bạn và đưa ra gợi ý dinh dưỡng phù hợp.';
        if ($goal === 'giảm cân') {
            $aiDescription = 'AI phân tích cho thấy bạn nên ưu tiên chế độ ít carb, nhiều protein và rau xanh để giảm mỡ và duy trì năng lượng.';
        } elseif ($goal === 'tăng cơ') {
            $aiDescription = 'AI nhận thấy bạn cần bổ sung nhiều đạm và năng lượng sạch để hỗ trợ tăng cơ và phục hồi.';
        }

        if ($heartRate > 90) {
            $aiDescription = 'Nhịp tim của bạn đang cao hơn bình thường, AI đề xuất chế độ ăn nhẹ nhàng, giàu điện giải và dễ tiêu.';
        }

        if ($spo2 < 95) {
            $aiDescription = 'SpO2 hơi thấp, AI đề xuất thực phẩm giàu chất chống oxy hoá và hydrat để hỗ trợ hệ hô hấp.';
        }

        if ($sleep < 6) {
            $aiDescription = 'Bạn đang thiếu ngủ; AI đề xuất bổ sung nhiều rau củ, protein và nước để cải thiện phục hồi.';
        }

        $aiMeals = [];
        if ($goal === 'giảm cân') {
            $aiMeals = [
                ['label' => 'Bữa sáng', 'name' => 'Yến mạch trái cây & hạt chia', 'kcal' => 420],
                ['label' => 'Bữa trưa', 'name' => 'Salad ức gà nướng & quinoa', 'kcal' => 600],
                ['label' => 'Bữa tối', 'name' => 'Cá hồi nướng + rau xanh', 'kcal' => 480],
                ['label' => 'Bữa phụ', 'name' => 'Sữa chua Hy Lạp + hạt óc chó', 'kcal' => 220],
            ];
        } elseif ($goal === 'tăng cơ') {
            $aiMeals = [
                ['label' => 'Bữa sáng', 'name' => 'Trứng ốp la + bánh mì nguyên cám', 'kcal' => 520],
                ['label' => 'Bữa trưa', 'name' => 'Ức gà nướng + gạo lứt & rau củ', 'kcal' => 720],
                ['label' => 'Bữa tối', 'name' => 'Thịt bò áp chảo + khoai lang', 'kcal' => 680],
                ['label' => 'Bữa phụ', 'name' => 'Sinh tố chuối hạt điều', 'kcal' => 260],
            ];
        } else {
            $aiMeals = [
                ['label' => 'Bữa sáng', 'name' => 'Yến mạch & sữa hạt', 'kcal' => 400],
                ['label' => 'Bữa trưa', 'name' => 'Cơm gạo lứt + cá hồi & rau xanh', 'kcal' => 650],
                ['label' => 'Bữa tối', 'name' => 'Salad gà nướng & bơ', 'kcal' => 520],
                ['label' => 'Bữa phụ', 'name' => 'Táo xanh + hạt hạnh nhân', 'kcal' => 210],
            ];
        }

        if ($heartRate > 90) {
            $aiMeals = [
                ['label' => 'Bữa sáng', 'name' => 'Sinh tố dâu + chuối', 'kcal' => 390],
                ['label' => 'Bữa trưa', 'name' => 'Salad cá hồi & khoai lang', 'kcal' => 620],
                ['label' => 'Bữa tối', 'name' => 'Canh gà nấm & rau cải', 'kcal' => 470],
                ['label' => 'Bữa phụ', 'name' => 'Sữa chua Hy Lạp', 'kcal' => 180],
            ];
        }

        if ($spo2 < 95) {
            $aiMeals = [
                ['label' => 'Bữa sáng', 'name' => 'Sinh tố xanh cải xoăn', 'kcal' => 380],
                ['label' => 'Bữa trưa', 'name' => 'Gà áp chảo & rau xanh', 'kcal' => 600],
                ['label' => 'Bữa tối', 'name' => 'Cá hồi hấp & bông cải', 'kcal' => 500],
                ['label' => 'Bữa phụ', 'name' => 'Nước ép cà rốt', 'kcal' => 150],
            ];
        }

        // Prefer a doctor-provided recommendation when available for this user
        $doctorPlan = [
            'doctor' => 'Chưa có đề xuất từ bác sĩ',
            'advice' => 'Chưa có đề xuất cụ thể từ bác sĩ. Hệ thống sẽ hiển thị gợi ý AI.',
            'meals' => [],
        ];

        try {
            $recModel = \App\Models\DoctorRecommendation::where('user_id', $user->id)
                ->latest()
                ->first();

            if ($recModel) {
                // If a MealPlan exists for this user, prefer it (it has structured 'days')
                $latestMealPlan = \App\Models\MealPlan::where('patient_id', $user->id)->latest()->first();
                if ($latestMealPlan) {
                    // Flatten days -> meals for display (take first day or merge)
                    $flatMeals = [];
                    $days = $latestMealPlan->days ?? [];
                    if (is_array($days)) {
                        foreach ($days as $d) {
                            if (isset($d['meals']) && is_array($d['meals'])) {
                                $flatMeals = array_merge($flatMeals, $d['meals']);
                            }
                        }
                    }

                    if (empty($flatMeals) && is_array($recModel->meals)) {
                        $flatMeals = $recModel->meals;
                    }

                    $doctorPlan = [
                        'doctor' => $recModel->doctor?->name ?? 'Bác sĩ',
                        'advice' => $recModel->advice ?? '',
                        'meals' => $flatMeals,
                    ];
                } else {
                    $doctorPlan = [
                        'doctor' => $recModel->doctor?->name ?? 'Bác sĩ',
                        'advice' => $recModel->advice ?? '',
                        'meals' => $recModel->meals ?? [],
                    ];
                }
            }
        } catch (\Exception $e) {
            // fallback to default static plan if any error
            $doctorPlan = [
                'doctor' => 'Bác sĩ chuyên khoa Dinh dưỡng Nguyễn Thị Lan',
                'advice' => 'Bác sĩ đề xuất thêm rau xanh, hạn chế đồ chiên rán và ưu tiên protein nạc để cân bằng năng lượng và hỗ trợ phục hồi.',
                'meals' => [
                    ['label' => 'Bữa sáng', 'name' => 'Trứng luộc + salad rau củ', 'kcal' => 380],
                    ['label' => 'Bữa trưa', 'name' => 'Gà áp chảo + bông cải xanh', 'kcal' => 590],
                    ['label' => 'Bữa tối', 'name' => 'Canh bí đỏ + ức gà', 'kcal' => 450],
                    ['label' => 'Bữa phụ', 'name' => 'Sữa chua Hy Lạp', 'kcal' => 190],
                ],
            ];
        }

        return [
            [
                'title' => 'Thực đơn ' . ucfirst($goal) . ' AI đề xuất',
                'description' => $aiDescription,
                'meals' => $aiMeals,
            ],
            $doctorPlan,
        ];
    }
}

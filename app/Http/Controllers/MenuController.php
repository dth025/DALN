<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        [$aiSuggestedPlan, $doctorRecommendedPlan] = $this->generateHealthBasedPlans($user);

        $meals = [
            ['title' => 'Bữa sáng', 'time' => '07:00', 'kcal' => 420, 'name' => 'Yến mạch trái cây & hạt chia', 'img' => 'https://images.unsplash.com/photo-1517673400267-0251440c45dc?w=600&q=80', 'tags' => ['Giàu chất xơ', 'Vegan']],
            ['title' => 'Bữa trưa', 'time' => '12:30', 'kcal' => 680, 'name' => 'Cơm gạo lứt ức gà nướng & rau củ', 'img' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=600&q=80', 'tags' => ['Cao đạm', 'Low-carb']],
            ['title' => 'Bữa tối', 'time' => '18:30', 'kcal' => 520, 'name' => 'Salad cá hồi avocado', 'img' => 'https://images.unsplash.com/photo-1467003909585-2f8a72700288?w=600&q=80', 'tags' => ['Omega-3', 'Ít calo']],
            ['title' => 'Bữa phụ', 'time' => '21:00', 'kcal' => 200, 'name' => 'Sữa chua Hy Lạp & việt quất', 'img' => 'https://images.unsplash.com/photo-1488477181946-6428a0291777?w=600&q=80', 'tags' => ['Probiotic']],
        ];

        $macros = [
            ['label' => 'Calories', 'value' => '1,820', 'target' => '2,000', 'color' => 'from-orange-500 to-amber-400', 'width' => '91%'],
            ['label' => 'Protein', 'value' => '120g', 'target' => '140g', 'color' => 'from-rose-500 to-pink-400', 'width' => '85%'],
            ['label' => 'Carbs', 'value' => '210g', 'target' => '250g', 'color' => 'from-blue-500 to-cyan-400', 'width' => '84%'],
            ['label' => 'Fat', 'value' => '55g', 'target' => '65g', 'color' => 'from-emerald-500 to-teal-400', 'width' => '84%'],
        ];

        return view('menu', [
            'macros' => $macros,
            'meals' => $meals,
            'aiSuggestedPlan' => $aiSuggestedPlan,
            'doctorRecommendedPlan' => $doctorRecommendedPlan,
        ]);
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
                $doctorPlan = [
                    'doctor' => $recModel->doctor?->name ?? 'Bác sĩ',
                    'advice' => $recModel->advice ?? '',
                    'meals' => $recModel->meals ?? [],
                ];
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

<?php

namespace Database\Seeders;

use App\Models\MealPlan;
use Illuminate\Database\Seeder;

class MealPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'title' => 'Low-Carb Balanced Week',
                'description' => 'Giảm carb, tăng protein, phù hợp cho giảm cân nhẹ.',
                'calories' => 1800,
                'tags' => ['low-carb','balanced'],
                'is_template' => true,
                'days' => [],
            ],
            [
                'title' => 'Diabetic Friendly',
                'description' => 'Ưu tiên thực phẩm ít đường, giàu chất xơ.',
                'calories' => 2000,
                'tags' => ['diabetic','balanced'],
                'is_template' => true,
                'days' => [],
            ],
            [
                'title' => 'High Protein Builder',
                'description' => 'Phù hợp người cần tăng cơ, nhiều protein.',
                'calories' => 2500,
                'tags' => ['high-protein'],
                'is_template' => true,
                'days' => [],
            ],
            [
                'title' => 'Vegetarian Variety',
                'description' => 'Dành cho người ăn chay, cân bằng dinh dưỡng.',
                'calories' => 2000,
                'tags' => ['vegetarian','balanced'],
                'is_template' => true,
                'days' => [],
            ],
        ];

        foreach ($templates as $t) {
            MealPlan::create($t);
        }

        // Create a few randomized plans using factory
        MealPlan::factory()->count(5)->create();
    }
}

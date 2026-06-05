<?php

namespace Database\Factories;

use App\Models\MealPlan;
use Illuminate\Database\Eloquent\Factories\Factory;

class MealPlanFactory extends Factory
{
    protected $model = MealPlan::class;

    public function definition()
    {
        $tags = ['low-carb','high-protein','vegetarian','diabetic','heart','keto','balanced'];

        $days = [];
        for ($d = 0; $d < 7; $d++) {
            $days[] = [
                'meals' => [
                    ['name' => $this->faker->words(3, true), 'calories' => $this->faker->numberBetween(150, 600)],
                    ['name' => $this->faker->words(3, true), 'calories' => $this->faker->numberBetween(150, 600)],
                    ['name' => $this->faker->words(3, true), 'calories' => $this->faker->numberBetween(150, 600)],
                ],
            ];
        }

        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'calories' => $this->faker->numberBetween(1200, 3000),
            'tags' => [$this->faker->randomElement($tags)],
            'is_template' => true,
            'days' => $days,
        ];
    }
}

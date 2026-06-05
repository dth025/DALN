<?php

namespace App\Policies;

use App\Models\MealPlan;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MealPlanPolicy
{
    use HandlesAuthorization;

    public function view(?User $user, MealPlan $mealPlan)
    {
        if ($mealPlan->is_template) {
            return true;
        }

        if (! $user) return false;

        return $user->id === $mealPlan->patient_id || optional($user->doctor)->id === $mealPlan->doctor_id;
    }

    public function create(User $user)
    {
        // Any authenticated user can create a personal meal plan; only doctors can create templates
        return (bool) $user;
    }

    public function assign(User $user, MealPlan $mealPlan)
    {
        // Only doctors can assign plans to patients
        return (bool) optional($user->doctor)->id;
    }

    public function update(User $user, MealPlan $mealPlan)
    {
        // Doctor who created it or the patient
        return $user->id === $mealPlan->patient_id || optional($user->doctor)->id === $mealPlan->doctor_id;
    }

    public function delete(User $user, MealPlan $mealPlan)
    {
        return optional($user->doctor)->id === $mealPlan->doctor_id;
    }
}

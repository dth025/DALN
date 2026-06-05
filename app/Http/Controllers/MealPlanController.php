<?php

namespace App\Http\Controllers;

use App\Models\MealPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MealPlanController extends Controller
{
    public function index()
    {
        // list public templates and user's assigned plans
        $user = Auth::user();
        $plans = MealPlan::where(function($q) use ($user) {
            $q->where('is_template', true)
              ->orWhere('patient_id', $user->id)
              ->orWhere('doctor_id', optional($user->doctor)->id);
        })->get();

        return response()->json($plans);
    }

    public function show($id)
    {
        $plan = MealPlan::findOrFail($id);
        return response()->json($plan);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'calories' => 'nullable|integer',
            'tags' => 'nullable|array',
            'days' => 'nullable|array',
            'patient_id' => 'nullable|integer',
            'is_template' => 'nullable|boolean',
        ]);

        $user = Auth::user();

        // If user is a doctor, record doctor_id
        if (optional($user)->doctor) {
            $data['doctor_id'] = $user->doctor->id;
        }

        $plan = MealPlan::create($data);

        return response()->json($plan, 201);
    }

    public function assignToPatient(Request $request, $id)
    {
        $user = Auth::user();
        $plan = MealPlan::findOrFail($id);

        $this->authorize('assign', $plan);

        $data = $request->validate([
            'patient_id' => 'required|integer',
        ]);

        $plan->patient_id = $data['patient_id'];
        $plan->doctor_id = $user->doctor->id;
        $plan->save();

        return response()->json($plan);
    }
}

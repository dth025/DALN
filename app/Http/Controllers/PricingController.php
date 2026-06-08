<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PricingController extends Controller
{
    public function index()
    {
        $reviews = \App\Models\Feedback::whereNull('parent_id')
            ->with(['replies' => function ($q) {
                $q->orderBy('created_at', 'asc');
            }, 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        $currentPlan = auth()->user()->plan ?? 'Free';

        return view('pricing', compact('reviews', 'currentPlan'));
    }
}

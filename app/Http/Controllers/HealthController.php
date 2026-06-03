<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class HealthController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        // Limit the amount of historical data sent to the view to avoid
        // heavy view serialization and potential max execution time errors.
        $metrics = \App\Models\HealthMetric::where('user_id', $user->id)
            ->orderBy('recorded_at', 'desc')
            ->take(30)
            ->get();

        // Normalize to a simple array keyed by YYYY-MM-DD and include only
        // the fields needed by the frontend to keep payload small.
        $history = $metrics->reverse()->mapWithKeys(function ($item) {
            $dateKey = substr($item->recorded_at, 0, 10);
            return [$dateKey => [
                'heart_rate' => $item->heart_rate,
                'steps' => $item->steps,
                'calories' => $item->calories,
                'weight' => $item->weight,
                'sleep_hours' => $item->sleep_hours,
                'spo2' => $item->spo2,
                'water_intake' => $item->water_intake,
                'burned' => $item->burned ?? 2000,
            ]];
        });

        return view('health', [
            'user' => $user,
            'history' => $history
        ]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        
        // Data for latest user profile
        $userData = $request->only([
            'heart_rate', 'spo2', 'water_intake', 'sleep_hours', 'weight', 'height', 'steps', 'calories'
        ]);
        $user->update($userData);

        // Data for historical tracking
        $metricData = $request->only([
            'heart_rate', 'spo2', 'water_intake', 'sleep_hours', 'weight', 'steps', 'calories'
        ]);
        
        $recordedAt = $request->input('recorded_at', now()->toDateString());
        
        // Ngăn chặn lưu dữ liệu cho tương lai
        if ($recordedAt > now()->format('Y-m-d')) {
            return response()->json(['error' => 'Chưa đến ngày này'], 422);
        }

        \App\Models\HealthMetric::updateOrCreate(
            ['user_id' => $user->id, 'recorded_at' => $recordedAt],
            array_merge($metricData, [
                'burned' => $request->input('burned', 2000)
            ])
        );

        if ($request->ajax()) {
            return response()->json(['message' => 'Cập nhật thành công!']);
        }

        return back()->with('status', 'health-updated');
    }

    // =============================================
    // QR PAIRING
    // =============================================

    /** Generate a one-time token and return it as JSON */
    public function generateQrToken(Request $request)
    {
        $token = Str::random(32);
        // Store userId keyed by token for 6 minutes
        Cache::put("qr_pair_{$token}", [
            'user_id' => auth()->id(),
            'data'    => null,
        ], 360);

        return response()->json([
            'token' => $token,
            'url'   => route('health.pair.page', $token),
        ]);
    }

    /** Web client polls this every 3s to detect mobile submission */
    public function pollQrData(Request $request)
    {
        $token = $request->query('token');
        $payload = Cache::get("qr_pair_{$token}");

        if (!$payload) {
            return response()->json(['status' => 'expired']);
        }

        if ($payload['data']) {
            // Remove token so it can't be reused
            Cache::forget("qr_pair_{$token}");
            return response()->json(['status' => 'ok', 'data' => $payload['data']]);
        }

        return response()->json(['status' => 'waiting']);
    }

    /** Mobile landing page after QR scan */
    public function pairPage($token)
    {
        $payload = Cache::get("qr_pair_{$token}");

        if (!$payload) {
            abort(410, 'Mã QR đã hết hạn. Vui lòng quét lại.');
        }

        return view('pair-mobile', ['token' => $token]);
    }

    /** Mobile submits data here */
    public function pairSubmit(Request $request, $token)
    {
        $payload = Cache::get("qr_pair_{$token}");

        if (!$payload) {
            return response()->json(['error' => 'Token hết hạn'], 410);
        }

        $data = $request->validate([
            'heart_rate'   => 'nullable|integer|min:30|max:250',
            'spo2'         => 'nullable|integer|min:50|max:100',
            'weight'       => 'nullable|numeric|min:1|max:500',
            'height'       => 'nullable|numeric|min:50|max:300',
            'water_intake' => 'nullable|numeric|min:0|max:20',
            'sleep_hours'  => 'nullable|numeric|min:0|max:24',
        ]);

        // Save to DB directly
        $user = \App\Models\User::find($payload['user_id']);
        if ($user) {
            $user->update(array_filter($data, fn($v) => $v !== null));

            // Sync to today's health_metrics
            $metricData = array_filter($data, fn($v, $k) => $v !== null && $k !== 'height', ARRAY_FILTER_USE_BOTH);
            if (!empty($metricData)) {
                \App\Models\HealthMetric::updateOrCreate(
                    ['user_id' => $user->id, 'recorded_at' => now()->toDateString()],
                    $metricData
                );
            }
        }

        // Signal web client
        Cache::put("qr_pair_{$token}", [
            'user_id' => $payload['user_id'],
            'data'    => $data,
        ], 60);

        return response()->json(['message' => 'Đồng bộ thành công!']);
    }
}

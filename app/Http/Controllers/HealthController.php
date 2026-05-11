<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class HealthController extends Controller
{
    public function index()
    {
        return view('health', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $user->update($request->only([
            'heart_rate', 'spo2', 'water_intake', 'sleep_hours', 'weight', 'height'
        ]));

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
        }

        // Signal web client
        Cache::put("qr_pair_{$token}", [
            'user_id' => $payload['user_id'],
            'data'    => $data,
        ], 60);

        return response()->json(['message' => 'Đồng bộ thành công!']);
    }
}

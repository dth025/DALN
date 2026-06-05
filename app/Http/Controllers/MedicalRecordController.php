<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MedicalRecord;
use App\Models\User;

class MedicalRecordController extends Controller
{
    public function saveRecord(Request $request)
    {
        if (!session()->has('doctor_logged_in')) {
            return response()->json(['success' => false, 'message' => 'Quyền truy cập bị từ chối!'], 403);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'diagnosis' => 'required|string',
            'symptoms' => 'required|string',
            'prescribed_medicine' => 'nullable|string',
            'exam_result' => 'nullable|string',
            'treatment_instructions' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $doctor = session('doctor_logged_in');
        $doctorId = is_array($doctor) ? ($doctor['id'] ?? null) : ($doctor->id ?? null);

        $record = MedicalRecord::create([
            'doctor_id' => $doctorId,
            'user_id' => $request->user_id,
            'diagnosis' => $request->diagnosis,
            'symptoms' => $request->symptoms,
            'exam_result' => $request->exam_result,
            'prescribed_medicine' => $request->prescribed_medicine,
            'treatment_instructions' => $request->treatment_instructions,
            'notes' => $request->notes,
            'recorded_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lưu hồ sơ bệnh án thành công!',
            'record' => $record
        ]);
    }

    public function getHistory($userId)
    {
        if (!session()->has('doctor_logged_in')) {
            return response()->json(['success' => false, 'message' => 'Quyền truy cập bị từ chối!'], 403);
        }

        $records = MedicalRecord::where('user_id', $userId)
            ->with('doctor')
            ->orderBy('recorded_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'records' => $records
        ]);
    }
}

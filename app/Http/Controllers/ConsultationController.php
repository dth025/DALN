<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consultation;
use Illuminate\Support\Facades\Storage;

class ConsultationController extends Controller
{
    public function getMessages($userId)
    {
        if (!session()->has('doctor_logged_in')) {
            return response()->json(['success' => false, 'message' => 'Quyền truy cập bị từ chối!'], 403);
        }

        $doctor = session('doctor_logged_in');
        $doctorId = is_array($doctor) ? ($doctor['id'] ?? null) : ($doctor->id ?? null);

        // Mark incoming user messages as read
        Consultation::where('doctor_id', $doctorId)
            ->where('user_id', $userId)
            ->where('sender', 'patient')
            ->update(['is_read' => true]);

        $messages = Consultation::where('doctor_id', $doctorId)
            ->where('user_id', $userId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'messages' => $messages
        ]);
    }

    public function sendMessage(Request $request)
    {
        if (!session()->has('doctor_logged_in')) {
            return response()->json(['success' => false, 'message' => 'Quyền truy cập bị từ chối!'], 403);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required_without:file|nullable|string',
            'file' => 'nullable|file|max:10240', // Max 10MB
        ]);

        $doctor = session('doctor_logged_in');
        $doctorId = is_array($doctor) ? ($doctor['id'] ?? null) : ($doctor->id ?? null);
        
        $filePath = null;
        $fileType = null;

        // Handling optional file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $extension = strtolower($file->getClientOriginalExtension());
            
            // Put in public storage
            $path = $file->store('consultation_files', 'public');
            $filePath = asset('storage/' . $path);
            
            if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
                $fileType = 'image';
            } else {
                $fileType = 'document';
            }
        }

        $messageText = $request->message ?: '';
        if (empty($messageText) && $filePath) {
            $messageText = ($fileType === 'image') ? 'Đã gửi một hình ảnh.' : 'Đã gửi một tài liệu.';
        }

        $consultation = Consultation::create([
            'doctor_id' => $doctorId,
            'user_id' => $request->user_id,
            'sender' => 'doctor',
            'message' => $messageText,
            'file_path' => $filePath,
            'file_type' => $fileType,
            'is_read' => false
        ]);

        return response()->json([
            'success' => true,
            'message' => $consultation
        ]);
    }
}

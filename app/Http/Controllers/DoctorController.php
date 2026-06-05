<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\MealPlan;
use App\Models\User;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Consultation;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class DoctorController extends Controller
{
    public function showRegister()
    {
        if (session()->has('doctor_logged_in')) {
            return redirect()->route('doctor.dashboard');
        }
        return view('doctor.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:doctors',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'required|string|max:20',
            'specialty' => 'required|string|max:255',
            'place' => 'required|string|max:255',
            'address' => 'nullable|string',
            'avatar' => 'nullable|string',
        ]);

        $doctor = Doctor::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'specialty' => $request->specialty,
            'place' => $request->place,
            'address' => $request->address,
            'avatar' => $request->avatar ?: 'https://ui-avatars.com/api/?name=' . urlencode($request->name) . '&background=0284c7&color=fff',
            'status' => 'active'
        ]);

        session(['doctor_logged_in' => $doctor]);

        return redirect()->route('doctor.dashboard')->with('success', 'Đăng ký tài khoản bác sĩ thành công!');
    }

    public function showLogin()
    {
        if (session()->has('doctor_logged_in')) {
            return redirect()->route('doctor.dashboard');
        }
        return view('doctor.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $doctor = Doctor::where('email', $request->email)->first();

        if ($doctor && Hash::check($request->password, $doctor->password)) {
            if ($doctor->status === 'blocked') {
                return back()->withErrors(['email' => 'Tài khoản bác sĩ của bạn đang bị khóa!'])->withInput();
            }

            session(['doctor_logged_in' => $doctor]);
            return redirect()->route('doctor.dashboard');
        }

        return back()->withErrors(['email' => 'Email hoặc mật khẩu không chính xác!'])->withInput();
    }

    public function logout()
    {
        session()->forget('doctor_logged_in');
        return redirect()->route('doctor.login');
    }

    public function index()
    {
        if (!session()->has('doctor_logged_in')) {
            return redirect()->route('doctor.login');
        }

        $doctorSession = session('doctor_logged_in');
        $doctorId = is_array($doctorSession) ? ($doctorSession['id'] ?? null) : ($doctorSession->id ?? null);
        if (!$doctorId) {
            session()->forget('doctor_logged_in');
            return redirect()->route('doctor.login');
        }
        $doctor = Doctor::findOrFail($doctorId);

        // Retrieve patients list
        $patients = User::all()->map(function ($patient) {
            // Calculate BMI
            $bmi = 0;
            if ($patient->height > 0 && $patient->weight > 0) {
                $heightInMeters = $patient->height / 100;
                $bmi = round($patient->weight / ($heightInMeters * $heightInMeters), 1);
            }
            $patient->bmi = $bmi;
            return $patient;
        });

        // Appointments for this doctor
        $appointments = Appointment::where(function($query) use ($doctor) {
                $query->where('doctor_id', $doctor->id)
                      ->orWhere('doctor_name', $doctor->name);
            })
            ->with('patient')
            ->orderBy('appointment_date', 'asc')
            ->get();

        // Statistics
        $totalPatients = $patients->count();
        
        $todayAppointmentsCount = Appointment::where(function($query) use ($doctor) {
                $query->where('doctor_id', $doctor->id)
                      ->orWhere('doctor_name', $doctor->name);
            })
            ->whereDate('appointment_date', today())
            ->count();

        $completedExamsCount = Appointment::where(function($query) use ($doctor) {
                $query->where('doctor_id', $doctor->id)
                      ->orWhere('doctor_name', $doctor->name);
            })
            ->where('status', 'completed')
            ->count();

        $pendingExamsCount = Appointment::where(function($query) use ($doctor) {
                $query->where('doctor_id', $doctor->id)
                      ->orWhere('doctor_name', $doctor->name);
            })
            ->where('status', 'scheduled')
            ->count();

        // Previous Medical Records recorded by this doctor
        $medicalRecords = MedicalRecord::where('doctor_id', $doctor->id)
            ->with('patient')
            ->orderBy('recorded_at', 'desc')
            ->get();

        // Notifications - new appointments, new patients, abnormal health alerts
        $notifications = [
            [
                'title' => 'Lịch khám mới',
                'message' => 'Bệnh nhân Nguyễn Văn A vừa đặt lịch khám lúc 09:00 ngày mai.',
                'time' => '5 phút trước',
                'type' => 'appointment'
            ],
            [
                'title' => 'Cảnh báo sức khỏe',
                'message' => 'Bệnh nhân Trần Thị B có nhịp tim bất thường (112 bpm).',
                'time' => '20 phút trước',
                'type' => 'alert'
            ],
            [
                'title' => 'Bệnh nhân mới',
                'message' => 'Lê Hoàng C vừa đăng ký tài khoản và cập nhật chỉ số sinh học.',
                'time' => '1 giờ trước',
                'type' => 'patient'
            ]
        ];

        return view('doctor.index', compact(
            'doctor',
            'patients',
            'appointments',
            'totalPatients',
            'todayAppointmentsCount',
            'completedExamsCount',
            'pendingExamsCount',
            'medicalRecords',
            'notifications'
        ));
    }

    public function updateProfile(Request $request)
    {
        if (!session()->has('doctor_logged_in')) {
            return response()->json(['success' => false, 'message' => 'Quyền truy cập bị từ chối!'], 403);
        }

        $doctorSession = session('doctor_logged_in');
        $doctorId = is_array($doctorSession) ? ($doctorSession['id'] ?? null) : ($doctorSession->id ?? null);
        if (!$doctorId) {
            return response()->json(['success' => false, 'message' => 'Quyền truy cập bị từ chối!'], 403);
        }
        $doctor = Doctor::findOrFail($doctorId);

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'specialty' => 'required|string|max:255',
            'place' => 'required|string|max:255',
            'address' => 'nullable|string',
            'avatar' => 'nullable|string',
        ]);

        $doctor->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'specialty' => $request->specialty,
            'place' => $request->place,
            'address' => $request->address,
            'avatar' => $request->avatar ?: $doctor->avatar,
        ]);

        // Refresh session
        session(['doctor_logged_in' => $doctor]);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật thông tin hồ sơ bác sĩ thành công!',
            'doctor' => $doctor
        ]);
    }

    public function saveRecommendation(Request $request)
    {
        if (!session()->has('doctor_logged_in')) {
            return response()->json(['success' => false, 'message' => 'Quyền truy cập bị từ chối!'], 403);
        }

        $doctorSession = session('doctor_logged_in');
        $doctorId = is_array($doctorSession) ? ($doctorSession['id'] ?? null) : ($doctorSession->id ?? null);
        if (!$doctorId) {
            return response()->json(['success' => false, 'message' => 'Quyền truy cập bị từ chối!'], 403);
        }

        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'advice' => 'required|string|max:2000',
            'meals' => 'nullable', // can be string (JSON) or array
        ]);

        $meals = null;
        if (isset($data['meals']) && $data['meals'] !== null && $data['meals'] !== '') {
            // Accept array directly (from client) or JSON string
            if (is_array($data['meals'])) {
                $meals = $data['meals'];
            } else {
                try {
                    $decoded = json_decode($data['meals'], true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $meals = $decoded;
                    }
                } catch (\Exception $e) {
                    $meals = null;
                }
            }
        }

        $rec = \App\Models\DoctorRecommendation::create([
            'doctor_id' => $doctorId,
            'user_id' => $data['user_id'],
            'advice' => $data['advice'],
            'meals' => $meals,
        ]);

        // If meals were provided, convert into a MealPlan and save
        $createdMealPlan = null;
        if (!empty($meals) && is_array($meals)) {
            // Determine if meals is already structured as days (each item has 'meals')
            $isDays = false;
            if (count($meals) > 0 && isset($meals[0]) && is_array($meals[0]) && array_key_exists('meals', $meals[0])) {
                $days = $meals;
                $isDays = true;
            } else {
                // Treat as single-day meals array
                $days = [ ['meals' => $meals] ];
            }

            // Calculate total calories if provided
            $totalCalories = 0;
            foreach ($days as $day) {
                if (!empty($day['meals']) && is_array($day['meals'])) {
                    foreach ($day['meals'] as $m) {
                        if (is_array($m)) {
                            if (isset($m['kcal'])) $totalCalories += (int) $m['kcal'];
                            elseif (isset($m['calories'])) $totalCalories += (int) $m['calories'];
                        }
                    }
                }
            }

            $planTitle = 'Chế độ ăn bác sĩ đề xuất - ' . now()->format('Y-m-d');

            $createdMealPlan = MealPlan::create([
                'title' => $planTitle,
                'description' => substr($data['advice'], 0, 1000),
                'calories' => $totalCalories > 0 ? $totalCalories : null,
                'tags' => ['from-doctor'],
                'doctor_id' => $doctorId,
                'patient_id' => $data['user_id'],
                'is_template' => false,
                'days' => $days,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Lưu đề xuất thành công',
            'recommendation' => $rec,
            'meal_plan' => $createdMealPlan,
        ]);
    }

    public function toggleAppointmentStatus(Request $request, $id)
    {
        if (!session()->has('doctor_logged_in')) {
            return response()->json(['success' => false, 'message' => 'Quyền truy cập bị từ chối!'], 403);
        }

        $request->validate([
            'status' => 'required|in:scheduled,completed,canceled'
        ]);

        $appointment = Appointment::findOrFail($id);
        $appointment->status = $request->status;
        $appointment->save();

        $statusText = 'lên lịch lại';
        if ($request->status === 'completed') $statusText = 'hoàn thành';
        if ($request->status === 'canceled') $statusText = 'hủy';

        return response()->json([
            'success' => true,
            'message' => "Đã cập nhật trạng thái lịch khám thành {$statusText}!"
        ]);
    }

    // Dev self-healing migration trigger
    public function devMigrate()
    {
        try {
            \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            
            // Seed a default doctor with password for testing
            $defaultDoctorEmail = 'doctor@healthai.vn';
            $existing = Doctor::where('email', $defaultDoctorEmail)->first();
            if (!$existing) {
                Doctor::create([
                    'name' => 'BS. Nguyễn Văn Minh',
                    'specialty' => 'Tim mạch',
                    'email' => $defaultDoctorEmail,
                    'phone' => '0912111222',
                    'password' => Hash::make('doctor123'),
                    'avatar' => 'https://i.pravatar.cc/100?img=68',
                    'place' => 'Vinmec Times City',
                    'address' => '458 Minh Khai, Hai Bà Trưng, Hà Nội',
                    'status' => 'active'
                ]);
            } else {
                // Ensure it has a password
                if (empty($existing->password)) {
                    $existing->password = Hash::make('doctor123');
                    $existing->save();
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Tự động nâng cấp Cơ sở dữ liệu và Seeder thành công! Tài khoản test: doctor@healthai.vn / doctor123'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi tự động nâng cấp: ' . $e->getMessage()
            ], 500);
        }
    }
}

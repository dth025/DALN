<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Appointment;
use App\Models\HealthMetric;
use App\Models\Doctor;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        if (!session()->has('admin_logged_in')) {
            return view('admin.login');
        }

        // Self-healing database check (Ensure columns and doctors table exist)
        try {
            if (!\Illuminate\Support\Facades\Schema::hasColumn('users', 'plan') || 
                !\Illuminate\Support\Facades\Schema::hasColumn('users', 'status') || 
                !\Illuminate\Support\Facades\Schema::hasTable('doctors')) {
                \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            }

            // Seed default doctors if empty
            if (\Illuminate\Support\Facades\Schema::hasTable('doctors') && \App\Models\Doctor::count() === 0) {
                \App\Models\Doctor::create([
                    'name' => 'BS. Nguyễn Văn Minh',
                    'specialty' => 'Tim mạch',
                    'email' => 'nguyenvanminh@healthai.vn',
                    'phone' => '0912111222',
                    'avatar' => 'https://i.pravatar.cc/100?img=68',
                    'place' => 'Vinmec Times City',
                    'status' => 'active'
                ]);
                \App\Models\Doctor::create([
                    'name' => 'BS. Trần Thị Hoa',
                    'specialty' => 'Dinh dưỡng',
                    'email' => 'tranthihoa@healthai.vn',
                    'phone' => '0987333444',
                    'avatar' => 'https://i.pravatar.cc/100?img=47',
                    'place' => 'Tư vấn video (Online)',
                    'status' => 'active'
                ]);
                \App\Models\Doctor::create([
                    'name' => 'BS. Lê Hoàng Nam',
                    'specialty' => 'Da liễu',
                    'email' => 'lehoangnam@healthai.vn',
                    'phone' => '0905555666',
                    'avatar' => 'https://i.pravatar.cc/100?img=12',
                    'place' => 'Bệnh viện Bạch Mai',
                    'status' => 'active'
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Self-healing migrations/seeds failed: ' . $e->getMessage());
        }

        // 1. Lấy dữ liệu tổng quan
        $totalUsers = User::count();
        $activeToday = User::where('updated_at', '>=', now()->startOfDay())->count();
        
        // Mock Doanh thu
        $totalRevenue = 48250000; // VNĐ
        $revenueToday = 1450000;
        
        // Mock Gói dịch vụ đã bán
        $packagesSold = [
            'free' => User::where('plan', 'Free')->count(),
            'pro' => User::where('plan', 'Pro')->count(),
            'premium' => User::where('plan', 'Premium')->count()
        ];
        
        // Mock AI phân tích
        $aiAnalysesToday = 142;
        
        // 2. Danh sách người dùng từ Database
        $users = User::all();
        $usersList = $users->map(function ($user) {
            // Lấy 3 chỉ số sức khỏe gần nhất
            $recentMetrics = HealthMetric::where('user_id', $user->id)
                ->orderBy('recorded_at', 'desc')
                ->take(3)
                ->get();

            $activity = $recentMetrics->map(function ($metric) {
                return [
                    'date' => $metric->recorded_at,
                    'steps' => $metric->steps ?? 0,
                    'sleep' => (float)($metric->sleep_hours ?? 0)
                ];
            })->toArray();

            // Tính BMI
            $bmi = 0;
            if ($user->height > 0 && $user->weight > 0) {
                $heightInMeters = $user->height / 100;
                $bmi = round($user->weight / ($heightInMeters * $heightInMeters), 1);
            }

            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? 'N/A',
                'plan' => $user->plan ?? 'Free',
                'status' => $user->status ?? 'active',
                'created_at' => $user->created_at ? $user->created_at->format('Y-m-d') : 'N/A',
                'avatar' => $user->avatar ? (str_starts_with($user->avatar, 'http') ? $user->avatar : asset('storage/' . $user->avatar)) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=6366f1&color=fff',
                'dob' => $user->dob ?? 'N/A',
                'gender' => $user->gender ?? 'N/A',
                'height' => (float)($user->height ?? 0),
                'weight' => (float)($user->weight ?? 0),
                'blood_type' => $user->blood_type ?? 'N/A',
                'bmi' => $bmi,
                'heart_rate' => $user->heart_rate ?? 0,
                'spo2' => $user->spo2 ?? 0,
                'activity' => $activity
            ];
        })->toArray();

        // 3. Danh sách gói dịch vụ
        $packagesList = [
            [
                'id' => 1,
                'name' => 'Free',
                'price' => 0,
                'duration' => 'Trọn đời',
                'subscribers' => User::where('plan', 'Free')->count(),
                'status' => 'active',
                'features' => ['Theo dõi sức khỏe cơ bản', 'AI Chatbot 20 câu/ngày', 'Báo cáo tuần cơ bản'],
                'color' => 'from-slate-500 to-slate-700',
                'icon' => 'sparkles'
            ],
            [
                'id' => 2,
                'name' => 'Pro',
                'price' => 149000,
                'duration' => '1 tháng',
                'subscribers' => User::where('plan', 'Pro')->count(),
                'status' => 'active',
                'features' => ['Theo dõi sức khỏe chi tiết', 'AI Chatbot không giới hạn', 'Thực đơn AI & Luyện tập AI', 'Hỗ trợ 24/7'],
                'color' => 'from-indigo-500 to-blue-600',
                'icon' => 'shield-check'
            ],
            [
                'id' => 3,
                'name' => 'Premium',
                'price' => 299000,
                'duration' => '1 tháng',
                'subscribers' => User::where('plan', 'Premium')->count(),
                'status' => 'active',
                'features' => ['Tất cả tính năng của gói Pro', 'Lịch khám ưu tiên với bác sĩ', 'Phân tích Gen & Đề xuất nâng cao', 'Trợ lý Y tế riêng bằng AI'],
                'color' => 'from-violet-600 to-purple-800',
                'icon' => 'award'
            ]
        ];

        // 4. Danh sách phản hồi (Feedback) từ Database
        $feedbacksFromDb = \App\Models\Feedback::whereNull('parent_id')
            ->with(['replies', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        $feedbacksList = [];
        foreach ($feedbacksFromDb as $fb) {
            $adminReply = $fb->replies->where('is_admin_reply', true)->first();
            $feedbacksList[] = [
                'id' => $fb->id,
                'name' => $fb->user ? $fb->user->name : ($fb->guest_name ?? 'Khách'),
                'avatar' => $fb->user ? ($fb->user->avatar ? asset('storage/' . $fb->user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($fb->user->name) . '&background=6366f1&color=fff') : ($fb->guest_avatar ?? 'https://ui-avatars.com/api/?name=Guest&background=64748b&color=fff'),
                'content' => $fb->content,
                'rating' => $fb->rating,
                'created_at' => $fb->created_at->format('Y-m-d'),
                'status' => $adminReply ? 'replied' : 'pending',
                'reply' => $adminReply ? $adminReply->content : null,
                'likes_count' => $fb->likes_count,
                'dislikes_count' => $fb->dislikes_count,
                'replies' => $fb->replies->map(function($r) {
                    return [
                        'id' => $r->id,
                        'name' => $r->user ? $r->user->name : ($r->guest_name ?? 'Khách'),
                        'avatar' => $r->user ? ($r->user->avatar ? asset('storage/' . $r->user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($r->user->name) . '&background=6366f1&color=fff') : ($r->guest_avatar ?? 'https://ui-avatars.com/api/?name=Guest&background=64748b&color=fff'),
                        'content' => $r->content,
                        'is_admin_reply' => $r->is_admin_reply,
                        'created_at' => $r->created_at->format('Y-m-d H:i'),
                        'likes_count' => $r->likes_count,
                        'dislikes_count' => $r->dislikes_count
                    ];
                })->toArray()
            ];
        }

        // 5. Giao dịch gần đây (Recent transactions)
        $recentTransactions = [
            ['id' => 'TXN-9842', 'name' => 'Nguyễn Văn A', 'plan' => 'Premium', 'amount' => 299000, 'date' => '2026-05-28 14:32', 'status' => 'success'],
            ['id' => 'TXN-9841', 'name' => 'Trần Thị B', 'plan' => 'Pro', 'amount' => 149000, 'date' => '2026-05-28 09:15', 'status' => 'success'],
            ['id' => 'TXN-9840', 'name' => 'Phạm Minh D', 'plan' => 'Premium', 'amount' => 299000, 'date' => '2026-05-27 18:40', 'status' => 'success'],
            ['id' => 'TXN-9839', 'name' => 'Lê Hoàng C', 'plan' => 'Pro', 'amount' => 149000, 'date' => '2026-05-26 11:22', 'status' => 'failed'],
            ['id' => 'TXN-9838', 'name' => 'Vũ Hải E', 'plan' => 'Pro', 'amount' => 149000, 'date' => '2026-05-25 08:05', 'status' => 'success'],
        ];

        // 6. Thống kê biểu đồ doanh thu theo tháng (Chart.js)
        $monthlyRevenue = [
            'labels' => ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5'],
            'data' => [12500000, 18200000, 22400000, 31200000, 48250000]
        ];

        // 7. Hoạt động hệ thống gần đây (Activity logs)
        $activityLogs = [
            ['time' => '2 phút trước', 'icon' => 'user-plus', 'color' => 'text-primary bg-primary/10', 'text' => 'Người dùng mới Nguyễn Văn An đăng ký tài khoản.'],
            ['time' => '15 phút trước', 'icon' => 'credit-card', 'color' => 'text-success bg-success/10', 'text' => 'Giao dịch thành công gói Premium từ người dùng Phạm Minh D.'],
            ['time' => '1 giờ trước', 'icon' => 'message-square', 'color' => 'text-amber-500 bg-amber-500/10', 'text' => 'Phản hồi mới từ Lê Hoàng C về việc hỗ trợ Garmin.'],
            ['time' => '3 giờ trước', 'icon' => 'alert-triangle', 'color' => 'text-rose-500 bg-rose-500/10', 'text' => 'Hệ thống AI ghi nhận tải lượng CPU cao (>85%).'],
        ];

        $doctorsList = Doctor::all()->toArray();

        return view('admin.index', compact(
            'totalUsers',
            'activeToday',
            'totalRevenue',
            'revenueToday',
            'packagesSold',
            'aiAnalysesToday',
            'usersList',
            'packagesList',
            'feedbacksList',
            'recentTransactions',
            'monthlyRevenue',
            'activityLogs',
            'doctorsList'
        ));
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($request->email === 'admin@healthai.vn' && $request->password === 'admin123') {
            session(['admin_logged_in' => true]);
            return redirect()->route('admin.index');
        }

        return back()->withErrors([
            'email' => 'Tài khoản hoặc mật khẩu admin không chính xác!',
        ])->withInput($request->only('email'));
    }

    public function logout()
    {
        session()->forget('admin_logged_in');
        return redirect()->route('admin.index');
    }

    public function toggleUserStatus($id)
    {
        if (!session()->has('admin_logged_in')) {
            return response()->json(['success' => false, 'message' => 'Quyền truy cập bị từ chối!'], 403);
        }

        $user = User::findOrFail($id);
        $user->status = ($user->status === 'blocked') ? 'active' : 'blocked';
        $user->save();

        $actionText = ($user->status === 'blocked') ? 'khóa' : 'mở khóa';
        return response()->json([
            'success' => true,
            'message' => "Đã {$actionText} thành công tài khoản {$user->name}!",
            'status' => $user->status
        ]);
    }

    public function deleteUser($id)
    {
        if (!session()->has('admin_logged_in')) {
            return response()->json(['success' => false, 'message' => 'Quyền truy cập bị từ chối!'], 403);
        }

        $user = User::findOrFail($id);
        $name = $user->name;
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => "Đã xóa thành công tài khoản {$name}!"
        ]);
    }

    public function saveDoctor(Request $request)
    {
        if (!session()->has('admin_logged_in')) {
            return response()->json(['success' => false, 'message' => 'Quyền truy cập bị từ chối!'], 403);
        }

        $request->validate([
            'name' => 'required',
            'specialty' => 'required',
            'place' => 'required',
        ]);

        if ($request->has('id') && $request->id) {
            $doctor = Doctor::findOrFail($request->id);
            $message = 'Cập nhật thông tin bác sĩ thành công!';
        } else {
            $doctor = new Doctor();
            $message = 'Thêm bác sĩ mới thành công!';
        }

        $doctor->name = $request->name;
        $doctor->specialty = $request->specialty;
        $doctor->email = $request->email;
        $doctor->phone = $request->phone;
        $doctor->place = $request->place;
        $doctor->avatar = $request->avatar ?: 'https://ui-avatars.com/api/?name=' . urlencode($request->name) . '&background=10b981&color=fff';
        $doctor->status = $request->status ?: 'active';
        $doctor->save();

        return response()->json([
            'success' => true,
            'message' => $message,
            'doctor' => $doctor
        ]);
    }

    public function toggleDoctorStatus($id)
    {
        if (!session()->has('admin_logged_in')) {
            return response()->json(['success' => false, 'message' => 'Quyền truy cập bị từ chối!'], 403);
        }

        $doctor = Doctor::findOrFail($id);
        $doctor->status = ($doctor->status === 'blocked') ? 'active' : 'blocked';
        $doctor->save();

        $actionText = ($doctor->status === 'blocked') ? 'khóa' : 'mở khóa';
        return response()->json([
            'success' => true,
            'message' => "Đã {$actionText} thành công bác sĩ {$doctor->name}!",
            'status' => $doctor->status
        ]);
    }

    public function deleteDoctor($id)
    {
        if (!session()->has('admin_logged_in')) {
            return response()->json(['success' => false, 'message' => 'Quyền truy cập bị từ chối!'], 403);
        }

        $doctor = Doctor::findOrFail($id);
        $name = $doctor->name;
        $doctor->delete();

        return response()->json([
            'success' => true,
            'message' => "Đã xóa thành công bác sĩ {$name}!"
        ]);
    }
}

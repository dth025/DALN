<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Appointment;
use App\Models\HealthMetric;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        if (!session()->has('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        // 1. Lấy dữ liệu tổng quan
        $totalUsers = User::count() + 142; // Cộng thêm số ảo cho sinh động
        $activeToday = User::where('updated_at', '>=', now()->startOfDay())->count() + 37;
        
        // Mock Doanh thu
        $totalRevenue = 48250000; // VNĐ
        $revenueToday = 1450000;
        
        // Mock Gói dịch vụ đã bán
        $packagesSold = [
            'free' => 84,
            'pro' => 45,
            'premium' => 28
        ];
        
        // Mock AI phân tích
        $aiAnalysesToday = 142;
        
        // 2. Danh sách người dùng (Mock dữ liệu chi tiết cho Admin)
        $usersList = [
            [
                'id' => 1,
                'name' => 'Nguyễn Văn A',
                'email' => 'nguyenvana@gmail.com',
                'phone' => '0912345678',
                'plan' => 'Premium',
                'status' => 'active',
                'created_at' => '2026-01-15',
                'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=150&h=150&q=80',
                'dob' => '1995-05-12',
                'gender' => 'Nam',
                'height' => 175,
                'weight' => 70,
                'blood_type' => 'O+',
                'bmi' => 22.9,
                'heart_rate' => 72,
                'spo2' => 99,
                'activity' => [
                    ['date' => '2026-05-28', 'steps' => 8400, 'sleep' => 7.5],
                    ['date' => '2026-05-27', 'steps' => 10200, 'sleep' => 8.0],
                    ['date' => '2026-05-26', 'steps' => 7200, 'sleep' => 6.8]
                ]
            ],
            [
                'id' => 2,
                'name' => 'Trần Thị B',
                'email' => 'tranthib@gmail.com',
                'phone' => '0987654321',
                'plan' => 'Pro',
                'status' => 'active',
                'created_at' => '2026-02-20',
                'avatar' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=150&h=150&q=80',
                'dob' => '1998-10-05',
                'gender' => 'Nữ',
                'height' => 160,
                'weight' => 52,
                'blood_type' => 'A+',
                'bmi' => 20.3,
                'heart_rate' => 78,
                'spo2' => 98,
                'activity' => [
                    ['date' => '2026-05-28', 'steps' => 6100, 'sleep' => 8.2],
                    ['date' => '2026-05-27', 'steps' => 5400, 'sleep' => 7.0],
                    ['date' => '2026-05-26', 'steps' => 9000, 'sleep' => 7.5]
                ]
            ],
            [
                'id' => 3,
                'name' => 'Lê Hoàng C',
                'email' => 'lehoangc@gmail.com',
                'phone' => '0905556677',
                'plan' => 'Free',
                'status' => 'blocked',
                'created_at' => '2026-03-02',
                'avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=150&h=150&q=80',
                'dob' => '1990-12-25',
                'gender' => 'Nam',
                'height' => 180,
                'weight' => 85,
                'blood_type' => 'B-',
                'bmi' => 26.2,
                'heart_rate' => 82,
                'spo2' => 96,
                'activity' => [
                    ['date' => '2026-05-28', 'steps' => 3200, 'sleep' => 5.5],
                    ['date' => '2026-05-27', 'steps' => 4100, 'sleep' => 6.0],
                    ['date' => '2026-05-26', 'steps' => 3800, 'sleep' => 5.8]
                ]
            ],
            [
                'id' => 4,
                'name' => 'Phạm Minh D',
                'email' => 'phamminhd@gmail.com',
                'phone' => '0933445566',
                'plan' => 'Premium',
                'status' => 'active',
                'created_at' => '2026-04-10',
                'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=150&h=150&q=80',
                'dob' => '1993-08-14',
                'gender' => 'Nữ',
                'height' => 165,
                'weight' => 56,
                'blood_type' => 'AB+',
                'bmi' => 20.6,
                'heart_rate' => 68,
                'spo2' => 99,
                'activity' => [
                    ['date' => '2026-05-28', 'steps' => 11000, 'sleep' => 8.0],
                    ['date' => '2026-05-27', 'steps' => 12500, 'sleep' => 7.8],
                    ['date' => '2026-05-26', 'steps' => 9500, 'sleep' => 8.5]
                ]
            ],
            [
                'id' => 5,
                'name' => 'Vũ Hải E',
                'email' => 'vuhaie@gmail.com',
                'phone' => '0977889900',
                'plan' => 'Free',
                'status' => 'active',
                'created_at' => '2026-05-01',
                'avatar' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=150&h=150&q=80',
                'dob' => '1987-03-30',
                'gender' => 'Nam',
                'height' => 170,
                'weight' => 74,
                'blood_type' => 'O-',
                'bmi' => 25.6,
                'heart_rate' => 74,
                'spo2' => 97,
                'activity' => [
                    ['date' => '2026-05-28', 'steps' => 4500, 'sleep' => 6.2],
                    ['date' => '2026-05-27', 'steps' => 5000, 'sleep' => 6.5],
                    ['date' => '2026-05-26', 'steps' => 4200, 'sleep' => 7.0]
                ]
            ]
        ];

        // 3. Danh sách gói dịch vụ
        $packagesList = [
            [
                'id' => 1,
                'name' => 'Free',
                'price' => 0,
                'duration' => 'Trọn đời',
                'subscribers' => 84,
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
                'subscribers' => 45,
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
                'subscribers' => 28,
                'status' => 'active',
                'features' => ['Tất cả tính năng của gói Pro', 'Lịch khám ưu tiên với bác sĩ', 'Phân tích Gen & Đề xuất nâng cao', 'Trợ lý Y tế riêng bằng AI'],
                'color' => 'from-violet-600 to-purple-800',
                'icon' => 'award'
            ]
        ];

        // 4. Danh sách phản hồi (Feedback)
        $feedbacksList = [
            [
                'id' => 1,
                'name' => 'Nguyễn Văn A',
                'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=150&h=150&q=80',
                'content' => 'Giao diện ứng dụng cực kỳ đẹp và mượt mà! Thích nhất chức năng Thực đơn AI rất sát thực tế.',
                'rating' => 5,
                'created_at' => '2026-05-28',
                'status' => 'pending'
            ],
            [
                'id' => 2,
                'name' => 'Trần Thị B',
                'avatar' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=150&h=150&q=80',
                'content' => 'Chatbot AI trả lời rất thông minh và hữu ích khi mình bị ho khan nửa đêm. Sẽ gia hạn gói Pro!',
                'rating' => 5,
                'created_at' => '2026-05-27',
                'status' => 'replied',
                'reply' => 'Chào bạn B, cảm ơn bạn đã tin tưởng HealthAI. Đội ngũ phát triển luôn nỗ lực hết mình để nâng cao trải nghiệm của bạn!'
            ],
            [
                'id' => 3,
                'name' => 'Lê Hoàng C',
                'avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=150&h=150&q=80',
                'content' => 'Thiếu phần kết nối với Smart Watch Garmin của mình. Mong sớm cập nhật.',
                'rating' => 4,
                'created_at' => '2026-05-25',
                'status' => 'replied',
                'reply' => 'Cảm ơn phản hồi của anh C. Tính năng đồng bộ Garmin đang được thử nghiệm và sẽ phát hành trong phiên bản tới.'
            ]
        ];

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
            'activityLogs'
        ));
    }

    public function showLoginForm()
    {
        if (session()->has('admin_logged_in')) {
            return redirect()->route('admin.index');
        }
        return view('admin.login');
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
        return redirect()->route('admin.login');
    }
}

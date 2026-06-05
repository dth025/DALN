<!DOCTYPE html>
<html lang="vi" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Doctor Dashboard — HealthAI</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            background-color: #090d1a;
            color: #e2e8f0;
        }
        .glass {
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .glass-sidebar {
            background: rgba(11, 19, 43, 0.9);
            backdrop-filter: blur(20px);
        }
        .shadow-glow {
            box-shadow: 0 0 25px -5px rgba(2, 132, 199, 0.25);
        }
        .gradient-primary {
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
        }
        .gradient-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        .gradient-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }
        .gradient-rose {
            background: linear-gradient(135deg, #f43f5e 0%, #e11d48 100%);
        }
        .gradient-text {
            background: linear-gradient(135deg, #38bdf8 0%, #0284c7 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="antialiased min-h-screen relative overflow-x-hidden">
    
    <!-- Background Glow Patterns -->
    <div class="pointer-events-none fixed inset-0 -z-10" style="background: radial-gradient(circle at 50% 50%, #0f172a 0%, #020617 100%);"></div>
    <div class="pointer-events-none fixed -top-40 -right-40 -z-10 h-96 w-96 rounded-full bg-sky-500/10 blur-[120px]"></div>
    <div class="pointer-events-none fixed -bottom-40 -left-40 -z-10 h-96 w-96 rounded-full bg-indigo-500/10 blur-[120px]"></div>

    <div class="flex min-h-screen">
        
        <!-- ================= SIDEBAR LEFT ================= -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 -translate-x-full transition-transform duration-300 lg:translate-x-0 glass-sidebar border-r border-slate-800 text-slate-300 flex flex-col">
            <!-- Sidebar Header -->
            <div class="p-6 border-b border-slate-800 flex items-center justify-between">
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl gradient-primary shadow-glow group-hover:scale-105 transition-transform">
                        <i data-lucide="heart-pulse" class="h-5 w-5 text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-base font-bold tracking-tight text-white">Health<span class="gradient-text">AI</span></h1>
                        <p class="text-[9px] font-medium uppercase tracking-wider text-sky-400 leading-none">Doctor Workspace</p>
                    </div>
                </a>
                <button onclick="toggleSidebar()" class="lg:hidden p-1.5 rounded-lg hover:bg-white/10 text-slate-400 hover:text-white">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
            </div>

            <!-- Profile Overview -->
            <div class="p-4 mx-4 mt-6 rounded-2xl bg-white/5 border border-white/5 flex items-center gap-3">
                <img src="{{ $doctor->avatar ?: 'https://ui-avatars.com/api/?name='.urlencode($doctor->name).'&background=0284c7&color=fff' }}" alt="doctor-avatar" class="h-10 w-10 rounded-full border border-white/15 object-cover">
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold text-white leading-tight truncate">{{ $doctor->name }}</p>
                    <p class="text-[9px] text-sky-400 font-semibold uppercase tracking-wider mt-0.5">{{ $doctor->specialty }}</p>
                </div>
            </div>

            <!-- Nav Menu Items -->
            <nav class="flex-1 p-4 space-y-1.5 mt-6 overflow-y-auto scrollbar-hide">
                <button onclick="switchTab('dashboard')" id="nav-dashboard" class="nav-btn w-full flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition-all text-white gradient-primary shadow-glow">
                    <i data-lucide="layout-dashboard" class="h-4 w-4"></i> Dashboard
                </button>
                
                <button onclick="switchTab('patients')" id="nav-patients" class="nav-btn w-full flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition-all text-slate-400 hover:bg-white/5 hover:text-white">
                    <i data-lucide="users" class="h-4 w-4"></i> Danh sách bệnh nhân
                </button>

                <button onclick="switchTab('medical-records')" id="nav-medical-records" class="nav-btn w-full flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition-all text-slate-400 hover:bg-white/5 hover:text-white">
                    <i data-lucide="folder-heart" class="h-4 w-4"></i> Hồ sơ bệnh án
                </button>
                
                <button onclick="switchTab('consultations')" id="nav-consultations" class="nav-btn w-full flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition-all text-slate-400 hover:bg-white/5 hover:text-white">
                    <i data-lucide="message-square" class="h-4 w-4"></i> Tư vấn sức khỏe
                </button>
                
                <button onclick="switchTab('health-stats')" id="nav-health-stats" class="nav-btn w-full flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition-all text-slate-400 hover:bg-white/5 hover:text-white">
                    <i data-lucide="trending-up" class="h-4 w-4"></i> Thống kê sức khỏe
                </button>

                <button onclick="switchTab('appointments')" id="nav-appointments" class="nav-btn w-full flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition-all text-slate-400 hover:bg-white/5 hover:text-white">
                    <i data-lucide="calendar" class="h-4 w-4"></i> Lịch hẹn khám
                </button>
                
                <button onclick="switchTab('notifications')" id="nav-notifications" class="nav-btn w-full flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition-all text-slate-400 hover:bg-white/5 hover:text-white justify-between">
                    <span class="flex items-center gap-3"><i data-lucide="bell" class="h-4 w-4"></i> Thông báo</span>
                    <span class="h-5 w-5 text-[10px] font-bold bg-rose-500/20 text-rose-400 rounded-full flex items-center justify-center">3</span>
                </button>
                
                <button onclick="switchTab('profile')" id="nav-profile" class="nav-btn w-full flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition-all text-slate-400 hover:bg-white/5 hover:text-white">
                    <i data-lucide="user" class="h-4 w-4"></i> Hồ sơ bác sĩ
                </button>
            </nav>

            <!-- Bottom Exit -->
            <div class="p-4 border-t border-slate-800">
                <form method="POST" action="{{ route('doctor.logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-rose-400 hover:bg-rose-500/10 transition-colors">
                        <i data-lucide="log-out" class="h-4 w-4"></i> Đăng xuất
                    </button>
                </form>
            </div>
        </aside>

        <!-- Sidebar backdrop for mobile -->
        <div id="sidebar-backdrop" onclick="toggleSidebar()" class="fixed inset-0 z-40 bg-black/60 backdrop-blur-sm hidden lg:hidden"></div>

        <!-- ================= MAIN CONTENT CONTAINER ================= -->
        <div class="flex-1 lg:pl-64 flex flex-col min-h-screen">
            
            <!-- HEADER TOPBAR -->
            <header class="sticky top-0 z-30 w-full border-b border-slate-800/80 bg-slate-950/80 backdrop-blur-xl px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between shadow-sm">
                <!-- Left: Sidebar trigger on mobile -->
                <div class="flex items-center gap-4">
                    <button onclick="toggleSidebar()" class="lg:hidden flex h-10 w-10 items-center justify-center rounded-xl bg-slate-900 border border-slate-800 text-slate-200 hover:bg-slate-800 transition-colors">
                        <i data-lucide="menu" class="h-5 w-5"></i>
                    </button>
                    <div>
                        <h2 class="text-lg font-black tracking-tight flex items-center gap-2">
                            Bác sĩ <span class="gradient-text">Workspace</span>
                        </h2>
                    </div>
                </div>

                <!-- Right Header Actions -->
                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-bold text-white">{{ $doctor->place }}</p>
                        <p class="text-[10px] text-slate-400 font-semibold mt-0.5">Hệ thống Y Tế HealthAI</p>
                    </div>
                    <img src="{{ $doctor->avatar }}" class="h-9 w-9 rounded-full border border-sky-500/20 object-cover shadow-glow">
                </div>
            </header>

            <!-- MAIN WORKSPACE -->
            <main class="flex-1 px-4 sm:px-6 lg:px-8 py-6 max-w-[1600px] w-full mx-auto space-y-6">

                <!-- ================= TAB: DASHBOARD ================= -->
                <section id="tab-dashboard" class="tab-pane space-y-6">
                    
                    <!-- Welcome Glow Card -->
                    <div class="relative overflow-hidden rounded-3xl border border-white/10 p-6 md:p-8 shadow-elevated animate-fade-in-up">
                        <div class="absolute inset-0 gradient-primary opacity-25"></div>
                        <div class="absolute inset-0 bg-[radial-gradient(circle_at_70%_120%,rgba(14,165,233,0.15),transparent_60%)]"></div>

                        <div class="relative flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                            <div>
                                <div class="inline-flex items-center gap-1.5 rounded-full border border-sky-400/30 bg-sky-400/10 px-3 py-1 text-[11px] font-bold text-sky-400 backdrop-blur-md">
                                    <i data-lucide="sparkles" class="h-3 w-3"></i>
                                    Không gian làm việc Bác sĩ
                                </div>
                                <h1 class="mt-4 text-2xl font-black md:text-3xl lg:text-4xl text-white">Xin chào, {{ $doctor->name }}</h1>
                                <p class="mt-2 text-sm text-slate-300 max-w-xl">Hôm nay có lịch khám mới của bạn. AI ghi nhận điểm sức khỏe trung bình của bệnh nhân đạt 78.4 điểm.</p>
                            </div>
                            <div class="rounded-2xl border border-sky-500/20 bg-sky-500/5 p-5 text-center backdrop-blur-md">
                                <p class="text-[10px] font-black uppercase tracking-wider text-sky-400">Chuyên Khoa khám</p>
                                <p class="text-2xl font-black mt-1 tracking-tight text-white">{{ $doctor->specialty }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics Summary Grid -->
                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                        <!-- Card 1 -->
                        <div class="glass rounded-2xl p-5 hover:border-sky-500/30 transition-all duration-300 relative group overflow-hidden">
                            <div class="flex items-center justify-between">
                                <div class="h-10 w-10 rounded-xl gradient-primary text-white flex items-center justify-center shadow-glow">
                                    <i data-lucide="users" class="h-5 w-5"></i>
                                </div>
                            </div>
                            <p class="mt-4 text-[10px] font-black uppercase tracking-wider text-slate-400">Tổng số bệnh nhân</p>
                            <p class="text-3xl font-black tracking-tight mt-1 text-white">{{ $totalPatients }}</p>
                        </div>
                        
                        <!-- Card 2 -->
                        <div class="glass rounded-2xl p-5 hover:border-sky-500/30 transition-all duration-300 relative group overflow-hidden">
                            <div class="flex items-center justify-between">
                                <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 text-white flex items-center justify-center shadow-glow">
                                    <i data-lucide="calendar" class="h-5 w-5"></i>
                                </div>
                            </div>
                            <p class="mt-4 text-[10px] font-black uppercase tracking-wider text-slate-400">Lịch hẹn hôm nay</p>
                            <p class="text-3xl font-black tracking-tight mt-1 text-white">{{ $todayAppointmentsCount }}</p>
                        </div>

                        <!-- Card 3 -->
                        <div class="glass rounded-2xl p-5 hover:border-sky-500/30 transition-all duration-300 relative group overflow-hidden">
                            <div class="flex items-center justify-between">
                                <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white flex items-center justify-center shadow-glow">
                                    <i data-lucide="check-square" class="h-5 w-5"></i>
                                </div>
                            </div>
                            <p class="mt-4 text-[10px] font-black uppercase tracking-wider text-slate-400">Ca khám hoàn thành</p>
                            <p class="text-3xl font-black tracking-tight mt-1 text-white">{{ $completedExamsCount }}</p>
                        </div>

                        <!-- Card 4 -->
                        <div class="glass rounded-2xl p-5 hover:border-sky-500/30 transition-all duration-300 relative group overflow-hidden">
                            <div class="flex items-center justify-between">
                                <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-sky-500 to-indigo-600 text-white flex items-center justify-center shadow-glow">
                                    <i data-lucide="clock" class="h-5 w-5"></i>
                                </div>
                            </div>
                            <p class="mt-4 text-[10px] font-black uppercase tracking-wider text-slate-400">Ca khám đang chờ</p>
                            <p class="text-3xl font-black tracking-tight mt-1 text-white">{{ $pendingExamsCount }}</p>
                        </div>
                    </div>

                    <div class="grid gap-6 lg:grid-cols-2">
                        <!-- Recent Diagnoses logs -->
                        <div class="glass rounded-3xl p-6 flex flex-col justify-between">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-base font-bold text-white flex items-center gap-2">
                                    <i data-lucide="folder-heart" class="text-sky-400 h-5 w-5"></i> Ghi chép bệnh án gần đây
                                </h3>
                                <button onclick="switchTab('medical-records')" class="text-xs font-bold text-sky-400 hover:underline">Xem thêm &rarr;</button>
                            </div>
                            <div class="space-y-4">
                                @forelse($medicalRecords->take(3) as $record)
                                <div class="bg-white/5 border border-white/5 rounded-2xl p-4 text-xs font-semibold">
                                    <div class="flex justify-between items-start mb-2">
                                        <p class="text-white font-bold">{{ $record->patient->name }}</p>
                                        <span class="text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($record->recorded_at)->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-slate-300 font-normal"><span class="font-bold text-sky-400">Triệu chứng:</span> {{ $record->symptoms }}</p>
                                    <p class="text-slate-300 font-normal mt-1"><span class="font-bold text-emerald-400">Chẩn đoán:</span> {{ $record->diagnosis }}</p>
                                </div>
                                @empty
                                <div class="text-center py-6 text-slate-400">Chưa ghi chép bệnh án nào.</div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Today Appointments -->
                        <div class="glass rounded-3xl p-6 flex flex-col justify-between">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-base font-bold text-white flex items-center gap-2">
                                    <i data-lucide="calendar" class="text-amber-500 h-5 w-5"></i> Lịch hẹn hôm nay
                                </h3>
                                <button onclick="switchTab('appointments')" class="text-xs font-bold text-sky-400 hover:underline">Quản lý lịch &rarr;</button>
                            </div>
                            <div class="space-y-4">
                                @forelse($appointments->where('appointment_date', '>=', now()->startOfDay())->where('appointment_date', '<=', now()->endOfDay())->take(3) as $appt)
                                <div class="bg-white/5 border border-white/5 rounded-2xl p-4 text-xs flex justify-between items-center">
                                    <div>
                                        <p class="font-bold text-white">{{ $appt->patient->name }}</p>
                                        <p class="text-slate-400 mt-1 flex items-center gap-1"><i data-lucide="clock" class="h-3 w-3"></i> {{ \Carbon\Carbon::parse($appt->appointment_date)->format('H:i') }}</p>
                                    </div>
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $appt->status === 'completed' ? 'bg-emerald-500/10 text-emerald-400' : ($appt->status === 'canceled' ? 'bg-rose-500/10 text-rose-400' : 'bg-amber-500/10 text-amber-400') }}">
                                        {{ $appt->status === 'completed' ? 'Đã khám' : ($appt->status === 'canceled' ? 'Đã hủy' : 'Đang chờ') }}
                                    </span>
                                </div>
                                @empty
                                <div class="text-center py-6 text-slate-400">Không có lịch hẹn nào hôm nay.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </section>

                                <!-- Recommend Modal -->
                                <div id="recommendModal" style="display:none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
                                    <div class="relative w-full max-w-2xl rounded-2xl bg-slate-900 p-6 shadow-lg text-slate-200">
                                        <button onclick="closeRecommendModal()" class="absolute right-3 top-3 text-slate-400">✕</button>
                                        <h3 class="text-lg font-semibold mb-3">Gợi ý chế độ ăn cho bệnh nhân</h3>

                                        <form id="recommendForm">
                                            @csrf
                                            <input type="hidden" id="rec_user_id" name="user_id" />
                                            <div class="mb-3">
                                                <label class="block text-sm text-slate-400 mb-1">Bệnh nhân</label>
                                                <input type="text" id="rec_user_name" class="w-full rounded-lg border border-slate-700 bg-slate-800 px-3 py-2 text-slate-200" readonly />
                                            </div>
                                            <div class="mb-3">
                                                <label class="block text-sm text-slate-400 mb-1">Lời khuyên (advice)</label>
                                                <textarea id="rec_advice" name="advice" rows="4" class="w-full rounded-lg border border-slate-700 bg-slate-800 px-3 py-2 text-slate-200" required></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="block text-sm text-slate-400 mb-1">Danh sách bữa ăn</label>
                                                <div id="rec_meal_items" class="space-y-3"></div>
                                                <button type="button" onclick="addMealRow()" class="rounded-lg border px-4 py-2 text-sm">Thêm bữa mới</button>
                                                <p class="text-[11px] text-slate-400 mt-1">Nhập mô tả cho mỗi bữa ăn. Chỉ cần tối thiểu một bữa nếu muốn gửi chế độ ăn.</p>
                                            </div>
                                            <div class="flex justify-end gap-2 mt-4">
                                                <button type="button" onclick="closeRecommendModal()" class="rounded-lg border px-4 py-2">Hủy</button>
                                                <button type="button" onclick="loadSampleMeals()" class="rounded-lg border px-4 py-2">Load mẫu</button>
                                                <button type="button" onclick="submitRecommendation()" class="rounded-lg bg-emerald-500 px-4 py-2 text-black font-semibold">Gửi đề xuất</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <script>
                                    function openRecommendModal(userId, userName) {
                                        document.getElementById('rec_user_id').value = userId;
                                        document.getElementById('rec_user_name').value = userName;
                                        document.getElementById('rec_advice').value = '';
                                        renderMealRows([]);
                                        document.getElementById('recommendModal').style.display = 'flex';
                                    }
                                    function closeRecommendModal() {
                                        document.getElementById('recommendModal').style.display = 'none';
                                    }

                                    function createMealRow(meal = {}) {
                                        const container = document.getElementById('rec_meal_items');
                                        const row = document.createElement('div');
                                        row.className = 'meal-item-row grid gap-3 md:grid-cols-3 items-end';
                                        row.innerHTML = `
                                            <div>
                                                <label class="block text-sm text-slate-400 mb-1">Bữa</label>
                                                <input type="text" class="meal-label w-full rounded-lg border border-slate-700 bg-slate-800 px-3 py-2 text-slate-200" value="${meal.label ?? ''}" placeholder="Bữa sáng / Bữa trưa" />
                                            </div>
                                            <div>
                                                <label class="block text-sm text-slate-400 mb-1">Món ăn</label>
                                                <input type="text" class="meal-name w-full rounded-lg border border-slate-700 bg-slate-800 px-3 py-2 text-slate-200" value="${meal.name ?? ''}" placeholder="Yến mạch, ức gà..." />
                                            </div>
                                            <div class="flex flex-col gap-2">
                                                <div>
                                                    <label class="block text-sm text-slate-400 mb-1">Calories</label>
                                                    <input type="number" min="0" class="meal-kcal w-full rounded-lg border border-slate-700 bg-slate-800 px-3 py-2 text-slate-200" value="${meal.kcal ?? ''}" placeholder="400" />
                                                </div>
                                                <button type="button" onclick="removeMealRow(this)" class="rounded-lg border px-3 py-2 text-sm text-red-400">Xoá</button>
                                            </div>
                                        `;
                                        container.appendChild(row);
                                    }

                                    function addMealRow(meal = {}) {
                                        createMealRow(meal);
                                    }

                                    function removeMealRow(button) {
                                        const row = button.closest('.meal-item-row');
                                        if (row) {
                                            row.remove();
                                        }
                                    }

                                    function renderMealRows(meals = []) {
                                        const container = document.getElementById('rec_meal_items');
                                        container.innerHTML = '';
                                        if (meals.length === 0) {
                                            addMealRow();
                                            addMealRow();
                                            addMealRow();
                                            return;
                                        }
                                        meals.forEach(meal => createMealRow(meal));
                                    }

                                    async function submitRecommendation() {
                                        const userId = document.getElementById('rec_user_id').value;
                                        const advice = document.getElementById('rec_advice').value;
                                        const token = document.querySelector('input[name="_token"]').value;

                                        const rows = document.querySelectorAll('.meal-item-row');
                                        const meals = [];
                                        let hasError = false;

                                        rows.forEach(row => {
                                            const label = row.querySelector('.meal-label')?.value.trim();
                                            const name = row.querySelector('.meal-name')?.value.trim();
                                            const kcalValue = row.querySelector('.meal-kcal')?.value;
                                            const kcal = kcalValue !== undefined && kcalValue !== null && kcalValue !== '' ? parseInt(kcalValue, 10) : null;

                                            if (label || name || kcal) {
                                                if (!label || !name) {
                                                    hasError = true;
                                                    return;
                                                }
                                                meals.push({ label, name, kcal });
                                            }
                                        });

                                        if (hasError) {
                                            alert('Vui lòng điền đầy đủ cả Bữa và Món ăn cho mỗi dòng đã nhập.');
                                            return;
                                        }

                                        let payload = { user_id: userId, advice: advice };
                                        if (meals.length > 0) {
                                            payload.meals = meals;
                                        }

                                        try {
                                            const res = await fetch('{{ route('doctor.recommendations.save') }}', {
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                    'Accept': 'application/json',
                                                    'X-CSRF-TOKEN': token,
                                                    'X-Requested-With': 'XMLHttpRequest'
                                                },
                                                body: JSON.stringify(payload)
                                            });

                                            if (!res.ok) {
                                                let errText = '';
                                                try {
                                                    const errJson = await res.json();
                                                    errText = errJson.message || JSON.stringify(errJson);
                                                } catch (e) {
                                                    errText = await res.text();
                                                }
                                                alert('Lỗi: ' + (errText || res.statusText));
                                                return;
                                            }

                                            const data = await res.json();
                                            if (data.success) {
                                                alert('Lưu đề xuất thành công');
                                                closeRecommendModal();
                                            } else {
                                                alert('Lỗi: ' + (data.message || 'Không lưu được'));
                                            }
                                        } catch (err) {
                                            alert('Lỗi khi gửi yêu cầu: ' + (err.message || err));
                                        }
                                    }

                                    function loadSampleMeals() {
                                        renderMealRows([
                                            { label: 'Bữa sáng', name: 'Yến mạch', kcal: 400 },
                                            { label: 'Bữa trưa', name: 'Ức gà nướng + rau', kcal: 650 },
                                            { label: 'Bữa tối', name: 'Salad cá hồi', kcal: 520 },
                                        ]);
                                    }
                                </script>

                <!-- ================= TAB: PATIENTS ================= -->
                <section id="tab-patients" class="tab-pane space-y-6 hidden">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <h2 class="text-2xl font-black tracking-tight">Danh sách Bệnh nhân</h2>
                            <p class="text-xs text-slate-400 mt-1">Xem, lọc và cập nhật hồ sơ y tế bệnh nhân của hệ thống HealthAI.</p>
                        </div>
                        <div class="flex flex-wrap gap-2.5 w-full sm:w-auto">
                            <!-- Search -->
                            <div class="relative flex-1 sm:w-64">
                                <i data-lucide="search" class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                                <input id="patient-search" onkeyup="filterPatients()" placeholder="Tìm kiếm bệnh nhân..." class="h-10 w-full rounded-xl border border-slate-700 bg-slate-900/50 pl-9 pr-4 text-xs font-semibold outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-500/10">
                            </div>
                            <!-- Filter Gender -->
                            <select id="patient-filter-gender" onchange="filterPatients()" class="h-10 text-xs rounded-xl border border-slate-700 bg-slate-900/50 px-3 font-semibold outline-none focus:border-sky-500">
                                <option value="all">Tất cả giới tính</option>
                                <option value="Nam">Nam</option>
                                <option value="Nữ">Nữ</option>
                            </select>
                            <!-- Filter Health Status -->
                            <select id="patient-filter-status" onchange="filterPatients()" class="h-10 text-xs rounded-xl border border-slate-700 bg-slate-900/50 px-3 font-semibold outline-none focus:border-sky-500">
                                <option value="all">Tất cả sức khỏe</option>
                                <option value="normal">Bình thường</option>
                                <option value="alert">Cảnh báo</option>
                                <option value="abnormal">Bất thường</option>
                            </select>
                        </div>
                    </div>

                    <!-- Patients Table -->
                    <div class="glass rounded-3xl overflow-hidden shadow-glow">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-slate-800 bg-slate-950/40 text-[10px] font-black uppercase tracking-wider text-slate-400">
                                        <th class="p-4 pl-6">Mã bệnh nhân</th>
                                        <th class="p-4">Họ và tên</th>
                                        <th class="p-4">Giới tính / Tuổi</th>
                                        <th class="p-4">Số điện thoại</th>
                                        <th class="p-4 text-center">Chỉ số sinh học</th>
                                        <th class="p-4 text-center">Tình trạng sức khỏe</th>
                                        <th class="p-4 text-right pr-6">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody id="patients-table-body" class="divide-y divide-slate-800 text-xs font-semibold text-slate-300">
                                    @foreach($patients as $patient)
                                    @php
                                        $age = 25;
                                        if($patient->dob) {
                                            $age = \Carbon\Carbon::parse($patient->dob)->age;
                                        }
                                        
                                        // Health status logic
                                        $statusClass = 'bg-emerald-500/10 text-emerald-400';
                                        $statusText = 'Bình thường';
                                        $statusType = 'normal';
                                        
                                        if ($patient->heart_rate > 100 || $patient->heart_rate < 60 || $patient->spo2 < 95) {
                                            $statusClass = 'bg-rose-500/10 text-rose-400 border border-rose-500/20';
                                            $statusText = 'Bất thường';
                                            $statusType = 'abnormal';
                                        } elseif ($patient->heart_rate > 90 || $patient->spo2 <= 96) {
                                            $statusClass = 'bg-amber-500/10 text-amber-400 border border-amber-500/20';
                                            $statusText = 'Cảnh báo';
                                            $statusType = 'alert';
                                        }
                                    @endphp
                                    <tr class="hover:bg-slate-800/20 transition-colors patient-row" data-gender="{{ $patient->gender }}" data-status="{{ $statusType }}">
                                        <td class="p-4 pl-6 text-sky-400">#PAT-{{ $patient->id }}</td>
                                        <td class="p-4">
                                            <div class="flex items-center gap-3">
                                                <img src="{{ $patient->avatar }}" alt="avatar" class="h-8 w-8 rounded-full border border-slate-700 object-cover">
                                                <div>
                                                    <p class="font-bold text-white">{{ $patient->name }}</p>
                                                    <p class="text-[10px] text-slate-400 font-normal">{{ $patient->email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-4">{{ $patient->gender }} / {{ $age }} tuổi</td>
                                        <td class="p-4">{{ $patient->phone ?: 'N/A' }}</td>
                                        <td class="p-4 text-center text-[11px]">
                                            <span class="inline-flex gap-2.5">
                                                <span>❤️ {{ $patient->heart_rate ?: 75 }} bpm</span>
                                                <span>🩸 SpO₂: {{ $patient->spo2 ?: 98 }}%</span>
                                                <span>🏃 Steps: {{ number_format($patient->steps ?: 6000) }}</span>
                                            </span>
                                        </td>
                                        <td class="p-4 text-center">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $statusClass }}">
                                                {{ $statusText }}
                                            </span>
                                        </td>
                                        <td class="p-4 text-right pr-6">
                                            <div class="inline-flex gap-2">
                                                <button onclick="viewPatientMedicalRecord({{ $patient->id }})" class="h-8 px-3 rounded-lg border border-slate-700 hover:border-sky-500/50 bg-slate-900 text-sky-400 hover:text-white transition-all">Hồ sơ</button>
                                                <button onclick="openChatWithPatient({{ $patient->id }}, '{{ $patient->name }}', '{{ $patient->avatar }}')" class="h-8 px-3 rounded-lg border border-slate-700 hover:border-indigo-500/50 bg-slate-900 text-indigo-400 hover:text-white transition-all">Tư vấn</button>
                                                <button onclick="openRecommendModal({{ $patient->id }}, '{{ addslashes($patient->name) }}')" class="h-8 px-3 rounded-lg border border-slate-700 hover:border-emerald-500/50 bg-slate-900 text-emerald-400 hover:text-white transition-all">Gợi ý chế độ ăn</button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <!-- ================= TAB: MEDICAL RECORDS ================= -->
                <section id="tab-medical-records" class="tab-pane space-y-6 hidden">
                    <div class="grid gap-6 lg:grid-cols-3">
                        <!-- Patient Selector List -->
                        <div class="glass rounded-3xl p-6 lg:col-span-1 flex flex-col h-[750px]">
                            <h3 class="text-sm font-bold text-white mb-4 flex items-center gap-2"><i data-lucide="users" class="text-sky-400 h-4 w-4"></i> Chọn bệnh nhân</h3>
                            <div class="relative mb-4">
                                <i data-lucide="search" class="absolute left-3 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-slate-500"></i>
                                <input id="record-patient-search" onkeyup="filterRecordPatients(this)" placeholder="Tìm nhanh..." class="h-9 w-full rounded-xl border border-slate-700 bg-slate-900/50 pl-9 pr-4 text-xs font-semibold outline-none focus:border-sky-500">
                            </div>
                            
                            <div class="flex-1 overflow-y-auto space-y-2 pr-1 scrollbar-hide" id="record-patients-list">
                                @foreach($patients as $p)
                                <button onclick="selectPatientForRecord({{ $p->id }})" id="btn-record-patient-{{ $p->id }}" class="btn-record-patient w-full text-left p-3 rounded-2xl bg-white/5 border border-white/5 hover:border-sky-500/30 flex items-center gap-3 transition-all">
                                    <img src="{{ $p->avatar }}" class="h-8 w-8 rounded-full border border-slate-700 object-cover">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-bold text-white truncate">{{ $p->name }}</p>
                                        <p class="text-[10px] text-slate-400 mt-0.5">❤️ {{ $p->heart_rate ?: 75 }} bpm · SpO₂: {{ $p->spo2 ?: 98 }}%</p>
                                    </div>
                                </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Record Details and Form -->
                        <div class="lg:col-span-2 space-y-6" id="record-details-panel" style="display: none;">
                            <!-- Patient Info overview -->
                            <div class="glass rounded-3xl p-6 grid grid-cols-2 md:grid-cols-4 gap-4 relative overflow-hidden">
                                <div class="col-span-2 md:col-span-4 flex items-center gap-4 border-b border-slate-800 pb-4 mb-2">
                                    <img id="record-info-avatar" src="" class="h-12 w-12 rounded-full border border-sky-500/20 object-cover">
                                    <div>
                                        <h3 id="record-info-name" class="text-base font-extrabold text-white"></h3>
                                        <p id="record-info-bio" class="text-xs text-slate-400 mt-0.5"></p>
                                    </div>
                                </div>
                                <div class="bg-slate-900/50 border border-slate-800 p-3 rounded-2xl text-center">
                                    <p class="text-[9px] font-black uppercase text-slate-400">Chiều cao / Cân nặng</p>
                                    <p id="record-info-heightweight" class="text-sm font-bold text-white mt-1"></p>
                                </div>
                                <div class="bg-slate-900/50 border border-slate-800 p-3 rounded-2xl text-center">
                                    <p class="text-[9px] font-black uppercase text-slate-400">Nhóm máu</p>
                                    <p id="record-info-blood" class="text-sm font-bold text-white mt-1"></p>
                                </div>
                                <div class="bg-slate-900/50 border border-slate-800 p-3 rounded-2xl text-center">
                                    <p class="text-[9px] font-black uppercase text-slate-400">Chỉ số BMI</p>
                                    <p id="record-info-bmi" class="text-sm font-bold text-white mt-1"></p>
                                </div>
                                <div class="bg-slate-900/50 border border-slate-800 p-3 rounded-2xl text-center">
                                    <p class="text-[9px] font-black uppercase text-slate-400 font-bold text-sky-400">Bệnh nền</p>
                                    <p id="record-info-disease" class="text-xs font-bold text-white mt-1 truncate"></p>
                                </div>
                            </div>

                            <!-- Historical Charts -->
                            <div class="glass rounded-3xl p-6">
                                <h4 class="text-xs font-black uppercase tracking-widest text-sky-400 mb-4">Biểu đồ Xu hướng sức khỏe của người dùng</h4>
                                <div class="h-60 relative">
                                    <canvas id="patientTrendChart" class="w-full h-full"></canvas>
                                </div>
                            </div>

                            <!-- History Diagnoses list -->
                            <div class="glass rounded-3xl p-6">
                                <h4 class="text-xs font-black uppercase tracking-widest text-emerald-400 mb-3">Lịch sử khám & Chẩn đoán cũ</h4>
                                <div class="space-y-3" id="patient-diagnosis-history">
                                    <!-- Loaded by JS -->
                                </div>
                            </div>

                            <!-- Exam Record entry form -->
                            <div class="glass rounded-3xl p-6">
                                <h3 class="text-base font-bold text-white mb-4 flex items-center gap-2"><i data-lucide="edit" class="text-sky-400 h-5 w-5"></i> Ghi chép khám bệnh & kê đơn mới</h3>
                                <form id="medical-record-form" class="space-y-4" onsubmit="event.preventDefault(); saveMedicalRecord();">
                                    <input type="hidden" id="record-patient-id" name="user_id">
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="space-y-1.5">
                                            <label for="record-symptoms" class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Triệu chứng của bệnh nhân</label>
                                            <textarea id="record-symptoms" rows="2" placeholder="VD: Đau ngực nhẹ, khó thở khi gắng sức..." required class="w-full rounded-xl border border-slate-700 bg-slate-900/50 p-3 text-xs outline-none focus:border-sky-500"></textarea>
                                        </div>
                                        <div class="space-y-1.5">
                                            <label for="record-diagnosis" class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Chẩn đoán của Bác sĩ</label>
                                            <textarea id="record-diagnosis" rows="2" placeholder="VD: Huyết áp không ổn định, trào ngược nhẹ..." required class="w-full rounded-xl border border-slate-700 bg-slate-900/50 p-3 text-xs outline-none focus:border-sky-500"></textarea>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="space-y-1.5">
                                            <label for="record-exam-result" class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Kết quả khám lâm sàng</label>
                                            <textarea id="record-exam-result" rows="2" placeholder="VD: Tim đập đều, phổi trong, không ran..." class="w-full rounded-xl border border-slate-700 bg-slate-900/50 p-3 text-xs outline-none focus:border-sky-500"></textarea>
                                        </div>
                                        <div class="space-y-1.5">
                                            <label for="record-medicine" class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Thuốc kê đơn</label>
                                            <textarea id="record-medicine" rows="2" placeholder="VD: Paracetamol 500mg, uống 2 lần/ngày sau ăn..." class="w-full rounded-xl border border-slate-700 bg-slate-900/50 p-3 text-xs outline-none focus:border-sky-500"></textarea>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="space-y-1.5">
                                            <label for="record-instructions" class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Hướng dẫn điều trị</label>
                                            <textarea id="record-instructions" rows="2" placeholder="VD: Hạn chế ăn mặn, tập thể dục nhẹ 30 phút/ngày..." class="w-full rounded-xl border border-slate-700 bg-slate-900/50 p-3 text-xs outline-none focus:border-sky-500"></textarea>
                                        </div>
                                        <div class="space-y-1.5">
                                            <label for="record-notes" class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Ghi chú thêm</label>
                                            <textarea id="record-notes" rows="2" placeholder="VD: Hẹn tái khám sau 2 tuần nếu triệu chứng không thuyên giảm..." class="w-full rounded-xl border border-slate-700 bg-slate-900/50 p-3 text-xs outline-none focus:border-sky-500"></textarea>
                                        </div>
                                    </div>

                                    <div class="pt-4 border-t border-slate-800 flex justify-end gap-3">
                                        <button type="button" onclick="exportPDFDemo()" class="h-10 text-xs font-semibold rounded-xl border border-slate-700 bg-slate-900 hover:bg-slate-800 px-5 flex items-center gap-1.5"><i data-lucide="file-text" class="h-4 w-4"></i> Xuất PDF</button>
                                        <button type="submit" class="h-10 text-xs font-semibold rounded-xl gradient-primary px-6 text-white shadow-glow hover:scale-[1.02] active:scale-[0.98] transition-transform">Lưu hồ sơ bệnh án</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Blank Select Message -->
                        <div class="lg:col-span-2 glass rounded-3xl p-10 flex flex-col items-center justify-center text-center h-[500px]" id="record-blank-message">
                            <div class="h-16 w-16 rounded-full bg-slate-800 text-sky-400 flex items-center justify-center mb-4 shadow-glow">
                                <i data-lucide="folder-heart" class="h-8 w-8"></i>
                            </div>
                            <h3 class="text-lg font-bold text-white">Chưa chọn bệnh nhân</h3>
                            <p class="text-xs text-slate-400 max-w-sm mt-2">Vui lòng chọn một bệnh nhân ở danh sách bên trái để xem hồ sơ bệnh án chi tiết, biểu đồ theo thời gian và thực hiện kê đơn mới.</p>
                        </div>
                    </div>
                </section>

                <!-- ================= TAB: CONSULTATIONS ================= -->
                <section id="tab-consultations" class="tab-pane space-y-6 hidden">
                    <div class="grid gap-6 lg:grid-cols-4">
                        <!-- Chat Contacts List -->
                        <div class="glass rounded-3xl p-4 lg:col-span-1 flex flex-col h-[700px]">
                            <h3 class="text-xs font-black uppercase tracking-widest text-slate-400 p-2 mb-2 flex items-center gap-1.5"><i data-lucide="message-square" class="h-4 w-4 text-sky-400"></i> Cuộc trò chuyện</h3>
                            <div class="relative mb-3">
                                <i data-lucide="search" class="absolute left-3 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-slate-500"></i>
                                <input id="chat-patient-search" onkeyup="filterChatPatients(this)" placeholder="Tìm nhanh..." class="h-9 w-full rounded-xl border border-slate-700 bg-slate-900/50 pl-9 pr-4 text-xs font-semibold outline-none focus:border-sky-500">
                            </div>
                            <div class="flex-1 overflow-y-auto space-y-1.5 scrollbar-hide" id="chat-patients-list">
                                @foreach($patients as $p)
                                <button onclick="selectPatientForChat({{ $p->id }}, '{{ $p->name }}', '{{ $p->avatar }}')" id="btn-chat-patient-{{ $p->id }}" class="btn-chat-patient w-full text-left p-2.5 rounded-2xl bg-white/5 border border-white/5 hover:border-sky-500/30 flex items-center gap-3 transition-all relative">
                                    <img src="{{ $p->avatar }}" class="h-8 w-8 rounded-full border border-slate-700 object-cover">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-bold text-white truncate">{{ $p->name }}</p>
                                        <p class="text-[10px] text-slate-400 truncate mt-0.5 font-normal">Nhấn để nhắn tin tư vấn...</p>
                                    </div>
                                    <span class="absolute right-2 top-2 text-[9px] text-slate-500">Online</span>
                                </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Chat Console Box -->
                        <div class="lg:col-span-3 flex flex-col glass rounded-3xl overflow-hidden h-[700px]" id="chat-console-panel" style="display: none;">
                            <!-- Header info -->
                            <div class="bg-slate-900 border-b border-slate-800 p-4 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <img id="chat-active-avatar" src="" class="h-10 w-10 rounded-full border border-sky-500/20 object-cover">
                                    <div>
                                        <p id="chat-active-name" class="text-xs font-bold text-white"></p>
                                        <p class="text-[10px] text-emerald-400 font-semibold flex items-center gap-1 mt-0.5">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Bệnh nhân đang trực tuyến
                                        </p>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <!-- Video call mock -->
                                    <button onclick="triggerVideoCall()" class="h-9 w-9 rounded-xl bg-sky-500/10 text-sky-400 border border-sky-500/20 flex items-center justify-center hover:bg-sky-500 hover:text-white transition-colors">
                                        <i data-lucide="video" class="h-4.5 w-4.5"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Chat Messages log scrollable -->
                            <div id="chat-messages-container" class="flex-1 overflow-y-auto p-4 space-y-4 bg-slate-950/20">
                                <!-- Messages will load dynamically by AJAX -->
                            </div>

                            <!-- Chat Input box panel -->
                            <div class="bg-slate-900 border-t border-slate-800 p-4">
                                <form id="chat-send-form" onsubmit="event.preventDefault(); sendChatMessage();" class="flex gap-2.5 items-center">
                                    <input type="hidden" id="chat-active-user-id">
                                    
                                    <!-- File input mock triggers -->
                                    <label class="h-10 w-10 shrink-0 border border-slate-700 bg-slate-800 rounded-xl flex items-center justify-center text-slate-400 hover:text-white cursor-pointer transition-colors relative">
                                        <i data-lucide="image" class="h-4.5 w-4.5"></i>
                                        <input type="file" id="chat-file-image" accept="image/*" class="hidden" onchange="handleFileSelected(this, 'image')">
                                    </label>
                                    <label class="h-10 w-10 shrink-0 border border-slate-700 bg-slate-800 rounded-xl flex items-center justify-center text-slate-400 hover:text-white cursor-pointer transition-colors relative">
                                        <i data-lucide="paperclip" class="h-4.5 w-4.5"></i>
                                        <input type="file" id="chat-file-doc" class="hidden" onchange="handleFileSelected(this, 'document')">
                                    </label>

                                    <!-- Main input -->
                                    <input id="chat-input-message" placeholder="Nhập tin nhắn tư vấn y khoa..." required class="h-10 flex-1 rounded-xl border border-slate-700 bg-slate-950 px-4 text-xs font-semibold outline-none focus:border-sky-500">

                                    <button type="submit" class="h-10 px-5 rounded-xl gradient-primary text-white font-bold text-xs uppercase tracking-wider shadow-glow hover:scale-[1.02] transition-transform flex items-center gap-1.5">
                                        <span>Gửi</span> <i data-lucide="send" class="h-3 w-3"></i>
                                    </button>
                                </form>
                                <div id="selected-file-indicator" class="hidden text-[10px] text-sky-400 font-bold mt-2 flex items-center gap-1">
                                    <i data-lucide="file" class="h-3 w-3"></i> <span id="selected-file-name"></span>
                                    <button onclick="clearSelectedFile()" class="text-rose-500 hover:underline ml-2">Hủy</button>
                                </div>
                            </div>
                        </div>

                        <!-- Blank Select Chat Message -->
                        <div class="lg:col-span-3 glass rounded-3xl p-10 flex flex-col items-center justify-center text-center h-[500px]" id="chat-blank-message">
                            <div class="h-16 w-16 rounded-full bg-slate-800 text-indigo-400 flex items-center justify-center mb-4 shadow-glow">
                                <i data-lucide="message-square" class="h-8 w-8"></i>
                            </div>
                            <h3 class="text-lg font-bold text-white">Chưa chọn bệnh nhân tư vấn</h3>
                            <p class="text-xs text-slate-400 max-w-sm mt-2">Vui lòng chọn một bệnh nhân ở danh sách trò chuyện bên trái để bắt đầu nhắn tin tư vấn trực tuyến, gửi hình ảnh chỉ số hoặc kết nối cuộc gọi video.</p>
                        </div>
                    </div>
                </section>

                <!-- ================= TAB: HEALTH STATISTICS ================= -->
                <section id="tab-health-stats" class="tab-pane space-y-6 hidden">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-black tracking-tight">Thống kê Sức khỏe & Phân tích AI</h2>
                            <p class="text-xs text-slate-400 mt-1">Giám sát toàn diện chỉ số sinh học trung bình của toàn bộ bệnh nhân.</p>
                        </div>
                    </div>

                    <!-- AI Medical Dashboard Header -->
                    <div class="relative overflow-hidden rounded-3xl border border-sky-500/20 p-6 md:p-8">
                        <div class="absolute inset-0 bg-sky-500/5 backdrop-blur-md"></div>
                        <div class="relative grid gap-6 md:grid-cols-4 items-center">
                            <!-- Overall health score gauge mock -->
                            <div class="text-center md:border-r border-slate-800 pr-4">
                                <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">Điểm sức khỏe chung</p>
                                <p class="text-5xl font-black text-emerald-400 mt-2 font-display">82<span class="text-xs font-normal text-slate-400">/100</span></p>
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-400 bg-emerald-400/10 px-2.5 py-0.5 rounded-full mt-2">Khá tốt</span>
                            </div>
                            
                            <div class="col-span-3 space-y-2 text-xs">
                                <h4 class="font-bold text-white flex items-center gap-1.5"><i data-lucide="brain-circuit" class="text-sky-400 h-4 w-4"></i> Đánh giá tổng hợp của Trợ lý AI Bác sĩ</h4>
                                <p class="text-slate-300 leading-relaxed font-normal">Hệ thống AI ghi nhận 92% bệnh nhân của bạn có chỉ số nhịp tim ổn định. Tuy nhiên, có sự gia tăng nhẹ chỉ số huyết áp bất thường ở nhóm người cao tuổi (>60) vào mùa hè. Cần theo dõi chặt chẽ cảnh báo thông báo.</p>
                                
                                <div class="grid grid-cols-2 gap-4 pt-2">
                                    <div class="flex items-start gap-2 bg-rose-500/5 border border-rose-500/10 p-2.5 rounded-xl">
                                        <i data-lucide="alert-triangle" class="text-rose-400 h-4 w-4 shrink-0 mt-0.5 animate-pulse"></i>
                                        <div>
                                            <p class="font-bold text-rose-400">2 cảnh báo khẩn cấp</p>
                                            <p class="text-[10px] text-slate-400 leading-normal">Bệnh nhân Trần Thị B và Nguyễn Văn A chỉ số vượt ngưỡng.</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-2 bg-emerald-500/5 border border-emerald-500/10 p-2.5 rounded-xl">
                                        <i data-lucide="sparkles" class="text-emerald-400 h-4 w-4 shrink-0 mt-0.5"></i>
                                        <div>
                                            <p class="font-bold text-emerald-400">Khuyến nghị đề xuất</p>
                                            <p class="text-[10px] text-slate-400 leading-normal">Cập nhật cẩm nang dinh dưỡng phòng ngừa đột quỵ hè.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics grid charts -->
                    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                        <!-- BMI -->
                        <div class="glass rounded-3xl p-6">
                            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 flex items-center justify-between">
                                <span>Phân bố chỉ số BMI bệnh nhân</span> <i data-lucide="activity" class="h-4 w-4 text-sky-400"></i>
                            </h4>
                            <div class="h-52 relative">
                                <canvas id="bmiPieChart"></canvas>
                            </div>
                        </div>

                        <!-- HR -->
                        <div class="glass rounded-3xl p-6">
                            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 flex items-center justify-between">
                                <span>Biểu đồ nhịp tim trung bình theo ngày</span> <i data-lucide="heart" class="h-4 w-4 text-rose-500"></i>
                            </h4>
                            <div class="h-52 relative">
                                <canvas id="hrDayChart"></canvas>
                            </div>
                        </div>

                        <!-- Sleep & Water -->
                        <div class="glass rounded-3xl p-6">
                            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 flex items-center justify-between">
                                <span>Calories & Nước tiêu thụ tuần</span> <i data-lucide="droplet" class="h-4 w-4 text-blue-400"></i>
                            </h4>
                            <div class="h-52 relative">
                                <canvas id="caloriesWaterChart"></canvas>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- ================= TAB: APPOINTMENTS ================= -->
                <section id="tab-appointments" class="tab-pane space-y-6 hidden">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-black tracking-tight">Quản lý Lịch Hẹn</h2>
                            <p class="text-xs text-slate-400 mt-1">Quản lý danh sách đặt chỗ và sắp xếp thời gian tái khám cho bệnh nhân.</p>
                        </div>
                    </div>

                    <!-- Appointments list -->
                    <div class="glass rounded-3xl overflow-hidden shadow-glow">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-slate-800 bg-slate-950/40 text-[10px] font-black uppercase tracking-wider text-slate-400">
                                        <th class="p-4 pl-6">Mã đặt hẹn</th>
                                        <th class="p-4">Họ và tên bệnh nhân</th>
                                        <th class="p-4">Chuyên khoa khám</th>
                                        <th class="p-4">Ngày khám</th>
                                        <th class="p-4">Giờ khám</th>
                                        <th class="p-4 text-center">Trạng thái lịch</th>
                                        <th class="p-4 text-right pr-6">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody id="appointments-table-body" class="divide-y divide-slate-800 text-xs font-semibold text-slate-300">
                                    @forelse($appointments as $appt)
                                    @php
                                        $statusClass = 'bg-amber-500/10 text-amber-400';
                                        $statusText = 'Đang chờ khám';
                                        if($appt->status === 'completed') {
                                            $statusClass = 'bg-emerald-500/10 text-emerald-400';
                                            $statusText = 'Hoàn thành';
                                        } elseif($appt->status === 'canceled') {
                                            $statusClass = 'bg-rose-500/10 text-rose-400';
                                            $statusText = 'Đã hủy';
                                        }
                                    @endphp
                                    <tr class="hover:bg-slate-800/20 transition-colors">
                                        <td class="p-4 pl-6 text-sky-400">#APT-{{ $appt->id }}</td>
                                        <td class="p-4">
                                            <div class="flex items-center gap-3">
                                                <img src="{{ $appt->patient->avatar ?: 'https://ui-avatars.com/api/?name='.urlencode($appt->patient->name).'&background=0284c7&color=fff' }}" alt="avatar" class="h-8 w-8 rounded-full object-cover border border-slate-700">
                                                <div>
                                                    <p class="font-bold text-white">{{ $appt->patient->name }}</p>
                                                    <p class="text-[10px] text-slate-400 font-normal">{{ $appt->patient->email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-4 text-slate-300">{{ $appt->specialty }}</td>
                                        <td class="p-4 text-slate-400">{{ \Carbon\Carbon::parse($appt->appointment_date)->format('Y-m-d') }}</td>
                                        <td class="p-4 text-white font-bold">{{ \Carbon\Carbon::parse($appt->appointment_date)->format('H:i') }}</td>
                                        <td class="p-4 text-center">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $statusClass }}">
                                                {{ $statusText }}
                                            </span>
                                        </td>
                                        <td class="p-4 text-right pr-6">
                                            <div class="inline-flex gap-2">
                                                @if($appt->status === 'scheduled')
                                                <button onclick="toggleAppointment({{ $appt->id }}, 'completed')" class="h-8 px-2.5 rounded-lg border border-emerald-500/30 bg-emerald-500/5 hover:bg-emerald-500 text-emerald-400 hover:text-white transition-all text-[10px]">Hoàn thành</button>
                                                <button onclick="toggleAppointment({{ $appt->id }}, 'canceled')" class="h-8 px-2.5 rounded-lg border border-rose-500/30 bg-rose-500/5 hover:bg-rose-500 text-rose-400 hover:text-white transition-all text-[10px]">Hủy lịch</button>
                                                @else
                                                <span class="text-xs text-slate-500 italic font-normal">Đã xử lý</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="p-8 text-center text-slate-400 italic">Không có lịch hẹn khám nào.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <!-- ================= TAB: NOTIFICATIONS ================= -->
                <section id="tab-notifications" class="tab-pane space-y-6 hidden">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-black tracking-tight">Thông báo Y Tế Realtime</h2>
                            <p class="text-xs text-slate-400 mt-1 font-semibold">Tự động đồng bộ các cập nhật và cảnh báo cấp cứu.</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @foreach($notifications as $notif)
                        <div class="glass p-5 rounded-3xl border border-white/5 hover:border-sky-500/30 transition-all flex gap-4 items-start relative overflow-hidden">
                            <!-- Indicator colors -->
                            <div class="h-10 w-10 shrink-0 rounded-2xl flex items-center justify-center {{ $notif['type'] === 'alert' ? 'bg-rose-500/10 text-rose-400' : ($notif['type'] === 'appointment' ? 'bg-amber-500/10 text-amber-400' : 'bg-sky-500/10 text-sky-400') }}">
                                <i data-lucide="{{ $notif['type'] === 'alert' ? 'alert-triangle' : ($notif['type'] === 'appointment' ? 'calendar' : 'user-plus') }}" class="h-5 w-5"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-start">
                                    <h4 class="text-sm font-bold text-white">{{ $notif['title'] }}</h4>
                                    <span class="text-[10px] text-slate-400 font-semibold">{{ $notif['time'] }}</span>
                                </div>
                                <p class="text-xs text-slate-300 font-normal mt-1 leading-relaxed">{{ $notif['message'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </section>

                <!-- ================= TAB: PROFILE ================= -->
                <section id="tab-profile" class="tab-pane space-y-6 hidden">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-black tracking-tight">Hồ sơ bác sĩ chuyên môn</h2>
                            <p class="text-xs text-slate-400 mt-1">Cập nhật hồ sơ năng lực khám bệnh và thông tin cá nhân của bạn.</p>
                        </div>
                    </div>

                    <div class="grid gap-6 lg:grid-cols-3">
                        <!-- Profile preview info Card -->
                        <div class="glass rounded-3xl p-6 lg:col-span-1 text-center flex flex-col items-center justify-center relative overflow-hidden">
                            <div class="absolute -right-20 -top-20 h-40 w-40 rounded-full bg-sky-500/5 blur-3xl"></div>
                            
                            <img src="{{ $doctor->avatar }}" id="profile-preview-avatar" class="h-24 w-24 rounded-full border-2 border-sky-500/40 object-cover shadow-glow mb-4">
                            <h3 class="text-lg font-bold text-white" id="profile-preview-name">{{ $doctor->name }}</h3>
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-sky-500/10 text-sky-400 border border-sky-500/20 mt-2">{{ $doctor->specialty }}</span>
                            
                            <ul class="w-full text-xs text-slate-300 font-normal space-y-3 mt-6 border-t border-slate-800 pt-6 text-left">
                                <li class="flex justify-between"><span>Số điện thoại:</span> <span class="font-semibold text-white" id="profile-preview-phone">{{ $doctor->phone }}</span></li>
                                <li class="flex justify-between"><span>Nơi công tác:</span> <span class="font-semibold text-white" id="profile-preview-place">{{ $doctor->place }}</span></li>
                                <li class="flex justify-between"><span>Địa chỉ:</span> <span class="font-semibold text-white truncate max-w-[150px]" id="profile-preview-address">{{ $doctor->address ?: 'Chưa cập nhật' }}</span></li>
                            </ul>
                        </div>

                        <!-- Profile Form Editor -->
                        <div class="glass rounded-3xl p-6 lg:col-span-2">
                            <h3 class="text-sm font-bold text-white mb-6 border-b border-slate-800 pb-2">Chỉnh sửa thông tin hồ sơ</h3>
                            
                            <form id="doctor-profile-form" onsubmit="event.preventDefault(); updateDoctorProfile();" class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="space-y-1.5">
                                        <label for="prof-name" class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Họ và tên bác sĩ</label>
                                        <input id="prof-name" value="{{ $doctor->name }}" required class="h-10 text-xs w-full rounded-xl border border-slate-700 bg-slate-900/50 px-3 font-semibold outline-none focus:border-sky-500">
                                    </div>
                                    <div class="space-y-1.5">
                                        <label for="prof-phone" class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Số điện thoại</label>
                                        <input id="prof-phone" value="{{ $doctor->phone }}" required class="h-10 text-xs w-full rounded-xl border border-slate-700 bg-slate-900/50 px-3 font-semibold outline-none focus:border-sky-500">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="space-y-1.5">
                                        <label for="prof-specialty" class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Chuyên khoa khám</label>
                                        <input id="prof-specialty" value="{{ $doctor->specialty }}" required class="h-10 text-xs w-full rounded-xl border border-slate-700 bg-slate-900/50 px-3 font-semibold outline-none focus:border-sky-500">
                                    </div>
                                    <div class="space-y-1.5">
                                        <label for="prof-place" class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Nơi công tác / Bệnh viện</label>
                                        <input id="prof-place" value="{{ $doctor->place }}" required class="h-10 text-xs w-full rounded-xl border border-slate-700 bg-slate-900/50 px-3 font-semibold outline-none focus:border-sky-500">
                                    </div>
                                </div>

                                <div class="space-y-1.5">
                                    <label for="prof-avatar" class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Link ảnh đại diện (Avatar URL)</label>
                                    <input id="prof-avatar" value="{{ $doctor->avatar }}" class="h-10 text-xs w-full rounded-xl border border-slate-700 bg-slate-900/50 px-3 font-semibold outline-none focus:border-sky-500">
                                </div>

                                <div class="space-y-1.5">
                                    <label for="prof-address" class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Địa chỉ phòng khám / Phòng riêng</label>
                                    <textarea id="prof-address" rows="3" class="text-xs w-full rounded-xl border border-slate-700 bg-slate-900/50 p-3 font-semibold outline-none focus:border-sky-500">{{ $doctor->address }}</textarea>
                                </div>

                                <div class="pt-4 border-t border-slate-800 flex justify-end">
                                    <button type="submit" class="h-10 text-xs font-semibold rounded-xl gradient-primary px-6 text-white shadow-glow hover:scale-[1.02] active:scale-[0.98] transition-transform">Lưu lại cấu hình</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>

            </main>
        </div>
    </div>

    <!-- ================= MODALS & POPUPS ================= -->
    
    <!-- Video Call Mock Modal -->
    <div id="videoCallModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/90 backdrop-blur-md opacity-0 pointer-events-none transition-all duration-300">
        <div class="relative w-full max-w-2xl overflow-hidden rounded-[2.5rem] border border-slate-800 bg-slate-950 p-4 shadow-glow flex flex-col h-[500px]">
            <!-- Top camera label -->
            <div class="flex justify-between items-center text-xs px-2 mb-2">
                <span class="text-sky-400 font-bold flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-rose-500 animate-ping"></span> Live Consultation Video Call</span>
                <span class="text-slate-400" id="call-duration">00:00</span>
            </div>

            <!-- Double camera view grids -->
            <div class="flex-1 grid grid-cols-2 gap-4 rounded-2xl overflow-hidden relative bg-slate-900">
                <!-- Patient Video (Main Mock image) -->
                <div class="relative bg-slate-850 flex items-center justify-center overflow-hidden">
                    <img id="video-call-patient-img" src="" class="absolute inset-0 w-full h-full object-cover">
                    <div class="absolute bottom-3 left-3 bg-black/60 px-2.5 py-0.5 rounded text-[10px] text-white" id="video-call-patient-name">Patient</div>
                </div>
                <!-- Doctor Video (Preview mock) -->
                <div class="relative bg-slate-800 flex items-center justify-center overflow-hidden">
                    <img src="{{ $doctor->avatar }}" class="absolute inset-0 w-full h-full object-cover">
                    <div class="absolute bottom-3 left-3 bg-black/60 px-2.5 py-0.5 rounded text-[10px] text-white">Bác sĩ (Bạn)</div>
                </div>
            </div>

            <!-- Call Controls toolbar -->
            <div class="mt-4 flex justify-center gap-4 py-2">
                <button onclick="toggleCallMute(this)" class="h-11 w-11 rounded-full border border-slate-700 bg-slate-800 flex items-center justify-center text-slate-300 hover:bg-slate-700 transition-colors">
                    <i data-lucide="mic" class="h-5 w-5"></i>
                </button>
                <button onclick="toggleCallVideo(this)" class="h-11 w-11 rounded-full border border-slate-700 bg-slate-800 flex items-center justify-center text-slate-300 hover:bg-slate-700 transition-colors">
                    <i data-lucide="video" class="h-5 w-5"></i>
                </button>
                <button onclick="closeVideoCall()" class="h-11 w-12 rounded-2xl bg-rose-600 text-white flex items-center justify-center hover:bg-rose-700 shadow-glow transition-all">
                    <i data-lucide="phone-off" class="h-5 w-5"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- ================= JAVASCRIPT STATE ENGINE ================= -->
    <script>
        // Init Lucide
        lucide.createIcons();

        // Data arrays passed from PHP
        let patients = @json($patients);
        let selectedPatientId = null;
        let selectedChatUserId = null;
        let currentChart = null;

        // File upload attachments state
        let selectedFile = null;
        let selectedFileType = null;

        // Switch Tabs beautifully
        function switchTab(tabId) {
            document.querySelectorAll('.tab-pane').forEach(el => el.classList.add('hidden'));
            const selectedTab = document.getElementById('tab-' + tabId);
            if (selectedTab) selectedTab.classList.remove('hidden');

            document.querySelectorAll('.nav-btn').forEach(btn => {
                btn.className = "nav-btn w-full flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition-all text-slate-400 hover:bg-white/5 hover:text-white";
            });

            const activeBtn = document.getElementById('nav-' + tabId);
            if (activeBtn) {
                activeBtn.className = "nav-btn w-full flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition-all text-white gradient-primary shadow-glow";
            }

            // Close sidebar on mobile
            if (window.innerWidth < 1024) {
                const sidebar = document.getElementById('sidebar');
                const backdrop = document.getElementById('sidebar-backdrop');
                if (sidebar && !sidebar.classList.contains('-translate-x-full')) {
                    sidebar.classList.add('-translate-x-full');
                    backdrop.classList.add('hidden');
                }
            }
        }

        // Toggle Sidebar on mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            sidebar.classList.toggle('-translate-x-full');
            backdrop.classList.toggle('hidden');
        }

        // ------------------ TAB: PATIENTS FILTERING ------------------
        function filterPatients() {
            const searchVal = document.getElementById('patient-search').value.toLowerCase();
            const genderVal = document.getElementById('patient-filter-gender').value;
            const statusVal = document.getElementById('patient-filter-status').value;

            document.querySelectorAll('.patient-row').forEach(row => {
                const text = row.innerText.toLowerCase();
                const gender = row.getAttribute('data-gender');
                const status = row.getAttribute('data-status');

                const matchesSearch = text.includes(searchVal);
                const matchesGender = (genderVal === 'all' || gender === genderVal);
                const matchesStatus = (statusVal === 'all' || status === statusVal);

                if (matchesSearch && matchesGender && matchesStatus) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // ------------------ TAB: MEDICAL RECORDS CRUD ------------------
        function filterRecordPatients(input) {
            const val = input.value.toLowerCase();
            document.querySelectorAll('.btn-record-patient').forEach(btn => {
                const text = btn.innerText.toLowerCase();
                btn.style.display = text.includes(val) ? '' : 'none';
            });
        }

        function viewPatientMedicalRecord(patientId) {
            switchTab('medical-records');
            selectPatientForRecord(patientId);
        }

        function selectPatientForRecord(patientId) {
            selectedPatientId = patientId;
            
            // Highlight button
            document.querySelectorAll('.btn-record-patient').forEach(btn => {
                btn.classList.remove('border-sky-500/80', 'bg-sky-500/10');
            });
            const activeBtn = document.getElementById('btn-record-patient-' + patientId);
            if(activeBtn) {
                activeBtn.classList.add('border-sky-500/80', 'bg-sky-500/10');
            }

            const patient = patients.find(p => p.id === patientId);
            if (!patient) return;

            // Show panel
            document.getElementById('record-blank-message').style.display = 'none';
            document.getElementById('record-details-panel').style.display = 'block';

            // Set info
            document.getElementById('record-info-avatar').src = patient.avatar;
            document.getElementById('record-info-name').innerText = patient.name;
            
            const dobYear = patient.dob ? new Date(patient.dob).getFullYear() : 2000;
            const age = new Date().getFullYear() - dobYear;
            document.getElementById('record-info-bio').innerText = `${patient.gender} · ${age} tuổi · SĐT: ${patient.phone || 'Chưa cập nhật'}`;
            document.getElementById('record-info-heightweight').innerText = `${patient.height || 0} cm / ${patient.weight || 0} kg`;
            document.getElementById('record-info-blood').innerText = patient.blood_type || 'N/A';
            document.getElementById('record-info-bmi').innerText = patient.bmi ? `${patient.bmi} kg/m²` : 'N/A';
            document.getElementById('record-info-disease').innerText = patient.health_goals || 'Không có';

            document.getElementById('record-patient-id').value = patient.id;

            // Load Historical Medical Records list
            loadDiagnosisHistory(patient.id);

            // Draw Charts
            renderPatientTrendChart(patient);
        }

        function loadDiagnosisHistory(patientId) {
            const container = document.getElementById('patient-diagnosis-history');
            container.innerHTML = '<p class="text-xs text-slate-400 italic">Đang tải lịch sử bệnh án...</p>';

            fetch(`/doctor/medical-records/history/${patientId}`)
            .then(res => res.json())
            .then(data => {
                container.innerHTML = '';
                if(data.success && data.records.length > 0) {
                    data.records.forEach(rec => {
                        const div = document.createElement('div');
                        div.className = "bg-white/5 border border-white/5 p-4 rounded-2xl text-xs";
                        div.innerHTML = `
                            <div class="flex justify-between items-center mb-1">
                                <span class="font-bold text-white">Khám ngày: ${new Date(rec.recorded_at).toLocaleDateString('vi-VN')}</span>
                                <span class="text-[10px] text-sky-400 font-bold">BS. ${rec.doctor.name}</span>
                            </div>
                            <p class="text-slate-300 font-normal mt-1"><span class="font-bold text-slate-400">Triệu chứng:</span> ${rec.symptoms}</p>
                            <p class="text-slate-300 font-normal mt-1"><span class="font-bold text-emerald-400">Chẩn đoán:</span> ${rec.diagnosis}</p>
                            ${rec.prescribed_medicine ? `<p class="text-slate-300 font-normal mt-1"><span class="font-bold text-sky-400">Thuốc kê:</span> ${rec.prescribed_medicine}</p>` : ''}
                            ${rec.treatment_instructions ? `<p class="text-slate-300 font-normal mt-1"><span class="font-bold text-slate-400">Hướng dẫn:</span> ${rec.treatment_instructions}</p>` : ''}
                        `;
                        container.appendChild(div);
                    });
                } else {
                    container.innerHTML = '<p class="text-xs text-slate-500 italic">Chưa có lịch sử chẩn đoán lâm sàng nào.</p>';
                }
            })
            .catch(err => {
                console.error(err);
                container.innerHTML = '<p class="text-xs text-rose-400 italic">Không thể kết nối tải lịch sử.</p>';
            });
        }

        function renderPatientTrendChart(patient) {
            if (currentChart) {
                currentChart.destroy();
            }

            const ctx = document.getElementById('patientTrendChart').getContext('2d');
            
            // Mocking trend data based on user variables
            const labels = ['Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'Chủ nhật'];
            const heartRates = [
                patient.heart_rate ? patient.heart_rate - 2 : 72,
                patient.heart_rate ? patient.heart_rate + 3 : 75,
                patient.heart_rate ? patient.heart_rate : 74,
                patient.heart_rate ? patient.heart_rate - 5 : 70,
                patient.heart_rate ? patient.heart_rate + 10 : 82,
                patient.heart_rate ? patient.heart_rate + 1 : 75,
                patient.heart_rate ? patient.heart_rate : 74,
            ];
            
            const steps = [
                (patient.steps || 6000) - 1200,
                (patient.steps || 6000) + 500,
                (patient.steps || 6000) - 200,
                (patient.steps || 6000) + 1500,
                (patient.steps || 6000) - 800,
                (patient.steps || 6000) + 3000,
                (patient.steps || 6000)
            ];

            currentChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Nhịp tim (bpm)',
                            data: heartRates,
                            borderColor: '#f43f5e',
                            borderWidth: 2.5,
                            backgroundColor: 'rgba(244, 63, 94, 0.05)',
                            fill: true,
                            tension: 0.35,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Bước chân',
                            data: steps,
                            borderColor: '#38bdf8',
                            borderWidth: 2.5,
                            backgroundColor: 'rgba(56, 189, 248, 0.05)',
                            fill: true,
                            tension: 0.35,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            grid: { color: 'rgba(255, 255, 255, 0.05)' },
                            ticks: { color: '#94a3b8', font: { size: 9 } }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            grid: { drawOnChartArea: false },
                            ticks: { color: '#94a3b8', font: { size: 9 } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: '#94a3b8', font: { size: 9 } }
                        }
                    },
                    plugins: {
                        legend: {
                            labels: { color: '#e2e8f0', font: { size: 10 } }
                        }
                    }
                }
            });
        }

        function saveMedicalRecord() {
            const patientId = document.getElementById('record-patient-id').value;
            const symptoms = document.getElementById('record-symptoms').value;
            const diagnosis = document.getElementById('record-diagnosis').value;
            const examResult = document.getElementById('record-exam-result').value;
            const prescribedMedicine = document.getElementById('record-medicine').value;
            const treatmentInstructions = document.getElementById('record-instructions').value;
            const notes = document.getElementById('record-notes').value;

            fetch('/doctor/medical-records/save', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    user_id: patientId,
                    symptoms: symptoms,
                    diagnosis: diagnosis,
                    exam_result: examResult,
                    prescribed_medicine: prescribedMedicine,
                    treatment_instructions: treatmentInstructions,
                    notes: notes
                })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    alert(data.message);
                    // Clear inputs
                    document.getElementById('record-symptoms').value = '';
                    document.getElementById('record-diagnosis').value = '';
                    document.getElementById('record-exam-result').value = '';
                    document.getElementById('record-medicine').value = '';
                    document.getElementById('record-instructions').value = '';
                    document.getElementById('record-notes').value = '';
                    // Reload logs
                    loadDiagnosisHistory(patientId);
                } else {
                    alert(data.message || 'Lỗi lưu dữ liệu!');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Không thể kết nối máy chủ!');
            });
        }

        function exportPDFDemo() {
            alert('Khởi chạy xuất Bệnh án & Đơn thuốc dưới định dạng PDF thành công!\nTệp tin đang được tải xuống thiết bị của bạn.');
        }

        // ------------------ TAB: CONSULTATIONS CHAT ------------------
        function filterChatPatients(input) {
            const val = input.value.toLowerCase();
            document.querySelectorAll('.btn-chat-patient').forEach(btn => {
                const text = btn.innerText.toLowerCase();
                btn.style.display = text.includes(val) ? '' : 'none';
            });
        }

        function openChatWithPatient(patientId, name, avatar) {
            switchTab('consultations');
            selectPatientForChat(patientId, name, avatar);
        }

        function selectPatientForChat(userId, name, avatar) {
            selectedChatUserId = userId;

            // Highlight contact btn
            document.querySelectorAll('.btn-chat-patient').forEach(btn => {
                btn.classList.remove('border-sky-500/80', 'bg-sky-500/10');
            });
            const activeBtn = document.getElementById('btn-chat-patient-' + userId);
            if(activeBtn) {
                activeBtn.classList.add('border-sky-500/80', 'bg-sky-500/10');
            }

            document.getElementById('chat-blank-message').style.display = 'none';
            document.getElementById('chat-console-panel').style.display = 'flex';

            document.getElementById('chat-active-avatar').src = avatar;
            document.getElementById('chat-active-name').innerText = name;
            document.getElementById('chat-active-user-id').value = userId;

            // Load Chat Messages
            loadChatMessages(userId);
        }

        function loadChatMessages(userId) {
            const container = document.getElementById('chat-messages-container');
            container.innerHTML = '<p class="text-xs text-slate-500 text-center italic py-10">Đang tải tin nhắn tư vấn...</p>';

            fetch(`/doctor/consultations/chat/${userId}`)
            .then(res => res.json())
            .then(data => {
                container.innerHTML = '';
                if(data.success && data.messages.length > 0) {
                    data.messages.forEach(msg => {
                        const isDoc = msg.sender === 'doctor';
                        const bubble = document.createElement('div');
                        bubble.className = `flex ${isDoc ? 'justify-end' : 'justify-start'}`;
                        
                        let attachmentHtml = '';
                        if(msg.file_path) {
                            if(msg.file_type === 'image') {
                                attachmentHtml = `<img src="${msg.file_path}" class="rounded-xl max-w-[200px] mt-2 border border-slate-700 shadow-glow">`;
                            } else {
                                attachmentHtml = `
                                    <a href="${msg.file_path}" target="_blank" class="mt-2 flex items-center gap-1.5 bg-slate-900 border border-slate-800 p-2 rounded-xl text-[10px] text-sky-400 font-bold hover:underline">
                                        <i data-lucide="file" class="h-3 w-3"></i> Tải tài liệu đính kèm
                                    </a>
                                `;
                            }
                        }

                        bubble.innerHTML = `
                            <div class="max-w-[70%] p-3.5 rounded-2xl text-xs font-semibold ${isDoc ? 'bg-sky-600 text-white rounded-tr-none' : 'bg-slate-800 text-slate-200 rounded-tl-none'}">
                                <p class="font-normal leading-relaxed">${msg.message}</p>
                                ${attachmentHtml}
                                <span class="block text-[8px] text-right mt-1.5 opacity-60">${new Date(msg.created_at).toLocaleTimeString('vi-VN', {hour: '2-digit', minute:'2-digit'})}</span>
                            </div>
                        `;
                        container.appendChild(bubble);
                    });
                } else {
                    container.innerHTML = '<p class="text-xs text-slate-550 text-center italic py-10">Chưa có lịch sử nhắn tin. Gửi lời khuyên y tế của bạn ngay.</p>';
                }
                container.scrollTop = container.scrollHeight;
                lucide.createIcons();
            })
            .catch(err => {
                console.error(err);
                container.innerHTML = '<p class="text-xs text-rose-400 text-center italic py-10">Lỗi kết nối tải tin nhắn.</p>';
            });
        }

        function handleFileSelected(input, type) {
            if(input.files && input.files[0]) {
                selectedFile = input.files[0];
                selectedFileType = type;
                
                document.getElementById('selected-file-indicator').classList.remove('hidden');
                document.getElementById('selected-file-name').innerText = selectedFile.name;
            }
        }

        function clearSelectedFile() {
            selectedFile = null;
            selectedFileType = null;
            document.getElementById('chat-file-image').value = '';
            document.getElementById('chat-file-doc').value = '';
            document.getElementById('selected-file-indicator').classList.add('hidden');
        }

        function sendChatMessage() {
            const userId = document.getElementById('chat-active-user-id').value;
            const message = document.getElementById('chat-input-message').value;

            const formData = new FormData();
            formData.append('user_id', userId);
            formData.append('message', message);
            if(selectedFile) {
                formData.append('file', selectedFile);
            }

            fetch('/doctor/consultations/send', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    document.getElementById('chat-input-message').value = '';
                    clearSelectedFile();
                    loadChatMessages(userId);
                } else {
                    alert(data.message || 'Lỗi gửi tin nhắn!');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Không thể kết nối máy chủ!');
            });
        }

        // Video Call Modal Mock
        let callTimer = null;
        function triggerVideoCall() {
            const patient = patients.find(p => p.id === selectedChatUserId);
            if(!patient) return;

            document.getElementById('video-call-patient-img').src = patient.avatar;
            document.getElementById('video-call-patient-name').innerText = patient.name;

            const modal = document.getElementById('videoCallModal');
            modal.classList.remove('opacity-0', 'pointer-events-none');
            modal.classList.add('opacity-100', 'pointer-events-auto');

            // Timer
            let seconds = 0;
            const timerEl = document.getElementById('call-duration');
            timerEl.innerText = "00:00";
            callTimer = setInterval(() => {
                seconds++;
                const mins = String(Math.floor(seconds / 60)).padStart(2, '0');
                const secs = String(seconds % 60).padStart(2, '0');
                timerEl.innerText = `${mins}:${secs}`;
            }, 1000);
        }

        function closeVideoCall() {
            clearInterval(callTimer);
            const modal = document.getElementById('videoCallModal');
            modal.classList.add('opacity-0', 'pointer-events-none');
            modal.classList.remove('opacity-100', 'pointer-events-auto');
        }

        function toggleCallMute(btn) {
            btn.classList.toggle('bg-rose-500/20');
            btn.classList.toggle('text-rose-400');
            const icon = btn.querySelector('i');
            // Mock toggling
        }

        function toggleCallVideo(btn) {
            btn.classList.toggle('bg-rose-500/20');
            btn.classList.toggle('text-rose-400');
        }

        // ------------------ TAB: APPOINTMENTS ------------------
        function toggleAppointment(apptId, status) {
            fetch(`/doctor/appointments/${apptId}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ status: status })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message || 'Lỗi cập nhật!');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Không thể kết nối máy chủ!');
            });
        }

        // ------------------ TAB: DOCTOR PROFILE ------------------
        function updateDoctorProfile() {
            const name = document.getElementById('prof-name').value;
            const phone = document.getElementById('prof-phone').value;
            const specialty = document.getElementById('prof-specialty').value;
            const place = document.getElementById('prof-place').value;
            const avatar = document.getElementById('prof-avatar').value;
            const address = document.getElementById('prof-address').value;

            fetch('/doctor/profile', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    name: name,
                    phone: phone,
                    specialty: specialty,
                    place: place,
                    avatar: avatar,
                    address: address
                })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    alert(data.message);
                    // Update preview card
                    document.getElementById('profile-preview-name').innerText = data.doctor.name;
                    document.getElementById('profile-preview-phone').innerText = data.doctor.phone;
                    document.getElementById('profile-preview-place').innerText = data.doctor.place;
                    document.getElementById('profile-preview-address').innerText = data.doctor.address || 'Chưa cập nhật';
                    if (data.doctor.avatar) {
                        document.getElementById('profile-preview-avatar').src = data.doctor.avatar;
                    }
                } else {
                    alert(data.message || 'Lỗi cập nhật hồ sơ!');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Không thể kết nối máy chủ!');
            });
        }

        // ------------------ CHARTS ENGINE ------------------
        document.addEventListener('DOMContentLoaded', () => {
            // Overall BMI chart
            const bmiCtx = document.getElementById('bmiPieChart').getContext('2d');
            new Chart(bmiCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Thiếu cân (<18.5)', 'Bình thường (18.5-24.9)', 'Thừa cân (>=25)'],
                    datasets: [{
                        data: [12, 65, 23],
                        backgroundColor: ['#38bdf8', '#10b981', '#f59e0b'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    cutout: '70%'
                }
            });

            // Heart rates day chart
            const hrCtx = document.getElementById('hrDayChart').getContext('2d');
            new Chart(hrCtx, {
                type: 'bar',
                data: {
                    labels: ['Sáng', 'Trưa', 'Chiều', 'Tối', 'Đêm'],
                    datasets: [{
                        label: 'Nhịp tim (bpm)',
                        data: [72, 85, 78, 68, 62],
                        backgroundColor: 'rgba(244, 63, 94, 0.45)',
                        borderColor: '#f43f5e',
                        borderWidth: 1.5,
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { grid: { color: 'rgba(255, 255, 255, 0.05)' }, ticks: { color: '#94a3b8', font: { size: 9 } } },
                        x: { grid: { display: false }, ticks: { color: '#94a3b8', font: { size: 9 } } }
                    },
                    plugins: { legend: { display: false } }
                }
            });

            // Calories & Water weekly chart
            const calCtx = document.getElementById('caloriesWaterChart').getContext('2d');
            new Chart(calCtx, {
                type: 'line',
                data: {
                    labels: ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN'],
                    datasets: [
                        {
                            label: 'Calories (kcal)',
                            data: [2100, 2400, 1950, 2300, 2600, 2800, 2200],
                            borderColor: '#a855f7',
                            borderWidth: 2,
                            fill: false,
                            tension: 0.3
                        },
                        {
                            label: 'Nước (L)',
                            data: [1.8, 2.2, 1.5, 2.0, 2.5, 2.4, 1.9],
                            borderColor: '#06b6d4',
                            borderWidth: 2,
                            fill: false,
                            tension: 0.3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { grid: { color: 'rgba(255, 255, 255, 0.05)' }, ticks: { color: '#94a3b8', font: { size: 9 } } },
                        x: { grid: { display: false }, ticks: { color: '#94a3b8', font: { size: 9 } } }
                    },
                    plugins: { legend: { labels: { color: '#94a3b8', font: { size: 9 } } } }
                }
            });
        });
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="vi" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard — HealthAI</title>

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
        .glass {
            background: rgba(255, 255, 255, 0.45);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px border-border/50;
        }
        .dark .glass {
            background: rgba(15, 23, 42, 0.45);
        }
        .glass-sidebar {
            background: rgba(15, 23, 42, 0.9);
            backdrop-filter: blur(20px);
        }
        .shadow-glow {
            box-shadow: 0 0 25px -5px rgba(99, 102, 241, 0.25);
        }
        .gradient-primary {
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        }
        .gradient-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        .gradient-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }
        .gradient-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }
        .gradient-dark {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        }
        @keyframes pulse-glow {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.05); opacity: 0.8; }
        }
        .animate-pulse-glow {
            animation: pulse-glow 3s infinite ease-in-out;
        }
    </style>

    <script>
        // Synchronize Theme
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        function toggleDark() {
            document.documentElement.classList.toggle('dark');
            let isDark = document.documentElement.classList.contains('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        }
    </script>
</head>
<body class="bg-background text-foreground antialiased min-h-screen relative overflow-x-hidden">
    
    <!-- Background Glow Patterns matching user pages -->
    <div class="pointer-events-none fixed inset-0 -z-10" style="background: var(--gradient-hero)"></div>
    <div class="pointer-events-none fixed inset-0 -z-10 grid-bg opacity-30"></div>
    <div class="pointer-events-none fixed -top-40 -right-40 -z-10 h-96 w-96 rounded-full bg-primary/10 blur-[120px]"></div>
    <div class="pointer-events-none fixed -bottom-40 -left-40 -z-10 h-96 w-96 rounded-full bg-accent/10 blur-[120px]"></div>

    <div class="flex min-h-screen">
        
        <!-- ================= SIDEBAR LEFT ================= -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 -translate-x-full transition-transform duration-300 lg:translate-x-0 glass-sidebar border-r border-border/40 text-slate-300 flex flex-col">
            <!-- Sidebar Header -->
            <div class="p-6 border-b border-border/20 flex items-center justify-between">
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl gradient-primary shadow-glow group-hover:scale-105 transition-transform">
                        <i data-lucide="heart-pulse" class="h-5 w-5 text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-base font-bold tracking-tight text-white">Health<span class="gradient-text">AI</span></h1>
                        <p class="text-[9px] font-medium uppercase tracking-wider text-primary/80 leading-none">Admin Control</p>
                    </div>
                </a>
                <button onclick="toggleSidebar()" class="lg:hidden p-1.5 rounded-lg hover:bg-white/10 text-slate-400 hover:text-white">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
            </div>

            <!-- Profile Overview (Quick Admin Badge) -->
            <div class="p-4 mx-4 mt-6 rounded-2xl bg-white/5 border border-white/5 flex items-center gap-3">
                <img src="https://ui-avatars.com/api/?name=Admin+HealthAI&background=6366f1&color=fff" alt="admin" class="h-10 w-10 rounded-full border border-white/15">
                <div>
                    <p class="text-xs font-bold text-white leading-tight">Admin System</p>
                    <p class="text-[9px] text-emerald-400 font-semibold uppercase tracking-wider mt-0.5 flex items-center gap-1">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Trực tuyến
                    </p>
                </div>
            </div>

            <!-- Nav Menu Items -->
            <nav class="flex-1 p-4 space-y-1.5 mt-6">
                <button onclick="switchTab('dashboard')" id="nav-dashboard" class="nav-btn w-full flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition-all text-white gradient-primary shadow-glow">
                    <i data-lucide="layout-dashboard" class="h-4 w-4"></i> Dashboard
                </button>
                
                <button onclick="switchTab('users')" id="nav-users" class="nav-btn w-full flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition-all text-slate-400 hover:bg-white/5 hover:text-white">
                    <i data-lucide="users" class="h-4 w-4"></i> Quản lý người dùng
                </button>

                <button onclick="switchTab('doctors')" id="nav-doctors" class="nav-btn w-full flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition-all text-slate-400 hover:bg-white/5 hover:text-white">
                    <i data-lucide="stethoscope" class="h-4 w-4"></i> Quản lý bác sĩ
                </button>
                
                <button onclick="switchTab('packages')" id="nav-packages" class="nav-btn w-full flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition-all text-slate-400 hover:bg-white/5 hover:text-white">
                    <i data-lucide="credit-card" class="h-4 w-4"></i> Quản lý gói dịch vụ
                </button>
                
                <button onclick="switchTab('revenue')" id="nav-revenue" class="nav-btn w-full flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition-all text-slate-400 hover:bg-white/5 hover:text-white">
                    <i data-lucide="trending-up" class="h-4 w-4"></i> Thống kê doanh thu
                </button>
                
                <button onclick="switchTab('feedback')" id="nav-feedback" class="nav-btn w-full flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition-all text-slate-400 hover:bg-white/5 hover:text-white">
                    <i data-lucide="message-square" class="h-4 w-4"></i> Quản lý phản hồi
                </button>
                
                <button onclick="switchTab('settings')" id="nav-settings" class="nav-btn w-full flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition-all text-slate-400 hover:bg-white/5 hover:text-white">
                    <i data-lucide="settings" class="h-4 w-4"></i> Cài đặt hệ thống
                </button>
            </nav>

            <!-- Bottom Exit -->
            <div class="p-4 border-t border-border/20">
                <form method="POST" action="{{ route('admin.logout') }}">
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
            <header class="sticky top-0 z-30 w-full border-b border-border/40 bg-background/80 backdrop-blur-xl px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between shadow-sm">
                <!-- Left: Sidebar trigger on mobile -->
                <div class="flex items-center gap-4">
                    <button onclick="toggleSidebar()" class="lg:hidden flex h-10 w-10 items-center justify-center rounded-xl bg-card border border-border text-foreground hover:bg-accent transition-colors">
                        <i data-lucide="menu" class="h-5 w-5"></i>
                    </button>
                    <div class="hidden sm:block">
                        <h2 id="page-title-badge" class="text-lg font-black tracking-tight flex items-center gap-2">
                            Admin <span class="gradient-text">Control Center</span>
                        </h2>
                    </div>
                </div>

                <!-- Right Header Actions -->
                <div class="flex items-center gap-3">
                    
                    <!-- Search bar (Realtime search toggle) -->
                    <div class="relative hidden md:block w-64">
                        <i data-lucide="search" class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground"></i>
                        <input id="global-search-input" onkeyup="handleGlobalSearch(this)" placeholder="Tìm kiếm nhanh..." class="h-10 w-full rounded-full border border-border/50 bg-card/50 pl-10 pr-4 text-xs font-semibold outline-none transition-all focus:border-primary focus:ring-4 focus:ring-primary/10">
                    </div>

                    <!-- Dark Mode Toggle -->
                    <button onclick="toggleDark()" class="flex h-10 w-10 items-center justify-center rounded-xl border border-border/50 bg-card/50 text-muted-foreground transition-all hover:text-primary hover:border-primary/30">
                        <i data-lucide="moon" class="h-4 w-4 block dark:hidden"></i>
                        <i data-lucide="sun" class="h-4 w-4 hidden dark:block"></i>
                    </button>

                    <!-- Realtime Notification Dropdown -->
                    <div class="relative group">
                        <button class="relative flex h-10 w-10 items-center justify-center rounded-xl border border-border/50 bg-card/50 text-muted-foreground transition-all hover:text-primary hover:border-primary/30">
                            <i data-lucide="bell" class="h-4 w-4"></i>
                            <span class="absolute right-3 top-3 h-2 w-2 rounded-full bg-destructive animate-pulse"></span>
                        </button>
                        
                        <!-- Notifications Popup -->
                        <div class="absolute right-0 top-full mt-2 w-80 origin-top-right rounded-2xl border border-border/50 bg-card p-4 shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <div class="flex items-center justify-between border-b border-border/40 pb-2 mb-2">
                                <span class="text-xs font-bold text-foreground">Thông báo hệ thống</span>
                                <span class="rounded bg-primary/10 px-1.5 py-0.5 text-[9px] font-black text-primary uppercase">Mới</span>
                            </div>
                            <div class="space-y-3">
                                <div class="flex gap-2.5 items-start text-xs hover:bg-muted/30 p-1.5 rounded-lg">
                                    <div class="h-6 w-6 shrink-0 rounded-full bg-primary/10 text-primary flex items-center justify-center"><i data-lucide="user-plus" class="h-3 w-3"></i></div>
                                    <div>
                                        <p class="font-semibold">User mới đăng ký</p>
                                        <p class="text-[10px] text-muted-foreground">Nguyễn Văn An vừa tham gia.</p>
                                    </div>
                                </div>
                                <div class="flex gap-2.5 items-start text-xs hover:bg-muted/30 p-1.5 rounded-lg">
                                    <div class="h-6 w-6 shrink-0 rounded-full bg-success/10 text-success flex items-center justify-center"><i data-lucide="credit-card" class="h-3 w-3"></i></div>
                                    <div>
                                        <p class="font-semibold">Giao dịch Premium thành công</p>
                                        <p class="text-[10px] text-muted-foreground">Nhận 299,000 VNĐ từ Phạm Minh D.</p>
                                    </div>
                                </div>
                                <div class="flex gap-2.5 items-start text-xs hover:bg-muted/30 p-1.5 rounded-lg">
                                    <div class="h-6 w-6 shrink-0 rounded-full bg-amber-500/10 text-amber-500 flex items-center justify-center"><i data-lucide="message-square" class="h-3 w-3"></i></div>
                                    <div>
                                        <p class="font-semibold">Phản hồi mới</p>
                                        <p class="text-[10px] text-muted-foreground">Lê Hoàng C gửi phản hồi góp ý.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="h-8 w-px bg-border/40 mx-1"></div>

                    <!-- User Circle Avatar -->
                    <div class="relative shrink-0">
                        <img src="https://ui-avatars.com/api/?name=Admin+HealthAI&background=6366f1&color=fff" alt="user" class="h-9 w-9 rounded-full ring-2 ring-primary/20 object-cover shadow-sm">
                    </div>
                </div>
            </header>

            <!-- MAIN WORKSPACE -->
            <main class="flex-1 px-4 sm:px-6 lg:px-8 py-6 max-w-[1600px] w-full mx-auto space-y-6">

                <!-- ================= TAB: DASHBOARD ================= -->
                <section id="tab-dashboard" class="tab-pane space-y-6">
                    
                    <!-- Welcome Glow Card -->
                    <div class="relative overflow-hidden rounded-3xl border border-border/50 p-6 md:p-8 shadow-elevated animate-fade-in-up">
                        <div class="absolute inset-0 gradient-primary opacity-90"></div>
                        <div class="absolute inset-0 grid-bg opacity-20"></div>
                        <div class="absolute -right-20 -top-20 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>

                        <div class="relative flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                            <div class="text-white">
                                <div class="inline-flex items-center gap-1.5 rounded-full border border-white/30 bg-white/15 px-3 py-1 text-[11px] font-bold backdrop-blur-md">
                                    <i data-lucide="sparkles" class="h-3 w-3"></i>
                                    Trung tâm Giám sát AI
                                </div>
                                <h1 class="mt-4 text-2xl font-black md:text-3xl lg:text-4xl">Tổng quan Hệ thống Smart Health App</h1>
                                <p class="mt-2 text-sm text-white/80 max-w-xl">Hôm nay hệ thống hoạt động tối ưu. Trí tuệ nhân tạo ghi nhận không có sự cố quá tải nào và tỷ lệ đăng ký gói dịch vụ Premium đang tăng 14.2%.</p>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="rounded-2xl border border-white/20 bg-white/10 p-5 text-center text-white backdrop-blur-md">
                                    <p class="text-[10px] font-black uppercase tracking-wider opacity-80">AI Phân tích Hôm nay</p>
                                    <p class="text-3xl font-black mt-1 font-display tracking-tight">{{ $aiAnalysesToday }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics Summary Grid -->
                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 xl:grid-cols-6">
                        <!-- Card 1 -->
                        <div class="glass rounded-2xl border border-border/50 p-5 shadow-soft hover:shadow-glow transition-all duration-300 relative group overflow-hidden">
                            <div class="flex items-center justify-between">
                                <div class="h-10 w-10 rounded-xl gradient-primary text-white flex items-center justify-center shadow-glow">
                                    <i data-lucide="users" class="h-5 w-5"></i>
                                </div>
                                <span class="text-[10px] font-bold text-success bg-success/10 px-2 py-0.5 rounded-full">+12%</span>
                            </div>
                            <p class="mt-4 text-[10px] font-black uppercase tracking-wider text-muted-foreground">Tổng người dùng</p>
                            <p class="text-2xl font-black tracking-tight mt-1 font-display">{{ number_format($totalUsers) }}</p>
                            <div class="absolute -right-8 -top-8 h-20 w-20 rounded-full bg-primary/5 blur-xl group-hover:opacity-100 transition-opacity"></div>
                        </div>
                        
                        <!-- Card 2 -->
                        <div class="glass rounded-2xl border border-border/50 p-5 shadow-soft hover:shadow-glow transition-all duration-300 relative group overflow-hidden">
                            <div class="flex items-center justify-between">
                                <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-emerald-500 to-green-600 text-white flex items-center justify-center shadow-glow">
                                    <i data-lucide="activity" class="h-5 w-5"></i>
                                </div>
                                <span class="text-[10px] font-bold text-success bg-success/10 px-2 py-0.5 rounded-full">+8%</span>
                            </div>
                            <p class="mt-4 text-[10px] font-black uppercase tracking-wider text-muted-foreground">Hoạt động Hôm nay</p>
                            <p class="text-2xl font-black tracking-tight mt-1 font-display">{{ $activeToday }}</p>
                        </div>

                        <!-- Card 3 -->
                        <div class="glass rounded-2xl border border-border/50 p-5 shadow-soft hover:shadow-glow transition-all duration-300 relative group overflow-hidden col-span-2 sm:col-span-1">
                            <div class="flex items-center justify-between">
                                <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-violet-500 to-purple-600 text-white flex items-center justify-center shadow-glow">
                                    <i data-lucide="credit-card" class="h-5 w-5"></i>
                                </div>
                                <span class="text-[10px] font-bold text-purple-400 bg-purple-400/10 px-2 py-0.5 rounded-full">Premium</span>
                            </div>
                            <p class="mt-4 text-[10px] font-black uppercase tracking-wider text-muted-foreground">Đã Bán (Premium)</p>
                            <p class="text-2xl font-black tracking-tight mt-1 font-display">{{ $packagesSold['premium'] }}</p>
                        </div>

                        <!-- Card 4 -->
                        <div class="glass rounded-2xl border border-border/50 p-5 shadow-soft hover:shadow-glow transition-all duration-300 relative group overflow-hidden">
                            <div class="flex items-center justify-between">
                                <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 text-white flex items-center justify-center shadow-glow">
                                    <i data-lucide="trending-up" class="h-5 w-5"></i>
                                </div>
                                <span class="text-[10px] font-bold text-success bg-success/10 px-2 py-0.5 rounded-full">+24%</span>
                            </div>
                            <p class="mt-4 text-[10px] font-black uppercase tracking-wider text-muted-foreground">Doanh thu Tổng</p>
                            <p class="text-2xl font-black tracking-tight mt-1 font-display text-primary">{{ number_format($totalRevenue) }}<span class="text-xs ml-0.5 font-normal text-muted-foreground">đ</span></p>
                        </div>

                        <!-- Card 5 -->
                        <div class="glass rounded-2xl border border-border/50 p-5 shadow-soft hover:shadow-glow transition-all duration-300 relative group overflow-hidden">
                            <div class="flex items-center justify-between">
                                <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-rose-500 to-pink-600 text-white flex items-center justify-center shadow-glow">
                                    <i data-lucide="message-square" class="h-5 w-5"></i>
                                </div>
                                <span class="text-[10px] font-bold text-amber-500 bg-amber-500/10 px-2 py-0.5 rounded-full">Đánh giá</span>
                            </div>
                            <p class="mt-4 text-[10px] font-black uppercase tracking-wider text-muted-foreground">Tổng Phản Hồi</p>
                            <p class="text-2xl font-black tracking-tight mt-1 font-display">{{ count($feedbacksList) }}</p>
                        </div>

                        <!-- Card 6 -->
                        <div class="glass rounded-2xl border border-border/50 p-5 shadow-soft hover:shadow-glow transition-all duration-300 relative group overflow-hidden">
                            <div class="flex items-center justify-between">
                                <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-cyan-500 to-blue-600 text-white flex items-center justify-center shadow-glow">
                                    <i data-lucide="zap" class="h-5 w-5"></i>
                                </div>
                                <span class="text-[10px] font-bold text-success bg-success/10 px-2 py-0.5 rounded-full">100%</span>
                            </div>
                            <p class="mt-4 text-[10px] font-black uppercase tracking-wider text-muted-foreground">AI Uptime</p>
                            <p class="text-2xl font-black tracking-tight mt-1 font-display text-emerald-500">Online</p>
                        </div>
                    </div>

                    <!-- Double Column Grid (Charts & Activity Logs) -->
                    <div class="grid gap-6 lg:grid-cols-7">
                        <!-- Chart Area -->
                        <div class="glass rounded-[2rem] border border-border/40 p-6 shadow-soft lg:col-span-4 min-h-[400px] flex flex-col">
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <h3 class="text-xs font-black uppercase tracking-widest text-primary">Phát triển tài chính</h3>
                                    <h4 class="text-lg font-bold text-foreground mt-0.5">Biểu đồ Doanh thu hệ thống</h4>
                                </div>
                                <div class="inline-flex gap-2">
                                    <button class="px-3 py-1.5 rounded-xl border border-border/40 bg-background/50 text-[10px] font-bold tracking-wider hover:bg-accent">5 tháng qua</button>
                                </div>
                            </div>
                            <div class="flex-1 relative min-h-[300px]">
                                <canvas id="revenueChart" class="w-full h-full"></canvas>
                            </div>
                        </div>

                        <!-- User Growth & Gói dịch vụ Charts -->
                        <div class="glass rounded-[2rem] border border-border/40 p-6 shadow-soft lg:col-span-3 flex flex-col justify-between gap-6">
                            <div>
                                <h3 class="text-xs font-black uppercase tracking-widest text-primary">Breakdown</h3>
                                <h4 class="text-lg font-bold text-foreground mt-0.5">Tỷ lệ gói dịch vụ đăng ký</h4>
                            </div>
                            <div class="flex justify-center items-center h-48 w-full">
                                <canvas id="packagesPieChart"></canvas>
                            </div>
                            <div class="grid grid-cols-3 gap-2 border-t border-border/20 pt-4 text-center">
                                <div>
                                    <p class="text-[9px] font-black text-muted-foreground uppercase">Free</p>
                                    <p class="text-base font-bold text-slate-400 mt-1">53.5%</p>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black text-primary uppercase">Pro</p>
                                    <p class="text-base font-bold text-primary mt-1">28.7%</p>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black text-purple-400 uppercase">Premium</p>
                                    <p class="text-base font-bold text-purple-400 mt-1">17.8%</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Lower Double Column Grid (Transactions & Logs) -->
                    <div class="grid gap-6 lg:grid-cols-7">
                        <!-- Transactions Card -->
                        <div class="glass rounded-[2rem] border border-border/40 p-6 shadow-soft lg:col-span-4 flex flex-col">
                            <h3 class="text-lg font-bold text-foreground mb-4">Giao dịch gần đây</h3>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="border-b border-border/30 text-[10px] font-black uppercase tracking-wider text-muted-foreground">
                                            <th class="pb-3">Mã TXN</th>
                                            <th class="pb-3">Người dùng</th>
                                            <th class="pb-3">Gói</th>
                                            <th class="pb-3 text-right">Số tiền</th>
                                            <th class="pb-3 text-right">Trạng thái</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-border/20 text-xs font-semibold">
                                        @foreach($recentTransactions as $txn)
                                        <tr>
                                            <td class="py-3.5 text-primary font-bold">{{ $txn['id'] }}</td>
                                            <td class="py-3.5">{{ $txn['name'] }}</td>
                                            <td class="py-3.5">
                                                <span class="rounded px-1.5 py-0.5 text-[9px] font-black uppercase {{ $txn['plan'] === 'Premium' ? 'bg-purple-500/10 text-purple-400' : 'bg-blue-500/10 text-blue-400' }}">
                                                    {{ $txn['plan'] }}
                                                </span>
                                            </td>
                                            <td class="py-3.5 text-right font-bold">{{ number_format($txn['amount']) }}đ</td>
                                            <td class="py-3.5 text-right">
                                                <span class="inline-flex items-center gap-1 text-[10px] font-bold {{ $txn['status'] === 'success' ? 'text-emerald-400 bg-emerald-400/15' : 'text-rose-400 bg-rose-400/15' }} px-2 py-0.5 rounded-full">
                                                    {{ $txn['status'] === 'success' ? 'Thành công' : 'Thất bại' }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- System Activity Logs -->
                        <div class="glass rounded-[2rem] border border-border/40 p-6 shadow-soft lg:col-span-3 flex flex-col justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-foreground mb-4">Hoạt động hệ thống</h3>
                                <div class="space-y-4">
                                    @foreach($activityLogs as $log)
                                    <div class="flex gap-3.5 items-start text-xs">
                                        <div class="h-8 w-8 rounded-xl flex items-center justify-center shadow-soft shrink-0 {{ $log['color'] }}">
                                            <i data-lucide="{{ $log['icon'] }}" class="h-4 w-4"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-semibold leading-relaxed">{{ $log['text'] }}</p>
                                            <p class="text-[10px] text-muted-foreground mt-0.5">{{ $log['time'] }}</p>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="border-t border-border/20 pt-4 mt-6 text-center">
                                <button onclick="switchTab('settings')" class="text-xs font-bold text-primary hover:underline">Xem cấu hình bảo mật AI &rarr;</button>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- ================= TAB: USERS ================= -->
                <section id="tab-users" class="tab-pane space-y-6 hidden">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <h2 class="text-2xl font-black tracking-tight">Quản lý Người Dùng</h2>
                            <p class="text-xs text-muted-foreground mt-1">Danh sách tài khoản hệ thống cùng chỉ số sinh học cập nhật tự động.</p>
                        </div>
                        <div class="flex flex-wrap gap-2.5">
                            <select id="filter-plan" onchange="filterUsers()" class="h-10 text-xs rounded-xl border border-border/50 bg-card px-3 font-semibold outline-none focus:border-primary">
                                <option value="all">Tất cả gói</option>
                                <option value="Free">Gói Free</option>
                                <option value="Pro">Gói Pro</option>
                                <option value="Premium">Gói Premium</option>
                            </select>
                            <select id="filter-status" onchange="filterUsers()" class="h-10 text-xs rounded-xl border border-border/50 bg-card px-3 font-semibold outline-none focus:border-primary">
                                <option value="all">Tất cả trạng thái</option>
                                <option value="active">Hoạt động</option>
                                <option value="blocked">Đã khóa</option>
                            </select>
                        </div>
                    </div>

                    <!-- Users Table Card -->
                    <div class="glass rounded-3xl border border-border/40 overflow-hidden shadow-soft">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-border/30 bg-card/40 text-[10px] font-black uppercase tracking-wider text-muted-foreground">
                                        <th class="p-4 pl-6">Họ tên</th>
                                        <th class="p-4">Thông tin liên hệ</th>
                                        <th class="p-4">Gói sử dụng</th>
                                        <th class="p-4">Ngày tạo</th>
                                        <th class="p-4 text-center">Trạng thái</th>
                                        <th class="p-4 text-right pr-6">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody id="users-table-body" class="divide-y divide-border/20 text-xs font-semibold">
                                    <!-- Dynamic rows loaded from javascript array -->
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Table Footer Pagination -->
                        <div class="p-4 bg-card/35 border-t border-border/20 flex items-center justify-between">
                            <span class="text-xs text-muted-foreground font-semibold">Hiển thị <span class="font-bold text-foreground" id="users-count-showing">0</span> trong <span class="font-bold text-foreground" id="users-count-total">0</span> người dùng</span>
                            <div class="inline-flex gap-2">
                                <button class="h-8 px-3 rounded-lg border border-border/50 text-[10px] font-black uppercase bg-background/50 hover:bg-accent">Trang trước</button>
                                <button class="h-8 px-3 rounded-lg border border-border/50 text-[10px] font-black uppercase bg-background/50 hover:bg-accent">Trang sau</button>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- ================= TAB: DOCTORS ================= -->
                <section id="tab-doctors" class="tab-pane space-y-6 hidden">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <h2 class="text-2xl font-black tracking-tight">Quản lý Bác Sĩ</h2>
                            <p class="text-xs text-muted-foreground mt-1">Danh sách bác sĩ cộng tác trên hệ thống y tế.</p>
                        </div>
                        <div class="flex flex-wrap gap-2.5">
                            <button onclick="openNewDoctorModal()" class="group inline-flex items-center gap-2 rounded-2xl gradient-primary px-5 py-3 text-xs font-semibold text-white shadow-glow transition-transform hover:scale-[1.03]">
                                <i data-lucide="plus" class="h-4 w-4"></i> Thêm bác sĩ mới
                            </button>
                        </div>
                    </div>

                    <!-- Doctors Table Card -->
                    <div class="glass rounded-3xl border border-border/40 overflow-hidden shadow-soft">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-border/30 bg-card/40 text-[10px] font-black uppercase tracking-wider text-muted-foreground">
                                        <th class="p-4 pl-6">Họ tên bác sĩ</th>
                                        <th class="p-4">Chuyên khoa</th>
                                        <th class="p-4">Địa điểm làm việc</th>
                                        <th class="p-4">Liên hệ</th>
                                        <th class="p-4 text-center">Trạng thái</th>
                                        <th class="p-4 text-right pr-6">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody id="doctors-table-body" class="divide-y divide-border/20 text-xs font-semibold">
                                    <!-- Dynamic rows loaded from javascript array -->
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Table Footer Pagination -->
                        <div class="p-4 bg-card/35 border-t border-border/20 flex items-center justify-between">
                            <span class="text-xs text-muted-foreground font-semibold">Hiển thị <span class="font-bold text-foreground" id="doctors-count-showing">0</span> trong <span class="font-bold text-foreground" id="doctors-count-total">0</span> bác sĩ</span>
                        </div>
                    </div>
                </section>

                <!-- ================= TAB: PACKAGES ================= -->
                <section id="tab-packages" class="tab-pane space-y-6 hidden">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-black tracking-tight">Quản lý Gói dịch vụ</h2>
                            <p class="text-xs text-muted-foreground mt-1">Thiết lập cấu hình phân chia tính năng cùng giá bán gói.</p>
                        </div>
                        <button onclick="openNewPackageModal()" class="group inline-flex items-center gap-2 rounded-2xl gradient-primary px-5 py-3 text-xs font-semibold text-white shadow-glow transition-transform hover:scale-[1.03]">
                            <i data-lucide="plus" class="h-4 w-4"></i> Thêm gói mới
                        </button>
                    </div>

                    <!-- Packages List Grid -->
                    <div class="grid gap-6 md:grid-cols-3">
                        @foreach($packagesList as $pkg)
                        <div class="glass relative overflow-hidden rounded-[2.5rem] border border-border/40 p-6 shadow-soft hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between min-h-[480px]">
                            <!-- Glowing gradient circle top right -->
                            <div class="absolute -right-20 -top-20 h-52 w-52 rounded-full bg-gradient-to-br {{ $pkg['color'] }} opacity-10 blur-3xl pointer-events-none"></div>

                            <div>
                                <div class="flex justify-between items-start">
                                    <div class="h-12 w-12 rounded-2xl bg-gradient-to-br {{ $pkg['color'] }} text-white flex items-center justify-center shadow-glow">
                                        <i data-lucide="{{ $pkg['icon'] }}" class="h-6 w-6"></i>
                                    </div>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-emerald-400/10 text-emerald-400 border border-emerald-400/20">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span> Active
                                    </span>
                                </div>

                                <h3 class="mt-6 text-xl font-bold tracking-tight text-foreground">{{ $pkg['name'] }}</h3>
                                <div class="mt-2 flex items-baseline gap-1">
                                    <span class="text-3xl font-black tracking-tight font-display text-primary">{{ number_format($pkg['price']) }}đ</span>
                                    <span class="text-xs text-muted-foreground">/ {{ $pkg['duration'] }}</span>
                                </div>

                                <ul class="mt-6 space-y-3 text-xs text-muted-foreground font-semibold border-t border-border/20 pt-4">
                                    @foreach($pkg['features'] as $feat)
                                    <li class="flex items-start gap-2.5">
                                        <div class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-primary/10 text-primary">
                                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                        <span>{{ $feat }}</span>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="mt-8 pt-4 border-t border-border/20 flex gap-2.5">
                                <button onclick="openEditPackageModal({{ $pkg['id'] }})" class="flex-1 h-10 text-xs font-semibold rounded-xl border border-border/60 bg-background/50 hover:bg-accent text-center transition-colors">Chỉnh sửa</button>
                                <button class="h-10 w-10 shrink-0 flex items-center justify-center rounded-xl bg-rose-500/10 text-rose-400 hover:bg-rose-500 hover:text-white transition-colors">
                                    <i data-lucide="trash-2" class="h-4 w-4"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </section>

                <!-- ================= TAB: REVENUE ================= -->
                <section id="tab-revenue" class="tab-pane space-y-6 hidden">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <h2 class="text-2xl font-black tracking-tight">Thống kê Doanh thu</h2>
                            <p class="text-xs text-muted-foreground mt-1">Lịch sử giao dịch thanh toán trực tiếp qua ví MoMo & chuyển khoản ngân hàng.</p>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="mockExport('PDF')" class="h-10 text-xs font-semibold rounded-xl border border-border/60 bg-background/50 hover:bg-accent px-4 flex items-center gap-1.5">
                                <i data-lucide="file-text" class="h-4 w-4"></i> Xuất PDF
                            </button>
                            <button onclick="mockExport('Excel')" class="h-10 text-xs font-semibold rounded-xl gradient-success px-4 text-white hover:scale-[1.02] flex items-center gap-1.5 transition-all">
                                <i data-lucide="file-spreadsheet" class="h-4 w-4"></i> Xuất Excel
                            </button>
                        </div>
                    </div>

                    <!-- Top statistics -->
                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                        <div class="glass rounded-2xl border border-border/40 p-5 shadow-soft">
                            <p class="text-[10px] font-black uppercase tracking-wider text-muted-foreground">Doanh thu hôm nay</p>
                            <p class="text-2xl font-black mt-2 font-display text-emerald-500">{{ number_format($revenueToday) }}đ</p>
                        </div>
                        <div class="glass rounded-2xl border border-border/40 p-5 shadow-soft">
                            <p class="text-[10px] font-black uppercase tracking-wider text-muted-foreground">Doanh thu tháng này</p>
                            <p class="text-2xl font-black mt-2 font-display text-primary">{{ number_format($monthlyRevenue['data'][4]) }}đ</p>
                        </div>
                        <div class="glass rounded-2xl border border-border/40 p-5 shadow-soft">
                            <p class="text-[10px] font-black uppercase tracking-wider text-muted-foreground">Tổng Doanh thu lũy kế</p>
                            <p class="text-2xl font-black mt-2 font-display text-purple-500">{{ number_format($totalRevenue) }}đ</p>
                        </div>
                        <div class="glass rounded-2xl border border-border/40 p-5 shadow-soft">
                            <p class="text-[10px] font-black uppercase tracking-wider text-muted-foreground">Tỷ lệ chuyển đổi Premium</p>
                            <p class="text-2xl font-black mt-2 font-display text-amber-500">22.4%</p>
                        </div>
                    </div>

                    <!-- Transactions List Card -->
                    <div class="glass rounded-3xl border border-border/40 overflow-hidden shadow-soft">
                        <div class="p-6 border-b border-border/20 flex justify-between items-center bg-card/30">
                            <h3 class="text-base font-bold text-foreground">Nhật ký giao dịch chi tiết</h3>
                            <input onkeyup="handleTxnSearch(this)" placeholder="Lọc theo mã TXN hoặc tên..." class="h-9 w-60 rounded-full border border-border/50 bg-background/50 pl-4 pr-4 text-xs font-semibold outline-none focus:border-primary">
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-border/30 text-[10px] font-black uppercase tracking-wider text-muted-foreground bg-card/20">
                                        <th class="p-4 pl-6">Mã giao dịch</th>
                                        <th class="p-4">Khách hàng</th>
                                        <th class="p-4">Gói mua</th>
                                        <th class="p-4">Thời gian</th>
                                        <th class="p-4 text-right">Số tiền</th>
                                        <th class="p-4 text-right pr-6">Cổng thanh toán</th>
                                    </tr>
                                </thead>
                                <tbody id="txn-table-body" class="divide-y divide-border/20 text-xs font-semibold">
                                    @foreach($recentTransactions as $txn)
                                    <tr class="txn-row" data-search="{{ strtolower($txn['id'] . ' ' . $txn['name']) }}">
                                        <td class="p-4 pl-6 text-primary font-bold">{{ $txn['id'] }}</td>
                                        <td class="p-4">{{ $txn['name'] }}</td>
                                        <td class="p-4">
                                            <span class="rounded px-1.5 py-0.5 text-[9px] font-black uppercase {{ $txn['plan'] === 'Premium' ? 'bg-purple-500/10 text-purple-400' : 'bg-blue-500/10 text-blue-400' }}">
                                                {{ $txn['plan'] }}
                                            </span>
                                        </td>
                                        <td class="p-4 text-muted-foreground">{{ $txn['date'] }}</td>
                                        <td class="p-4 text-right font-bold">{{ number_format($txn['amount']) }}đ</td>
                                        <td class="p-4 text-right pr-6 text-muted-foreground font-semibold">Ví MoMo</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <!-- ================= TAB: FEEDBACK ================= -->
                <section id="tab-feedback" class="tab-pane space-y-6 hidden">
                    <div>
                        <h2 class="text-2xl font-black tracking-tight">Quản lý Phản Hồi</h2>
                        <p class="text-xs text-muted-foreground mt-1">Tổng hợp đóng góp và phản ảnh từ người dùng để cải thiện dịch vụ.</p>
                    </div>

                    <!-- Feedback Grid -->
                    <div class="grid gap-6 md:grid-cols-2">
                        @foreach($feedbacksList as $fb)
                        <div class="glass rounded-[2rem] border border-border/40 p-6 shadow-soft flex flex-col justify-between gap-5 relative group overflow-hidden">
                            <div class="absolute -right-12 -top-12 h-32 w-32 rounded-full bg-primary/5 blur-xl group-hover:bg-primary/10 transition-colors"></div>

                            <div>
                                <div class="flex justify-between items-start gap-4">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $fb['avatar'] }}" alt="user" class="h-10 w-10 rounded-full border border-border/40">
                                        <div>
                                            <h4 class="text-sm font-bold text-foreground">{{ $fb['name'] }}</h4>
                                            <p class="text-[10px] text-muted-foreground mt-0.5">Thành viên</p>
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-end gap-1.5">
                                        <!-- Stars system -->
                                        <div class="flex text-amber-400">
                                            @for($s=1; $s<=5; $s++)
                                            <i data-lucide="star" class="h-3.5 w-3.5 {{ $s <= $fb['rating'] ? 'fill-current' : 'opacity-30' }}"></i>
                                            @endfor
                                        </div>
                                        <span class="text-[9px] text-muted-foreground">{{ $fb['created_at'] }}</span>
                                    </div>
                                </div>
                                
                                <p class="mt-4 text-xs font-semibold text-foreground/80 leading-relaxed italic bg-muted/20 p-3.5 rounded-2xl border border-border/30">
                                    "{{ $fb['content'] }}"
                                </p>

                                <!-- Like / Dislike reactions for Admin -->
                                <div class="flex items-center gap-4 text-xs mt-3 text-muted-foreground">
                                    <button onclick="reactFeedbackAdmin({{ $fb['id'] }}, 'like')" class="flex items-center gap-1.5 hover:text-primary transition-all font-bold cursor-pointer group">
                                        <i data-lucide="thumbs-up" class="h-4 w-4 group-hover:scale-110 transition-transform"></i>
                                        <span id="like-count-admin-{{ $fb['id'] }}">{{ $fb['likes_count'] }}</span>
                                    </button>
                                    <button onclick="reactFeedbackAdmin({{ $fb['id'] }}, 'dislike')" class="flex items-center gap-1.5 hover:text-destructive transition-all font-bold cursor-pointer group">
                                        <i data-lucide="thumbs-down" class="h-4 w-4 group-hover:scale-110 transition-transform"></i>
                                        <span id="dislike-count-admin-{{ $fb['id'] }}">{{ $fb['dislikes_count'] }}</span>
                                    </button>
                                </div>

                                <!-- Nested replies -->
                                @if(count($fb['replies']) > 0)
                                <div class="mt-4 pl-4 border-l-2 border-border/50 space-y-3.5">
                                    @foreach($fb['replies'] as $reply)
                                    <div class="p-3 rounded-2xl bg-muted/10 border border-border/20 space-y-2">
                                        <div class="flex justify-between items-start gap-4">
                                            <div class="flex items-center gap-2.5">
                                                <img src="{{ $reply['avatar'] }}" alt="avatar" class="h-7 w-7 rounded-full border border-border/40">
                                                <div>
                                                    <div class="flex items-center gap-1.5 flex-wrap">
                                                        <span class="text-xs font-bold text-foreground leading-none">{{ $reply['name'] }}</span>
                                                        @if($reply['is_admin_reply'])
                                                            <span class="rounded bg-primary/10 border border-primary/20 px-1.5 py-0.5 text-[8px] font-black text-primary uppercase">Admin</span>
                                                        @endif
                                                    </div>
                                                    <span class="text-[8px] text-muted-foreground">{{ $reply['created_at'] }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-xs text-foreground/80 leading-relaxed font-semibold">
                                            {{ $reply['content'] }}
                                        </p>
                                        <!-- Reply Reactions -->
                                        <div class="flex items-center gap-3 text-[10px] text-muted-foreground font-semibold">
                                            <button onclick="reactFeedbackAdmin({{ $reply['id'] }}, 'like')" class="flex items-center gap-1 hover:text-primary transition-all cursor-pointer">
                                                <i data-lucide="thumbs-up" class="h-3 w-3"></i>
                                                <span id="like-count-admin-{{ $reply['id'] }}">{{ $reply['likes_count'] }}</span>
                                            </button>
                                            <button onclick="reactFeedbackAdmin({{ $reply['id'] }}, 'dislike')" class="flex items-center gap-1 hover:text-destructive transition-all cursor-pointer">
                                                <i data-lucide="thumbs-down" class="h-3 w-3"></i>
                                                <span id="dislike-count-admin-{{ $reply['id'] }}">{{ $reply['dislikes_count'] }}</span>
                                            </button>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                            </div>

                            <div class="pt-4 border-t border-border/20 flex gap-2.5 justify-end">
                                <button onclick="openFeedbackModal({{ $fb['id'] }})" class="h-10 text-xs font-semibold rounded-xl bg-gradient-to-r {{ $fb['status'] === 'replied' ? 'from-slate-600 to-slate-700' : 'from-indigo-600 to-blue-600 shadow-glow' }} px-5 text-white hover:scale-[1.02] transition-transform">
                                    {{ $fb['status'] === 'replied' ? 'Phản hồi khác' : 'Trả lời người dùng' }}
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </section>

                <!-- ================= TAB: SETTINGS ================= -->
                <section id="tab-settings" class="tab-pane space-y-6 hidden">
                    <div>
                        <h2 class="text-2xl font-black tracking-tight">Cài đặt Hệ thống</h2>
                        <p class="text-xs text-muted-foreground mt-1">Cấu hình tham số AI, cổng thanh toán và thông tin ứng dụng.</p>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <!-- AI Settings -->
                        <div class="glass rounded-[2rem] border border-border/40 p-6 shadow-soft space-y-5">
                            <h3 class="text-lg font-bold text-foreground border-b border-border/20 pb-2 flex items-center gap-2">
                                <i data-lucide="brain-circuit" class="text-primary h-5 w-5"></i> Cấu hình AI Engine
                            </h3>
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h4 class="text-xs font-bold uppercase tracking-wider text-foreground">AI Diagnostic Active</h4>
                                        <p class="text-[10px] text-muted-foreground mt-0.5">Tự động chuẩn đoán chỉ số sức khỏe của người dùng.</p>
                                    </div>
                                    <input type="checkbox" checked class="h-5 w-10 appearance-none bg-border rounded-full relative cursor-pointer outline-none transition-colors checked:bg-primary before:content-[''] before:h-4 before:w-4 before:bg-white before:rounded-full before:absolute before:top-0.5 before:left-0.5 checked:before:left-5.5 before:transition-all">
                                </div>
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h4 class="text-xs font-bold uppercase tracking-wider text-foreground">Generative Meal Planner</h4>
                                        <p class="text-[10px] text-muted-foreground mt-0.5">Cho phép AI đề xuất thực đơn chi tiết theo Macros.</p>
                                    </div>
                                    <input type="checkbox" checked class="h-5 w-10 appearance-none bg-border rounded-full relative cursor-pointer outline-none transition-colors checked:bg-primary before:content-[''] before:h-4 before:w-4 before:bg-white before:rounded-full before:absolute before:top-0.5 before:left-0.5 checked:before:left-5.5 before:transition-all">
                                </div>
                                <div class="space-y-1.5 pt-2">
                                    <label class="text-xs font-bold uppercase tracking-wider text-muted-foreground">Model AI Tư Vấn Chính</label>
                                    <select class="h-10 text-xs w-full rounded-xl border border-border/50 bg-background px-3 font-semibold outline-none focus:border-primary">
                                        <option>Gemini 1.5 Flash (Standard)</option>
                                        <option>Gemini 1.5 Pro (Recommended)</option>
                                        <option>Med-PaLM 2 (Medical specialized)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- System Identity -->
                        <div class="glass rounded-[2rem] border border-border/40 p-6 shadow-soft space-y-5">
                            <h3 class="text-lg font-bold text-foreground border-b border-border/20 pb-2 flex items-center gap-2">
                                <i data-lucide="glob" class="text-primary h-5 w-5"></i> Nhận diện thương hiệu
                            </h3>
                            <div class="space-y-4">
                                <div class="space-y-1.5">
                                    <label class="text-xs font-bold uppercase tracking-wider text-muted-foreground">Tên hệ thống</label>
                                    <input value="HealthAI" class="h-10 text-xs w-full rounded-xl border border-border/50 bg-background px-3 font-semibold outline-none focus:border-primary">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-xs font-bold uppercase tracking-wider text-muted-foreground">Email Hỗ trợ y tế</label>
                                    <input value="support@healthai.vn" class="h-10 text-xs w-full rounded-xl border border-border/50 bg-background px-3 font-semibold outline-none focus:border-primary">
                                </div>
                                <div class="flex justify-between items-center pt-2">
                                    <div>
                                        <h4 class="text-xs font-bold uppercase tracking-wider text-foreground">Tuân thủ tiêu chuẩn bảo mật y tế HIPAA</h4>
                                        <p class="text-[10px] text-muted-foreground mt-0.5">Tự động mã hóa đầu cuối chỉ số của người bệnh.</p>
                                    </div>
                                    <span class="px-2.5 py-0.5 rounded bg-emerald-400/10 text-emerald-400 text-[10px] font-black uppercase">Đạt chuẩn</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button onclick="alert('Cài đặt hệ thống đã được lưu thành công!')" class="group inline-flex items-center gap-2 rounded-2xl gradient-primary px-6 py-3.5 text-sm font-semibold text-white shadow-glow transition-transform hover:scale-[1.03]">
                            Lưu cấu hình hệ thống
                        </button>
                    </div>
                </section>

            </main>
        </div>
    </div>

    <!-- ================= MODALS & POPUPS ================= -->
    
    <!-- User Detail Modal Popup -->
    <div id="userDetailModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-background/80 backdrop-blur-md opacity-0 pointer-events-none transition-all duration-300">
        <div onclick="closeUserModal()" class="absolute inset-0 cursor-pointer"></div>
        <div class="relative w-full max-w-3xl overflow-hidden rounded-[2.5rem] border border-border/50 bg-card/95 p-6 md:p-8 shadow-elevated backdrop-blur-2xl transition-all duration-300 transform scale-95 opacity-0" id="userModalContent">
            
            <button onclick="closeUserModal()" class="absolute right-4 top-4 flex h-9 w-9 items-center justify-center rounded-full border border-border bg-background/50 text-muted-foreground backdrop-blur-sm transition-all hover:bg-accent hover:text-foreground">
                <i data-lucide="x" class="h-4 w-4"></i>
            </button>

            <div class="flex flex-col md:flex-row gap-6 md:items-center border-b border-border/20 pb-6">
                <img id="detail-user-avatar" src="" alt="avatar" class="h-16 w-16 rounded-full border-2 border-primary/20 object-cover shadow-soft">
                <div class="flex-1">
                    <div class="flex items-center gap-3">
                        <h3 id="detail-user-name" class="text-xl font-bold tracking-tight text-foreground"></h3>
                        <span id="detail-user-plan" class="rounded px-2 py-0.5 text-[9px] font-black uppercase"></span>
                    </div>
                    <p id="detail-user-email" class="text-xs text-muted-foreground mt-1"></p>
                </div>
                <div class="flex gap-2">
                    <button id="btn-toggle-block" onclick="toggleBlockUser()" class="h-10 px-4 rounded-xl text-xs font-bold text-white transition-all"></button>
                    <button onclick="deleteUser()" class="h-10 w-10 flex items-center justify-center rounded-xl bg-rose-500/10 text-rose-400 hover:bg-rose-500 hover:text-white transition-colors">
                        <i data-lucide="trash-2" class="h-4 w-4"></i>
                    </button>
                </div>
            </div>

            <!-- Health Information Grid -->
            <div class="grid grid-cols-2 gap-4 md:grid-cols-4 mt-6">
                <div class="bg-muted/30 p-4 rounded-2xl border border-border/30 text-center">
                    <p class="text-[9px] font-black uppercase text-muted-foreground tracking-wider">Chỉ số BMI</p>
                    <p id="detail-user-bmi" class="text-xl font-black mt-1"></p>
                </div>
                <div class="bg-muted/30 p-4 rounded-2xl border border-border/30 text-center">
                    <p class="text-[9px] font-black uppercase text-muted-foreground tracking-wider">Nhịp tim</p>
                    <p id="detail-user-hr" class="text-xl font-black mt-1"></p>
                </div>
                <div class="bg-muted/30 p-4 rounded-2xl border border-border/30 text-center">
                    <p class="text-[9px] font-black uppercase text-muted-foreground tracking-wider">SpO₂</p>
                    <p id="detail-user-spo2" class="text-xl font-black mt-1"></p>
                </div>
                <div class="bg-muted/30 p-4 rounded-2xl border border-border/30 text-center">
                    <p class="text-[9px] font-black uppercase text-muted-foreground tracking-wider">Nhóm máu</p>
                    <p id="detail-user-blood" class="text-xl font-black mt-1"></p>
                </div>
            </div>

            <!-- User Bio Info List -->
            <div class="mt-6 border-t border-border/20 pt-6">
                <h4 class="text-xs font-bold uppercase tracking-wider text-foreground mb-3">Thông tin sinh học & Liên hệ</h4>
                <div class="grid gap-3 text-xs text-muted-foreground font-semibold sm:grid-cols-2">
                    <div class="flex justify-between border-b border-border/10 pb-1.5"><span>Số điện thoại:</span><span id="detail-user-phone" class="text-foreground"></span></div>
                    <div class="flex justify-between border-b border-border/10 pb-1.5"><span>Ngày sinh:</span><span id="detail-user-dob" class="text-foreground"></span></div>
                    <div class="flex justify-between border-b border-border/10 pb-1.5"><span>Giới tính:</span><span id="detail-user-gender" class="text-foreground"></span></div>
                    <div class="flex justify-between border-b border-border/10 pb-1.5"><span>Chiều cao:</span><span id="detail-user-height" class="text-foreground"></span></div>
                </div>
            </div>

            <!-- User Activity History List -->
            <div class="mt-6 border-t border-border/20 pt-6">
                <h4 class="text-xs font-bold uppercase tracking-wider text-foreground mb-3">Lịch sử luyện tập gần nhất</h4>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-[11px] font-semibold text-muted-foreground">
                        <thead>
                            <tr class="border-b border-border/30">
                                <th class="pb-2">Ngày</th>
                                <th class="pb-2 text-right">Bước chân</th>
                                <th class="pb-2 text-right">Giờ ngủ</th>
                            </tr>
                        </thead>
                        <tbody id="detail-user-activity-rows" class="divide-y divide-border/10">
                            <!-- Dynamic rows loaded by JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Package Modal Add/Edit -->
    <div id="packageModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-background/80 backdrop-blur-md opacity-0 pointer-events-none transition-all duration-300">
        <div onclick="closePackageModal()" class="absolute inset-0 cursor-pointer"></div>
        <div class="relative w-full max-w-lg overflow-hidden rounded-[2.5rem] border border-border/50 bg-card/95 p-6 shadow-elevated backdrop-blur-2xl transition-all duration-300 transform scale-95 opacity-0" id="packageModalContent">
            <button onclick="closePackageModal()" class="absolute right-4 top-4 flex h-9 w-9 items-center justify-center rounded-full border border-border bg-background/50 text-muted-foreground backdrop-blur-sm hover:bg-accent">
                <i data-lucide="x" class="h-4 w-4"></i>
            </button>

            <h3 id="package-modal-title" class="text-lg font-bold text-foreground mb-6">Thêm gói dịch vụ mới</h3>
            
            <div class="space-y-4">
                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-muted-foreground">Tên gói dịch vụ</label>
                    <input id="pkg-input-name" class="h-10 text-xs w-full rounded-xl border border-border/50 bg-background px-3 font-semibold outline-none focus:border-primary">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold uppercase tracking-wider text-muted-foreground">Giá bán (VNĐ)</label>
                        <input id="pkg-input-price" type="number" class="h-10 text-xs w-full rounded-xl border border-border/50 bg-background px-3 font-semibold outline-none focus:border-primary">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold uppercase tracking-wider text-muted-foreground">Thời hạn</label>
                        <input id="pkg-input-duration" value="1 tháng" class="h-10 text-xs w-full rounded-xl border border-border/50 bg-background px-3 font-semibold outline-none focus:border-primary">
                    </div>
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-muted-foreground">Các tính năng (mỗi dòng 1 tính năng)</label>
                    <textarea id="pkg-input-features" rows="4" class="text-xs w-full rounded-xl border border-border/50 bg-background p-3 font-semibold outline-none focus:border-primary"></textarea>
                </div>
            </div>

            <div class="mt-8 pt-4 border-t border-border/20 flex gap-2.5 justify-end">
                <button onclick="closePackageModal()" class="h-10 text-xs font-semibold rounded-xl border border-border/60 bg-background/50 px-5 text-center transition-colors">Hủy</button>
                <button onclick="savePackage()" class="h-10 text-xs font-semibold rounded-xl gradient-primary px-5 text-white shadow-glow hover:scale-[1.02] transition-transform">Lưu lại</button>
            </div>
        </div>
    </div>

    <!-- Feedback Modal Response Popup -->
    <div id="feedbackModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-background/80 backdrop-blur-md opacity-0 pointer-events-none transition-all duration-300">
        <div onclick="closeFeedbackModal()" class="absolute inset-0 cursor-pointer"></div>
        <div class="relative w-full max-w-lg overflow-hidden rounded-[2.5rem] border border-border/50 bg-card/95 p-6 shadow-elevated backdrop-blur-2xl transition-all duration-300 transform scale-95 opacity-0" id="feedbackModalContent">
            <button onclick="closeFeedbackModal()" class="absolute right-4 top-4 flex h-9 w-9 items-center justify-center rounded-full border border-border bg-background/50 text-muted-foreground backdrop-blur-sm hover:bg-accent">
                <i data-lucide="x" class="h-4 w-4"></i>
            </button>

            <h3 class="text-lg font-bold text-foreground mb-4">Trả lời phản hồi</h3>
            
            <div class="space-y-4">
                <div class="bg-muted/30 p-4 rounded-2xl border border-border/30 italic text-xs text-muted-foreground">
                    <p class="font-bold text-foreground not-italic mb-1" id="fb-user-name"></p>
                    <span id="fb-user-content"></span>
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-muted-foreground">Nội dung phản hồi của Admin</label>
                    <textarea id="fb-admin-reply" rows="4" placeholder="Nhập câu trả lời..." class="text-xs w-full rounded-xl border border-border/50 bg-background p-3 font-semibold outline-none focus:border-primary"></textarea>
                </div>
            </div>

            <div class="mt-8 pt-4 border-t border-border/20 flex gap-2.5 justify-end">
                <button onclick="closeFeedbackModal()" class="h-10 text-xs font-semibold rounded-xl border border-border/60 bg-background/50 px-5 text-center transition-colors">Đóng</button>
                <button onclick="submitFeedbackReply()" class="h-10 text-xs font-semibold rounded-xl gradient-primary px-5 text-white shadow-glow hover:scale-[1.02] transition-transform">Gửi phản hồi</button>
            </div>
        </div>
    </div>

    <!-- Doctor Modal Add/Edit -->
    <div id="doctorModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-background/80 backdrop-blur-md opacity-0 pointer-events-none transition-all duration-300">
        <div onclick="closeDoctorModal()" class="absolute inset-0 cursor-pointer"></div>
        <div class="relative w-full max-w-lg overflow-hidden rounded-[2.5rem] border border-border/50 bg-card/95 p-6 shadow-elevated backdrop-blur-2xl transition-all duration-300 transform scale-95 opacity-0" id="doctorModalContent">
            <button onclick="closeDoctorModal()" class="absolute right-4 top-4 flex h-9 w-9 items-center justify-center rounded-full border border-border bg-background/50 text-muted-foreground backdrop-blur-sm hover:bg-accent">
                <i data-lucide="x" class="h-4 w-4"></i>
            </button>

            <h3 id="doctor-modal-title" class="text-lg font-bold text-foreground mb-6">Thêm bác sĩ mới</h3>
            
            <div class="flex justify-center mb-6">
                <div class="relative group/avatar">
                    <img id="doc-preview-avatar" src="https://ui-avatars.com/api/?name=Doctor&background=10b981&color=fff" class="h-24 w-24 rounded-full object-cover border-2 border-primary/20 shadow-glow transition-transform duration-500 group-hover/avatar:scale-105">
                    <button type="button" onclick="document.getElementById('doc-input-avatar').click()" class="absolute -bottom-1 -right-1 flex h-8 w-8 items-center justify-center rounded-xl gradient-primary text-white shadow-lg transition-transform hover:scale-110 active:scale-95 z-20">
                        <i data-lucide="camera" class="h-4 w-4"></i>
                    </button>
                </div>
            </div>

            <div class="space-y-4">
                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-muted-foreground">Họ tên bác sĩ</label>
                    <input id="doc-input-name" class="h-10 text-xs w-full rounded-xl border border-border/50 bg-background px-3 font-semibold outline-none focus:border-primary">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold uppercase tracking-wider text-muted-foreground">Chuyên khoa</label>
                        <input id="doc-input-specialty" class="h-10 text-xs w-full rounded-xl border border-border/50 bg-background px-3 font-semibold outline-none focus:border-primary">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold uppercase tracking-wider text-muted-foreground">Địa điểm / Bệnh viện</label>
                        <input id="doc-input-place" class="h-10 text-xs w-full rounded-xl border border-border/50 bg-background px-3 font-semibold outline-none focus:border-primary">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold uppercase tracking-wider text-muted-foreground">Email</label>
                        <input id="doc-input-email" type="email" class="h-10 text-xs w-full rounded-xl border border-border/50 bg-background px-3 font-semibold outline-none focus:border-primary">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold uppercase tracking-wider text-muted-foreground">Số điện thoại</label>
                        <input id="doc-input-phone" class="h-10 text-xs w-full rounded-xl border border-border/50 bg-background px-3 font-semibold outline-none focus:border-primary">
                    </div>
                </div>
                <div class="space-y-1.5 hidden">
                    <label class="text-xs font-bold uppercase tracking-wider text-muted-foreground">Ảnh đại diện (Upload)</label>
                    <input type="file" id="doc-input-avatar" accept="image/*" onchange="previewAdminDoctorAvatar(this)">
                </div>
            </div>

            <div class="mt-8 pt-4 border-t border-border/20 flex gap-2.5 justify-end">
                <button onclick="closeDoctorModal()" class="h-10 text-xs font-semibold rounded-xl border border-border/60 bg-background/50 px-5 text-center transition-colors">Hủy</button>
                <button onclick="saveDoctor()" class="h-10 text-xs font-semibold rounded-xl gradient-primary px-5 text-white shadow-glow hover:scale-[1.02] transition-transform">Lưu lại</button>
            </div>
        </div>
    </div>

    <!-- ================= JAVASCRIPT STATE ENGINE ================= -->
    <script>
        // Init Lucide
        lucide.createIcons();

        // Data arrays passed from PHP
        let users = @json($usersList);
        let packages = @json($packagesList);
        let feedbacks = @json($feedbacksList);
        let doctors = @json($doctorsList);
        let selectedUserId = null;
        let selectedPackageId = null;
        let selectedFeedbackId = null;
        let selectedDoctorId = null;

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
                toggleSidebar();
            }

            // Load components when switching
            if (tabId === 'users') {
                renderUsersTable();
            }
            if (tabId === 'doctors') {
                renderDoctorsTable();
            }
        }

        // Toggle Sidebar on mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            sidebar.classList.toggle('-translate-x-full');
            backdrop.classList.toggle('hidden');
        }

        // ------------------ TAB: USERS MANAGEMENT ------------------
        function renderUsersTable() {
            const body = document.getElementById('users-table-body');
            body.innerHTML = '';

            const planFilter = document.getElementById('filter-plan').value;
            const statusFilter = document.getElementById('filter-status').value;
            let renderedCount = 0;

            users.forEach(user => {
                if (planFilter !== 'all' && user.plan !== planFilter) return;
                if (statusFilter !== 'all' && user.status !== statusFilter) return;

                renderedCount++;
                const row = document.createElement('tr');
                row.className = "hover:bg-muted/10 transition-colors";
                
                const statusBadge = user.status === 'active' 
                    ? '<span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-400 bg-emerald-400/15 px-2.5 py-0.5 rounded-full">Đang dùng</span>'
                    : '<span class="inline-flex items-center gap-1 text-[10px] font-bold text-rose-400 bg-rose-400/15 px-2.5 py-0.5 rounded-full">Đang khóa</span>';

                const planBadgeClass = user.plan === 'Premium' 
                    ? 'bg-purple-500/15 text-purple-400' 
                    : (user.plan === 'Pro' ? 'bg-blue-500/15 text-blue-400' : 'bg-slate-500/15 text-slate-400');

                row.innerHTML = `
                    <td class="p-4 pl-6 flex items-center gap-3">
                        <img src="${user.avatar}" alt="avatar" class="h-9 w-9 rounded-full object-cover border border-border/50">
                        <div>
                            <p class="font-bold text-foreground">${user.name}</p>
                            <p class="text-[10px] text-muted-foreground mt-0.5">${user.gender} · ${user.dob}</p>
                        </div>
                    </td>
                    <td class="p-4">
                        <p class="font-semibold text-foreground/80">${user.email}</p>
                        <p class="text-[10px] text-muted-foreground mt-0.5">${user.phone}</p>
                    </td>
                    <td class="p-4">
                        <span class="rounded px-2 py-0.5 text-[9px] font-black uppercase ${planBadgeClass}">${user.plan}</span>
                    </td>
                    <td class="p-4 text-muted-foreground font-medium">${user.created_at}</td>
                    <td class="p-4 text-center">${statusBadge}</td>
                    <td class="p-4 text-right pr-6">
                        <div class="inline-flex gap-2">
                            <button onclick="openUserModal(${user.id})" class="h-8 px-3 rounded-lg border border-border/50 text-[10px] font-black uppercase bg-background/50 hover:bg-accent text-primary">Xem chi tiết</button>
                        </div>
                    </td>
                `;
                body.appendChild(row);
            });

            // Update pagination text
            const showingEl = document.getElementById('users-count-showing');
            const totalEl = document.getElementById('users-count-total');
            if (showingEl && totalEl) {
                showingEl.innerText = renderedCount > 0 ? `1-${renderedCount}` : '0';
                totalEl.innerText = users.length;
            }

            lucide.createIcons();
        }

        function filterUsers() {
            renderUsersTable();
        }

        // Global search function
        function handleGlobalSearch(input) {
            const val = input.value.toLowerCase();
            if (document.getElementById('tab-users').classList.contains('hidden') === false) {
                // We are in users tab
                const rows = document.getElementById('users-table-body').querySelectorAll('tr');
                rows.forEach(row => {
                    const text = row.innerText.toLowerCase();
                    row.style.display = text.includes(val) ? '' : 'none';
                });
            } else if (document.getElementById('tab-revenue').classList.contains('hidden') === false) {
                const rows = document.getElementById('txn-table-body').querySelectorAll('.txn-row');
                rows.forEach(row => {
                    const text = row.getAttribute('data-search');
                    row.style.display = text.includes(val) ? '' : 'none';
                });
            }
        }

        function handleTxnSearch(input) {
            const val = input.value.toLowerCase();
            const rows = document.getElementById('txn-table-body').querySelectorAll('.txn-row');
            rows.forEach(row => {
                const text = row.getAttribute('data-search');
                row.style.display = text.includes(val) ? '' : 'none';
            });
        }

        // Open details modal
        function openUserModal(userId) {
            selectedUserId = userId;
            const user = users.find(u => u.id === userId);
            if (!user) return;

            document.getElementById('detail-user-name').innerText = user.name;
            document.getElementById('detail-user-email').innerText = user.email;
            document.getElementById('detail-user-avatar').src = user.avatar;
            document.getElementById('detail-user-bmi').innerText = user.bmi + ' (BMI)';
            document.getElementById('detail-user-hr').innerText = user.heart_rate + ' bpm';
            document.getElementById('detail-user-spo2').innerText = user.spo2 + ' %';
            document.getElementById('detail-user-blood').innerText = user.blood_type;
            
            document.getElementById('detail-user-phone').innerText = user.phone;
            document.getElementById('detail-user-dob').innerText = user.dob;
            document.getElementById('detail-user-gender').innerText = user.gender;
            document.getElementById('detail-user-height').innerText = user.height + ' cm';

            // Plan Badge
            const planEl = document.getElementById('detail-user-plan');
            planEl.innerText = user.plan;
            planEl.className = 'rounded px-2 py-0.5 text-[9px] font-black uppercase ' + 
                (user.plan === 'Premium' ? 'bg-purple-500/15 text-purple-400' : (user.plan === 'Pro' ? 'bg-blue-500/15 text-blue-400' : 'bg-slate-500/15 text-slate-400'));

            // Block toggler text & class
            const blockBtn = document.getElementById('btn-toggle-block');
            if (user.status === 'active') {
                blockBtn.innerText = 'Khóa tài khoản';
                blockBtn.className = 'h-10 px-4 rounded-xl text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 transition-all';
            } else {
                blockBtn.innerText = 'Mở khóa tài khoản';
                blockBtn.className = 'h-10 px-4 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 transition-all';
            }

            // Activity History rows
            const activityBody = document.getElementById('detail-user-activity-rows');
            activityBody.innerHTML = '';
            user.activity.forEach(act => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="py-2">${act.date}</td>
                    <td class="py-2 text-right">${act.steps.toLocaleString()} bước</td>
                    <td class="py-2 text-right">${act.sleep} giờ</td>
                `;
                activityBody.appendChild(tr);
            });

            // Trigger Modal Fly-in
            const modal = document.getElementById('userDetailModal');
            const content = document.getElementById('userModalContent');
            
            modal.classList.remove('opacity-0', 'pointer-events-none');
            modal.classList.add('opacity-100', 'pointer-events-auto');
            
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }

        function closeUserModal() {
            const modal = document.getElementById('userDetailModal');
            const content = document.getElementById('userModalContent');
            
            modal.classList.add('opacity-0', 'pointer-events-none');
            modal.classList.remove('opacity-100', 'pointer-events-auto');
            
            content.classList.add('scale-95', 'opacity-0');
            content.classList.remove('scale-100', 'opacity-100');
        }

        // Toggle Account status
        function toggleBlockUser() {
            if (!selectedUserId) return;
            
            fetch(`/admin/users/${selectedUserId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    closeUserModal();
                    location.reload();
                } else {
                    alert(data.message || 'Đã xảy ra lỗi!');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Không thể kết nối đến máy chủ!');
            });
        }

        function deleteUser() {
            if (!selectedUserId) return;
            
            const user = users.find(u => u.id === selectedUserId);
            if (!user) return;

            if (confirm(`Bạn có chắc chắn muốn xóa tài khoản ${user.name} vĩnh viễn?`)) {
                fetch(`/admin/users/${selectedUserId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        closeUserModal();
                        location.reload();
                    } else {
                        alert(data.message || 'Đã xảy ra lỗi!');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Không thể kết nối đến máy chủ!');
                });
            }
        }

        // ------------------ TAB: DOCTORS MANAGEMENT ------------------
        function renderDoctorsTable() {
            const body = document.getElementById('doctors-table-body');
            if (!body) return;
            body.innerHTML = '';

            let renderedCount = 0;

            doctors.forEach(doctor => {
                renderedCount++;
                const row = document.createElement('tr');
                row.className = "hover:bg-muted/10 transition-colors";
                
                const statusBadge = doctor.status === 'active' 
                    ? '<span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-400 bg-emerald-400/15 px-2.5 py-0.5 rounded-full">Hoạt động</span>'
                    : '<span class="inline-flex items-center gap-1 text-[10px] font-bold text-rose-400 bg-rose-400/15 px-2.5 py-0.5 rounded-full">Tạm ngưng</span>';

                row.innerHTML = `
                    <td class="p-4 pl-6 flex items-center gap-3">
                        <img src="${doctor.avatar}" alt="avatar" class="h-9 w-9 rounded-full object-cover border border-border/50">
                        <div>
                            <p class="font-bold text-foreground">${doctor.name}</p>
                        </div>
                    </td>
                    <td class="p-4">
                        <p class="font-semibold text-foreground/80">${doctor.specialty}</p>
                    </td>
                    <td class="p-4">
                        <p class="font-semibold text-foreground/80">${doctor.place}</p>
                    </td>
                    <td class="p-4 font-semibold text-foreground/80">
                        <p>${doctor.email || 'N/A'}</p>
                        <p class="text-[10px] text-muted-foreground mt-0.5">${doctor.phone || 'N/A'}</p>
                    </td>
                    <td class="p-4 text-center">${statusBadge}</td>
                    <td class="p-4 text-right pr-6">
                        <div class="inline-flex gap-2">
                            <button onclick="openEditDoctorModal(${doctor.id})" class="h-8 px-3 rounded-lg border border-border/50 text-[10px] font-black uppercase bg-background/50 hover:bg-accent text-primary">Sửa</button>
                            <button onclick="toggleBlockDoctor(${doctor.id})" class="h-8 px-3 rounded-lg border border-border/50 text-[10px] font-black uppercase bg-background/50 hover:bg-accent ${doctor.status === 'active' ? 'text-amber-500' : 'text-emerald-500'}">${doctor.status === 'active' ? 'Khóa' : 'Kích hoạt'}</button>
                            <button onclick="deleteDoctor(${doctor.id})" class="h-8 px-3 rounded-lg border border-border/50 text-[10px] font-black uppercase bg-background/50 hover:bg-accent text-rose-500">Xóa</button>
                        </div>
                    </td>
                `;
                body.appendChild(row);
            });

            // Update count text
            const showingEl = document.getElementById('doctors-count-showing');
            const totalEl = document.getElementById('doctors-count-total');
            if (showingEl) {
                showingEl.innerText = renderedCount > 0 ? `1-${renderedCount}` : '0';
            }
            if (totalEl) {
                totalEl.innerText = doctors.length;
            }

            lucide.createIcons();
        }

        function openNewDoctorModal() {
            selectedDoctorId = null;
            document.getElementById('doctor-modal-title').innerText = 'Thêm bác sĩ mới';
            document.getElementById('doc-input-name').value = '';
            document.getElementById('doc-input-specialty').value = '';
            document.getElementById('doc-input-place').value = '';
            document.getElementById('doc-input-email').value = '';
            document.getElementById('doc-input-phone').value = '';
            document.getElementById('doc-input-avatar').value = '';
            document.getElementById('doc-preview-avatar').src = 'https://ui-avatars.com/api/?name=Doctor&background=10b981&color=fff';

            triggerDoctorModal(true);
        }

        function openEditDoctorModal(docId) {
            selectedDoctorId = docId;
            const doctor = doctors.find(d => d.id === docId);
            if (!doctor) return;

            document.getElementById('doctor-modal-title').innerText = 'Chỉnh sửa bác sĩ';
            document.getElementById('doc-input-name').value = doctor.name;
            document.getElementById('doc-input-specialty').value = doctor.specialty;
            document.getElementById('doc-input-place').value = doctor.place;
            document.getElementById('doc-input-email').value = doctor.email || '';
            document.getElementById('doc-input-phone').value = doctor.phone || '';
            document.getElementById('doc-input-avatar').value = ''; // Reset file input
            document.getElementById('doc-preview-avatar').src = doctor.avatar || 'https://ui-avatars.com/api/?name='+encodeURIComponent(doctor.name)+'&background=10b981&color=fff';

            triggerDoctorModal(true);
        }

        function triggerDoctorModal(show) {
            const modal = document.getElementById('doctorModal');
            const content = document.getElementById('doctorModalContent');
            if (!modal || !content) return;
            if (show) {
                modal.classList.remove('opacity-0', 'pointer-events-none');
                modal.classList.add('opacity-100', 'pointer-events-auto');
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            } else {
                modal.classList.add('opacity-0', 'pointer-events-none');
                modal.classList.remove('opacity-100', 'pointer-events-auto');
                content.classList.add('scale-95', 'opacity-0');
                content.classList.remove('scale-100', 'opacity-100');
            }
        }

        function closeDoctorModal() {
            triggerDoctorModal(false);
        }

        function previewAdminDoctorAvatar(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('doc-preview-avatar').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function saveDoctor() {
            const name = document.getElementById('doc-input-name').value;
            const specialty = document.getElementById('doc-input-specialty').value;
            const place = document.getElementById('doc-input-place').value;
            const email = document.getElementById('doc-input-email').value;
            const phone = document.getElementById('doc-input-phone').value;
            const avatarFile = document.getElementById('doc-input-avatar').files[0];

            if (!name || !specialty || !place) {
                alert('Vui lòng nhập đầy đủ họ tên, chuyên khoa và địa điểm!');
                return;
            }

            let formData = new FormData();
            formData.append('name', name);
            formData.append('specialty', specialty);
            formData.append('place', place);
            formData.append('email', email);
            formData.append('phone', phone);
            if (avatarFile) {
                formData.append('avatar', avatarFile);
            }

            if (selectedDoctorId !== null) {
                formData.append('id', selectedDoctorId);
            }

            fetch('/admin/doctors/save', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    closeDoctorModal();
                    location.reload();
                } else {
                    alert(data.message || 'Đã xảy ra lỗi!');
                }
            })
            .catch(error => {
                console.error('Error saving doctor:', error);
                alert('Không thể kết nối đến máy chủ!');
            });
        }

        function toggleBlockDoctor(docId) {
            fetch(`/admin/doctors/${docId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message || 'Đã xảy ra lỗi!');
                }
            })
            .catch(error => {
                console.error('Error toggling status:', error);
                alert('Không thể kết nối đến máy chủ!');
            });
        }

        function deleteDoctor(docId) {
            const doctor = doctors.find(d => d.id === docId);
            if (!doctor) return;

            if (confirm(`Bạn có chắc chắn muốn xóa bác sĩ ${doctor.name} vĩnh viễn?`)) {
                fetch(`/admin/doctors/${docId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert(data.message || 'Đã xảy ra lỗi!');
                    }
                })
                .catch(error => {
                    console.error('Error deleting doctor:', error);
                    alert('Không thể kết nối đến máy chủ!');
                });
            }
        }

        // ------------------ TAB: PACKAGES MANAGEMENT ------------------
        function openNewPackageModal() {
            selectedPackageId = null;
            document.getElementById('package-modal-title').innerText = 'Thêm gói dịch vụ mới';
            document.getElementById('pkg-input-name').value = '';
            document.getElementById('pkg-input-price').value = '';
            document.getElementById('pkg-input-duration').value = '1 tháng';
            document.getElementById('pkg-input-features').value = '';

            triggerPackageModal(true);
        }

        function openEditPackageModal(pkgId) {
            selectedPackageId = pkgId;
            const pkg = packages.find(p => p.id === pkgId);
            if (!pkg) return;

            document.getElementById('package-modal-title').innerText = 'Chỉnh sửa gói dịch vụ';
            document.getElementById('pkg-input-name').value = pkg.name;
            document.getElementById('pkg-input-price').value = pkg.price;
            document.getElementById('pkg-input-duration').value = pkg.duration;
            document.getElementById('pkg-input-features').value = pkg.features.join('\n');

            triggerPackageModal(true);
        }

        function triggerPackageModal(show) {
            const modal = document.getElementById('packageModal');
            const content = document.getElementById('packageModalContent');
            if (show) {
                modal.classList.remove('opacity-0', 'pointer-events-none');
                modal.classList.add('opacity-100', 'pointer-events-auto');
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            } else {
                modal.classList.add('opacity-0', 'pointer-events-none');
                modal.classList.remove('opacity-100', 'pointer-events-auto');
                content.classList.add('scale-95', 'opacity-0');
                content.classList.remove('scale-100', 'opacity-100');
            }
        }

        function closePackageModal() {
            triggerPackageModal(false);
        }

        function savePackage() {
            const name = document.getElementById('pkg-input-name').value;
            const price = parseFloat(document.getElementById('pkg-input-price').value);
            const duration = document.getElementById('pkg-input-duration').value;
            const features = document.getElementById('pkg-input-features').value.split('\n').filter(f => f.trim() !== '');

            if (!name || isNaN(price)) {
                alert('Vui lòng nhập đầy đủ tên và giá bán hợp lệ!');
                return;
            }

            if (selectedPackageId === null) {
                // Add new package
                const newPkg = {
                    id: packages.length + 1,
                    name: name,
                    price: price,
                    duration: duration,
                    subscribers: 0,
                    status: 'active',
                    features: features,
                    color: 'from-blue-600 to-indigo-700',
                    icon: 'shield-check'
                };
                packages.push(newPkg);
                alert('Đã thêm gói dịch vụ mới thành công!');
            } else {
                // Edit package
                const pkg = packages.find(p => p.id === selectedPackageId);
                if (pkg) {
                    pkg.name = name;
                    pkg.price = price;
                    pkg.duration = duration;
                    pkg.features = features;
                    alert('Cập nhật gói dịch vụ thành công!');
                }
            }
            closePackageModal();
            location.reload(); // Reload to render Blade and update elements easily
        }

        // ------------------ TAB: FEEDBACKS MANAGEMENT ------------------
        function openFeedbackModal(fbId) {
            selectedFeedbackId = fbId;
            const fb = feedbacks.find(f => f.id === fbId);
            if (!fb) return;

            document.getElementById('fb-user-name').innerText = fb.name;
            document.getElementById('fb-user-content').innerText = fb.content;
            document.getElementById('fb-admin-reply').value = fb.reply || '';

            const modal = document.getElementById('feedbackModal');
            const content = document.getElementById('feedbackModalContent');
            
            modal.classList.remove('opacity-0', 'pointer-events-none');
            modal.classList.add('opacity-100', 'pointer-events-auto');
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }

        function closeFeedbackModal() {
            const modal = document.getElementById('feedbackModal');
            const content = document.getElementById('feedbackModalContent');
            
            modal.classList.add('opacity-0', 'pointer-events-none');
            modal.classList.remove('opacity-100', 'pointer-events-auto');
            content.classList.add('scale-95', 'opacity-0');
            content.classList.remove('scale-100', 'opacity-100');
        }

        function submitFeedbackReply() {
            const replyText = document.getElementById('fb-admin-reply').value;
            if (!replyText.trim()) {
                alert('Vui lòng nhập nội dung trả lời!');
                return;
            }

            fetch(`/admin/feedback/${selectedFeedbackId}/reply`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ reply: replyText })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Đã gửi phản hồi của Admin thành công!');
                    location.reload();
                } else {
                    alert('Đã xảy ra lỗi. Vui lòng thử lại!');
                }
            })
            .catch(error => {
                console.error('Error submitting reply:', error);
                alert('Không thể kết nối đến máy chủ!');
            });
        }

        function reactFeedbackAdmin(id, type) {
            fetch(`/feedback/${id}/react`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ type: type })
            })
            .then(response => response.json())
            .then(data => {
                if (data.likes !== undefined && data.dislikes !== undefined) {
                    const lCount = document.getElementById('like-count-admin-' + id);
                    const dCount = document.getElementById('dislike-count-admin-' + id);
                    if (lCount) lCount.innerText = data.likes;
                    if (dCount) dCount.innerText = data.dislikes;
                }
            })
            .catch(error => console.error('Error reacting to feedback:', error));
        }

        // Mock Export Function
        function mockExport(type) {
            alert(`Đang khởi chạy AI trích xuất dữ liệu...\nTệp báo cáo doanh thu dưới định dạng ${type} đang được tải xuống máy của bạn!`);
        }

        // ------------------ CHARTS ENGINE ------------------
        document.addEventListener('DOMContentLoaded', () => {
            const isDark = document.documentElement.classList.contains('dark');
            const gridColor = isDark ? 'rgba(255, 255, 255, 0.07)' : 'rgba(0, 0, 0, 0.05)';
            const textColor = isDark ? '#94a3b8' : '#475569';

            // 1. Revenue Chart
            const revCtx = document.getElementById('revenueChart').getContext('2d');
            new Chart(revCtx, {
                type: 'line',
                data: {
                    labels: @json($monthlyRevenue['labels']),
                    datasets: [{
                        label: 'Doanh thu (VNĐ)',
                        data: @json($monthlyRevenue['data']),
                        borderColor: '#6366f1',
                        borderWidth: 3.5,
                        backgroundColor: 'rgba(99, 102, 241, 0.07)',
                        fill: true,
                        tension: 0.35,
                        shadowOffsetX: 0,
                        shadowOffsetY: 4,
                        shadowBlur: 10,
                        shadowColor: 'rgba(99, 102, 241, 0.3)',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { color: textColor, font: { family: 'Inter', weight: 600, size: 10 } }
                        },
                        y: {
                            grid: { color: gridColor },
                            ticks: {
                                color: textColor,
                                font: { family: 'Inter', weight: 600, size: 10 },
                                callback: function(value) { return (value / 1000000) + 'M'; }
                            }
                        }
                    }
                }
            });

            // 2. Packages Pie Chart
            const pieCtx = document.getElementById('packagesPieChart').getContext('2d');
            new Chart(pieCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Free', 'Pro', 'Premium'],
                    datasets: [{
                        data: [84, 45, 28],
                        backgroundColor: ['#64748b', '#3b82f6', '#8b5cf6'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    cutout: '75%'
                }
            });
        });
    </script>
</body>
</html>

@extends('layouts.dashboard')

@section('title', 'HealthAI — Smart Health Dashboard')

@section('content')
    <style>
        @keyframes scan-line {
            0% { top: 0%; opacity: 0; }
            20% { opacity: 1; }
            80% { opacity: 1; }
            100% { top: 100%; opacity: 0; }
        }
        .animate-scan-line {
            animation: scan-line 3s linear infinite;
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .glass-flat {
            background: rgba(255, 255, 255, 0.45);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }
        .dark .glass-flat {
            background: rgba(15, 23, 42, 0.35);
        }
        .shadow-glow-soft {
            box-shadow: 0 10px 30px -10px rgba(var(--primary), 0.15);
        }
    </style>
<!-- Hero Banner -->
<section class="relative overflow-hidden rounded-3xl border border-border/50 p-6 md:p-8 shadow-elevated animate-fade-in-up">
    <div class="absolute inset-0 gradient-primary opacity-95"></div>
    <div class="absolute inset-0 grid-bg opacity-30"></div>
    <div class="absolute -right-20 -top-20 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
    <div class="absolute -bottom-32 -left-10 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>

    <div class="relative flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
        <div class="max-w-xl text-primary-foreground">
            <div class="inline-flex items-center gap-1.5 rounded-full border border-white/30 bg-white/15 px-3 py-1 text-[11px] font-medium backdrop-blur-md">
                <i data-lucide="sparkles" class="h-3 w-3"></i>
                HealthAI Insights · Cập nhật 2 phút trước
            </div>
            <h1 class="mt-4 text-2xl font-bold leading-tight md:text-3xl lg:text-4xl">
                Xin chào, <span class="italic">{{ Auth::user()->name ?? 'Nguyễn An' }}</span> 👋
            </h1>
            <p class="mt-2 text-sm text-white/80 md:text-base">
                Sức khoẻ hôm nay của bạn đang <span class="font-semibold text-white">rất tốt</span> — duy trì nhịp độ và đạt mục tiêu trong 2 ngày tới để mở khóa thành tựu mới.
            </p>

            <div class="mt-5 flex flex-wrap gap-2.5">
                <button class="rounded-xl bg-white px-4 py-2 text-xs font-semibold text-primary shadow-soft transition-transform hover:scale-[1.03] md:text-sm">
                    Xem báo cáo AI
                </button>
                <button class="rounded-xl border border-white/30 bg-white/10 px-4 py-2 text-xs font-semibold text-white backdrop-blur-md transition-colors hover:bg-white/20 md:text-sm">
                    Đặt mục tiêu mới
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <!-- Health Score Card -->
            @php
                $scoreClass = 'from-rose-500/40 to-rose-600/40 border-rose-500/50 shadow-rose-500/20';
                $scoreIconClass = 'bg-rose-500 text-white';
                $scoreLabelClass = 'text-rose-100';
                
                if ($whoScore >= 90) {
                    $scoreClass = 'from-emerald-500/40 to-teal-600/40 border-emerald-500/50 shadow-emerald-500/30';
                    $scoreIconClass = 'bg-emerald-500 text-white';
                    $scoreLabelClass = 'text-emerald-100';
                } elseif ($whoScore >= 75) {
                    $scoreClass = 'from-blue-500/40 to-indigo-600/40 border-blue-500/50 shadow-blue-500/20';
                    $scoreIconClass = 'bg-blue-500 text-white';
                    $scoreLabelClass = 'text-blue-100';
                } elseif ($whoScore >= 50) {
                    $scoreClass = 'from-amber-500/40 to-orange-600/40 border-amber-500/50 shadow-amber-500/20';
                    $scoreIconClass = 'bg-amber-500 text-white';
                    $scoreLabelClass = 'text-amber-100';
                }
            @endphp
            <div class="rounded-[2rem] border-2 bg-gradient-to-br p-6 backdrop-blur-xl shadow-2xl transition-all duration-500 hover:scale-[1.02] {{ $scoreClass }}">
                <div class="flex items-center gap-5">
                    <div class="h-14 w-14 rounded-2xl flex items-center justify-center shadow-lg {{ $scoreIconClass }}">
                        <i data-lucide="activity" class="h-8 w-8"></i>
                    </div>
                    <div>
                        <p class="text-xs font-black uppercase tracking-[0.2em] {{ $scoreLabelClass }}">Sức khỏe</p>
                        <p class="text-4xl font-black text-white mt-1 font-display tracking-tighter">{{ $whoScore }}<span class="text-sm opacity-60 ml-1">/100</span></p>
                    </div>
                </div>
            </div>

            <!-- Dynamic Streak Card -->
            @php
                $streakClass = 'from-white/10 to-white/5 border-white/20';
                $streakIconClass = 'bg-white/20 text-white';
                $streakLabelClass = 'text-white/60';
                
                if ($streak >= 30) {
                    $streakClass = 'from-indigo-600/50 to-purple-700/50 border-indigo-400/50 shadow-indigo-500/40';
                    $streakIconClass = 'bg-indigo-500 text-white';
                    $streakLabelClass = 'text-indigo-100';
                } elseif ($streak >= 14) {
                    $streakClass = 'from-amber-500/40 to-yellow-600/40 border-amber-400/50 shadow-amber-500/30';
                    $streakIconClass = 'bg-amber-500 text-white';
                    $streakLabelClass = 'text-amber-100';
                } elseif ($streak >= 7) {
                    $streakClass = 'from-emerald-500/40 to-teal-600/40 border-emerald-400/50';
                    $streakIconClass = 'bg-emerald-500 text-white';
                    $streakLabelClass = 'text-emerald-100';
                } elseif ($streak >= 3) {
                    $streakClass = 'from-sky-500/40 to-blue-600/40 border-sky-400/50';
                    $streakIconClass = 'bg-sky-500 text-white';
                    $streakLabelClass = 'text-sky-100';
                }
            @endphp
            <div class="rounded-[2rem] border-2 bg-gradient-to-br p-6 backdrop-blur-xl relative group/streak shadow-2xl transition-all duration-500 hover:scale-[1.02] {{ $streakClass }}">
                <div class="flex items-center gap-5">
                    <div class="h-14 w-14 rounded-2xl flex items-center justify-center shadow-lg {{ $streakIconClass }}">
                        <i data-lucide="award" class="h-8 w-8"></i>
                    </div>
                    <div>
                        <p class="text-xs font-black uppercase tracking-[0.2em] {{ $streakLabelClass }}">Kỷ luật</p>
                        <p class="text-4xl font-black text-white mt-1 font-display tracking-tighter">{{ $streak }}<span class="text-sm opacity-60 ml-1">ngày</span></p>
                    </div>
                </div>
                
                <!-- Streak Tooltip -->
                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-4 px-4 py-3 bg-black/90 backdrop-blur-xl rounded-2xl text-[10px] text-white/90 w-52 opacity-0 group-hover/streak:opacity-100 transition-all duration-300 pointer-events-none z-50 shadow-2xl border border-white/20 scale-90 group-hover/streak:scale-100">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="h-2 w-2 rounded-full bg-primary animate-pulse"></div>
                        <p class="font-black text-primary uppercase tracking-widest">Luật Chuỗi</p>
                    </div>
                    <ul class="space-y-1.5 text-white/70 font-medium">
                        <li class="flex items-start gap-2"><span>•</span> Phải cập nhật mỗi ngày.</li>
                        <li class="flex items-start gap-2"><span>•</span> Cập nhật bù sẽ mất chuỗi.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section Wrapper -->
<div x-data="dashboardOverview()" class="space-y-6">
    <!-- Stat Cards -->
    @php
        $calcDiff = function($metric, $curr, $prev) {
            $c = $curr ? floatval($curr->{$metric}) : 0;
            $p = $prev ? floatval($prev->{$metric}) : 0;
            if ($p == 0) return ['label' => 'Bình thường', 'up' => true];
            $diff = (($c - $p) / $p) * 100;
            // Đối với nhịp tim, giảm là tốt (Up), tăng là xấu (Down)
            $isGood = ($metric === 'heart_rate') ? ($diff <= 0) : ($diff >= 0);
            return [
                'label' => ($diff > 0 ? '+' : '') . round($diff) . '%',
                'up' => $isGood
            ];
        };

        // Logic tính BMI
        $latestBmi = 0;
        $bmiDiff = ['label' => 'Ổn định', 'up' => true];
        $h_cm = $user->height ?: 170;
        if ($user->weight) {
            $latestBmi = round($user->weight / (($h_cm/100)**2), 1);
            if ($previous) {
                $prevBmi = $previous->weight / (($h_cm/100)**2);
                $diffVal = $latestBmi - $prevBmi;
                $bmiDiff = ['label' => ($diffVal > 0 ? '+' : '') . round($diffVal, 1), 'up' => $diffVal <= 0];
            }
        }

        $stats = [
            ['id' => 'bmi', 'label' => 'BMI', 'value' => $latestBmi ?: '—', 'unit' => 'kg/m²', 'trend' => $bmiDiff['label'], 'trendUp' => $bmiDiff['up'], 'icon' => 'ruler', 'bg' => 'from-blue-600 to-blue-400'],
            ['id' => 'heart_rate', 'label' => 'Nhịp tim', 'value' => $user->heart_rate ?: '—', 'unit' => 'bpm', 'trend' => $calcDiff('heart_rate', $user, $previous)['label'], 'trendUp' => $calcDiff('heart_rate', $user, $previous)['up'], 'icon' => 'heart-pulse', 'bg' => 'from-rose-500 to-pink-400'],
            ['id' => 'spo2', 'label' => 'SpO₂', 'value' => $user->spo2 ?: '—', 'unit' => '%', 'trend' => $calcDiff('spo2', $user, $previous)['label'], 'trendUp' => $calcDiff('spo2', $user, $previous)['up'], 'icon' => 'wind', 'bg' => 'from-cyan-500 to-teal-400'],
            ['id' => 'calories', 'label' => 'Calories', 'value' => number_format($user->calories ?: 0), 'unit' => 'kcal', 'trend' => $calcDiff('calories', $user, $previous)['label'], 'trendUp' => $calcDiff('calories', $user, $previous)['up'], 'icon' => 'flame', 'bg' => 'from-orange-500 to-amber-400'],
            ['id' => 'weight', 'label' => 'Cân nặng', 'value' => $user->weight ?: '—', 'unit' => 'kg', 'trend' => $calcDiff('weight', $user, $previous)['label'], 'trendUp' => $calcDiff('weight', $user, $previous)['up'], 'icon' => 'scale', 'bg' => 'from-purple-600 to-purple-400'],
            ['id' => 'water_intake', 'label' => 'Nước uống', 'value' => $user->water_intake ?: '0', 'unit' => 'L / 2.5L', 'trend' => round((($user->water_intake ?: 0)/2.5)*100) . '%', 'trendUp' => true, 'icon' => 'droplets', 'bg' => 'from-teal-600 to-emerald-500'],
            ['id' => 'sleep_hours', 'label' => 'Giấc ngủ', 'value' => $user->sleep_hours ?: '—', 'unit' => 'giờ', 'trend' => $calcDiff('sleep_hours', $user, $previous)['label'], 'trendUp' => $calcDiff('sleep_hours', $user, $previous)['up'], 'icon' => 'moon', 'bg' => 'from-amber-500 to-yellow-400'],
            ['id' => 'steps', 'label' => 'Bước chân', 'value' => number_format($user->steps ?: 0), 'unit' => '/ 10,000', 'trend' => $calcDiff('steps', $user, $previous)['label'], 'trendUp' => $calcDiff('steps', $user, $previous)['up'], 'icon' => 'footprints', 'bg' => 'from-emerald-500 to-teal-400'],
        ];
    @endphp

    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4 xl:grid-cols-8 mt-6">
        @foreach($stats as $s)
        <button 
            type="button"
            @click="activeMetric = '{{ $s['id'] }}'; $nextTick(() => renderChart())"
            :class="activeMetric === '{{ $s['id'] }}' ? 'border-primary bg-primary/5 shadow-glow ring-1 ring-primary/30' : 'border-border/50'"
            class="glass group relative overflow-hidden rounded-2xl p-4 text-left shadow-soft transition-all duration-300 w-full cursor-pointer focus:outline-none block hover:scale-[1.02] border hover:shadow-glow"
        >
            <div class="flex items-start justify-between">
                <div class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br {{ $s['bg'] }} text-white shadow-soft ring-1 ring-white/30">
                    <i data-lucide="{{ $s['icon'] }}" class="h-5 w-5"></i>
                </div>
                <span class="flex items-center gap-0.5 rounded-full px-1.5 py-0.5 text-[10px] font-semibold {{ $s['trendUp'] ? 'bg-success/15 text-success' : 'bg-destructive/15 text-destructive' }}">
                    <i data-lucide="{{ $s['trendUp'] ? 'trending-up' : 'trending-down' }}" class="h-2.5 w-2.5"></i>
                    {{ $s['trend'] }}
                </span>
            </div>

            <p class="mt-3 text-[11px] font-medium uppercase tracking-wider text-muted-foreground">{{ $s['label'] }}</p>
            <div class="mt-0.5 flex items-baseline gap-1">
                <span class="text-2xl font-bold tracking-tight font-display">{{ $s['value'] }}</span>
                <span class="text-[10px] text-muted-foreground">{{ $s['unit'] }}</span>
            </div>

            <div class="pointer-events-none absolute -right-8 -top-8 h-24 w-24 rounded-full bg-primary/10 opacity-0 blur-2xl transition-opacity duration-500 group-hover:opacity-100"></div>
        </button>
        @endforeach
    </div>

    <!-- Overview Chart & AI Diagnostic Area -->
    <div class="grid gap-6 lg:grid-cols-7">
        <!-- Chart Section (Now Narrower: 4/7) -->
        <div 
            @mousemove="mouseX = $event.clientX - $el.getBoundingClientRect().left; mouseY = $event.clientY - $el.getBoundingClientRect().top"
            x-data="{ mouseX: 0, mouseY: 0 }"
            class="glass relative overflow-hidden rounded-[2.5rem] p-8 shadow-soft lg:col-span-4 min-h-[590px] flex flex-col group/chart border border-white/10 dark:border-white/5"
        >
            <!-- Glowing Backlight cursor tracker -->
            <div 
                class="absolute pointer-events-none duration-500 opacity-0 group-hover/chart:opacity-100 transition-opacity blur-[80px] rounded-full bg-primary/10 w-64 h-64 -translate-x-1/2 -translate-y-1/2"
                :style="`left: ${mouseX}px; top: ${mouseY}px;`"
            ></div>
            
            <div class="absolute inset-0 gradient-primary opacity-[0.01]"></div>
            
            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                <div>
                    <h3 class="text-[10px] font-black uppercase tracking-[0.3em] text-primary/70 mb-1">Phân tích tổng quát</h3>
                    <div class="flex items-center flex-wrap gap-2.5 mt-1">
                        <p class="text-2xl font-black text-foreground leading-none" x-text="rangeLabel"></p>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-[9px] font-black uppercase tracking-widest bg-primary/10 text-primary border border-primary/20 shadow-glow-soft">
                            <span class="h-1.5 w-1.5 rounded-full bg-primary animate-pulse"></span>
                            <span x-text="getMetricLabel()"></span>
                        </span>
                    </div>
                </div>
                
                <!-- View Toggles moved back to top -->
                <div class="inline-flex p-1 bg-muted/40 backdrop-blur-xl rounded-2xl border border-border/40 relative z-50 items-center shadow-inner">
                    <button 
                        type="button"
                        @click="activeMetric = 'all'; renderChart()"
                        :class="activeMetric === 'all' ? 'bg-gradient-to-r from-violet-600 to-indigo-600 text-white shadow-glow font-black' : 'text-muted-foreground hover:text-foreground font-semibold'"
                        class="px-4 py-2 rounded-xl text-[10px] uppercase tracking-widest transition-all duration-300 cursor-pointer pointer-events-auto mr-1.5"
                    >
                        Tổng quát
                    </button>
                    <div class="w-[1px] bg-border/40 h-4 mr-1.5"></div>
                    <template x-for="mode in ['week', 'month', 'year']">
                        <button 
                            type="button"
                            @click="changeMode(mode)"
                            :class="viewMode === mode ? 'bg-primary text-white shadow-glow font-black' : 'text-muted-foreground hover:text-foreground font-semibold'"
                            class="px-4.5 py-2 rounded-xl text-[10px] uppercase tracking-widest transition-all duration-300 cursor-pointer pointer-events-auto"
                            x-text="mode === 'week' ? 'Tuần' : (mode === 'month' ? 'Tháng' : 'Năm')"
                        ></button>
                    </template>
                </div>
            </div>

            <!-- Interactive Metric Legend -->
            <div x-show="activeMetric === 'all'" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="flex flex-wrap items-center gap-1.5 relative z-10 mb-2">
                <template x-for="m in [{id:'heart_rate',label:'Nhịp tim',c:'#f43f5e'},{id:'spo2',label:'SpO₂',c:'#06b6d4'},{id:'weight',label:'Cân nặng',c:'#a855f7'},{id:'steps',label:'Bước chân',c:'#10b981'},{id:'calories',label:'Calories',c:'#f97316'},{id:'water_intake',label:'Nước',c:'#0ea5e9'},{id:'sleep_hours',label:'Ngủ',c:'#eab308'}]">
                    <button type="button" @click="activeMetric = m.id; $nextTick(() => renderChart())" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-bold tracking-wide bg-white/5 hover:bg-white/10 border border-white/8 hover:border-white/20 transition-all duration-300 text-muted-foreground hover:text-foreground cursor-pointer backdrop-blur-sm">
                        <span class="h-2 w-2 rounded-full transition-shadow duration-300" :style="'background:' + m.c + '; box-shadow: 0 0 6px ' + m.c + '50'"></span>
                        <span x-text="m.label"></span>
                    </button>
                </template>
            </div>

            <div class="flex-1 relative z-10 min-h-[350px] w-full flex items-center justify-center">
                <canvas id="overviewChart" class="w-full h-full"></canvas>
            </div>

            <!-- Stats Row (Chỉ số động) -->
            <div class="mt-8 grid grid-cols-3 gap-4 border-t border-border/30 pt-6 relative z-10">
                <template x-for="stat in bottomStats">
                    <div class="glass-flat rounded-[1.5rem] p-4 text-center group/stat border border-border/30 hover:border-primary/20 transition-all duration-500 hover:scale-[1.02] hover:shadow-glow relative overflow-hidden flex flex-col justify-center min-h-[85px]">
                        <div class="absolute -right-4 -bottom-4 h-12 w-12 rounded-full bg-primary/5 blur-xl group-hover/stat:bg-primary/10 transition-colors"></div>
                        <p class="text-[9px] font-black uppercase tracking-[0.2em] text-muted-foreground group-hover/stat:text-primary transition-colors" x-text="stat.label"></p>
                        <p class="text-lg font-black text-foreground mt-2 font-display tracking-tight" x-text="stat.value"></p>
                    </div>
                </template>
            </div>

            <!-- Navigation Bar (Arrows) at the very bottom -->
            <div class="mt-6 flex items-center justify-center relative z-50">
                <div class="flex gap-4 p-1.5 rounded-2xl bg-muted/10 border border-border/30">
                    <button type="button" @click="navigate(-1)" class="h-9 w-12 rounded-xl bg-background/40 border border-border/50 flex items-center justify-center hover:bg-primary hover:text-white transition-all shadow-sm cursor-pointer z-50">
                        <i data-lucide="chevron-left" class="h-4 w-4"></i>
                    </button>
                    <button type="button" @click="navigate(1)" class="h-9 w-12 rounded-xl bg-background/40 border border-border/50 flex items-center justify-center hover:bg-primary hover:text-white transition-all shadow-sm cursor-pointer z-50">
                        <i data-lucide="chevron-right" class="h-4 w-4"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- AI Diagnostic Panel (Now Wider: 3/7) -->
        <div class="glass relative overflow-hidden rounded-[2.5rem] p-8 shadow-soft lg:col-span-3 flex flex-col min-h-[500px]" x-data="{ scanning: false }">
            <!-- Diagnostic Header -->
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                        <i data-lucide="brain-circuit" class="h-6 w-6"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black uppercase tracking-widest">AI Diagnostic</h3>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <span class="h-1.5 w-1.5 rounded-full bg-success animate-pulse"></span>
                            <span class="text-[9px] font-bold text-success uppercase">System Active</span>
                        </div>
                    </div>
                </div>
                <button @click="scanning = true; setTimeout(() => scanning = false, 3000)" class="h-8 px-4 rounded-lg bg-primary/10 text-primary text-[10px] font-black uppercase hover:bg-primary hover:text-white transition-all">
                    Re-Scan
                </button>
            </div>

            <!-- Diagnostic Screen -->
            <div class="flex-1 bg-muted/20 dark:bg-black/40 rounded-3xl border border-border/50 p-6 relative overflow-hidden flex flex-col">
                <!-- Scanning Animation -->
                <template x-if="scanning">
                    <div class="absolute inset-0 z-20">
                        <div class="absolute top-0 left-0 w-full h-1 bg-primary/50 shadow-[0_0_15px_rgba(var(--primary),0.5)] animate-scan-line"></div>
                        <div class="absolute inset-0 bg-primary/5 animate-pulse"></div>
                    </div>
                </template>

                <!-- Diagnostic Content -->
                <div class="relative z-10 space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="h-2 w-2 rounded-full bg-primary mt-1.5 shadow-[0_0_8px_rgba(var(--primary),1)]"></div>
                        <div>
                            <p class="text-[10px] font-black text-muted-foreground dark:text-white/40 uppercase tracking-widest mb-1">Phân tích nhịp sinh học</p>
                            <p class="text-xs text-foreground dark:text-white/80 leading-relaxed italic">"Dữ liệu bước chân tuần này tăng 12% so với trung bình, cho thấy sự cải thiện rõ rệt về sức bền tim mạch."</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="h-2 w-2 rounded-full bg-amber-500 mt-1.5 shadow-[0_0_8px_rgba(245,158,11,1)]"></div>
                        <div>
                            <p class="text-[10px] font-black text-muted-foreground dark:text-white/40 uppercase tracking-widest mb-1">Cảnh báo năng lượng</p>
                            <p class="text-xs text-foreground dark:text-white/80 leading-relaxed">Tiêu thụ Calories đang ở mức thấp so với cường độ vận động. Hãy bổ sung thêm 200-300kcal vào bữa tối.</p>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-border/50">
                        <div class="flex justify-between items-end">
                            <div>
                                <p class="text-[10px] font-black text-primary uppercase tracking-widest">Độ tin cậy AI</p>
                                <p class="text-2xl font-black text-foreground dark:text-white mt-1">98.4<span class="text-xs ml-1 opacity-40">%</span></p>
                            </div>
                            <div class="h-12 w-24">
                                <!-- Small mini sparkline visual -->
                                <div class="flex items-end gap-1 h-full">
                                    <div class="w-2 bg-primary/20 h-[40%] rounded-t-sm"></div>
                                    <div class="w-2 bg-primary/40 h-[60%] rounded-t-sm"></div>
                                    <div class="w-2 bg-primary/60 h-[80%] rounded-t-sm"></div>
                                    <div class="w-2 bg-primary h-[100%] rounded-t-sm"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom Status -->
                <div class="mt-auto pt-6 flex items-center justify-between border-t border-border/50 text-[9px] font-bold text-muted-foreground dark:text-white/30 uppercase tracking-[0.2em]">
                    <span>Status: Optimal</span>
                    <span>ID: HS-924-AI</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function dashboardOverview() {
    return {
        viewMode: 'week',
        activeMetric: 'all',
        height: (() => {
            let h = {{ $user->height ?: 170 }};
            if (h > 250) h = h / 10;
            return h;
        })(),
        referenceTimestamp: new Date().getTime(),
        chart: null,
        history: @json($history),
        
        // Dynamic values computed during getData()
        sumVal: 0,
        avgVal: 0,
        maxVal: 0,
        minVal: 0,
        stability: 92,
        waterGoalMetDays: 0,
        bestMetricLabel: '—',
        worstMetricLabel: '—',
        overallStability: 90,

        init() {
            this.$nextTick(() => {
                this.renderChart();
                if (window.lucide) lucide.createIcons();
            });
        },

        get referenceDate() {
            return new Date(this.referenceTimestamp);
        },

        get rangeLabel() {
            const d = this.referenceDate;
            if (this.viewMode === 'week') {
                const start = new Date(d);
                start.setDate(d.getDate() - (d.getDay() === 0 ? 6 : d.getDay() - 1));
                const end = new Date(start);
                end.setDate(start.getDate() + 6);
                return `${start.getDate()}/${start.getMonth()+1} - ${end.getDate()}/${end.getMonth()+1}`;
            } else if (this.viewMode === 'month') {
                const monthNames = ["Tháng 1", "Tháng 2", "Tháng 3", "Tháng 4", "Tháng 5", "Tháng 6", "Tháng 7", "Tháng 8", "Tháng 9", "Tháng 10", "Tháng 11", "Tháng 12"];
                return `${monthNames[d.getMonth()]}, ${d.getFullYear()}`;
            } else {
                return `Năm ${d.getFullYear()}`;
            }
        },

        getMetricLabel() {
            const labels = {
                all: 'Tất cả chỉ số',
                bmi: 'BMI',
                heart_rate: 'Nhịp tim',
                spo2: 'SpO₂',
                calories: 'Calories',
                weight: 'Cân nặng',
                water_intake: 'Nước uống',
                sleep_hours: 'Giấc ngủ',
                steps: 'Bước chân'
            };
            return labels[this.activeMetric] || 'Sức khỏe';
        },

        get bmiCategory() {
            const bmi = this.avgVal;
            if (!bmi) return '—';
            if (bmi < 18.5) return 'Gầy';
            if (bmi <= 24.9) return 'Bình thường';
            if (bmi <= 29.9) return 'Tiền béo phì';
            return 'Béo phì';
        },

        get bottomStats() {
            const metric = this.activeMetric;
            if (metric === 'all') {
                return [
                    { label: 'Đạt cao nhất', value: this.bestMetricLabel || '—' },
                    { label: 'Cần cải thiện', value: this.worstMetricLabel || '—' },
                    { label: 'Độ ổn định chung', value: (this.overallStability || 90) + '%' }
                ];
            }
            const stats = {
                steps: [
                    { label: 'Trung bình bước', value: Math.round(this.avgVal).toLocaleString() + ' bước' },
                    { label: 'Tổng bước chân', value: this.sumVal.toLocaleString() + ' bước' },
                    { label: 'Độ ổn định', value: this.stability + '%' }
                ],
                calories: [
                    { label: 'Trung bình nạp', value: Math.round(this.avgVal).toLocaleString() + ' kcal' },
                    { label: 'Tổng Calo nạp', value: Math.round(this.sumVal).toLocaleString() + ' kcal' },
                    { label: 'Cao nhất', value: Math.round(this.maxVal).toLocaleString() + ' kcal' }
                ],
                heart_rate: [
                    { label: 'Trung bình nhịp tim', value: Math.round(this.avgVal) + ' bpm' },
                    { label: 'Cao nhất', value: this.maxVal ? this.maxVal + ' bpm' : '—' },
                    { label: 'Thấp nhất', value: this.minVal ? this.minVal + ' bpm' : '—' }
                ],
                spo2: [
                    { label: 'Trung bình SpO₂', value: Math.round(this.avgVal) + '%' },
                    { label: 'Thấp nhất SpO₂', value: this.minVal ? this.minVal + '%' : '—' },
                    { label: 'Trạng thái', value: this.avgVal >= 95 ? 'Tốt' : 'Cần chú ý' }
                ],
                weight: [
                    { label: 'Trung bình cân nặng', value: (Math.round(this.avgVal * 10) / 10) + ' kg' },
                    { label: 'Cao nhất', value: this.maxVal ? this.maxVal + ' kg' : '—' },
                    { label: 'Thấp nhất', value: this.minVal ? this.minVal + ' kg' : '—' }
                ],
                water_intake: [
                    { label: 'Trung bình uống', value: (Math.round(this.avgVal * 10) / 10) + ' L' },
                    { label: 'Tổng lượng nước', value: (Math.round(this.sumVal * 10) / 10) + ' L' },
                    { label: 'Đạt mục tiêu', value: this.waterGoalMetDays + ' ngày' }
                ],
                sleep_hours: [
                    { label: 'Trung bình ngủ', value: (Math.round(this.avgVal * 10) / 10) + ' giờ' },
                    { label: 'Ngủ nhiều nhất', value: this.maxVal ? this.maxVal + ' giờ' : '—' },
                    { label: 'Ngủ ít nhất', value: this.minVal ? this.minVal + ' giờ' : '—' }
                ],
                bmi: [
                    { label: 'Trung bình BMI', value: (Math.round(this.avgVal * 10) / 10) },
                    { label: 'Phân loại', value: this.bmiCategory },
                    { label: 'Trạng thái', value: this.bmiCategory === 'Bình thường' ? 'Tốt' : 'Cần điều chỉnh' }
                ]
            };
            return stats[metric] || [];
        },

        getMetricValue(record, metric) {
            if (!record) {
                return null;
            }
            if (metric === 'bmi') {
                const w = parseFloat(record.weight);
                const h = parseFloat(this.height || 170);
                if (!w || !h) return null;
                return parseFloat((w / ((h / 100) ** 2)).toFixed(1));
            }
            const val = record[metric];
            if (val === undefined || val === null || val === '') {
                return null;
            }
            return parseFloat(val);
        },

        getNormalizedValue(value, metricId) {
            if (value === null || value === undefined) return null;
            const targets = {
                steps: 10000,
                calories: 2000,
                heart_rate: 75,
                spo2: 100,
                weight: 70,
                water_intake: 2.5,
                sleep_hours: 8,
                bmi: 22
            };
            
            if (metricId === 'weight') {
                const avgWeight = this.getAverageWeight();
                return (value / (avgWeight || 70)) * 100;
            }
            
            const target = targets[metricId] || 100;
            return (value / target) * 100;
        },

        getAverageWeight() {
            if (!this.history || this.history.length === 0) return 70;
            const weights = this.history.map(h => parseFloat(h.weight)).filter(w => !isNaN(w) && w > 0);
            if (weights.length === 0) return 70;
            return weights.reduce((a, b) => a + b, 0) / weights.length;
        },

        getMetricValueWithLOCF(dateStr, metricId) {
            // Check if the date is in the future
            const now = new Date();
            const todayStr = now.getFullYear() + '-' + String(now.getMonth() + 1).padStart(2, '0') + '-' + String(now.getDate()).padStart(2, '0');
            if (dateStr > todayStr) {
                return null;
            }

            // For cumulative metrics, we DO NOT carry forward. If there is no record, it's 0.
            if (['steps', 'calories', 'water_intake'].includes(metricId)) {
                const exactRecord = this.history.find(h => h.recorded_at.startsWith(dateStr));
                return this.getMetricValue(exactRecord, metricId);
            }
            
            // For non-cumulative metrics, we use LOCF / FOCB / Defaults
            let lastVal = null;
            
            // Loop through history chronologically
            for (let i = 0; i < this.history.length; i++) {
                const h = this.history[i];
                const recDate = h.recorded_at.split(' ')[0];
                if (recDate > dateStr) {
                    break;
                }
                
                // Use getMetricValue helper
                const val = this.getMetricValue(h, metricId);
                if (val !== null && val !== undefined) {
                    lastVal = val;
                }
            }
            
            if (lastVal !== null) {
                return lastVal;
            }
            
            // FOCB (First Observation Carried Backward)
            for (let i = 0; i < this.history.length; i++) {
                const h = this.history[i];
                const val = this.getMetricValue(h, metricId);
                if (val !== null && val !== undefined) {
                    return val;
                }
            }
            
            // Defaults
            const defaults = {
                heart_rate: 70,
                spo2: 98,
                weight: this.getAverageWeight() || 70,
                sleep_hours: 7,
                bmi: 22
            };
            if (metricId === 'bmi') {
                const avgWeight = this.getAverageWeight() || 70;
                const ht = parseFloat(this.height || 170);
                return parseFloat((avgWeight / ((ht / 100) ** 2)).toFixed(1));
            }
            return defaults[metricId] !== undefined ? defaults[metricId] : 0;
        },

        navigate(direction) {
            const d = new Date(this.referenceTimestamp);
            if (this.viewMode === 'week') {
                d.setDate(d.getDate() + (direction * 7));
            } else if (this.viewMode === 'month') {
                d.setMonth(d.getMonth() + direction);
            } else {
                d.setFullYear(d.getFullYear() + direction);
            }
            this.referenceTimestamp = d.getTime();
            this.$nextTick(() => this.renderChart());
        },

        changeMode(mode) {
            this.viewMode = mode;
            this.referenceTimestamp = new Date().getTime(); // Reset về hôm nay khi đổi chế độ
            this.$nextTick(() => this.renderChart());
        },

        getData() {
            const ref = this.referenceDate;
            let labels = [];
            let records = [];

            if (this.viewMode === 'week') {
                labels = ["T2", "T3", "T4", "T5", "T6", "T7", "CN"];
                const startOfWeek = new Date(ref);
                startOfWeek.setDate(ref.getDate() - (ref.getDay() === 0 ? 6 : ref.getDay() - 1));
                
                for (let i = 0; i < 7; i++) {
                    const d = new Date(startOfWeek);
                    d.setDate(startOfWeek.getDate() + i);
                    const dateStr = d.toISOString().split('T')[0];
                    records.push(dateStr);
                }
            } else if (this.viewMode === 'month') {
                labels = ["Tuần 1", "Tuần 2", "Tuần 3", "Tuần 4"];
                const year = ref.getFullYear();
                const month = ref.getMonth();
                
                for (let w = 0; w < 4; w++) {
                    const start = w * 7 + 1;
                    const end = (w === 3) ? new Date(year, month + 1, 0).getDate() : (w + 1) * 7;
                    const weekDays = [];
                    for (let day = start; day <= end; day++) {
                        const d = new Date(year, month, day);
                        weekDays.push(d.toISOString().split('T')[0]);
                    }
                    records.push(weekDays);
                }
            } else {
                labels = ["Tháng 1", "Tháng 2", "Tháng 3", "Tháng 4", "Tháng 5", "Tháng 6", "Tháng 7", "Tháng 8", "Tháng 9", "Tháng 10", "Tháng 11", "Tháng 12"];
                const year = ref.getFullYear();
                
                for (let idx = 0; idx < 12; idx++) {
                    const daysInMonth = new Date(year, idx + 1, 0).getDate();
                    const monthDays = [];
                    for (let day = 1; day <= daysInMonth; day++) {
                        const d = new Date(year, idx, day);
                        monthDays.push(d.toISOString().split('T')[0]);
                    }
                    records.push(monthDays);
                }
            }

            const metricConfigs = {
                bmi: { label: 'BMI', borderColor: '#2563eb', unit: 'kg/m²' },
                heart_rate: { label: 'Nhịp tim', borderColor: '#f43f5e', unit: 'bpm' },
                spo2: { label: 'SpO₂', borderColor: '#06b6d4', unit: '%' },
                calories: { label: 'Calories', borderColor: '#f97316', unit: 'kcal' },
                weight: { label: 'Cân nặng', borderColor: '#a855f7', unit: 'kg' },
                water_intake: { label: 'Nước uống', borderColor: '#0ea5e9', unit: 'L' },
                sleep_hours: { label: 'Giấc ngủ', borderColor: '#eab308', unit: 'giờ' },
                steps: { label: 'Bước chân', borderColor: '#10b981', unit: 'bước' }
            };

            const targets = {
                steps: 10000,
                calories: 2000,
                heart_rate: 75,
                spo2: 95,
                weight: Math.round(this.getAverageWeight()) || 70,
                water_intake: 2.5,
                sleep_hours: 8,
                bmi: 22
            };

            const datasets = [];

            if (this.activeMetric === 'all') {
                const activeMetrics = ['heart_rate', 'spo2', 'weight', 'steps', 'calories', 'water_intake', 'sleep_hours'];
                activeMetrics.forEach(metricId => {
                    const config = metricConfigs[metricId];
                    const rawValues = [];
                    
                    records.forEach(group => {
                        if (this.viewMode === 'week') {
                            const rawVal = this.getMetricValueWithLOCF(group, metricId);
                            rawValues.push(rawVal);
                        } else {
                            const dailyVals = group.map(dateStr => this.getMetricValueWithLOCF(dateStr, metricId));
                            const validVals = dailyVals.filter(v => v !== null && v !== undefined);
                            if (validVals.length === 0) {
                                rawValues.push(null);
                            } else {
                                const avgRaw = validVals.reduce((a, b) => a + b, 0) / validVals.length;
                                rawValues.push(avgRaw);
                            }
                        }
                    });

                    const normalizedValues = rawValues.map(v => this.getNormalizedValue(v, metricId));

                    datasets.push({
                        metricId: metricId,
                        label: config.label,
                        unit: config.unit,
                        data: normalizedValues,
                        rawValues: rawValues,
                        borderColor: config.borderColor,
                        borderRadius: 6,
                        borderSkipped: false,
                        borderWidth: 0,
                        barThickness: 24,
                        maxBarThickness: 32,
                        order: 1,
                        shadowOffsetX: 0,
                        shadowOffsetY: 4,
                        shadowBlur: 10,
                        shadowColor: config.borderColor + '33',
                    });
                });

                // Compute dynamic stats for bottom row
                let bestMetric = null;
                let worstMetric = null;
                let maxProgress = -1;
                let minProgress = 9999;
                
                activeMetrics.forEach(metricId => {
                    const config = metricConfigs[metricId];
                    const rawValues = [];
                    records.forEach(group => {
                        if (this.viewMode === 'week') {
                            const rawVal = this.getMetricValueWithLOCF(group, metricId);
                            rawValues.push(rawVal);
                        } else {
                            const dailyVals = group.map(dateStr => this.getMetricValueWithLOCF(dateStr, metricId));
                            const validVals = dailyVals.filter(v => v !== null && v !== undefined);
                            if (validVals.length === 0) {
                                rawValues.push(null);
                            } else {
                                const avgRaw = validVals.reduce((a, b) => a + b, 0) / validVals.length;
                                rawValues.push(avgRaw);
                            }
                        }
                    });
                    const nonNullRaw = rawValues.filter(v => v !== null && v !== undefined);
                    const avgRawForPeriod = nonNullRaw.length > 0 ? (nonNullRaw.reduce((a, b) => a + b, 0) / nonNullRaw.length) : null;
                    const normalizedAvg = this.getNormalizedValue(avgRawForPeriod, metricId);

                    if (normalizedAvg !== null) {
                        if (normalizedAvg > maxProgress) {
                            maxProgress = normalizedAvg;
                            bestMetric = config.label + ` (${Math.round(normalizedAvg)}%)`;
                        }
                        if (normalizedAvg < minProgress) {
                            minProgress = normalizedAvg;
                            worstMetric = config.label + ` (${Math.round(normalizedAvg)}%)`;
                        }
                    }
                });

                this.bestMetricLabel = bestMetric || '—';
                this.worstMetricLabel = worstMetric || '—';

                // Calculate stability based on actual active logging across any metrics
                let actualRecordCount = 0;
                if (this.viewMode === 'week') {
                    records.forEach(dateStr => {
                        const hasRec = this.history.some(h => h.recorded_at.startsWith(dateStr));
                        if (hasRec) actualRecordCount++;
                    });
                    this.overallStability = Math.round((actualRecordCount / 7) * 100);
                } else if (this.viewMode === 'month') {
                    records.forEach(weekDays => {
                        const hasRec = weekDays.some(dateStr => this.history.some(h => h.recorded_at.startsWith(dateStr)));
                        if (hasRec) actualRecordCount++;
                    });
                    this.overallStability = Math.round((actualRecordCount / 4) * 100);
                } else {
                    records.forEach(monthDays => {
                        const hasRec = monthDays.some(dateStr => this.history.some(h => h.recorded_at.startsWith(dateStr)));
                        if (hasRec) actualRecordCount++;
                    });
                    this.overallStability = Math.round((actualRecordCount / 12) * 100);
                }

                return { labels, datasets };
            }

            const config = metricConfigs[this.activeMetric];
            const rawValues = [];

            records.forEach(group => {
                if (this.viewMode === 'week') {
                    const rawVal = this.getMetricValueWithLOCF(group, this.activeMetric);
                    rawValues.push(rawVal);
                } else {
                    const dailyVals = group.map(dateStr => this.getMetricValueWithLOCF(dateStr, this.activeMetric));
                    const validVals = dailyVals.filter(v => v !== null && v !== undefined);
                    if (validVals.length === 0) {
                        rawValues.push(null);
                    } else {
                        const avgRaw = validVals.reduce((a, b) => a + b, 0) / validVals.length;
                        rawValues.push(avgRaw);
                    }
                }
            });

            // 1. Active Metric Dataset
            datasets.push({
                metricId: this.activeMetric,
                label: config.label,
                unit: config.unit,
                data: rawValues,
                rawValues: rawValues,
                borderColor: config.borderColor,
                borderRadius: 8,
                borderSkipped: false,
                borderWidth: 0,
                barThickness: 32,
                maxBarThickness: 48,
                order: 1,
                shadowOffsetX: 0,
                shadowOffsetY: 6,
                shadowBlur: 12,
                shadowColor: config.borderColor + '55',
            });

            // 2. Goal/Target reference line dataset
            const targetVal = targets[this.activeMetric];
            const targetData = Array(labels.length).fill(targetVal);
            datasets.push({
                label: 'Mục tiêu',
                data: targetData,
                borderColor: 'rgba(148, 163, 184, 0.4)',
                borderWidth: 1.5,
                borderDash: [6, 6],
                fill: false,
                pointRadius: 0,
                pointHoverRadius: 0,
                order: 2,
                type: 'line'
            });

            // Compute stats for bottom row based on raw values of active metric
            const nonNullRaw = rawValues.filter(v => v !== null && v !== undefined);
            const count = nonNullRaw.length;
            
            this.sumVal = nonNullRaw.reduce((a, b) => a + b, 0);
            this.avgVal = count > 0 ? (this.sumVal / count) : 0;
            this.maxVal = count > 0 ? Math.max(...nonNullRaw) : 0;
            this.minVal = count > 0 ? Math.min(...nonNullRaw) : 0;
            
            let actualRecordCount = 0;
            if (this.viewMode === 'week') {
                records.forEach(dateStr => {
                    const hasRec = this.history.some(h => h.recorded_at.startsWith(dateStr));
                    if (hasRec) actualRecordCount++;
                });
                this.stability = Math.round((actualRecordCount / 7) * 100);
            } else if (this.viewMode === 'month') {
                records.forEach(weekDays => {
                    const hasRec = weekDays.some(dateStr => this.history.some(h => h.recorded_at.startsWith(dateStr)));
                    if (hasRec) actualRecordCount++;
                });
                this.stability = Math.round((actualRecordCount / 4) * 100);
            } else {
                records.forEach(monthDays => {
                    const hasRec = monthDays.some(dateStr => this.history.some(h => h.recorded_at.startsWith(dateStr)));
                    if (hasRec) actualRecordCount++;
                });
                this.stability = Math.round((actualRecordCount / 12) * 100);
            }
            
            this.waterGoalMetDays = nonNullRaw.filter(v => this.activeMetric === 'water_intake' && v >= 2.5).length;

            return { labels, datasets };
        },

        renderChart() {
            const ctx = document.getElementById('overviewChart');
            if (!ctx) return;
            
            const currentActiveMetric = this.activeMetric;
            const ctx2d = ctx.getContext('2d');
            const data = this.getData();

            if (this.chart) this.chart.destroy();

            // Premium styling & gradient fills
            data.datasets.forEach(dataset => {
                if (dataset.metricId) {
                    const baseColor = dataset.borderColor;
                    const gradient = ctx2d.createLinearGradient(0, 0, 0, ctx.clientHeight || 400);
                    if (currentActiveMetric === 'all') {
                        // Futuristic neon glowing lines with soft area fill
                        gradient.addColorStop(0, baseColor + '1c'); // 11% opacity glow
                        gradient.addColorStop(1, 'transparent');
                        dataset.backgroundColor = gradient;
                        dataset.hoverBackgroundColor = baseColor;
                        dataset.fill = true;
                        dataset.borderWidth = 3;
                        dataset.pointRadius = 4;
                        dataset.pointHoverRadius = 7;
                        dataset.pointBackgroundColor = baseColor;
                        dataset.pointBorderColor = '#080c1e'; // Deep dark background of panel
                        dataset.pointBorderWidth = 2;
                        dataset.tension = 0.4;
                        dataset.shadowBlur = 8;
                        dataset.shadowColor = baseColor + '77';
                        dataset.shadowOffsetY = 3;
                    } else {
                        // Futuristic rounded bar charts with glowing gradients
                        gradient.addColorStop(0, baseColor + 'f2'); // 95% opacity
                        gradient.addColorStop(1, baseColor + '1a'); // 10% opacity
                        dataset.backgroundColor = gradient;
                        dataset.hoverBackgroundColor = baseColor;
                        dataset.fill = false;
                        dataset.borderRadius = 8;
                        dataset.borderSkipped = false;
                        dataset.borderWidth = 0;
                        dataset.shadowBlur = 12;
                        dataset.shadowColor = baseColor + '55';
                        dataset.shadowOffsetY = 5;
                    }
                } else {
                    dataset.backgroundColor = 'transparent';
                }
            });

            const activeDataset = data.datasets.find(d => d.metricId === currentActiveMetric);
            const unit = activeDataset ? activeDataset.unit : '';

            // Shadow/Glow plugin
            if (!Chart.registry.plugins.get('shadowPlugin')) {
                Chart.register({
                    id: 'shadowPlugin',
                    beforeDatasetDraw(chart, args) {
                        const ds = args.meta._dataset;
                        if (ds && ds.shadowBlur) {
                            const c = chart.ctx;
                            c.save();
                            c.shadowOffsetX = ds.shadowOffsetX || 0;
                            c.shadowOffsetY = ds.shadowOffsetY || 4;
                            c.shadowBlur = ds.shadowBlur || 0;
                            c.shadowColor = ds.shadowColor || 'transparent';
                        }
                    },
                    afterDatasetDraw(chart) { chart.ctx.restore(); }
                });
            }
            // Crosshair vertical guide plugin
            if (!Chart.registry.plugins.get('crosshairLine')) {
                Chart.register({
                    id: 'crosshairLine',
                    afterDraw(chart) {
                        if (chart.tooltip?._active?.length > 0) {
                            const c = chart.ctx;
                            const x = chart.tooltip._active[0].element.x;
                            const topY = chart.scales.y.top;
                            const bottomY = chart.scales.y.bottom;
                            c.save();
                            // Vertical dashed guide line
                            c.beginPath();
                            c.moveTo(x, topY);
                            c.lineTo(x, bottomY);
                            c.lineWidth = 1;
                            c.strokeStyle = 'rgba(99, 102, 241, 0.15)';
                            c.setLineDash([4, 4]);
                            c.stroke();
                            // Glow halos at intersection points
                            chart.tooltip._active.forEach(pt => {
                                if (pt.element.options && pt.element.options.radius) {
                                    c.beginPath();
                                    c.arc(pt.element.x, pt.element.y, 10, 0, Math.PI * 2);
                                    c.fillStyle = 'rgba(99, 102, 241, 0.06)';
                                    c.fill();
                                }
                            });
                            c.restore();
                        }
                    }
                });
            }

            this.chart = new Chart(ctx, {
                type: currentActiveMetric === 'all' ? 'line' : 'bar',
                data: {
                    labels: data.labels,
                    datasets: data.datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: { padding: { top: 10, right: 10 } },
                    interaction: {
                        mode: 'index',
                        intersect: false,
                        axis: 'x'
                    },
                    onHover: (event, activeElements, chart) => {
                        chart.canvas.style.cursor = activeElements.length ? 'pointer' : 'default';
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(8, 12, 30, 0.94)',
                            borderColor: 'rgba(99, 102, 241, 0.12)',
                            borderWidth: 1,
                            padding: { top: 14, bottom: 14, left: 18, right: 18 },
                            titleColor: '#f1f5f9',
                            titleFont: { size: 13, weight: '800', family: "'Inter', sans-serif" },
                            titleMarginBottom: 10,
                            bodyColor: '#94a3b8',
                            bodyFont: { size: 11.5, family: "'Inter', sans-serif", weight: '500' },
                            bodySpacing: 7,
                            cornerRadius: 16,
                            displayColors: true,
                            boxWidth: 8,
                            boxHeight: 8,
                            boxPadding: 6,
                            usePointStyle: true,
                            caretSize: 6,
                            caretPadding: 10,
                            callbacks: {
                                label: function(context) {
                                    const ds = context.dataset;
                                    
                                    // Target line tooltip
                                    if (!ds.metricId) {
                                        return `🎯 ${ds.label}: ${context.parsed.y.toLocaleString()} ${unit}`;
                                    }
                                    
                                    if (currentActiveMetric === 'all') {
                                        const rawVal = ds.rawValues[context.dataIndex];
                                        const normVal = context.parsed.y;
                                        if (rawVal === null || rawVal === undefined) return null;
                                        
                                        let formattedVal = rawVal;
                                        if (ds.metricId === 'steps' || ds.metricId === 'calories') {
                                            formattedVal = Math.round(rawVal).toLocaleString();
                                        } else {
                                            formattedVal = (Math.round(rawVal * 10) / 10).toLocaleString();
                                        }
                                        const unitStr = ds.unit ? ` ${ds.unit}` : '';
                                        return `📊 ${ds.label}: ${formattedVal}${unitStr} (${Math.round(normVal)}%)`;
                                    } else {
                                        const rawVal = context.parsed.y;
                                        if (rawVal === null || rawVal === undefined) return null;
                                        
                                        let formattedVal = rawVal;
                                        if (ds.metricId === 'steps' || ds.metricId === 'calories') {
                                            formattedVal = Math.round(rawVal).toLocaleString();
                                        } else {
                                            formattedVal = (Math.round(rawVal * 10) / 10).toLocaleString();
                                        }
                                        const unitStr = ds.unit ? ` ${ds.unit}` : '';
                                        return `📊 ${ds.label}: ${formattedVal}${unitStr}`;
                                    }
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            stacked: false,
                            grid: { 
                                display: true,
                                color: 'rgba(148, 163, 184, 0.05)',
                                lineWidth: 1,
                            },
                            border: { dash: [3, 3], color: 'rgba(148, 163, 184, 0.06)' },
                            ticks: { 
                                font: { size: 11, weight: '700', family: "'Inter', sans-serif" }, 
                                color: '#64748b',
                                padding: 10
                              }
                          },
                          y: {
                              stacked: false,
                              type: 'linear',
                              display: true,
                              beginAtZero: true,
                            grid: {
                                color: 'rgba(148, 163, 184, 0.05)',
                                lineWidth: 1,
                            },
                            border: { dash: [3, 3], color: 'rgba(148, 163, 184, 0.06)' },
                            ticks: { 
                                font: { size: 10, weight: '600', family: "'Inter', sans-serif" }, 
                                color: '#64748b',
                                padding: 8,
                                maxTicksLimit: 7,
                                callback: function(value) {
                                    if (currentActiveMetric === 'all') {
                                        return value + '%';
                                    }
                                    if (currentActiveMetric === 'steps' || currentActiveMetric === 'calories') {
                                        if (value >= 1000) {
                                            return (value / 1000).toFixed(value % 1000 === 0 ? 0 : 1) + 'k';
                                        }
                                    }
                                    return value + (unit ? ' ' + unit : '');
                                }
                            }
                        }
                    },
                    animation: {
                        duration: 1400,
                        easing: 'easeOutQuart',
                        delay: (context) => {
                            let delay = 0;
                            if (context.type === 'data' && context.mode === 'default') {
                                delay = context.dataIndex * 100 + context.datasetIndex * 40;
                            }
                            return delay;
                        }
                    }
                }
            });
        }
    };
}
</script>
@endsection

@extends('layouts.dashboard')

@section('title', 'HealthAI — Smart Health Dashboard')

@section('content')
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

        <div class="grid grid-cols-2 gap-3 md:gap-4">
            <div class="rounded-2xl border border-white/25 bg-white/15 p-4 backdrop-blur-md">
                <i data-lucide="trending-up" class="h-4 w-4 text-white/80"></i>
                <p class="mt-2 text-[10px] uppercase tracking-wider text-white/70">Sức khỏe</p>
                <p class="mt-1 text-2xl font-bold text-white">94<span class="ml-1 text-xs font-medium text-white/70">/100</span></p>
            </div>
            <div class="rounded-2xl border border-white/25 bg-white/15 p-4 backdrop-blur-md">
                <i data-lucide="award" class="h-4 w-4 text-white/80"></i>
                <p class="mt-2 text-[10px] uppercase tracking-wider text-white/70">Streak</p>
                <p class="mt-1 text-2xl font-bold text-white">12<span class="ml-1 text-xs font-medium text-white/70">ngày</span></p>
            </div>
        </div>
    </div>
</section>

<!-- Stat Cards -->
<div class="grid grid-cols-2 gap-4 md:grid-cols-3 xl:grid-cols-6 mt-6">
    @php
        $stats = [
            ['label' => 'BMI', 'value' => '22.4', 'unit' => 'kg/m²', 'trend' => '+0.2', 'trendUp' => true, 'icon' => 'scale', 'bg' => 'from-blue-500 to-cyan-400'],
            ['label' => 'Nhịp tim', 'value' => '78', 'unit' => 'bpm', 'trend' => 'Bình thường', 'trendUp' => true, 'icon' => 'heart-pulse', 'bg' => 'from-rose-500 to-pink-400'],
            ['label' => 'Calories', 'value' => '1,820', 'unit' => 'kcal', 'trend' => '+12%', 'trendUp' => true, 'icon' => 'flame', 'bg' => 'from-orange-500 to-amber-400'],
            ['label' => 'Nước uống', 'value' => '1.8', 'unit' => 'L / 2.5L', 'trend' => '72%', 'trendUp' => true, 'icon' => 'droplets', 'bg' => 'from-sky-500 to-blue-400'],
            ['label' => 'Giấc ngủ', 'value' => '7.2', 'unit' => 'giờ', 'trend' => 'Tốt', 'trendUp' => true, 'icon' => 'moon', 'bg' => 'from-indigo-500 to-violet-400'],
            ['label' => 'Bước chân', 'value' => '8,432', 'unit' => '/ 10,000', 'trend' => '+8%', 'trendUp' => true, 'icon' => 'footprints', 'bg' => 'from-emerald-500 to-teal-400'],
        ];
    @endphp

    @foreach($stats as $s)
    <div class="glass group relative overflow-hidden rounded-2xl p-4 shadow-soft transition-all hover:shadow-glow">
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
    </div>
    @endforeach
</div>

<!-- Placeholder for Charts and AI Chatbot to be implemented or loaded via JS -->
<div class="mt-6 grid gap-6 lg:grid-cols-3">
    <div class="glass rounded-2xl p-5 shadow-soft lg:col-span-2 flex flex-col justify-center items-center text-center min-h-[300px]">
        <i data-lucide="line-chart" class="h-12 w-12 text-primary/50 mb-3"></i>
        <h3 class="text-base font-semibold">Biểu đồ tổng quan</h3>
        <p class="text-xs text-muted-foreground mt-1">Dữ liệu đang được đồng bộ...</p>
    </div>
    <div class="glass rounded-2xl p-5 shadow-soft flex flex-col justify-center items-center text-center min-h-[300px]">
        <i data-lucide="bot" class="h-12 w-12 text-primary/50 mb-3"></i>
        <h3 class="text-base font-semibold">AI Assistant</h3>
        <p class="text-xs text-muted-foreground mt-1">Sẵn sàng phân tích sức khỏe của bạn.</p>
    </div>
</div>
@endsection

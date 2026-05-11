@extends('layouts.dashboard')

@section('title', 'Luyện tập AI — HealthAI')

@section('content')
<!-- Page Header -->
<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <div class="flex items-center gap-4">
        <div class="flex h-12 w-12 items-center justify-center rounded-2xl gradient-primary text-primary-foreground shadow-glow">
            <i data-lucide="dumbbell" class="h-5 w-5"></i>
        </div>
        <div>
            <h1 class="text-2xl font-bold tracking-tight md:text-3xl font-display">Luyện tập AI</h1>
            <p class="mt-1 text-sm text-muted-foreground">Lịch tập được AI cá nhân hóa theo mục tiêu của bạn</p>
        </div>
    </div>
</div>

<!-- Stats -->
<div class="grid gap-4 md:grid-cols-3 mb-6">
    @php
        $stats = [
            ['icon' => 'flame', 'label' => 'Calories tuần', 'value' => '2,140', 'color' => 'from-orange-500 to-amber-400'],
            ['icon' => 'clock', 'label' => 'Tổng thời gian', 'value' => '4h 35m', 'color' => 'from-blue-500 to-cyan-400'],
            ['icon' => 'trophy', 'label' => 'Streak', 'value' => '12 ngày', 'color' => 'from-violet-500 to-purple-400'],
        ];
    @endphp
    @foreach($stats as $s)
    <div class="glass flex items-center gap-4 rounded-2xl p-5 shadow-soft">
        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br {{ $s['color'] }} text-white shadow-soft">
            <i data-lucide="{{ $s['icon'] }}" class="h-5 w-5"></i>
        </div>
        <div>
            <p class="text-[11px] uppercase tracking-wider text-muted-foreground">{{ $s['label'] }}</p>
            <p class="text-2xl font-bold font-display">{{ $s['value'] }}</p>
        </div>
    </div>
    @endforeach
</div>

<!-- Weekly Schedule -->
<div class="glass rounded-2xl p-5 shadow-soft mb-6">
    <div class="flex items-center justify-between">
        <h3 class="text-base font-semibold">Lịch tập tuần này</h3>
        <span class="flex items-center gap-1 rounded-full bg-success/15 px-2.5 py-1 text-[11px] font-semibold text-success">
            <i data-lucide="trending-up" class="h-3 w-3"></i> 4/7 ngày
        </span>
    </div>
    <div class="mt-4 grid grid-cols-7 gap-2">
        @php
            $week = ["T2", "T3", "T4", "T5", "T6", "T7", "CN"];
            $done = [true, true, false, true, true, false, false];
        @endphp
        @foreach($week as $i => $d)
        <div class="flex aspect-square flex-col items-center justify-center rounded-xl border {{ $done[$i] ? 'gradient-primary border-transparent text-primary-foreground shadow-soft' : 'border-border bg-card/40 text-muted-foreground' }}">
            <span class="text-[10px] font-medium uppercase">{{ $d }}</span>
            <span class="mt-1 text-lg font-bold">{{ $done[$i] ? "✓" : "—" }}</span>
        </div>
        @endforeach
    </div>
</div>

<!-- Workouts -->
<div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
    @php
        $workouts = [
            ['name' => 'HIIT Cardio Blast', 'duration' => '20 phút', 'kcal' => 280, 'level' => 'Trung bình', 'img' => 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?w=600&q=80', 'color' => 'from-rose-500 to-orange-400'],
            ['name' => 'Yoga buổi sáng', 'duration' => '30 phút', 'kcal' => 150, 'level' => 'Dễ', 'img' => 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=600&q=80', 'color' => 'from-violet-500 to-purple-400'],
            ['name' => 'Tăng cơ toàn thân', 'duration' => '45 phút', 'kcal' => 380, 'level' => 'Khó', 'img' => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=600&q=80', 'color' => 'from-blue-500 to-cyan-400'],
            ['name' => 'Core & Plank', 'duration' => '15 phút', 'kcal' => 120, 'level' => 'Trung bình', 'img' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=600&q=80', 'color' => 'from-emerald-500 to-teal-400'],
        ];
    @endphp

    @foreach($workouts as $w)
    <div class="glass group overflow-hidden rounded-2xl shadow-soft transition-shadow hover:shadow-glow">
        <div class="relative h-44 overflow-hidden">
            <img src="{{ $w['img'] }}" alt="{{ $w['name'] }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110" />
            <div class="absolute inset-0 bg-gradient-to-t {{ $w['color'] }} opacity-30"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
            <button class="absolute right-3 top-3 flex h-10 w-10 items-center justify-center rounded-full bg-white/95 text-primary shadow-glow transition-transform hover:scale-110">
                <i data-lucide="play" class="ml-0.5 h-4 w-4 fill-current"></i>
            </button>
            <div class="absolute bottom-3 left-3 right-3">
                <span class="rounded-full bg-white/20 px-2 py-0.5 text-[10px] font-bold uppercase text-white backdrop-blur">
                    {{ $w['level'] }}
                </span>
                <h3 class="mt-2 text-base font-bold text-white">{{ $w['name'] }}</h3>
            </div>
        </div>
        <div class="flex items-center justify-between p-4 text-xs">
            <span class="flex items-center gap-1 text-muted-foreground">
                <i data-lucide="clock" class="h-3.5 w-3.5"></i> {{ $w['duration'] }}
            </span>
            <span class="flex items-center gap-1 font-semibold text-orange-500">
                <i data-lucide="flame" class="h-3.5 w-3.5"></i> {{ $w['kcal'] }} kcal
            </span>
        </div>
    </div>
    @endforeach
</div>
@endsection

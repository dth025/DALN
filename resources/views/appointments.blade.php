@extends('layouts.dashboard')

@section('title', 'Lịch khám — HealthAI')

@section('content')
<!-- Page Header -->
<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <div class="flex items-center gap-4">
        <div class="flex h-12 w-12 items-center justify-center rounded-2xl gradient-primary text-primary-foreground shadow-glow">
            <i data-lucide="calendar-days" class="h-5 w-5"></i>
        </div>
        <div>
            <h1 class="text-2xl font-bold tracking-tight md:text-3xl font-display">Lịch khám</h1>
            <p class="mt-1 text-sm text-muted-foreground">Quản lý các cuộc hẹn y tế của bạn</p>
        </div>
    </div>
    <button class="flex items-center gap-2 rounded-xl gradient-primary px-4 py-2.5 text-sm font-semibold text-primary-foreground shadow-glow hover:scale-[1.02] transition-transform">
        <i data-lucide="plus" class="h-4 w-4"></i> Đặt lịch khám
    </button>
</div>

<!-- Calendar Mini -->
<div class="glass rounded-2xl p-5 shadow-soft mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-base font-semibold">Tháng 5, 2026</h3>
            <p class="text-xs text-muted-foreground">Tuần 19 · 3 cuộc hẹn</p>
        </div>
        <div class="flex gap-1">
            <button class="flex h-8 w-8 items-center justify-center rounded-lg border border-border hover:bg-accent">
                <i data-lucide="chevron-left" class="h-4 w-4"></i>
            </button>
            <button class="flex h-8 w-8 items-center justify-center rounded-lg border border-border hover:bg-accent">
                <i data-lucide="chevron-right" class="h-4 w-4"></i>
            </button>
        </div>
    </div>

    <div class="mt-5 grid grid-cols-7 gap-2">
        @php
            $days = ["CN", "T2", "T3", "T4", "T5", "T6", "T7"];
            $dates = [4, 5, 6, 7, 8, 9, 10];
            $events = [false, true, false, false, true, false, true];
        @endphp
        @foreach($days as $i => $d)
        <div class="text-center text-[10px] font-semibold uppercase text-muted-foreground">
            <p>{{ $d }}</p>
            <button class="mt-2 flex aspect-square w-full flex-col items-center justify-center rounded-xl border transition-all hover:scale-105 active:scale-95 {{ $i === 4 ? 'gradient-primary border-transparent text-primary-foreground shadow-glow' : 'border-border bg-card/40 text-foreground hover:border-primary/40' }}">
                <span class="text-base font-bold">{{ $dates[$i] }}</span>
                @if($events[$i])
                <span class="mt-0.5 h-1 w-1 rounded-full {{ $i === 4 ? 'bg-white' : 'bg-primary' }}"></span>
                @endif
            </button>
        </div>
        @endforeach
    </div>
</div>

<!-- Upcoming Appointments -->
<div class="space-y-3">
    <h3 class="text-sm font-semibold uppercase tracking-wider text-muted-foreground">Cuộc hẹn sắp tới</h3>
    
    @php
        $upcoming = [
            ['doctor' => 'BS. Nguyễn Văn Minh', 'spec' => 'Tim mạch', 'date' => '12 Th5, 2026', 'time' => '09:00 - 09:30', 'type' => 'Trực tiếp', 'place' => 'Vinmec Times City', 'img' => 'https://i.pravatar.cc/100?img=68', 'color' => 'from-rose-500 to-pink-400'],
            ['doctor' => 'BS. Trần Thị Hoa', 'spec' => 'Dinh dưỡng', 'date' => '15 Th5, 2026', 'time' => '14:00 - 14:45', 'type' => 'Online', 'place' => 'Tư vấn video', 'img' => 'https://i.pravatar.cc/100?img=47', 'color' => 'from-emerald-500 to-teal-400'],
            ['doctor' => 'BS. Lê Hoàng Nam', 'spec' => 'Da liễu', 'date' => '20 Th5, 2026', 'time' => '10:30 - 11:00', 'type' => 'Trực tiếp', 'place' => 'Bệnh viện Bạch Mai', 'img' => 'https://i.pravatar.cc/100?img=12', 'color' => 'from-blue-500 to-cyan-400'],
        ];
    @endphp

    @foreach($upcoming as $a)
    <div class="glass flex flex-col gap-4 rounded-2xl p-4 shadow-soft md:flex-row md:items-center transition-transform hover:translate-x-1">
        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br {{ $a['color'] }} text-white shadow-soft">
            <i data-lucide="stethoscope" class="h-6 w-6"></i>
        </div>

        <div class="flex flex-1 items-center gap-3">
            <img src="{{ $a['img'] }}" alt="{{ $a['doctor'] }}" class="h-12 w-12 rounded-xl object-cover ring-2 ring-border" />
            <div class="min-w-0 flex-1">
                <h4 class="text-sm font-semibold">{{ $a['doctor'] }}</h4>
                <p class="text-xs text-muted-foreground">{{ $a['spec'] }}</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3 text-xs md:flex md:items-center md:gap-5">
            <div class="flex items-center gap-1.5 text-muted-foreground">
                <i data-lucide="calendar-days" class="h-3.5 w-3.5"></i> {{ $a['date'] }}
            </div>
            <div class="flex items-center gap-1.5 text-muted-foreground">
                <i data-lucide="clock" class="h-3.5 w-3.5"></i> {{ $a['time'] }}
            </div>
            <div class="flex items-center gap-1.5 text-muted-foreground">
                <i data-lucide="{{ $a['type'] === 'Online' ? 'video' : 'map-pin' }}" class="h-3.5 w-3.5"></i>
                {{ $a['place'] }}
            </div>
        </div>

        <div class="flex gap-2">
            <button class="rounded-lg border border-border px-3 py-1.5 text-xs font-medium hover:bg-accent">
                Đổi lịch
            </button>
            <button class="rounded-lg gradient-primary px-3 py-1.5 text-xs font-semibold text-primary-foreground shadow-soft">
                Tham gia
            </button>
        </div>
    </div>
    @endforeach
</div>
@endsection

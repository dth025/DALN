@extends('layouts.dashboard')

@section('title', 'Thực đơn AI — HealthAI')

@section('content')
<!-- Page Header -->
<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <div class="flex items-center gap-4">
        <div class="flex h-12 w-12 items-center justify-center rounded-2xl gradient-primary text-primary-foreground shadow-glow">
            <i data-lucide="salad" class="h-5 w-5"></i>
        </div>
        <div>
            <h1 class="text-2xl font-bold tracking-tight md:text-3xl font-display">Thực đơn AI</h1>
            <p class="mt-1 text-sm text-muted-foreground">Kế hoạch dinh dưỡng được cá nhân hóa cho hôm nay</p>
        </div>
    </div>
    <button class="flex items-center gap-2 rounded-xl gradient-primary px-4 py-2.5 text-sm font-semibold text-primary-foreground shadow-glow hover:scale-[1.02] transition-transform">
        <i data-lucide="refresh-cw" class="h-4 w-4"></i> Tạo thực đơn mới
    </button>
</div>

<!-- Macros -->
<div class="grid grid-cols-2 gap-4 lg:grid-cols-4 mb-6">
    @php
        $macros = [
            ['label' => 'Calories', 'value' => '1,820', 'target' => '2,000', 'color' => 'from-orange-500 to-amber-400', 'width' => '91%'],
            ['label' => 'Protein', 'value' => '120g', 'target' => '140g', 'color' => 'from-rose-500 to-pink-400', 'width' => '85%'],
            ['label' => 'Carbs', 'value' => '210g', 'target' => '250g', 'color' => 'from-blue-500 to-cyan-400', 'width' => '84%'],
            ['label' => 'Fat', 'value' => '55g', 'target' => '65g', 'color' => 'from-emerald-500 to-teal-400', 'width' => '84%'],
        ];
    @endphp

    @foreach($macros as $m)
    <div class="glass rounded-2xl p-4 shadow-soft">
        <p class="text-[11px] uppercase tracking-wider text-muted-foreground">{{ $m['label'] }}</p>
        <p class="mt-1 text-2xl font-bold font-display">{{ $m['value'] }}</p>
        <p class="text-[10px] text-muted-foreground">/ {{ $m['target'] }}</p>
        <div class="mt-2 h-1.5 overflow-hidden rounded-full bg-muted">
            <div class="h-full bg-gradient-to-r {{ $m['color'] }}" style="width: {{ $m['width'] }};"></div>
        </div>
    </div>
    @endforeach
</div>

<!-- Meals -->
<div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4 mb-6">
    @php
        $meals = [
            ['title' => 'Bữa sáng', 'time' => '07:00', 'kcal' => 420, 'name' => 'Yến mạch trái cây & hạt chia', 'img' => 'https://images.unsplash.com/photo-1517673400267-0251440c45dc?w=600&q=80', 'tags' => ['Giàu chất xơ', 'Vegan']],
            ['title' => 'Bữa trưa', 'time' => '12:30', 'kcal' => 680, 'name' => 'Cơm gạo lứt ức gà nướng & rau củ', 'img' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=600&q=80', 'tags' => ['Cao đạm', 'Low-carb']],
            ['title' => 'Bữa tối', 'time' => '18:30', 'kcal' => 520, 'name' => 'Salad cá hồi avocado', 'img' => 'https://images.unsplash.com/photo-1467003909585-2f8a72700288?w=600&q=80', 'tags' => ['Omega-3', 'Ít calo']],
            ['title' => 'Bữa phụ', 'time' => '21:00', 'kcal' => 200, 'name' => 'Sữa chua Hy Lạp & việt quất', 'img' => 'https://images.unsplash.com/photo-1488477181946-6428a0291777?w=600&q=80', 'tags' => ['Probiotic']],
        ];
    @endphp

    @foreach($meals as $m)
    <div class="glass group overflow-hidden rounded-2xl shadow-soft transition-shadow hover:shadow-glow">
        <div class="relative h-40 overflow-hidden">
            <img src="{{ $m['img'] }}" alt="{{ $m['name'] }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110" />
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
            <div class="absolute left-3 top-3 inline-flex items-center gap-1 rounded-full bg-white/90 px-2 py-1 text-[10px] font-semibold text-foreground backdrop-blur">
                <i data-lucide="clock" class="h-3 w-3"></i> {{ $m['time'] }}
            </div>
            <div class="absolute right-3 top-3 inline-flex items-center gap-1 rounded-full bg-orange-500/90 px-2 py-1 text-[10px] font-bold text-white">
                <i data-lucide="flame" class="h-3 w-3"></i> {{ $m['kcal'] }} kcal
            </div>
            <p class="absolute bottom-3 left-3 text-xs font-bold uppercase tracking-wider text-white">
                {{ $m['title'] }}
            </p>
        </div>
        <div class="p-4">
            <h3 class="text-sm font-semibold">{{ $m['name'] }}</h3>
            <div class="mt-2 flex flex-wrap gap-1">
                @foreach($m['tags'] as $t)
                <span class="rounded-full bg-primary/10 px-2 py-0.5 text-[10px] font-medium text-primary">
                    {{ $t }}
                </span>
                @endforeach
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- AI Insight -->
<div class="glass relative overflow-hidden rounded-2xl p-5 shadow-soft">
    <i data-lucide="sparkles" class="absolute -right-4 -top-4 h-24 w-24 text-primary/10"></i>
    <div class="flex items-start gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl gradient-primary text-primary-foreground shadow-glow">
            <i data-lucide="sparkles" class="h-5 w-5"></i>
        </div>
        <div>
            <h3 class="text-sm font-semibold">AI Insight</h3>
            <p class="mt-1 text-xs text-muted-foreground">
                Bạn đang thiếu khoảng <b class="text-foreground">20g protein</b> so với mục tiêu. Hãy thêm 1 quả trứng luộc hoặc 100g ức gà vào bữa phụ chiều nay.
            </p>
        </div>
    </div>
</div>
@endsection

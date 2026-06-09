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
            <p class="mt-1 text-sm text-muted-foreground">Kế hoạch dinh dưỡng được cá nhân hóa và có đề xuất từ bác sĩ</p>
        </div>
    </div>
    <a href="{{ route('menu') }}" class="flex items-center gap-2 rounded-xl gradient-primary px-4 py-2.5 text-sm font-semibold text-primary-foreground shadow-glow hover:scale-[1.02] transition-transform">
        <i data-lucide="refresh-cw" class="h-4 w-4"></i> Tạo thực đơn mới
    </a>
</div>

<!-- Macros -->
<div class="grid grid-cols-2 gap-4 lg:grid-cols-4 mb-6">
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

<!-- AI & Doctor Diet Suggestions -->
<div class="flex items-center justify-between mb-4 gap-3">
    <div>
        <h2 class="text-lg font-bold">Gợi ý chế độ ăn tự động</h2>
        <p class="text-sm text-muted-foreground">Thực đơn sẽ cập nhật liên tục khi dữ liệu sức khỏe của bạn thay đổi.</p>
    </div>
    <div class="text-right text-[11px] text-muted-foreground">
        Cập nhật lần cuối: <span id="menu-last-updated">{{ now()->format('H:i:s') }}</span>
    </div>
</div>

<div id="diet-suggestion-blocks" class="grid gap-5 lg:grid-cols-2 mb-6">
    <div id="ai-plan" class="glass rounded-3xl p-6 shadow-soft border border-white/10 bg-gradient-to-br from-slate-950/80 to-slate-900/70">
        <div class="flex items-center gap-3 mb-4">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-primary/10 text-primary shadow-glow">
                <i data-lucide="cpu" class="h-6 w-6"></i>
            </div>
            <div>
                <h3 class="text-base font-semibold" id="ai-plan-title">{{ $aiSuggestedPlan['title'] }}</h3>
                <p class="text-xs text-muted-foreground">Dựa trên chỉ số cơ thể và mục tiêu sức khỏe của bạn.</p>
            </div>
        </div>
        <p id="ai-plan-description" class="text-sm leading-7 text-foreground/85">{{ $aiSuggestedPlan['description'] }}</p>
        <div id="ai-plan-meals" class="mt-6 space-y-3">
            @foreach($aiSuggestedPlan['meals'] as $meal)
            <div class="rounded-3xl border border-white/10 bg-slate-950/70 p-4 shadow-soft-sm">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-[11px] uppercase tracking-widest text-muted-foreground">{{ $meal['label'] }}</p>
                        <p class="mt-1 font-semibold text-foreground">{{ $meal['name'] }}</p>
                    </div>
                    <span class="rounded-full bg-primary/10 px-3 py-1 text-[10px] font-bold text-primary">{{ $meal['kcal'] }} kcal</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div id="doctor-plan" class="glass rounded-3xl p-6 shadow-soft border border-white/10 bg-gradient-to-br from-slate-950/80 to-slate-900/70">
        <div class="flex items-center gap-3 mb-4">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-500/10 text-emerald-400 shadow-glow">
                <i data-lucide="stethoscope" class="h-6 w-6"></i>
            </div>
            <div>
                <h3 class="text-base font-semibold">Chế độ ăn do bác sĩ đề xuất</h3>
                <p class="text-xs text-muted-foreground">Lời khuyên dinh dưỡng từ chuyên gia sức khỏe.</p>
            </div>
        </div>
        @if(isset($selectedDoctor) && $selectedDoctor)
            <div class="mb-4 rounded-3xl border border-emerald-500/20 bg-emerald-500/10 p-4 text-sm text-emerald-700">
                <strong>Bác sĩ đã chọn:</strong> {{ $selectedDoctor->name }} · {{ $selectedDoctor->specialty }}
            </div>
        @endif
        <p id="doctor-plan-description" class="text-sm leading-7 text-foreground/85">{{ $doctorRecommendedPlan['advice'] }}</p>
        <div id="doctor-plan-meals" class="mt-6 space-y-3">
            @foreach($doctorRecommendedPlan['meals'] as $meal)
            <div class="rounded-3xl border border-white/10 bg-slate-950/70 p-4 shadow-soft-sm">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-[11px] uppercase tracking-widest text-muted-foreground">{{ $meal['label'] }}</p>
                        <p class="mt-1 font-semibold text-foreground">{{ $meal['name'] }}</p>
                    </div>
                    <span class="rounded-full bg-emerald-500/10 px-3 py-1 text-[10px] font-bold text-emerald-400">{{ $meal['kcal'] }} kcal</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Choose a Doctor for Recommendation -->
<div class="glass rounded-3xl p-6 shadow-soft mb-6">
    <div class="flex items-center justify-between gap-3 mb-4">
        <div>
            <h2 class="text-base font-semibold">Chọn bác sĩ đề xuất thực đơn</h2>
            <p class="text-sm text-muted-foreground">Chọn bác sĩ phù hợp để xem gợi ý chuyên môn.</p>
        </div>
        @if(isset($selectedDoctor) && $selectedDoctor)
            <a href="{{ route('menu') }}" class="rounded-full border border-emerald-500/30 px-4 py-2 text-xs font-semibold text-emerald-500 hover:bg-emerald-500/10">Bỏ chọn</a>
        @endif
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @foreach($doctors as $doctor)
            <div class="rounded-3xl border border-border bg-card p-4 shadow-soft">
                <div class="flex items-center gap-3">
                    <img src="{{ $doctor->avatar }}" alt="{{ $doctor->name }}" class="h-12 w-12 rounded-2xl object-cover ring-2 ring-border" />
                    <div class="min-w-0">
                        <h4 class="text-sm font-semibold truncate">{{ $doctor->name }}</h4>
                        <p class="text-xs text-muted-foreground truncate">{{ $doctor->specialty }}</p>
                    </div>
                </div>
                <div class="mt-4 text-xs text-muted-foreground space-y-2">
                    <div class="flex items-center gap-2">
                        <i data-lucide="map-pin" class="h-3.5 w-3.5"></i>
                        <span>{{ $doctor->place }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i data-lucide="phone" class="h-3.5 w-3.5"></i>
                        <span>{{ $doctor->phone }}</span>
                    </div>
                </div>
                <div class="mt-4 flex items-center justify-between gap-2">
                    <span class="rounded-full bg-emerald-500/10 px-3 py-1 text-[11px] font-semibold text-emerald-400">{{ ucfirst($doctor->status) }}</span>
                    <a href="{{ route('menu', ['selected_doctor' => $doctor->id]) }}" class="rounded-xl border border-border px-3 py-2 text-xs font-medium hover:bg-accent">Chọn bác sĩ</a>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Meals -->
<div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4 mb-6">
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

<script>
    function renderMealItems(container, meals, badgeClass) {
        container.innerHTML = meals.map(function(meal) {
            return `
                <div class="rounded-3xl border border-white/10 bg-slate-950/70 p-4 shadow-soft-sm">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-[11px] uppercase tracking-widest text-muted-foreground">${meal.label}</p>
                            <p class="mt-1 font-semibold text-foreground">${meal.name}</p>
                        </div>
                        <span class="rounded-full ${badgeClass} px-3 py-1 text-[10px] font-bold">${meal.kcal} kcal</span>
                    </div>
                </div>
            `;
        }).join('');
    }

    async function updateMenuPlans() {
        try {
            var response = await fetch('{{ route('menu.ai.data') }}');
            if (!response.ok) return;
            var data = await response.json();

            document.getElementById('ai-plan-title').textContent = data.aiSuggestedPlan.title;
            document.getElementById('ai-plan-description').textContent = data.aiSuggestedPlan.description;
            document.getElementById('doctor-plan-description').textContent = data.doctorRecommendedPlan.advice;
            document.getElementById('menu-last-updated').textContent = data.updated_at;

            renderMealItems(document.getElementById('ai-plan-meals'), data.aiSuggestedPlan.meals, 'bg-primary/10 text-primary');
            renderMealItems(document.getElementById('doctor-plan-meals'), data.doctorRecommendedPlan.meals, 'bg-emerald-500/10 text-emerald-400');
        } catch (error) {
            console.error('Không thể cập nhật thực đơn AI:', error);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateMenuPlans();
        setInterval(updateMenuPlans, 15000);
    });
</script>
@endsection

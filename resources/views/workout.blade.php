@extends('layouts.dashboard')

@section('title', 'Luyện tập AI — HealthAI')

@section('content')
<div x-data="workoutApp()" class="space-y-6">
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
        <button @click="openCustomLogModal()" class="px-5 py-2.5 rounded-xl gradient-primary text-white font-bold text-xs uppercase tracking-widest shadow-glow hover:scale-[1.02] transition-all flex items-center gap-2">
            <i data-lucide="plus" class="h-4 w-4"></i> Ghi nhận bài tập
        </button>
    </div>

    <!-- Stats -->
    <div class="grid gap-4 md:grid-cols-3 mb-6">
        <div class="glass flex items-center gap-4 rounded-2xl p-5 shadow-soft border border-white/10">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-orange-500 to-amber-400 text-white shadow-soft">
                <i data-lucide="flame" class="h-5 w-5"></i>
            </div>
            <div>
                <p class="text-[11px] uppercase tracking-wider text-muted-foreground">Calories tuần</p>
                <p class="text-2xl font-bold font-display">{{ number_format($weeklyCalories) }} kcal</p>
            </div>
        </div>
        <div class="glass flex items-center gap-4 rounded-2xl p-5 shadow-soft border border-white/10">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-cyan-400 text-white shadow-soft">
                <i data-lucide="clock" class="h-5 w-5"></i>
            </div>
            <div>
                <p class="text-[11px] uppercase tracking-wider text-muted-foreground">Tổng thời gian</p>
                <p class="text-2xl font-bold font-display">{{ $weeklyDurationStr }}</p>
            </div>
        </div>
        <div class="glass flex items-center gap-4 rounded-2xl p-5 shadow-soft border border-white/10">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-violet-500 to-purple-400 text-white shadow-soft">
                <i data-lucide="trophy" class="h-5 w-5"></i>
            </div>
            <div>
                <p class="text-[11px] uppercase tracking-wider text-muted-foreground">Streak tập luyện</p>
                <p class="text-2xl font-bold font-display">{{ $streak }} ngày</p>
            </div>
        </div>
    </div>

    <!-- AI Advice & Recommended Workouts -->
    <div class="glass rounded-[2rem] p-6 md:p-8 shadow-soft border border-primary/20 mb-6 bg-gradient-to-br from-primary/5 to-transparent relative overflow-hidden group">
        <div class="absolute -right-24 -top-24 w-48 h-48 bg-primary/10 blur-[80px] rounded-full pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-1.5 rounded-full border border-primary/30 bg-primary/10 px-3 py-1 text-[11px] font-black uppercase tracking-widest text-primary mb-4">
                    <i data-lucide="sparkles" class="h-3 w-3 animate-pulse"></i>
                    HealthAI Gợi Ý Cá Nhân Hoá
                </div>
                <p class="text-sm md:text-base leading-relaxed text-foreground/90 font-medium italic">
                    "{{ $aiAnalysis }}"
                </p>
            </div>
            
            <div class="flex items-center gap-4 shrink-0">
                <div class="h-16 w-16 rounded-2xl bg-primary/10 flex items-center justify-center text-primary shadow-glow-soft">
                    <i data-lucide="brain-circuit" class="h-10 w-10"></i>
                </div>
            </div>
        </div>

        <!-- Recommended Workout Cards -->
        <div class="mt-8 grid gap-5 sm:grid-cols-2 relative z-10">
            @foreach($recommendedWorkouts as $w)
            <div class="glass rounded-2xl p-4 border border-border/50 flex items-center justify-between gap-4 hover:border-primary/30 transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="relative h-16 w-16 rounded-xl overflow-hidden shrink-0">
                        <img src="{{ $w['img'] }}" class="h-full w-full object-cover" />
                        <div class="absolute inset-0 bg-gradient-to-t {{ $w['color'] }} opacity-25"></div>
                    </div>
                    <div>
                        <span class="inline-block rounded-full bg-primary/10 px-2 py-0.5 text-[9px] font-bold uppercase text-primary mb-1">
                            {{ $w['level'] }} · {{ $w['type'] }}
                        </span>
                        <h4 class="text-sm font-bold text-foreground">{{ $w['name'] }}</h4>
                        <p class="text-[11px] text-muted-foreground mt-0.5">{{ $w['duration'] }} · {{ $w['kcal'] }} kcal</p>
                    </div>
                </div>
                <button @click="startWorkout({
                    name: '{{ $w['name'] }}',
                    duration: {{ intval($w['duration']) }},
                    kcal: {{ $w['kcal'] }}
                })" class="px-4 py-2 rounded-xl bg-primary text-white text-[10px] font-black uppercase tracking-widest shadow-glow hover:scale-105 transition-transform flex items-center gap-1.5 shrink-0">
                    <i data-lucide="play" class="h-3 w-3 fill-current"></i> Tập
                </button>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Weekly Schedule -->
    <div class="glass rounded-2xl p-5 shadow-soft mb-6 border border-white/10">
        <div class="flex items-center justify-between">
            <h3 class="text-base font-semibold">Lịch tập tuần này</h3>
            <span class="flex items-center gap-1 rounded-full bg-success/15 px-2.5 py-1 text-[11px] font-semibold text-success">
                <i data-lucide="trending-up" class="h-3 w-3"></i> {{ $doneDaysCount }}/7 ngày
            </span>
        </div>
        <div class="mt-4 grid grid-cols-7 gap-2">
            @foreach($weeklySchedule as $s)
            <div class="flex aspect-square flex-col items-center justify-center rounded-xl border {{ $s['done'] ? 'gradient-primary border-transparent text-primary-foreground shadow-soft' : 'border-border bg-card/40 text-muted-foreground' }}">
                <span class="text-[10px] font-medium uppercase">{{ $s['label'] }}</span>
                <span class="mt-1 text-lg font-bold">{{ $s['done'] ? "✓" : "—" }}</span>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Library Workouts -->
    <div class="mb-6">
        <h3 class="text-lg font-bold mb-4 font-display">Thư viện bài tập</h3>
        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
            @foreach($allPresetWorkouts as $w)
            <div class="glass group overflow-hidden rounded-2xl shadow-soft border border-white/10 transition-shadow hover:shadow-glow">
                <div class="relative h-44 overflow-hidden">
                    <img src="{{ $w['img'] }}" alt="{{ $w['name'] }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110" />
                    <div class="absolute inset-0 bg-gradient-to-t {{ $w['color'] }} opacity-30"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                    <button @click="startWorkout({
                        name: '{{ $w['name'] }}',
                        duration: {{ intval($w['duration']) }},
                        kcal: {{ $w['kcal'] }}
                    })" class="absolute right-3 top-3 flex h-10 w-10 items-center justify-center rounded-full bg-white/95 text-primary shadow-glow transition-transform hover:scale-110 z-20">
                        <i data-lucide="play" class="ml-0.5 h-4 w-4 fill-current"></i>
                    </button>
                    <div class="absolute bottom-3 left-3 right-3">
                        <span class="rounded-full bg-white/20 px-2 py-0.5 text-[10px] font-bold uppercase text-white backdrop-blur">
                            {{ $w['level'] }} · {{ $w['type'] }}
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
    </div>

    <!-- Workout Player Modal (Full screen overlay) -->
    <div x-cloak x-show="isPlaying" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-md p-4">
        <div x-cloak x-show="isPlaying" x-transition.scale class="glass border border-white/20 w-full max-w-lg rounded-[2.5rem] p-8 text-center flex flex-col items-center shadow-2xl relative overflow-hidden bg-gray-950/80">
            <!-- Glowing Backlight -->
            <div class="absolute top-12 left-1/2 -translate-x-1/2 w-64 h-64 bg-primary/20 blur-[100px] rounded-full pointer-events-none"></div>

            <!-- Header -->
            <div class="relative z-10 w-full flex items-center justify-between mb-8">
                <span class="text-[10px] font-black uppercase tracking-widest text-primary bg-primary/10 border border-primary/20 px-3 py-1 rounded-full">
                    Đang tập luyện
                </span>
                <button @click="finishWorkout(false)" class="text-muted-foreground hover:text-white transition-colors">
                    <i data-lucide="x" class="h-6 w-6"></i>
                </button>
            </div>

            <!-- Exercise Details -->
            <div class="relative z-10 space-y-2 mb-8">
                <h2 class="text-2xl font-black text-white font-display" x-text="activeWorkout.name"></h2>
                <p class="text-sm text-muted-foreground">Giữ nhịp thở đều đặn và duy trì động lực</p>
            </div>

            <!-- Breathing Pulse Circle (Visual) -->
            <div class="relative z-10 flex items-center justify-center h-48 w-48 rounded-full border-2 border-primary/20 mb-8">
                <!-- Pulsing halos -->
                <div class="absolute inset-2 rounded-full bg-primary/5 animate-ping" :style="isPaused ? 'animation-play-state: paused' : ''"></div>
                <div class="absolute inset-8 rounded-full bg-primary/10" :class="!isPaused ? 'animate-pulse' : ''"></div>
                
                <!-- Timer Counter -->
                <div class="relative flex flex-col items-center">
                    <span class="text-4xl font-black font-display text-white tracking-tight" x-text="formatTime(timeLeft)"></span>
                    <span class="text-[9px] font-bold text-muted-foreground uppercase tracking-widest mt-1">Còn lại</span>
                </div>
            </div>

            <!-- Realtime Burning Stats -->
            <div class="relative z-10 grid grid-cols-2 gap-6 w-full border-t border-b border-white/10 py-6 mb-8">
                <div class="text-center border-r border-white/10">
                    <p class="text-[10px] font-black uppercase tracking-widest text-muted-foreground mb-1">Calo tiêu hao</p>
                    <div class="flex items-baseline justify-center gap-1">
                        <span class="text-3xl font-black text-orange-500 font-display" x-text="caloriesBurnedSoFar"></span>
                        <span class="text-[10px] font-bold text-muted-foreground uppercase">kcal</span>
                    </div>
                </div>
                <div class="text-center">
                    <p class="text-[10px] font-black uppercase tracking-widest text-muted-foreground mb-1">Thời gian tập</p>
                    <div class="flex items-baseline justify-center gap-1">
                        <span class="text-3xl font-black text-blue-400 font-display" x-text="formatTime(totalDuration - timeLeft)"></span>
                        <span class="text-[10px] font-bold text-muted-foreground uppercase">đã tập</span>
                    </div>
                </div>
            </div>

            <!-- Controls -->
            <div class="relative z-10 flex items-center gap-4">
                <!-- Play/Pause -->
                <button @click="togglePause()" class="h-14 w-14 rounded-full bg-white/10 border border-white/20 text-white flex items-center justify-center hover:bg-white/20 transition-all cursor-pointer">
                    <i :data-lucide="isPaused ? 'play' : 'pause'" class="h-6 w-6"></i>
                </button>
                
                <!-- Stop/Complete -->
                <button @click="finishWorkout(true)" class="px-8 h-14 rounded-full gradient-primary text-white font-black uppercase tracking-widest text-xs shadow-glow hover:scale-105 transition-transform flex items-center gap-2 cursor-pointer">
                    <i data-lucide="check-check" class="h-4 w-4"></i> Hoàn thành
                </button>
            </div>
        </div>
    </div>

    <!-- Custom Workout Log Modal -->
    <div x-cloak x-show="showCustomLogModal" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
        <div x-cloak x-show="showCustomLogModal" x-transition.scale class="glass border border-white/20 w-full max-w-md rounded-[2rem] p-6 shadow-2xl relative bg-card">
            <div class="flex items-center justify-between mb-6">
                <h4 class="text-lg font-black uppercase tracking-tight text-foreground">Ghi nhận bài tập</h4>
                <button @click="showCustomLogModal = false" class="text-muted-foreground hover:text-foreground">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
            </div>

            <form @submit.prevent="submitCustomLog()" class="space-y-4">
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-wider text-muted-foreground mb-1.5">Tên bài tập / Thể loại</label>
                    <input type="text" x-model="customType" placeholder="Ví dụ: HIIT, Chạy bộ, Đạp xe..." class="w-full bg-background border border-border focus:border-primary/40 p-3.5 rounded-xl font-bold text-sm outline-none transition-all text-foreground" required>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-wider text-muted-foreground mb-1.5">Thời gian (phút)</label>
                        <input type="number" x-model="customDuration" min="1" class="w-full bg-background border border-border focus:border-primary/40 p-3.5 rounded-xl font-bold text-sm outline-none transition-all text-center text-foreground" required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-wider text-muted-foreground mb-1.5">Kcal đốt cháy</label>
                        <input type="number" x-model="customCalories" min="0" class="w-full bg-background border border-border focus:border-primary/40 p-3.5 rounded-xl font-bold text-sm outline-none transition-all text-center text-foreground" required>
                    </div>
                </div>

                <button type="submit" class="w-full mt-4 py-4 rounded-xl gradient-primary text-white font-black uppercase tracking-widest text-xs shadow-glow transition-all">
                    Lưu bài tập
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function workoutApp() {
    return {
        isPlaying: false,
        activeWorkout: { name: '', duration: 0, kcal: 0 },
        timeLeft: 0,
        totalDuration: 0,
        timer: null,
        isPaused: false,
        caloriesBurnedSoFar: 0,

        showCustomLogModal: false,
        customType: '',
        customDuration: 20,
        customCalories: 150,

        init() {
            this.$nextTick(() => {
                if (window.lucide) lucide.createIcons();
            });
        },

        startWorkout(workout) {
            this.activeWorkout = workout;
            this.totalDuration = workout.duration * 60; // in seconds
            this.timeLeft = this.totalDuration;
            this.isPaused = false;
            this.caloriesBurnedSoFar = 0;
            this.isPlaying = true;

            if (this.timer) clearInterval(this.timer);

            this.$nextTick(() => {
                if (window.lucide) lucide.createIcons();
            });

            this.timer = setInterval(() => {
                if (!this.isPaused) {
                    this.timeLeft--;
                    const elapsed = this.totalDuration - this.timeLeft;
                    this.caloriesBurnedSoFar = Math.round((workout.kcal / this.totalDuration) * elapsed);

                    if (this.timeLeft <= 0) {
                        this.finishWorkout(true);
                    }
                }
            }, 1000);
        },

        togglePause() {
            this.isPaused = !this.isPaused;
            this.$nextTick(() => {
                if (window.lucide) lucide.createIcons();
            });
        },

        finishWorkout(shouldSave = true) {
            clearInterval(this.timer);
            this.isPlaying = false;

            if (shouldSave) {
                const durationMinutes = Math.max(1, Math.round((this.totalDuration - this.timeLeft) / 60));
                const caloriesBurned = this.caloriesBurnedSoFar;
                
                this.saveWorkoutToDb(this.activeWorkout.name, durationMinutes, caloriesBurned);
            }
        },

        openCustomLogModal() {
            this.customType = '';
            this.customDuration = 20;
            this.customCalories = 150;
            this.showCustomLogModal = true;
            this.$nextTick(() => {
                if (window.lucide) lucide.createIcons();
            });
        },

        submitCustomLog() {
            this.showCustomLogModal = false;
            this.saveWorkoutToDb(this.customType, this.customDuration, this.customCalories);
        },

        saveWorkoutToDb(type, duration, calories) {
            fetch('{{ route("workout.log") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    type: type,
                    duration_minutes: duration,
                    calories_burned: calories
                })
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message || 'Lưu bài tập thành công!');
                window.location.reload();
            })
            .catch(err => {
                console.error(err);
                alert('Có lỗi xảy ra khi lưu bài tập.');
            });
        },

        formatTime(seconds) {
            const m = Math.floor(seconds / 60).toString().padStart(2, '0');
            const s = (seconds % 60).toString().padStart(2, '0');
            return `${m}:${s}`;
        }
    };
}
</script>
@endsection

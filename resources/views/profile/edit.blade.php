@extends('layouts.dashboard')

@section('title', 'Hồ sơ cá nhân — HealthAI')

@section('content')
<div x-data="{ 
    editModal: false, 
    passwordModal: false, 
    saving: false,
    async saveField() {
        this.saving = true;
        const form = document.getElementById('auto-save-form');
        const formData = new FormData(form);
        
        try {
            const response = await fetch('{{ route('profile.update') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            if (response.ok) {
                // Success - maybe show a small toast
            }
        } catch (e) {
            console.error('Auto-save failed', e);
        } finally {
            this.saving = false;
        }
    },
    clearAllData() {
        if(confirm('Bạn có chắc chắn muốn xóa toàn bộ dữ liệu sức khỏe cũ để bắt đầu lại?')) {
            const fields = ['height', 'weight', 'blood_type', 'health_goals', 'address', 'job'];
            fields.forEach(f => {
                const el = document.getElementsByName(f)[0];
                if(el) el.value = '';
                // Handle radio buttons
                if(f === 'blood_type') {
                   document.querySelectorAll('input[name=blood_type]').forEach(r => r.checked = false);
                }
            });
            this.saveField();
        }
    }
}" class="max-w-7xl mx-auto space-y-6 pb-12 animate-in fade-in duration-700">
    
    <!-- Saving Indicator -->
    <div x-show="saving" x-transition class="fixed bottom-8 left-8 z-50 bg-foreground text-background px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-widest flex items-center gap-2 shadow-2xl">
        <span class="h-2 w-2 rounded-full bg-primary animate-pulse"></span>
        Đang tự động lưu...
    </div>

    <!-- 1. HERO PROFILE CARD -->
    <div class="relative overflow-hidden rounded-[2.5rem] bg-card border border-border shadow-soft group">
        <div class="absolute inset-x-0 top-0 h-48 gradient-primary opacity-90"></div>
        <div class="absolute inset-0 grid-bg opacity-20" style="mask-image: linear-gradient(to bottom, black, transparent 40%)"></div>
        
        <div class="relative px-6 pb-8 pt-20 md:px-10 md:pb-10 md:pt-24">
            <div class="flex flex-col gap-8 md:flex-row md:items-end">
                <!-- Avatar Section -->
                <div class="relative shrink-0 mx-auto md:mx-0">
                    <div class="relative group/avatar">
                        <img id="avatar-preview" src="{{ Auth::user()->avatar_url }}" alt="avatar" class="h-32 w-32 md:h-40 md:w-40 rounded-full object-cover ring-8 ring-card shadow-glow transition-transform duration-500 group-hover/avatar:scale-105">
                        
                        <button onclick="document.getElementById('avatar-input').click()" class="absolute -bottom-2 -right-2 flex h-10 w-10 items-center justify-center rounded-2xl gradient-primary text-white shadow-lg transition-transform hover:scale-110 active:scale-95 z-20">
                            <i data-lucide="camera" class="h-5 w-5"></i>
                        </button>
                        
                        <form id="avatar-form" action="{{ route('profile.avatar.update') }}" method="POST" enctype="multipart/form-data" class="hidden">
                            @csrf
                            <input type="file" id="avatar-input" name="avatar" accept="image/*" onchange="document.getElementById('avatar-form').submit()">
                        </form>

                        <span class="absolute -top-1 left-1 flex h-5 w-5 z-20">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-success opacity-75"></span>
                            <span class="relative inline-flex h-5 w-5 rounded-full bg-success ring-2 ring-card"></span>
                        </span>
                    </div>
                </div>

                <!-- User Info -->
                <div class="flex-1 space-y-4 text-center md:text-left">
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-3">
                        <h2 class="text-3xl md:text-5xl font-black tracking-tighter text-foreground">{{ Auth::user()->name }}</h2>
                        <span class="flex items-center gap-1 rounded-full gradient-primary px-3 py-1 text-[10px] font-black text-white shadow-sm uppercase tracking-widest">
                            <i data-lucide="sparkles" class="h-3 w-3"></i> PREMIUM
                        </span>
                    </div>
                    <p class="text-sm md:text-base text-muted-foreground font-medium max-w-2xl mx-auto md:mx-0">
                        {{ Auth::user()->health_goals ?? 'Bắt đầu hành trình sức khỏe của bạn ngay hôm nay.' }}
                    </p>

                    <div class="grid grid-cols-3 gap-3 md:max-w-md pt-2 mx-auto md:mx-0">
                        <div class="rounded-2xl border border-border bg-emerald-500/5 p-3 text-center backdrop-blur-sm transition-all hover:scale-105 shadow-sm">
                            <p class="text-xl font-black text-emerald-500 leading-none mb-1">156</p>
                            <p class="text-[8px] font-black uppercase tracking-widest text-muted-foreground/70">ngày tập</p>
                        </div>
                        <div class="rounded-2xl border border-border bg-sky-500/5 p-3 text-center backdrop-blur-sm transition-all hover:scale-105 shadow-sm">
                            <p class="text-xl font-black text-sky-500 leading-none mb-1">2,340</p>
                            <p class="text-[8px] font-black uppercase tracking-widest text-muted-foreground/70">km đã đi</p>
                        </div>
                        <div class="rounded-2xl border border-border bg-primary/5 p-3 text-center backdrop-blur-sm transition-all hover:scale-105 shadow-sm">
                            <p class="text-xl font-black text-primary leading-none mb-1">94</p>
                            <p class="text-[8px] font-black uppercase tracking-widest text-muted-foreground/70">điểm SK</p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-center md:justify-end gap-3 pb-2 w-full md:w-auto">
                    <button @click="editModal = true" class="flex items-center gap-2 rounded-xl border border-border bg-card px-5 py-3 text-xs font-black uppercase tracking-widest transition-all hover:bg-accent active:scale-95 shadow-sm">
                        <i data-lucide="edit-3" class="h-4 w-4"></i> Chỉnh sửa
                    </button>
                    <button @click="passwordModal = true" class="flex items-center gap-2 rounded-xl border border-border bg-card px-5 py-3 text-xs font-black uppercase tracking-widest transition-all hover:bg-accent active:scale-95 shadow-sm">
                        <i data-lucide="lock" class="h-4 w-4"></i> Bảo mật
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. BODY METRICS & GOALS -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @php
                    $metrics = [
                        ['l' => 'Chiều cao', 'v' => Auth::user()->height ?? '--', 'u' => 'cm'],
                        ['l' => 'Cân nặng', 'v' => Auth::user()->weight ?? '--', 'u' => 'kg'],
                        ['l' => 'BMI', 'v' => '22.2', 'u' => ''],
                        ['l' => 'Nhóm máu', 'v' => Auth::user()->blood_type ?? '--', 'u' => ''],
                    ];
                @endphp
                @foreach($metrics as $m)
                    <div class="bg-card border border-border rounded-2xl p-5 shadow-sm">
                        <p class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">{{ $m['l'] }}</p>
                        <div class="mt-3 flex items-baseline gap-1">
                            <p class="text-2xl font-black tracking-tight text-foreground">{{ $m['v'] }}</p>
                            <span class="text-xs font-bold text-muted-foreground/60">{{ $m['u'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="bg-card border border-border rounded-3xl p-8 shadow-sm">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-sm font-black uppercase tracking-widest flex items-center gap-3 text-primary">
                        <i data-lucide="target" class="h-5 w-5"></i> Mục tiêu sức khỏe
                    </h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @php
                        $goals = [
                            ['i' => 'footprints', 'l' => 'Bước chân', 'c' => 8420, 't' => 10000, 'u' => 'bước', 'cl' => 'from-sky-500 to-blue-600'],
                            ['i' => 'flame', 'l' => 'Calo', 'c' => 480, 't' => 600, 'u' => 'kcal', 'cl' => 'from-orange-500 to-rose-600'],
                        ];
                    @endphp
                    @foreach($goals as $g)
                        <div class="bg-muted/10 border border-border p-5 rounded-2xl">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-xl bg-gradient-to-br {{ $g['cl'] }} flex items-center justify-center text-white">
                                        <i data-lucide="{{ $g['i'] }}" class="h-5 w-5"></i>
                                    </div>
                                    <span class="text-xs font-black uppercase">{{ $g['l'] }}</span>
                                </div>
                                <span class="text-xs font-black text-primary">{{ round(($g['c']/$g['t'])*100) }}%</span>
                            </div>
                            <div class="h-2 w-full bg-muted rounded-full overflow-hidden mb-2">
                                <div class="h-full bg-gradient-to-r {{ $g['cl'] }}" style="width: {{ ($g['c']/$g['t'])*100 }}%"></div>
                            </div>
                            <p class="text-[10px] font-bold text-muted-foreground"><span class="text-foreground">{{ number_format($g['c']) }}</span> / {{ number_format($g['t']) }} {{ $g['u'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- SIDEBAR -->
        <div class="space-y-6">
            <div class="bg-card border border-border rounded-3xl p-6 shadow-sm">
                <h3 class="text-xs font-black uppercase tracking-widest mb-6">Thành tựu</h3>
                <div class="grid grid-cols-3 gap-3">
                    @foreach(['flame', 'award', 'trophy', 'heart', 'star', 'zap'] as $icon)
                        <div class="aspect-square rounded-2xl bg-muted/20 flex items-center justify-center hover:bg-primary/10 transition-colors cursor-help">
                            <i data-lucide="{{ $icon }}" class="h-6 w-6 text-muted-foreground/50"></i>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <div class="bg-card border border-border rounded-[2.5rem] p-8 text-center space-y-4">
                <i data-lucide="bot" class="h-10 w-10 text-primary mx-auto animate-bounce"></i>
                <h4 class="text-xs font-black uppercase tracking-widest text-primary">Lời khuyên AI</h4>
                <p class="text-xs font-bold leading-relaxed italic opacity-70">"Chào {{ explode(' ', Auth::user()->name)[0] }}! Hãy uống thêm 500ml nước ngay lúc này để cơ thể luôn tràn đầy năng lượng nhé."</p>
            </div>
        </div>
    </div>

    <!-- EDIT MODAL (Auto-Save Enabled) -->
    <div x-show="editModal" 
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         style="display: none;">
        
        <div class="bg-card border border-border w-full max-w-2xl rounded-[3rem] shadow-2xl overflow-hidden max-h-[90vh] flex flex-col"
             @click.away="editModal = false">
            
            <div class="p-8 border-b border-border flex items-center justify-between bg-muted/20">
                <div class="flex items-center gap-4">
                    <div class="h-12 w-12 rounded-2xl gradient-primary flex items-center justify-center text-white shadow-lg">
                        <i data-lucide="user-cog" class="h-6 w-6"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black uppercase tracking-tighter">Thiết lập hồ sơ</h3>
                        <p class="text-[10px] font-black text-primary uppercase tracking-widest">Tự động đồng bộ hóa</p>
                    </div>
                </div>
                <button @click="editModal = false" class="h-12 w-12 rounded-2xl hover:bg-muted transition-colors flex items-center justify-center">
                    <i data-lucide="x" class="h-6 w-6"></i>
                </button>
            </div>

            <form id="auto-save-form" class="p-8 space-y-8 overflow-y-auto" @change="saveField()">
                @csrf
                @method('patch')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-3">
                        <label class="text-[10px] font-black uppercase tracking-[0.2em] text-muted-foreground ml-1">Họ và tên</label>
                        <input name="name" type="text" value="{{ old('name', Auth::user()->name) }}" class="w-full bg-muted/30 border-b-2 border-transparent focus:border-primary p-4 rounded-2xl font-bold text-sm outline-none transition-all">
                    </div>
                    <div class="space-y-3">
                        <label class="text-[10px] font-black uppercase tracking-[0.2em] text-muted-foreground ml-1">Số điện thoại</label>
                        <input name="phone" type="text" value="{{ old('phone', Auth::user()->phone) }}" class="w-full bg-muted/30 border-b-2 border-transparent focus:border-primary p-4 rounded-2xl font-bold text-sm outline-none transition-all">
                    </div>
                    <div class="space-y-3">
                        <label class="text-[10px] font-black uppercase tracking-[0.2em] text-muted-foreground ml-1">Ngày sinh</label>
                        <input name="dob" type="date" value="{{ old('dob', Auth::user()->dob) }}" class="w-full bg-muted/30 border-b-2 border-transparent focus:border-primary p-4 rounded-2xl font-bold text-sm outline-none transition-all">
                    </div>
                    <div class="space-y-3">
                        <label class="text-[10px] font-black uppercase tracking-[0.2em] text-muted-foreground ml-1">Giới tính</label>
                        <div class="flex p-1 bg-muted rounded-xl border border-border">
                            @foreach(['Nam', 'Nữ', 'Khác'] as $g)
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" name="gender" value="{{ $g }}" class="peer hidden" {{ Auth::user()->gender == $g ? 'checked' : '' }}>
                                    <div class="py-3 text-center rounded-lg text-[10px] font-black transition-all peer-checked:bg-white dark:peer-checked:bg-sidebar peer-checked:text-primary peer-checked:shadow-sm">
                                        {{ $g }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="space-y-3">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground ml-1">Chiều cao</label>
                        <input name="height" type="number" value="{{ Auth::user()->height }}" class="w-full bg-muted/30 p-4 rounded-2xl font-black text-lg text-primary text-center outline-none">
                    </div>
                    <div class="space-y-3">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground ml-1">Cân nặng</label>
                        <input name="weight" type="number" value="{{ Auth::user()->weight }}" class="w-full bg-muted/30 p-4 rounded-2xl font-black text-lg text-primary text-center outline-none">
                    </div>
                    <div class="col-span-2 space-y-3">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground ml-1">Nhóm máu</label>
                        <div class="grid grid-cols-4 gap-1.5">
                            @foreach(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $bt)
                                <label class="cursor-pointer">
                                    <input type="radio" name="blood_type" value="{{ $bt }}" class="peer hidden" {{ Auth::user()->blood_type == $bt ? 'checked' : '' }}>
                                    <div class="py-2 text-center rounded-xl border border-border text-[9px] font-black transition-all peer-checked:bg-rose-500 peer-checked:text-white peer-checked:border-rose-500">
                                        {{ $bt }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground ml-1">Địa chỉ liên hệ</label>
                    <input name="address" type="text" value="{{ Auth::user()->address }}" class="w-full bg-muted/30 border-b-2 border-transparent focus:border-primary p-4 rounded-2xl font-bold text-sm outline-none transition-all">
                </div>

                <div class="space-y-3">
                    <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground ml-1">Mục tiêu sức khỏe</label>
                    <textarea name="health_goals" rows="3" class="w-full bg-muted/30 border-b-2 border-transparent focus:border-primary p-5 rounded-[2rem] font-bold text-sm outline-none transition-all">{{ Auth::user()->health_goals }}</textarea>
                </div>

                <div class="pt-8 flex flex-col gap-4">
                    <div class="p-4 bg-primary/5 rounded-2xl border border-primary/10 flex items-center gap-3">
                        <i data-lucide="info" class="h-5 w-5 text-primary"></i>
                        <p class="text-[10px] font-bold text-primary italic">Dữ liệu của bạn được tự động lưu ngay khi có thay đổi.</p>
                    </div>
                    <button type="button" @click="clearAllData()" class="w-full py-4 rounded-2xl border border-destructive/20 text-destructive font-black uppercase tracking-widest text-[10px] hover:bg-destructive hover:text-white transition-all">
                        Xóa toàn bộ dữ liệu sức khỏe cũ (Làm mới)
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- PASSWORD MODAL -->
    <div x-show="passwordModal" 
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
         style="display: none;">
        <div class="bg-card border border-border w-full max-w-md rounded-[3rem] shadow-2xl overflow-hidden" @click.away="passwordModal = false">
            <div class="p-8 border-b border-border flex items-center justify-between">
                <h3 class="text-xl font-black uppercase tracking-widest">Đổi mật khẩu</h3>
                <button @click="passwordModal = false" class="h-10 w-10 rounded-xl hover:bg-muted flex items-center justify-center"><i data-lucide="x" class="h-6 w-6"></i></button>
            </div>
            <form action="{{ route('password.update') }}" method="POST" class="p-8 space-y-6">
                @csrf @method('put')
                <div class="space-y-4">
                    <input name="current_password" type="password" placeholder="Mật khẩu cũ" class="w-full rounded-2xl border border-border bg-muted/30 p-4 font-bold text-sm outline-none">
                    <input name="password" type="password" placeholder="Mật khẩu mới" class="w-full rounded-2xl border border-border bg-muted/30 p-4 font-bold text-sm outline-none">
                    <input name="password_confirmation" type="password" placeholder="Xác nhận mật khẩu" class="w-full rounded-2xl border border-border bg-muted/30 p-4 font-bold text-sm outline-none">
                </div>
                <button type="submit" class="w-full py-5 rounded-2xl gradient-primary text-white font-black uppercase tracking-widest text-xs shadow-glow transition-all">Cập nhật ngay</button>
            </form>
        </div>
    </div>
</div>
@endsection

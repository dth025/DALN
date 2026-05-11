<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold tracking-tight font-display text-foreground">Bắt đầu ngay hôm nay!</h2>
        <p class="mt-2 text-sm text-muted-foreground">Tạo tài khoản HealthAI để theo dõi sức khỏe thông minh</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Name -->
        <div class="space-y-2">
            <label for="name" class="text-xs font-bold uppercase tracking-widest text-muted-foreground ml-1">Họ và tên</label>
            <div class="relative group">
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-muted-foreground group-focus-within:text-primary transition-colors">
                    <i data-lucide="user" class="h-4 w-4"></i>
                </div>
                <input id="name" 
                       class="block w-full rounded-2xl border border-border bg-card/40 py-3 pl-11 pr-4 text-sm outline-none transition-all focus:border-primary/50 focus:ring-4 focus:ring-primary/10" 
                       type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" 
                       placeholder="Nguyễn Văn A" />
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-1 ml-1" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <!-- Email -->
            <div class="space-y-2">
                <label for="email" class="text-xs font-bold uppercase tracking-widest text-muted-foreground ml-1">Email</label>
                <div class="relative group">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-muted-foreground group-focus-within:text-primary transition-colors">
                        <i data-lucide="mail" class="h-4 w-4"></i>
                    </div>
                    <input id="email" 
                           class="block w-full rounded-2xl border border-border bg-card/40 py-3 pl-11 pr-4 text-sm outline-none transition-all focus:border-primary/50 focus:ring-4 focus:ring-primary/10" 
                           type="email" name="email" value="{{ old('email') }}" required autocomplete="username" 
                           placeholder="email@example.com" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-1 ml-1" />
            </div>

            <!-- Phone -->
            <div class="space-y-2">
                <label for="phone" class="text-xs font-bold uppercase tracking-widest text-muted-foreground ml-1">Số điện thoại</label>
                <div class="relative group">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-muted-foreground group-focus-within:text-primary transition-colors">
                        <i data-lucide="phone" class="h-4 w-4"></i>
                    </div>
                    <input id="phone" 
                           class="block w-full rounded-2xl border border-border bg-card/40 py-3 pl-11 pr-4 text-sm outline-none transition-all focus:border-primary/50 focus:ring-4 focus:ring-primary/10" 
                           type="text" name="phone" value="{{ old('phone') }}" 
                           placeholder="+84 ..." />
                </div>
                <x-input-error :messages="$errors->get('phone')" class="mt-1 ml-1" />
            </div>
        </div>

        <div class="grid grid-cols-2 gap-5">
            <!-- DOB -->
            <div class="space-y-2">
                <label for="dob" class="text-xs font-bold uppercase tracking-widest text-muted-foreground ml-1">Ngày sinh</label>
                <input id="dob" 
                       class="block w-full rounded-2xl border border-border bg-card/40 py-3 px-4 text-sm outline-none transition-all focus:border-primary/50 focus:ring-4 focus:ring-primary/10" 
                       type="date" name="dob" value="{{ old('dob') }}" />
                <x-input-error :messages="$errors->get('dob')" class="mt-1 ml-1" />
            </div>

            <!-- Gender -->
            <div class="space-y-2">
                <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground ml-1">Giới tính</label>
                <div class="flex gap-2">
                    @foreach(['Nam', 'Nữ', 'Khác'] as $g)
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="gender" value="{{ $g }}" class="peer hidden" {{ old('gender', 'Nam') == $g ? 'checked' : '' }}>
                            <div class="py-2.5 text-center rounded-xl border border-border bg-card/40 text-[10px] font-black uppercase tracking-tighter transition-all peer-checked:bg-primary peer-checked:text-white peer-checked:border-primary peer-checked:shadow-glow">
                                {{ $g }}
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-5">
            <!-- Height -->
            <div class="space-y-2">
                <label for="height" class="text-xs font-bold uppercase tracking-widest text-muted-foreground ml-1">Cao(cm)</label>
                <input id="height" class="block w-full rounded-2xl border border-border bg-card/40 py-3 px-4 text-sm outline-none focus:border-primary/50" type="number" name="height" value="{{ old('height') }}" />
            </div>
            <!-- Weight -->
            <div class="space-y-2">
                <label for="weight" class="text-xs font-bold uppercase tracking-widest text-muted-foreground ml-1">Nặng(kg)</label>
                <input id="weight" class="block w-full rounded-2xl border border-border bg-card/40 py-3 px-4 text-sm outline-none focus:border-primary/50" type="number" name="weight" value="{{ old('weight') }}" />
            </div>
            <!-- Blood Type -->
            <div class="space-y-2 col-span-3 md:col-span-1">
                <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground ml-1">Nhóm máu</label>
                <div class="grid grid-cols-4 md:grid-cols-2 lg:grid-cols-4 gap-1.5">
                    @foreach(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $bt)
                        <label class="cursor-pointer">
                            <input type="radio" name="blood_type" value="{{ $bt }}" class="peer hidden" {{ old('blood_type') == $bt ? 'checked' : '' }}>
                            <div class="py-2 text-center rounded-xl border border-border bg-card/40 text-[9px] font-black transition-all peer-checked:bg-destructive peer-checked:text-white peer-checked:border-destructive peer-checked:shadow-[0_0_10px_rgba(239,68,68,0.3)]">
                                {{ $bt }}
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Password -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="space-y-2">
                <label for="password" class="text-xs font-bold uppercase tracking-widest text-muted-foreground ml-1">Mật khẩu</label>
                <div class="relative group">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-muted-foreground group-focus-within:text-primary transition-colors">
                        <i data-lucide="lock" class="h-4 w-4"></i>
                    </div>
                    <input id="password" 
                           class="block w-full rounded-2xl border border-border bg-card/40 py-3 pl-11 pr-4 text-sm outline-none transition-all focus:border-primary/50 focus:ring-4 focus:ring-primary/10"
                           type="password" name="password" required autocomplete="new-password" placeholder="••••••" />
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-1 ml-1" />
            </div>

            <!-- Confirm Password -->
            <div class="space-y-2">
                <label for="password_confirmation" class="text-xs font-bold uppercase tracking-widest text-muted-foreground ml-1">Xác nhận</label>
                <div class="relative group">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-muted-foreground group-focus-within:text-primary transition-colors">
                        <i data-lucide="shield-check" class="h-4 w-4"></i>
                    </div>
                    <input id="password_confirmation" 
                           class="block w-full rounded-2xl border border-border bg-card/40 py-3 pl-11 pr-4 text-sm outline-none transition-all focus:border-primary/50 focus:ring-4 focus:ring-primary/10"
                           type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••" />
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 ml-1" />
            </div>
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full flex h-12 items-center justify-center rounded-2xl gradient-primary text-sm font-bold text-primary-foreground shadow-glow transition-all hover:scale-[1.02] active:scale-[0.98]">
                Đăng ký tài khoản
            </button>
        </div>

        <p class="mt-8 text-center text-sm text-muted-foreground">
            Đã có tài khoản? 
            <a href="{{ route('login') }}" class="font-bold text-primary hover:underline">Đăng nhập ngay</a>
        </p>
    </form>
</x-guest-layout>

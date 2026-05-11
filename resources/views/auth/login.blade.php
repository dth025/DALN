<x-guest-layout>
    <div class="text-center mb-10">
        <h2 class="text-3xl font-bold tracking-tight font-display text-foreground">Chào mừng trở lại!</h2>
        <p class="mt-2 text-sm text-muted-foreground">Đăng nhập vào tài khoản HealthAI của bạn</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div class="space-y-2">
            <label for="email" class="text-xs font-bold uppercase tracking-widest text-muted-foreground ml-1">Email</label>
            <div class="relative group">
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-muted-foreground group-focus-within:text-primary transition-colors">
                    <i data-lucide="mail" class="h-4 w-4"></i>
                </div>
                <input id="email" 
                       class="block w-full rounded-2xl border border-border bg-card/40 py-3 pl-11 pr-4 text-sm outline-none transition-all focus:border-primary/50 focus:ring-4 focus:ring-primary/10" 
                       type="email" name="email" :value="old('email')" required autofocus autocomplete="username" 
                       placeholder="email@example.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1 ml-1" />
        </div>

        <!-- Password -->
        <div class="space-y-2">
            <div class="flex items-center justify-between ml-1">
                <label for="password" class="text-xs font-bold uppercase tracking-widest text-muted-foreground">Mật khẩu</label>
                @if (Route::has('password.request'))
                    <a class="text-xs font-medium text-primary hover:underline" href="{{ route('password.request') }}">
                        Quên mật khẩu?
                    </a>
                @endif
            </div>
            <div class="relative group">
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-muted-foreground group-focus-within:text-primary transition-colors">
                    <i data-lucide="lock" class="h-4 w-4"></i>
                </div>
                <input id="password" 
                       class="block w-full rounded-2xl border border-border bg-card/40 py-3 pl-11 pr-4 text-sm outline-none transition-all focus:border-primary/50 focus:ring-4 focus:ring-primary/10"
                       type="password"
                       name="password"
                       required autocomplete="current-password"
                       placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1 ml-1" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center ml-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox" class="h-4 w-4 rounded border-border bg-card/40 text-primary focus:ring-primary/20 transition-all cursor-pointer" name="remember">
                <span class="ms-2 text-sm text-muted-foreground group-hover:text-foreground transition-colors">Duy trì đăng nhập</span>
            </label>
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full flex h-12 items-center justify-center rounded-2xl gradient-primary text-sm font-bold text-primary-foreground shadow-glow transition-all hover:scale-[1.02] active:scale-[0.98]">
                Đăng nhập ngay
            </button>
        </div>

        <!-- Social Login Separator -->
        <div class="relative my-8">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-border/60"></div>
            </div>
            <div class="relative flex justify-center text-xs uppercase">
                <span class="bg-card px-3 text-muted-foreground font-medium tracking-widest">Hoặc tiếp tục với</span>
            </div>
        </div>

        <!-- Social Buttons -->
        <div class="grid grid-cols-2 gap-4">
            <button type="button" class="flex h-11 items-center justify-center gap-2 rounded-2xl border border-border bg-card/40 text-xs font-bold text-foreground transition-all hover:bg-muted hover:border-primary/20">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="h-4 w-4" alt="Google">
                Google
            </button>
            <button type="button" class="flex h-11 items-center justify-center gap-2 rounded-2xl border border-border bg-card/40 text-xs font-bold text-foreground transition-all hover:bg-muted hover:border-primary/20">
                <i data-lucide="github" class="h-4 w-4"></i>
                GitHub
            </button>
        </div>

        <p class="mt-10 text-center text-sm text-muted-foreground">
            Chưa có tài khoản? 
            <a href="{{ route('register') }}" class="font-bold text-primary hover:underline">Đăng ký miễn phí</a>
        </p>
    </form>
</x-guest-layout>

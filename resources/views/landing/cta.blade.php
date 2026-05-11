<section class="mx-auto max-w-7xl px-4 pb-20 md:px-6 md:pb-28">
    <div class="relative overflow-hidden rounded-[2.5rem] border border-border/50 p-10 text-center shadow-elevated md:p-16">
        <div class="absolute inset-0 gradient-primary opacity-95"></div>
        <div class="absolute inset-0 grid-bg opacity-30"></div>
        <div class="absolute -right-20 -top-20 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
        <div class="absolute -bottom-20 -left-20 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>

        <div class="relative mx-auto max-w-2xl text-primary-foreground">
            <i data-lucide="sparkles" class="mx-auto h-8 w-8 mb-4"></i>
            <h2 class="text-3xl font-bold tracking-tight font-display md:text-5xl">
                Bắt đầu hành trình sức khỏe của bạn
            </h2>
            <p class="mt-4 text-base text-white/85 md:text-lg">
                Tham gia cùng hơn 12.500 người đang sống khỏe hơn mỗi ngày với HealthAI.
            </p>
            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <a href="{{ Auth::check() ? route('dashboard') : route('login') }}" class="inline-flex items-center gap-2 rounded-2xl bg-white px-6 py-3 text-sm font-semibold text-primary shadow-soft transition-transform hover:scale-[1.05]">
                    {{ Auth::check() ? 'Vào Dashboard' : 'Đăng ký ngay' }} <i data-lucide="{{ Auth::check() ? 'layout-dashboard' : 'arrow-right' }}" class="h-4 w-4"></i>
                </a>
                <a href="{{ Auth::check() ? '#features' : '#features' }}" class="inline-flex items-center gap-2 rounded-2xl border border-white/30 bg-white/10 px-6 py-3 text-sm font-semibold text-white backdrop-blur-md transition-colors hover:bg-white/20">
                    Tìm hiểu thêm
                </a>
            </div>
        </div>
    </div>
</section>

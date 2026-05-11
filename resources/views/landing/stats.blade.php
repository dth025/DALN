<section class="mx-auto max-w-7xl px-4 md:px-6">
    <div class="rounded-3xl border border-border/50 bg-card/60 p-6 shadow-soft backdrop-blur-xl md:p-8"
         x-data="{ stats: [
            { value: '12.5K+', label: 'Người dùng' },
            { value: '98%', label: 'Hài lòng' },
            { value: '1.2M', label: 'Phân tích AI' },
            { value: '24/7', label: 'Hỗ trợ AI' }
         ] }">
        <div class="grid grid-cols-2 gap-6 md:grid-cols-4">
            <template x-for="(s, i) in stats" :key="s.label">
                <div class="text-center"
                     x-intersect.once="$el.classList.add('animate-in', 'fade-in', 'slide-in-from-bottom-4')">
                    <p class="text-3xl font-bold gradient-text font-display md:text-4xl" x-text="s.value"></p>
                    <p class="mt-1 text-xs uppercase tracking-wider text-muted-foreground" x-text="s.label"></p>
                </div>
            </template>
        </div>
    </div>
</section>

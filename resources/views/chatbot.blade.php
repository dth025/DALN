@extends('layouts.dashboard')

@section('title', 'AI Chatbot — HealthAI')

@section('content')
<!-- Page Header -->
<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <div class="flex items-center gap-4">
        <div class="flex h-12 w-12 items-center justify-center rounded-2xl gradient-primary text-primary-foreground shadow-glow">
            <i data-lucide="bot" class="h-5 w-5"></i>
        </div>
        <div>
            <h1 class="text-2xl font-bold tracking-tight md:text-3xl font-display">AI Health Chatbot</h1>
            <p class="mt-1 text-sm text-muted-foreground">Trợ lý sức khỏe AI thông minh, sẵn sàng 24/7</p>
        </div>
    </div>
</div>

<div class="grid gap-6 lg:grid-cols-3" x-data="chatbotSystem()">
    <div class="space-y-4 lg:col-span-1">
        <!-- History -->
        <div class="glass rounded-2xl p-5 shadow-soft">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold">Lịch sử trò chuyện</h3>
                <i data-lucide="message-square" class="h-4 w-4 text-muted-foreground"></i>
            </div>
            <div class="mt-3 space-y-1.5">
                @forelse($history as $h)
                <button 
                    @click="userInput = '{{ $h->message }}'; sendMessage()"
                    class="group flex w-full items-start gap-2 rounded-xl p-2.5 text-left transition-colors hover:bg-sidebar-accent"
                >
                    <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary">
                        <i data-lucide="message-square" class="h-3.5 w-3.5"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-xs font-medium">{{ $h->message }}</p>
                        <p class="text-[10px] text-muted-foreground">{{ $h->created_at->diffForHumans() }}</p>
                    </div>
                </button>
                @empty
                <div class="py-4 text-center text-xs text-muted-foreground">
                    Chưa có lịch sử trò chuyện
                </div>
                @endforelse
            </div>
        </div>

        <!-- Prompts -->
        <div class="glass rounded-2xl p-5 shadow-soft">
            <div class="flex items-center gap-2">
                <i data-lucide="sparkles" class="h-4 w-4 text-primary"></i>
                <h3 class="text-sm font-semibold">Gợi ý câu hỏi</h3>
            </div>
            <div class="mt-3 space-y-2">
                @php
                    $prompts = [
                        "Phân tích chỉ số sức khỏe tổng thể của tôi",
                        "Gợi ý thực đơn 1.800 kcal tăng cơ",
                        "Bài tập 20 phút giảm mỡ bụng tại nhà",
                        "Tôi nên ngủ mấy giờ để hồi phục tốt?",
                    ];
                @endphp
                @foreach($prompts as $p)
                <button 
                    @click="userInput = '{{ $p }}'; sendMessage()"
                    class="w-full rounded-xl border border-border bg-card/40 p-3 text-left text-xs transition-all hover:border-primary hover:shadow-soft"
                >
                    {{ $p }}
                </button>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Chat Area -->
    <div class="lg:col-span-2">
        <div class="glass flex h-[600px] flex-col overflow-hidden rounded-2xl shadow-elevated">
            <!-- Chat Header -->
            <div class="flex items-center justify-between border-b border-border/50 bg-card/50 px-5 py-4 backdrop-blur-md">
                <div class="flex items-center gap-3">
                    <div class="relative flex h-10 w-10 items-center justify-center rounded-xl gradient-primary text-white shadow-glow">
                        <i data-lucide="bot" class="h-5 w-5"></i>
                        <span class="absolute -right-1 -top-1 flex h-3 w-3">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-success opacity-75"></span>
                            <span class="relative inline-flex h-3 w-3 rounded-full bg-success ring-2 ring-card"></span>
                        </span>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold">HealthAI Assistant</h3>
                        <p class="text-[10px] text-success font-medium">Trực tuyến</p>
                    </div>
                </div>
            </div>

            <!-- Chat Messages -->
            <div class="flex-1 overflow-y-auto p-5 space-y-6" id="chat-messages">
                <template x-for="(msg, index) in messages" :key="index">
                    <div :class="msg.role === 'user' ? 'flex flex-row-reverse items-start gap-3' : 'flex items-start gap-3'">
                        <div :class="msg.role === 'user' ? 'bg-primary text-white' : 'gradient-primary text-white'" class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg shadow-soft">
                            <i :data-lucide="msg.role === 'user' ? 'user' : 'bot'" class="h-4 w-4"></i>
                        </div>
                        <div :class="msg.role === 'user' ? 'rounded-tr-none bg-primary/10 border-primary/20' : 'rounded-tl-none border-border/50 bg-card/50'" class="max-w-[85%] rounded-2xl border p-4 shadow-sm backdrop-blur-sm">
                            <div class="prose prose-sm dark:prose-invert" x-html="msg.content"></div>
                        </div>
                    </div>
                </template>

                <!-- Typing Indicator -->
                <div x-show="isTyping" class="flex items-start gap-3 animate-pulse">
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg gradient-primary text-white shadow-soft">
                        <i data-lucide="bot" class="h-4 w-4"></i>
                    </div>
                    <div class="rounded-2xl rounded-tl-none border border-border/50 bg-card/50 p-4 shadow-sm backdrop-blur-sm italic text-xs text-muted-foreground">
                        HealthAI đang suy nghĩ...
                    </div>
                </div>
            </div>

            <!-- Chat Input -->
            <div class="border-t border-border/50 bg-card/50 p-4 backdrop-blur-md">
                <form @submit.prevent="sendMessage" class="relative flex items-end gap-2 rounded-2xl border border-border bg-background/50 p-2 shadow-sm transition-colors focus-within:border-primary/50 focus-within:bg-background">
                    <button type="button" class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl text-muted-foreground hover:bg-accent hover:text-foreground">
                        <i data-lucide="paperclip" class="h-4 w-4"></i>
                    </button>
                    <textarea
                        x-model="userInput"
                        @keydown.enter.prevent="sendMessage"
                        placeholder="Hỏi AI về sức khỏe, thực đơn..."
                        class="max-h-32 min-h-10 w-full resize-none bg-transparent py-2.5 text-sm outline-none placeholder:text-muted-foreground"
                        rows="1"
                    ></textarea>
                    <button type="submit" :disabled="isTyping || !userInput.trim()" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl gradient-primary text-white shadow-soft transition-transform hover:scale-105 active:scale-95 disabled:opacity-50">
                        <i data-lucide="send" class="h-4 w-4"></i>
                    </button>
                </form>
                <div class="mt-2 text-center text-[10px] text-muted-foreground">
                    AI có thể cung cấp thông tin y tế không chính xác. Hãy luôn tham khảo ý kiến bác sĩ.
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function chatbotSystem() {
    return {
        userInput: '',
        isTyping: false,
        messages: [
            { role: 'assistant', content: 'Xin chào! Tôi là HealthAI Assistant. Hôm nay tôi có thể giúp gì cho sức khỏe của bạn?' }
        ],
        
        async sendMessage() {
            if (!this.userInput.trim() || this.isTyping) return;
            
            const message = this.userInput;
            this.messages.push({ role: 'user', content: message });
            this.userInput = '';
            this.isTyping = true;
            
            this.scrollToBottom();

            try {
                const response = await fetch("{{ route('chatbot.send') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ message: message })
                });
                
                const data = await response.json();
                this.messages.push({ role: 'assistant', content: data.reply });
            } catch (error) {
                this.messages.push({ role: 'assistant', content: 'Xin lỗi, đã có lỗi xảy ra khi kết nối với AI.' });
            } finally {
                this.isTyping = false;
                this.$nextTick(() => {
                    this.scrollToBottom();
                    if (window.lucide) lucide.createIcons();
                });
            }
        },

        scrollToBottom() {
            const container = document.getElementById('chat-messages');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        }
    }
}
</script>
@endsection

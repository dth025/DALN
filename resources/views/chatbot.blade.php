@extends('layouts.dashboard')

@section('title', 'Tư vấn sức khỏe — HealthAI')

@section('content')
<div class="space-y-6" x-data="medConsultationSystem()" x-init="init()">
    <div x-show="newMessageToast" x-transition class="fixed right-6 top-24 z-50 max-w-xs rounded-3xl border border-sky-400/30 bg-slate-950/95 p-4 text-sm text-slate-100 shadow-lg shadow-slate-950/50">
        <span x-text="newMessageToast"></span>
    </div>
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-sm uppercase tracking-[0.3em] text-primary">Tư vấn sức khỏe</p>
            <h1 class="mt-3 text-3xl font-bold tracking-tight">Chat AI hoặc chat với bác sĩ</h1>
            <p class="mt-2 max-w-2xl text-sm text-muted-foreground">Trao đổi tức thì với AI, hoặc chọn bác sĩ để hỏi trực tiếp mà không cần tải lại trang.</p>
        </div>
        <div class="rounded-3xl border border-border/50 bg-card/80 p-3 text-xs text-slate-400">
            <span class="font-semibold text-foreground">Tip:</span> Chuyển tab để chọn giữa AI và bác sĩ. Tin nhắn được tải tự động qua AJAX.
        </div>
    </div>

    <div class="glass rounded-3xl border border-border/60 p-4 shadow-elevated">
        <div class="flex flex-wrap items-center gap-3">
            <button type="button" @click="switchTab('ai')" :class="activeTab === 'ai' ? 'bg-slate-950 text-white' : 'bg-slate-900 text-slate-300'" class="rounded-full px-4 py-2 text-sm font-semibold transition">
                Chat AI
            </button>
            <button type="button" @click="switchTab('doctor')" :class="activeTab === 'doctor' ? 'bg-slate-950 text-white' : 'bg-slate-900 text-slate-300'" class="rounded-full px-4 py-2 text-sm font-semibold transition">
                Chat Bác sĩ
            </button>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="glass rounded-3xl p-5 lg:col-span-1 h-[720px] overflow-hidden flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-xs font-black uppercase tracking-widest text-slate-400">Bảng điều khiển</p>
                    <h2 class="text-lg font-bold text-white">{{ __('Tư vấn') }}</h2>
                </div>
                <span class="rounded-full bg-emerald-500/10 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.2em] text-emerald-300">Realtime</span>
            </div>

            <div class="relative mb-4">
                <i data-lucide="search" class="absolute left-3 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-slate-500"></i>
                <input type="text" x-model="searchText" @input="filterDoctors()" placeholder="Tìm kiếm bác sĩ..." class="h-11 w-full rounded-2xl border border-slate-700 bg-slate-950/80 pl-11 pr-4 text-xs font-semibold text-slate-200 outline-none focus:border-sky-500">
            </div>

            <template x-if="activeTab === 'ai'">
                <div class="space-y-4 overflow-y-auto pr-1" style="max-height: calc(100% - 62px);">
                    <div class="glass rounded-3xl p-4 border border-border/50">
                        <h3 class="text-sm font-bold text-white">Lịch sử AI</h3>
                        <div class="mt-3 space-y-3">
                            <template x-for="entry in aiHistory" :key="entry.time">
                                <button @click="userInput = entry.message; switchTab('ai'); sendAIMessage()" class="w-full text-left rounded-2xl border border-border/60 bg-slate-900/80 p-3 text-xs text-slate-200 hover:border-sky-500/40">
                                    <p class="truncate font-medium text-white" x-text="entry.message"></p>
                                    <p class="mt-1 text-[10px] text-slate-500" x-text="entry.time"></p>
                                </button>
                            </template>
                            <template x-if="aiHistory.length === 0">
                                <p class="text-xs text-slate-500">Chưa có lịch sử AI.</p>
                            </template>
                        </div>
                    </div>

                    <div class="glass rounded-3xl p-4 border border-border/50">
                        <h3 class="text-sm font-bold text-white">Gợi ý câu hỏi</h3>
                        <div class="mt-3 grid gap-3">
                            <button type="button" @click="selectPrompt('Phân tích chỉ số sức khỏe tổng thể của tôi')" class="rounded-2xl border border-border/60 bg-slate-900/80 px-4 py-3 text-left text-xs text-slate-200 hover:border-sky-500/40">Phân tích chỉ số sức khỏe tổng thể của tôi</button>
                            <button type="button" @click="selectPrompt('Gợi ý thực đơn 1.800 kcal tăng cơ')" class="rounded-2xl border border-border/60 bg-slate-900/80 px-4 py-3 text-left text-xs text-slate-200 hover:border-sky-500/40">Gợi ý thực đơn 1.800 kcal tăng cơ</button>
                            <button type="button" @click="selectPrompt('Bài tập 20 phút giảm mỡ bụng tại nhà')" class="rounded-2xl border border-border/60 bg-slate-900/80 px-4 py-3 text-left text-xs text-slate-200 hover:border-sky-500/40">Bài tập 20 phút giảm mỡ bụng tại nhà</button>
                            <button type="button" @click="selectPrompt('Tôi nên ngủ mấy giờ để hồi phục tốt?')" class="rounded-2xl border border-border/60 bg-slate-900/80 px-4 py-3 text-left text-xs text-slate-200 hover:border-sky-500/40">Tôi nên ngủ mấy giờ để hồi phục tốt?</button>
                        </div>
                    </div>
                </div>
            </template>

            <template x-if="activeTab === 'doctor'">
                <div class="overflow-y-auto pr-1" style="max-height: calc(100% - 62px);">
                    <div class="space-y-3">
                        <template x-for="doctor in filteredDoctors" :key="doctor.id">
                            <button type="button" @click="selectDoctor(doctor)" :class="selectedDoctor && selectedDoctor.id === doctor.id ? 'border-sky-500/40 bg-sky-500/10' : 'border-border/60 bg-slate-900/80'" class="w-full rounded-3xl border p-4 text-left transition">
                                <div class="flex items-center gap-3">
                                    <img :src="doctor.avatar" class="h-11 w-11 rounded-full border border-slate-700 object-cover">
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-semibold text-white" x-text="doctor.name"></p>
                                        <p class="text-[11px] text-slate-400" x-text="doctor.specialty"></p>
                                    </div>
                                    <template x-if="doctor.unread_count > 0">
                                        <span class="ml-auto rounded-full bg-rose-500 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.15em] text-white" x-text="doctor.unread_count + ' mới'"></span>
                                    </template>
                                </div>
                            </button>
                        </template>
                        <template x-if="filteredDoctors.length === 0">
                            <p class="text-xs text-slate-500">Không tìm thấy bác sĩ.</p>
                        </template>
                    </div>
                </div>
            </template>
        </div>

        <div class="lg:col-span-2 flex flex-col h-[720px]">
            <div class="glass h-full flex flex-col overflow-hidden rounded-3xl border border-border/60">
                <div class="flex items-center justify-between border-b border-border/50 bg-card/60 px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div class="relative flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-900 text-slate-100 shadow-sm">
                            <template x-if="activeTab === 'ai'">
                                <i data-lucide="bot" class="h-5 w-5"></i>
                            </template>
                            <template x-if="activeTab === 'doctor'">
                                <i data-lucide="user-check" class="h-5 w-5"></i>
                            </template>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-white" x-text="activeTab === 'ai' ? 'HealthAI Assistant' : (selectedDoctor ? selectedDoctor.name : 'Chọn bác sĩ để chat')"></p>
                            <p class="text-[10px] text-slate-400" x-text="activeTab === 'ai' ? 'AI tư vấn 24/7' : (selectedDoctor ? selectedDoctor.specialty : 'Nhấn vào một bác sĩ bên trái')"></p>
                        </div>
                    </div>
                    <span class="rounded-full bg-emerald-500/10 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.2em] text-emerald-300" x-text="activeTab === 'ai' ? 'Trực tuyến AI' : 'Trực tuyến bác sĩ'"></span>
                </div>

                <div class="flex-1 overflow-y-auto p-5 space-y-4 bg-slate-950/80" id="chat-panel">
                    <template x-if="activeTab === 'ai'">
                        <template x-for="(msg, index) in aiMessages" :key="index">
                            <div :class="msg.sender === 'user' ? 'justify-end' : 'justify-start'" class="flex">
                                <div :class="msg.sender === 'user' ? 'bg-sky-600 text-white rounded-tl-3xl rounded-bl-3xl rounded-br-none' : 'bg-slate-800 text-slate-200 rounded-tr-3xl rounded-br-3xl rounded-bl-none'" class="max-w-[80%] rounded-3xl border border-border p-4 text-sm shadow-sm">
                                    <div x-html="msg.content"></div>
                                    <div class="mt-2 text-[10px] text-slate-500 text-right" x-text="msg.time"></div>
                                </div>
                            </div>
                        </template>
                    </template>

                    <template x-if="activeTab === 'doctor'">
                        <template x-if="selectedDoctor">
                            <template x-for="(msg, index) in doctorMessages" :key="index">
                                <div :class="msg.sender === 'patient' ? 'justify-end' : 'justify-start'" class="flex">
                                    <div :class="msg.sender === 'patient' ? 'bg-sky-600 text-white rounded-tl-3xl rounded-bl-3xl rounded-br-none' : 'bg-slate-800 text-slate-200 rounded-tr-3xl rounded-br-3xl rounded-bl-none'" class="max-w-[80%] rounded-3xl border border-border p-4 text-sm shadow-sm">
                                        <div x-html="msg.message"></div>
                                        <template x-if="msg.file_path">
                                            <div class="mt-3">
                                                <template x-if="msg.file_type === 'image'">
                                                    <img :src="msg.file_path" class="rounded-3xl max-w-[260px] border border-slate-700" />
                                                </template>
                                                <template x-if="msg.file_type !== 'image'">
                                                    <a :href="msg.file_path" target="_blank" class="inline-flex items-center gap-2 rounded-2xl border border-slate-700 bg-slate-900/80 px-3 py-2 text-[11px] text-sky-300">Tải tệp đính kèm</a>
                                                </template>
                                            </div>
                                        </template>
                                        <div class="mt-2 text-[10px] text-slate-500 text-right" x-text="msg.time"></div>
                                    </div>
                                </div>
                            </template>
                        </template>
                        <template x-if="!selectedDoctor">
                            <div class="flex h-full items-center justify-center rounded-3xl border border-dashed border-slate-700 bg-slate-900/70 p-6 text-center text-slate-400">
                                <p>Chọn bác sĩ để bắt đầu chat trực tiếp.</p>
                            </div>
                        </template>
                    </template>

                    <div x-show="isTyping" class="flex items-start gap-3 animate-pulse">
                        <div class="flex h-9 w-9 items-center justify-center rounded-2xl bg-slate-900 text-sky-400 shadow-soft">
                            <i data-lucide="loader" class="h-4 w-4 animate-spin"></i>
                        </div>
                        <div class="rounded-3xl border border-border bg-slate-900/80 p-4 text-sm text-slate-400">Đang tải tin nhắn...</div>
                    </div>
                </div>

                <div class="border-t border-border/50 bg-card/60 p-4">
                    <form @submit.prevent="activeTab === 'ai' ? sendAIMessage() : sendDoctorMessage()" class="grid gap-3">
                        <div class="relative rounded-3xl border border-border bg-slate-950/80 px-4 py-3 focus-within:border-sky-500">
                            <textarea x-model="userInput" rows="2" placeholder="Viết tin nhắn của bạn..." class="w-full resize-none border-0 bg-transparent text-sm text-slate-100 outline-none placeholder:text-slate-500"></textarea>
                        </div>
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex items-center gap-3 text-xs text-slate-500">
                                <i data-lucide="paperclip" class="h-4 w-4"></i>
                                <label class="cursor-pointer font-semibold text-slate-300">Đính kèm
                                    <input type="file" id="doctor-chat-file" class="sr-only" @change="handleFileUpload($event)">
                                </label>
                                <span x-text="attachedFileName"></span>
                            </div>
                            <button type="submit" :disabled="isTyping || (!userInput.trim() && activeTab === 'ai') || (activeTab === 'doctor' && !userInput.trim() && !attachedFile)" class="inline-flex items-center justify-center rounded-full bg-sky-500 px-6 py-3 text-sm font-semibold text-slate-950 shadow-glow hover:bg-sky-400 disabled:opacity-50">
                                <i data-lucide="send" class="mr-2 h-4 w-4"></i> Gửi
                            </button>
                        </div>
                    </form>
                    <p class="mt-2 text-[10px] text-slate-500">Tin nhắn gửi đi sẽ được cập nhật ngay lập tức mà không cần tải lại trang.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function medConsultationSystem() {
    return {
        activeTab: 'ai',
        searchText: '',
        userInput: '',
        attachedFile: null,
        attachedFileName: '',
        isTyping: false,
        aiHistory: [],
        aiMessages: [],
        doctors: [],
        filteredDoctors: [],
        selectedDoctor: null,
        doctorMessages: [],
        newMessageToast: '',
        unreadDoctors: {},
        pollIntervalId: null,

        async init() {
            this.aiHistory = @json($history->map(function($h) {
                return ['message' => $h->message, 'time' => $h->created_at->diffForHumans()];
            })->values());
            this.aiMessages = [{ sender: 'assistant', content: 'Xin chào! Tôi là HealthAI Assistant. Hôm nay tôi có thể giúp gì cho sức khỏe của bạn?', time: '' }];
            await this.loadDoctors();
            this.startPolling();

            // Auto select doctor from URL query parameter
            const urlParams = new URLSearchParams(window.location.search);
            const docId = urlParams.get('doctor_id');
            if (docId) {
                this.activeTab = 'doctor';
                const doc = this.doctors.find(d => d.id == docId);
                if (doc) {
                    this.selectDoctor(doc);
                }
            }
        },

        startPolling() {
            if (this.pollIntervalId) {
                clearInterval(this.pollIntervalId);
            }
            this.pollIntervalId = setInterval(async () => {
                await this.loadDoctors();
                if (this.selectedDoctor) {
                    await this.pollDoctorMessages();
                }
            }, 5000);
        },

        switchTab(tab) {
            this.activeTab = tab;
            if (tab === 'doctor') {
                this.loadDoctors();
            }
        },

        selectPrompt(text) {
            this.userInput = text;
            this.sendAIMessage();
        },

        async loadDoctors() {
            try {
                const response = await fetch('{{ route('chatbot.doctors') }}');
                const data = await response.json();
                if (data.success) {
                    this.doctors = data.doctors;
                    if (this.searchText.trim()) {
                        this.filterDoctors();
                    } else {
                        this.filteredDoctors = data.doctors;
                    }
                }
            } catch (error) {
                console.error(error);
            }
        },

        filterDoctors() {
            const query = this.searchText.toLowerCase();
            this.filteredDoctors = this.doctors.filter(d => d.name.toLowerCase().includes(query) || d.specialty.toLowerCase().includes(query));
        },

        selectDoctor(doctor) {
            this.selectedDoctor = doctor;
            this.doctorMessages = [];
            this.userInput = '';
            this.attachedFile = null;
            this.attachedFileName = '';
            this.loadDoctorMessages(doctor.id);
        },

        async pollDoctorMessages() {
            if (!this.selectedDoctor) return;
            try {
                const response = await fetch(`/chatbot/messages/${this.selectedDoctor.id}`);
                const data = await response.json();
                if (data.success) {
                    if (data.messages.length > this.doctorMessages.length) {
                        this.newMessageToast = 'Bạn có tin nhắn mới từ bác sĩ.';
                        setTimeout(() => { this.newMessageToast = ''; }, 4500);
                    }
                    this.doctorMessages = data.messages.map(msg => ({
                        sender: msg.sender,
                        message: msg.message,
                        file_path: msg.file_path,
                        file_type: msg.file_type,
                        time: new Date(msg.created_at).toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' })
                    }));
                    this.scrollChatToBottom();
                }
            } catch (error) {
                console.error(error);
            }
        },

        async loadDoctorMessages(doctorId) {
            if (!doctorId) return;
            try {
                const response = await fetch(`/chatbot/messages/${doctorId}`);
                const data = await response.json();
                if (data.success) {
                    this.doctorMessages = data.messages.map(msg => ({
                        sender: msg.sender,
                        message: msg.message,
                        file_path: msg.file_path,
                        file_type: msg.file_type,
                        time: new Date(msg.created_at).toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' })
                    }));
                    this.scrollChatToBottom();
                }
            } catch (error) {
                console.error(error);
            }
        },

        scrollChatToBottom() {
            this.$nextTick(() => {
                const container = document.getElementById('chat-panel');
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            });
        },

        async sendAIMessage() {
            if (!this.userInput.trim() || this.isTyping) return;
            const message = this.userInput;
            this.aiMessages.push({ sender: 'user', content: message, time: 'Vừa xong' });
            this.aiHistory.unshift({ message, time: 'Vừa xong' });
            this.userInput = '';
            this.isTyping = true;
            try {
                const response = await fetch('{{ route('chatbot.send') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ message })
                });
                const data = await response.json();
                this.aiMessages.push({ sender: 'assistant', content: data.reply, time: new Date().toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' }) });
            } catch (error) {
                this.aiMessages.push({ sender: 'assistant', content: 'Xin lỗi, đã có lỗi xảy ra khi kết nối với AI.', time: '' });
                console.error(error);
            } finally {
                this.isTyping = false;
                this.$nextTick(() => {
                    const container = document.getElementById('chat-panel');
                    if (container) container.scrollTop = container.scrollHeight;
                    if (window.lucide) lucide.createIcons();
                });
            }
        },

        handleFileUpload(event) {
            const file = event.target.files[0];
            if (!file) {
                this.attachedFile = null;
                this.attachedFileName = '';
                return;
            }
            this.attachedFile = file;
            this.attachedFileName = file.name;
        },

        async sendDoctorMessage() {
            if (!this.selectedDoctor) return;
            if (!this.userInput.trim() && !this.attachedFile) return;
            if (this.isTyping) return;

            const formData = new FormData();
            formData.append('doctor_id', this.selectedDoctor.id);
            formData.append('message', this.userInput);
            if (this.attachedFile) {
                formData.append('file', this.attachedFile);
            }

            this.doctorMessages.push({ sender: 'patient', message: this.userInput || (this.attachedFile ? 'Đã gửi tệp đính kèm.' : ''), file_path: this.attachedFile ? URL.createObjectURL(this.attachedFile) : null, file_type: this.attachedFile ? (this.attachedFile.type.startsWith('image/') ? 'image' : 'document') : null, time: 'Vừa xong' });
            this.userInput = '';
            this.attachedFile = null;
            this.attachedFileName = '';
            this.isTyping = true;

            try {
                const response = await fetch('{{ route('chatbot.doctor.send') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                });
                const data = await response.json();
                if (data.success) {
                    await this.loadDoctorMessages(this.selectedDoctor.id);
                } else {
                    alert(data.message || 'Lỗi gửi tin nhắn');
                }
            } catch (error) {
                console.error(error);
                alert('Không thể kết nối máy chủ.');
            } finally {
                this.isTyping = false;
                this.$nextTick(() => {
                    const container = document.getElementById('chat-panel');
                    if (container) container.scrollTop = container.scrollHeight;
                });
            }
        }
    }
}
</script>
@endsection

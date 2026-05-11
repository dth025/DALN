@extends('layouts.dashboard')

@section('title', 'Theo dõi sức khỏe — HealthAI')

@section('content')
<div x-data="healthTracker()" class="max-w-7xl mx-auto space-y-8 pb-12 animate-in fade-in duration-700">
    
    <!-- Page Header -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="flex h-14 w-14 items-center justify-center rounded-[1.5rem] gradient-primary text-primary-foreground shadow-glow">
                <i data-lucide="activity" class="h-6 w-6"></i>
            </div>
            <div>
                <h1 class="text-3xl font-black tracking-tighter md:text-4xl">Theo dõi sức khỏe</h1>
                <p class="mt-1 text-sm font-bold text-muted-foreground uppercase tracking-widest">Đồng bộ dữ liệu thời gian thực</p>
            </div>
        </div>
        
        <div class="flex items-center gap-3">
            <div x-show="saving" class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-primary/10 border border-primary/20 text-[10px] font-black uppercase text-primary animate-pulse">
                <i data-lucide="refresh-cw" class="h-3 w-3 animate-spin"></i> Đang đồng bộ...
            </div>
            <button @click="showModal = true" class="flex items-center gap-2 rounded-2xl gradient-primary px-6 py-3 text-xs font-black uppercase tracking-widest text-white shadow-glow hover:scale-[1.02] active:scale-95 transition-all">
                <i data-lucide="plus" class="h-4 w-4"></i> Cập nhật chỉ số
            </button>
        </div>
    </div>

    <!-- PREMIUM UPDATE MODAL -->
    <div x-show="showModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-background/60 backdrop-blur-md">
        
        <div @click.away="showModal = false" 
             class="relative w-full max-w-2xl bg-card border border-border rounded-[3rem] shadow-elevated overflow-hidden animate-in zoom-in duration-300">
            
            <div class="absolute inset-0 gradient-primary opacity-[0.03] -z-10"></div>
            
            <!-- Modal Header -->
            <div class="px-10 pt-10 pb-6 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-primary/10 text-primary">
                        <i data-lucide="edit-3" class="h-5 w-5"></i>
                    </div>
                    <div>
                        <h4 class="text-xl font-black uppercase tracking-tight text-foreground">Cập nhật chỉ số mới</h4>
                        <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest">Dữ liệu sẽ được lưu tự động</p>
                    </div>
                </div>
                <button @click="showModal = false" class="h-10 w-10 flex items-center justify-center rounded-full hover:bg-muted transition-colors text-muted-foreground">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="px-10 pb-10">
                <form id="health-form" @input.debounce.500ms="saveMetrics()" class="grid gap-6 md:grid-cols-2">
                    @csrf
                    <!-- Group 1: Vital Signs -->
                    <div class="p-6 rounded-[2rem] bg-muted/20 border border-border/50 space-y-5">
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-primary">Chỉ số sinh tồn</p>
                        <div class="space-y-4">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground ml-1">Nhịp tim (bpm)</label>
                                <div class="relative">
                                    <input type="number" name="heart_rate" x-model="data.heart_rate" class="w-full bg-background border border-border focus:border-primary/40 p-4 rounded-xl font-black text-lg outline-none transition-all pl-12">
                                    <i data-lucide="heart" class="absolute left-4 top-1/2 -translate-y-1/2 h-4 w-4 text-rose-500"></i>
                                </div>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground ml-1">SpO₂ (%)</label>
                                <div class="relative">
                                    <input type="number" name="spo2" x-model="data.spo2" class="w-full bg-background border border-border focus:border-primary/40 p-4 rounded-xl font-black text-lg outline-none transition-all pl-12">
                                    <i data-lucide="wind" class="absolute left-4 top-1/2 -translate-y-1/2 h-4 w-4 text-blue-500"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Group 2: Body Metrics -->
                    <div class="p-6 rounded-[2rem] bg-muted/20 border border-border/50 space-y-5">
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-primary">Chỉ số cơ thể</p>
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground ml-1">Cân nặng (kg)</label>
                                    <input type="number" step="0.1" name="weight" x-model="data.weight" class="w-full bg-background border border-border focus:border-primary/40 p-4 rounded-xl font-black text-lg outline-none transition-all text-center">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground ml-1">Cao (cm)</label>
                                    <input type="number" name="height" x-model="data.height" class="w-full bg-background border border-border focus:border-primary/40 p-4 rounded-xl font-black text-lg outline-none transition-all text-center">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground ml-1">Nước (Lít)</label>
                                    <input type="number" step="0.1" name="water_intake" x-model="data.water_intake" class="w-full bg-background border border-border focus:border-primary/40 p-4 rounded-xl font-black text-lg outline-none transition-all text-center">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground ml-1">Ngủ (Giờ)</label>
                                    <input type="number" step="0.5" name="sleep_hours" x-model="data.sleep_hours" class="w-full bg-background border border-border focus:border-primary/40 p-4 rounded-xl font-black text-lg outline-none transition-all text-center">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                
                <div class="mt-8 flex justify-center">
                    <button @click="showModal = false" class="rounded-2xl bg-muted px-12 py-4 text-[10px] font-black uppercase tracking-widest text-foreground hover:bg-muted/80 transition-all">Hoàn tất</button>
                </div>
            </div>
        </div>
    </div>

    <!-- DEVICE CONNECTIVITY SECTION -->
    <div class="bg-card border border-border rounded-[2.5rem] p-8 shadow-soft relative overflow-hidden group">
        <div class="absolute inset-0 gradient-primary opacity-[0.01] group-hover:opacity-[0.03] transition-opacity"></div>
        <div class="relative z-10">
            <!-- Header Row -->
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6 mb-6">
                <div class="space-y-1">
                    <h3 class="text-xl font-black uppercase tracking-tight text-foreground">Kết nối thiết bị đeo</h3>
                    <p class="text-xs font-bold text-muted-foreground uppercase tracking-widest">Đồng bộ chỉ số thời gian thực từ vòng tay / đồng hồ của bạn qua Bluetooth</p>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap items-center gap-3 shrink-0">
                    <!-- CSV Import Button -->
                    <button @click="openCsvModal()"
                            class="flex items-center gap-2 rounded-2xl px-5 py-3 text-[10px] font-black uppercase tracking-widest active:scale-95 transition-all border bg-orange-500/10 border-orange-500/30 text-orange-500 hover:bg-orange-500/20">
                        <i data-lucide="file-up" class="h-4 w-4"></i>
                        Import báo cáo
                    </button>

                    <!-- QR Connect Button -->
                    <button @click="openQrModal()"
                            :class="qrConnected ? 'bg-success text-white border-success/30' : 'bg-card border-border text-foreground hover:border-primary/30 hover:text-primary'"
                            class="flex items-center gap-2 rounded-2xl px-5 py-3 text-[10px] font-black uppercase tracking-widest active:scale-95 transition-all border">
                        <i data-lucide="qr-code" class="h-4 w-4"></i>
                        <span x-text="qrConnected ? 'Đã đồng bộ QR' : 'Kết nối QR'"></span>
                    </button>

                    <!-- Bluetooth Connect Button -->
                    <button @click="connectBluetooth()"
                            :disabled="btConnecting"
                            :class="btConnected
                                ? 'bg-success text-white border-success/30 shadow-glow-success'
                                : 'bg-foreground text-background hover:scale-105'"
                            class="flex items-center gap-2 rounded-2xl px-6 py-3 text-[10px] font-black uppercase tracking-widest active:scale-95 transition-all shadow-glow-dark border border-transparent disabled:opacity-60 disabled:cursor-not-allowed">
                        <span x-show="btConnecting" class="flex items-center gap-2">
                            <i data-lucide="loader-2" class="h-4 w-4 animate-spin"></i> Đang tìm...
                        </span>
                        <span x-show="!btConnecting && !btConnected" class="flex items-center gap-2">
                            <i data-lucide="bluetooth" class="h-4 w-4"></i> Bluetooth
                        </span>
                        <span x-show="!btConnecting && btConnected" class="flex items-center gap-2">
                            <i data-lucide="bluetooth-connected" class="h-4 w-4"></i> Đã kết nối
                        </span>
                    </button>
                </div>
            </div>

            <!-- Status Bar -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Connection Status Card -->
                <div :class="btConnected ? 'border-success/30 bg-success/5' : 'border-border bg-muted/20'"
                     class="flex items-center gap-4 p-4 rounded-2xl border transition-all">
                    <div :class="btConnected ? 'bg-success/20 text-success' : 'bg-muted text-muted-foreground'"
                         class="h-10 w-10 rounded-xl flex items-center justify-center shrink-0">
                        <i :data-lucide="btConnected ? 'bluetooth-connected' : 'bluetooth-off'" class="h-5 w-5"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Trạng thái</p>
                        <p class="text-sm font-black" :class="btConnected ? 'text-success' : 'text-foreground'">
                            <span x-text="btConnected ? btDeviceName : 'Chưa kết nối'"></span>
                        </p>
                    </div>
                </div>

                <!-- Live Heart Rate from BLE -->
                <div :class="btConnected ? 'border-rose-500/30 bg-rose-500/5' : 'border-border bg-muted/20'"
                     class="flex items-center gap-4 p-4 rounded-2xl border transition-all">
                    <div :class="btConnected ? 'bg-rose-500/20 text-rose-500' : 'bg-muted text-muted-foreground'"
                         class="h-10 w-10 rounded-xl flex items-center justify-center shrink-0">
                        <i data-lucide="heart" class="h-5 w-5" :class="btConnected ? 'animate-pulse' : ''"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Nhịp tim (Live)</p>
                        <p class="text-sm font-black text-foreground">
                            <span x-text="btConnected ? (data.heart_rate + ' BPM') : '—'"></span>
                        </p>
                    </div>
                </div>

                <!-- Platform Connections (static) -->
                <div class="flex items-center gap-3 p-4 rounded-2xl border border-border bg-muted/20">
                    <div class="flex gap-2">
                        <div class="h-8 w-8 rounded-lg bg-card border border-border flex items-center justify-center shadow-sm">
                            <i data-lucide="heart" class="h-4 w-4 text-rose-500"></i>
                        </div>
                        <div class="h-8 w-8 rounded-lg bg-card border border-border flex items-center justify-center shadow-sm">
                            <i data-lucide="activity" class="h-4 w-4 text-blue-500"></i>
                        </div>
                        <div class="h-8 w-8 rounded-lg bg-card border border-border flex items-center justify-center shadow-sm">
                            <i data-lucide="watch" class="h-4 w-4 text-primary"></i>
                        </div>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Hỗ trợ</p>
                        <p class="text-xs font-black text-foreground">Smartwatch · Mi Band · Fitbit</p>
                    </div>
                </div>
            </div>

            <!-- BLE Not Supported Warning -->
            <div x-show="btNotSupported" class="mt-4 flex items-center gap-3 p-4 rounded-2xl border border-yellow-500/30 bg-yellow-500/5 text-yellow-600 dark:text-yellow-400">
                <i data-lucide="alert-triangle" class="h-5 w-5 shrink-0"></i>
                <p class="text-xs font-bold">Trình duyệt của bạn không hỗ trợ Web Bluetooth. Vui lòng dùng Chrome hoặc Edge trên máy tính / Android.</p>
            </div>
        </div>
    </div>

    <!-- ===================== CSV IMPORT MODAL ===================== -->
    <div x-show="showCsvModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[110] flex items-center justify-center p-4 bg-background/70 backdrop-blur-md">
        <div @click.away="closeCsvModal()" class="relative w-full max-w-lg bg-card border border-border rounded-[2.5rem] shadow-elevated overflow-hidden">
            <div class="absolute inset-0 gradient-primary opacity-[0.03]"></div>
            <div class="relative p-8">

                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-2xl bg-orange-500/10 text-orange-500 flex items-center justify-center">
                            <i data-lucide="file-up" class="h-5 w-5"></i>
                        </div>
                        <div>
                            <h4 class="text-lg font-black uppercase tracking-tight">Import báo cáo sức khỏe</h4>
                            <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest">Tải lên bất kỳ file báo cáo nào — CSV, Excel, JSON, TXT</p>
                        </div>
                    </div>
                    <button @click="closeCsvModal()" class="h-9 w-9 flex items-center justify-center rounded-full hover:bg-muted transition-colors text-muted-foreground">
                        <i data-lucide="x" class="h-4 w-4"></i>
                    </button>
                </div>

                <!-- Hỗ trợ -->
                <div class="mb-5 p-4 rounded-2xl bg-muted/30 border border-border text-xs font-bold text-muted-foreground flex flex-wrap gap-x-4 gap-y-1">
                    <span class="font-black text-foreground">Hỗ trợ:</span>
                    <span>📄 CSV</span>
                    <span>📊 Excel (.xlsx)</span>
                    <span>📃 JSON</span>
                    <span>📄 TXT</span>
                    <span>📱 Mi Fitness · Zepp · Garmin · Fitbit</span>
                </div>

                <!-- Drop Zone -->
                <div x-show="!csvPreview"
                     @dragover.prevent="csvDragOver = true"
                     @dragleave="csvDragOver = false"
                     @drop.prevent="handleCsvDrop($event)"
                     :class="csvDragOver ? 'border-orange-500 bg-orange-500/10' : 'border-border hover:border-orange-500/50 hover:bg-orange-500/5'"
                     class="border-2 border-dashed rounded-2xl p-8 text-center transition-all cursor-pointer"
                     @click="$refs.csvInput.click()">
                    <i data-lucide="upload-cloud" class="h-10 w-10 mx-auto mb-3 text-muted-foreground"></i>
                    <p class="text-sm font-black text-foreground">Kéo thả file vào đây</p>
                    <p class="text-xs font-bold text-muted-foreground mt-1">CSV · Excel (.xlsx) · JSON · TXT — hoặc bấm để chọn file</p>
                    <input type="file" x-ref="csvInput" accept="*" class="hidden" @change="handleCsvFile($event)">
                </div>

                <!-- Preview Table -->
                <div x-show="csvPreview" class="space-y-4">
                    <p class="text-[10px] font-black uppercase tracking-widest text-success">✓ Đã phân tích — Dữ liệu mới nhất:</p>
                    <div class="grid grid-cols-2 gap-3">
                        <template x-for="(val, key) in csvPreview" :key="key">
                            <div x-show="val !== null" class="flex items-center gap-3 p-3 rounded-xl bg-muted/30 border border-border">
                                <div class="h-8 w-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center shrink-0 text-sm" x-text="csvIcons[key] || '📊'"></div>
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-widest text-muted-foreground" x-text="csvLabels[key] || key"></p>
                                    <p class="text-sm font-black text-foreground" x-text="val + ' ' + (csvUnits[key] || '')"></p>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="flex gap-3 mt-2">
                        <button @click="csvPreview = null; csvDragOver = false" class="flex-1 rounded-2xl border border-border bg-muted/20 py-3 text-[10px] font-black uppercase tracking-widest text-foreground hover:bg-muted/40 transition-all">
                            Chọn file khác
                        </button>
                        <button @click="applyCsvData()" class="flex-1 rounded-2xl gradient-primary py-3 text-[10px] font-black uppercase tracking-widest text-white shadow-glow hover:scale-[1.02] transition-all">
                            ✓ Áp dụng chỉ số
                        </button>
                    </div>
                </div>

                <p x-show="csvError" class="mt-3 text-xs font-bold text-destructive" x-text="csvError"></p>
            </div>
        </div>
    </div>

    <!-- QR MODAL -->
    <div x-show="showQrModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[110] flex items-center justify-center p-4 bg-background/70 backdrop-blur-md">
        <div @click.away="closeQrModal()" class="relative w-full max-w-sm bg-card border border-border rounded-[2.5rem] shadow-elevated overflow-hidden">
            <div class="absolute inset-0 gradient-primary opacity-[0.03]"></div>
            <div class="relative p-8">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h4 class="text-lg font-black uppercase tracking-tight">Quét mã QR</h4>
                        <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest">Mở bằng điện thoại để đồng bộ</p>
                    </div>
                    <button @click="closeQrModal()" class="h-9 w-9 flex items-center justify-center rounded-full hover:bg-muted transition-colors text-muted-foreground">
                        <i data-lucide="x" class="h-4 w-4"></i>
                    </button>
                </div>

                <!-- QR Code -->
                <div class="flex items-center justify-center">
                    <div x-show="qrLoading" class="h-48 w-48 flex items-center justify-center">
                        <i data-lucide="loader-2" class="h-8 w-8 animate-spin text-primary"></i>
                    </div>
                    <div id="qr-canvas-wrap" x-show="!qrLoading" class="p-3 bg-white rounded-2xl shadow-lg"></div>
                </div>

                <!-- Polling Status -->
                <div class="mt-6 flex items-center justify-center gap-2">
                    <span x-show="!qrConnected" class="h-2 w-2 rounded-full bg-primary animate-pulse"></span>
                    <span x-show="qrConnected" class="h-2 w-2 rounded-full bg-success"></span>
                    <p class="text-[10px] font-black uppercase tracking-widest"
                       :class="qrConnected ? 'text-success' : 'text-muted-foreground'"
                       x-text="qrConnected ? '✓ Đồng bộ thành công!' : 'Đang chờ điện thoại quét...'">
                    </p>
                </div>

                <!-- Timer -->
                <p x-show="!qrConnected" class="mt-2 text-center text-[10px] font-bold text-muted-foreground">
                    Mã hết hạn sau <span x-text="qrTimer" class="text-primary font-black"></span>
                </p>
            </div>
        </div>
    </div>

    <!-- Health Metrics Grid (Live Data) -->
    <div class="grid grid-cols-2 gap-6 md:grid-cols-3 lg:grid-cols-5">
        @php
            $metrics = [
                ['id' => 'heart_rate', 'label' => 'Nhịp tim', 'unit' => 'bpm', 'icon' => 'heart', 'color' => 'from-rose-500 to-pink-400'],
                ['id' => 'spo2', 'label' => 'SpO₂', 'unit' => '%', 'icon' => 'wind', 'color' => 'from-blue-500 to-cyan-400'],
                ['id' => 'weight', 'label' => 'Cân nặng', 'unit' => 'kg', 'icon' => 'scale', 'color' => 'from-violet-500 to-purple-400'],
                ['id' => 'water_intake', 'label' => 'Nước', 'unit' => 'L', 'icon' => 'droplets', 'color' => 'from-sky-500 to-blue-400'],
                ['id' => 'sleep_hours', 'label' => 'Giấc ngủ', 'unit' => 'h', 'icon' => 'moon', 'color' => 'from-indigo-500 to-violet-400'],
            ];
        @endphp

        @foreach($metrics as $m)
        <div class="bg-card border border-border rounded-3xl p-6 shadow-soft transition-all hover:border-primary/30 group">
            <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br {{ $m['color'] }} text-white shadow-lg transition-transform group-hover:scale-110">
                <i data-lucide="{{ $m['icon'] }}" class="h-6 w-6"></i>
            </div>
            <p class="mt-4 text-[10px] font-black uppercase tracking-widest text-muted-foreground">{{ $m['label'] }}</p>
            <p class="mt-1 text-3xl font-black font-display tracking-tight text-foreground">
                <span x-text="data.{{ $m['id'] }} || '—'"></span>
                <span class="text-xs font-bold text-muted-foreground uppercase tracking-widest ml-1">{{ $m['unit'] }}</span>
            </p>
        </div>
        @endforeach
    </div>

    <!-- Charts area -->
    <div class="grid gap-8 lg:grid-cols-3 pt-4">
        <div class="bg-card border border-border rounded-[2.5rem] p-8 shadow-soft lg:col-span-2 min-h-[400px] flex flex-col relative overflow-hidden group">
            <div class="absolute inset-0 gradient-primary opacity-[0.02] group-hover:opacity-[0.05] transition-opacity"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-lg font-black uppercase tracking-widest text-foreground">Biểu đồ nhịp tim</h3>
                        <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest">Dữ liệu 24 giờ qua</p>
                    </div>
                    <div class="flex gap-2">
                        <span class="h-2 w-2 rounded-full bg-primary animate-pulse"></span>
                        <span class="text-[10px] font-black text-primary uppercase">Live</span>
                    </div>
                </div>
                <div class="h-64 flex items-center justify-center border-2 border-dashed border-border rounded-[2rem] bg-muted/10">
                    <p class="text-xs font-bold text-muted-foreground uppercase tracking-widest italic">Hệ thống đang phân tích chỉ số...</p>
                </div>
            </div>
        </div>

        <div class="bg-card border border-border rounded-[2.5rem] p-8 shadow-soft min-h-[400px] flex flex-col group">
             <div class="flex items-center justify-between mb-8">
                <h3 class="text-lg font-black uppercase tracking-widest text-foreground">Giấc ngủ</h3>
                <i data-lucide="moon" class="h-5 w-5 text-indigo-400"></i>
            </div>
            <div class="flex-1 space-y-6">
                <div class="space-y-2">
                    <div class="flex justify-between text-[10px] font-black uppercase tracking-widest">
                        <span class="text-muted-foreground">Chất lượng</span>
                        <span class="text-success">Tốt (85%)</span>
                    </div>
                    <div class="h-3 w-full bg-muted/40 rounded-full overflow-hidden">
                        <div class="h-full bg-success w-[85%] rounded-full shadow-glow-success"></div>
                    </div>
                </div>
                <div class="p-6 rounded-2xl bg-indigo-500/5 border border-indigo-500/10 space-y-3">
                    <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest">AI Phân tích</p>
                    <p class="text-xs font-bold text-foreground/80 leading-relaxed italic">"Dữ liệu giấc ngủ đêm qua cho thấy bạn đang phục hồi tốt. Hãy duy trì khung giờ ngủ này."</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
<script>
function healthTracker() {
    return {
        showModal: false,
        saving: false,

        // --- Bluetooth State ---
        btConnecting: false,
        btConnected: false,
        btNotSupported: false,
        btDeviceName: '',
        btCharacteristic: null,

        // --- QR State ---
        showQrModal: false,
        qrLoading: false,
        qrConnected: false,
        qrToken: null,
        qrTimer: '6:00',
        _qrPollInterval: null,
        _qrCountdownInterval: null,

        // --- CSV Import State ---
        showCsvModal: false,
        csvPreview: null,
        csvDragOver: false,
        csvError: null,
        csvLabels: {
            heart_rate: 'Nhịp tim', spo2: 'SpO₂',
            weight: 'Cân nặng', height: 'Chiều cao',
            water_intake: 'Nước uống', sleep_hours: 'Giấc ngủ',
        },
        csvUnits: {
            heart_rate: 'BPM', spo2: '%',
            weight: 'kg', height: 'cm',
            water_intake: 'L', sleep_hours: 'giờ',
        },
        csvIcons: {
            heart_rate: '❤️', spo2: '💨',
            weight: '⚖️', height: '📏',
            water_intake: '💧', sleep_hours: '🌙',
        },

        data: {
            heart_rate: '{{ $user->heart_rate }}',
            spo2: '{{ $user->spo2 }}',
            weight: '{{ $user->weight }}',
            water_intake: '{{ $user->water_intake }}',
            sleep_hours: '{{ $user->sleep_hours }}',
            height: '{{ $user->height }}',
        },

        // =====================
        // CSV IMPORT
        // =====================
        openCsvModal() {
            this.showCsvModal = true;
            this.csvPreview = null;
            this.csvDragOver = false;
            this.csvError = null;
        },

        closeCsvModal() {
            this.showCsvModal = false;
        },

        handleCsvDrop(e) {
            this.csvDragOver = false;
            const file = e.dataTransfer.files[0];
            if (file) this.parseAnyFile(file);
        },

        handleCsvFile(e) {
            const file = e.target.files[0];
            if (file) this.parseAnyFile(file);
        },

        // Main dispatcher — detects format by extension
        parseAnyFile(file) {
            this.csvError = null;
            this.csvPreview = null;
            const ext = file.name.split('.').pop().toLowerCase();

            if (ext === 'xlsx' || ext === 'xls') {
                // Excel — needs SheetJS
                if (typeof XLSX === 'undefined') {
                    this.csvError = 'Thư viện đọc Excel chưa sẵn sàng. Vui lòng thử lại.';
                    return;
                }
                const reader = new FileReader();
                reader.onload = (e) => {
                    const wb = XLSX.read(e.target.result, { type: 'array' });
                    const ws = wb.Sheets[wb.SheetNames[0]];
                    const csvText = XLSX.utils.sheet_to_csv(ws);
                    this.parseDelimitedText(csvText, ',');
                };
                reader.readAsArrayBuffer(file);

            } else if (ext === 'json') {
                const reader = new FileReader();
                reader.onload = (e) => this.parseJsonText(e.target.result);
                reader.readAsText(file, 'UTF-8');

            } else {
                // CSV or TXT — auto-detect delimiter
                const reader = new FileReader();
                reader.onload = (e) => {
                    const text = e.target.result;
                    // Detect delimiter: comma, semicolon, or tab
                    const delim = text.includes('\t') ? '\t' : text.includes(';') ? ';' : ',';
                    this.parseDelimitedText(text, delim);
                };
                reader.readAsText(file, 'UTF-8');
            }
        },

        // Parse CSV / TXT with any delimiter
        parseDelimitedText(text, delim) {
            const ALIASES = {
                heart_rate:   ['heart rate','heart_rate','heartrate','nhịp tim','nhip tim','bpm','pulse','hr'],
                spo2:         ['spo2','spo₂','blood oxygen','oxygen saturation','oxymeter','oxy'],
                weight:       ['weight','cân nặng','can nang','khối lượng','body weight'],
                height:       ['height','chiều cao','chieu cao','stature'],
                water_intake: ['water','water intake','nước','nuoc','nước uống','nuoc uong','hydration','drink'],
                sleep_hours:  ['sleep','sleep hours','giấc ngủ','giac ngu','sleep duration','ngủ','ngu','hour of sleep'],
            };

            const lines = text.trim().split(/\r?\n/).filter(l => l.trim());
            if (lines.length < 2) {
                this.csvError = 'File không có dữ liệu. Vui lòng kiểm tra lại.';
                return;
            }

            const headers = lines[0].split(delim).map(h => h.trim().toLowerCase().replace(/['"]/g, ''));
            const colMap = {};
            headers.forEach((h, idx) => {
                for (const [field, aliases] of Object.entries(ALIASES)) {
                    if (aliases.some(a => h.includes(a)) && !(field in colMap)) colMap[field] = idx;
                }
            });

            if (Object.keys(colMap).length === 0) {
                this.csvError = 'Không nhận dạng được cột nào. Hãy đảm bảo file có tiêu đề cột rõ ràng (ví dụ: heart_rate, weight, spo2...)';
                return;
            }

            const lastLine = lines[lines.length - 1];
            const cols = lastLine.split(delim).map(c => c.trim().replace(/['"]/g, ''));
            const preview = {};
            for (const [field, idx] of Object.entries(colMap)) {
                const num = parseFloat(cols[idx]);
                preview[field] = isNaN(num) ? null : num;
            }
            this.csvPreview = preview;
        },

        // Parse JSON file
        parseJsonText(text) {
            const ALIASES = {
                heart_rate:   ['heart_rate','heartRate','heart rate','nhịp tim','bpm','pulse','hr'],
                spo2:         ['spo2','SpO2','blood_oxygen','oxygen'],
                weight:       ['weight','cân nặng','kg'],
                height:       ['height','chiều cao','cm'],
                water_intake: ['water_intake','water','nước'],
                sleep_hours:  ['sleep_hours','sleep','sleepHours','giấc ngủ'],
            };

            try {
                let obj = JSON.parse(text);
                // If it's an array, take the last item
                if (Array.isArray(obj)) obj = obj[obj.length - 1];

                // Flatten one level deep
                const flat = {};
                const flatten = (o, prefix = '') => {
                    for (const [k, v] of Object.entries(o)) {
                        if (typeof v === 'object' && v !== null && !Array.isArray(v)) {
                            flatten(v, prefix + k + '.');
                        } else {
                            flat[(prefix + k).toLowerCase()] = v;
                        }
                    }
                };
                flatten(obj);

                const preview = {};
                for (const [field, aliases] of Object.entries(ALIASES)) {
                    for (const alias of aliases) {
                        const key = Object.keys(flat).find(k => k.includes(alias.toLowerCase()));
                        if (key !== undefined) {
                            const num = parseFloat(flat[key]);
                            if (!isNaN(num)) { preview[field] = num; break; }
                        }
                    }
                }

                if (Object.keys(preview).length === 0) {
                    this.csvError = 'Không tìm thấy dữ liệu sức khỏe trong file JSON này.';
                    return;
                }
                this.csvPreview = preview;
            } catch(e) {
                this.csvError = 'File JSON không hợp lệ. Vui lòng kiểm tra lại.';
            }
        },

        applyCsvData() {
            if (!this.csvPreview) return;
            for (const [field, val] of Object.entries(this.csvPreview)) {
                if (val !== null) this.data[field] = val;
            }
            this.closeCsvModal();
            this.saveMetrics();
        },

        // =====================
        // QR CONNECT
        // =====================
        async openQrModal() {
            this.showQrModal = true;
            this.qrLoading = true;
            this.qrConnected = false;
            this.qrToken = null;
            document.getElementById('qr-canvas-wrap').innerHTML = '';

            try {
                const res = await fetch('{{ route('health.qr.token') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                const json = await res.json();
                this.qrToken = json.token;

                // Generate QR
                QRCode.toCanvas(document.createElement('canvas'), json.url, { width: 200, margin: 1 }, (err, canvas) => {
                    if (!err) {
                        document.getElementById('qr-canvas-wrap').appendChild(canvas);
                    }
                });

                this.qrLoading = false;

                // Countdown
                let secs = 360;
                this._qrCountdownInterval = setInterval(() => {
                    secs--;
                    const m = Math.floor(secs / 60);
                    const s = secs % 60;
                    this.qrTimer = `${m}:${s.toString().padStart(2,'0')}`;
                    if (secs <= 0) clearInterval(this._qrCountdownInterval);
                }, 1000);

                // Poll for data
                this._qrPollInterval = setInterval(async () => {
                    try {
                        const pollRes = await fetch(`{{ route('health.qr.poll') }}?token=${this.qrToken}`);
                        const pollJson = await pollRes.json();

                        if (pollJson.status === 'ok') {
                            // Merge incoming data
                            Object.assign(this.data, pollJson.data);
                            this.qrConnected = true;
                            clearInterval(this._qrPollInterval);
                            clearInterval(this._qrCountdownInterval);
                            // Auto-close modal after 2s
                            setTimeout(() => { this.closeQrModal(); }, 2000);
                        } else if (pollJson.status === 'expired') {
                            clearInterval(this._qrPollInterval);
                        }
                    } catch(e) {}
                }, 3000);

            } catch(e) {
                this.qrLoading = false;
                console.error('QR error:', e);
            }
        },

        closeQrModal() {
            this.showQrModal = false;
            clearInterval(this._qrPollInterval);
            clearInterval(this._qrCountdownInterval);
        },

        // =====================
        // BLUETOOTH
        // =====================
        async connectBluetooth() {
            if (!navigator.bluetooth) {
                this.btNotSupported = true;
                return;
            }

            if (this.btConnected) {
                if (this.btCharacteristic) {
                    try { await this.btCharacteristic.stopNotifications(); } catch(e) {}
                }
                this.btConnected = false;
                this.btDeviceName = '';
                this.btCharacteristic = null;
                return;
            }

            this.btConnecting = true;
            this.btNotSupported = false;

            try {
                const device = await navigator.bluetooth.requestDevice({
                    filters: [{ services: ['heart_rate'] }],
                    optionalServices: ['battery_service']
                });

                this.btDeviceName = device.name || 'Thiết bị BLE';
                device.addEventListener('gattserverdisconnected', () => {
                    this.btConnected = false;
                    this.btDeviceName = '';
                    this.btCharacteristic = null;
                });

                const server  = await device.gatt.connect();
                const service = await server.getPrimaryService('heart_rate');
                const char    = await service.getCharacteristic('heart_rate_measurement');
                this.btCharacteristic = char;
                await char.startNotifications();

                char.addEventListener('characteristicvaluechanged', (event) => {
                    const value = event.target.value;
                    const flags = value.getUint8(0);
                    const heartRate = (flags & 0x1)
                        ? value.getUint16(1, true)
                        : value.getUint8(1);
                    this.data.heart_rate = heartRate;
                    this.saveMetrics();
                });

                this.btConnected = true;
            } catch (err) {
                if (err.name !== 'NotFoundError') console.error('Bluetooth error:', err);
            } finally {
                this.btConnecting = false;
                if (window.lucide) lucide.createIcons();
            }
        },

        async saveMetrics() {
            this.saving = true;
            const form = document.getElementById('health-form');
            const formData = new FormData(form);

            try {
                await fetch('{{ route('health.update') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
            } catch (e) {
                console.error('Update failed', e);
            } finally {
                setTimeout(() => { this.saving = false; }, 500);
            }
        }
    }
}
</script>
@endsection

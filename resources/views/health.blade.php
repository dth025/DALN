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
        
        <div class="flex flex-wrap items-center gap-3">
            <div x-show="saving" class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-primary/10 border border-primary/20 text-[10px] font-black uppercase text-primary animate-pulse">
                <i data-lucide="refresh-cw" class="h-3 w-3 animate-spin"></i> Đang đồng bộ...
            </div>

            <div class="relative flex items-center">
                <input type="date" x-model="selectedDate" @change="updateChartsData()" class="bg-card border border-border text-foreground px-4 py-2.5 rounded-2xl font-bold text-xs uppercase tracking-widest outline-none focus:border-primary/40 cursor-pointer h-[42px] appearance-none">
            </div>

            <button @click="showModal = true" class="flex items-center gap-2 rounded-2xl gradient-primary px-6 py-2.5 h-[42px] text-xs font-black uppercase tracking-widest text-white shadow-glow hover:scale-[1.02] active:scale-95 transition-all">
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
             class="relative w-full max-w-5xl bg-card border border-border rounded-[3rem] shadow-elevated overflow-hidden animate-in zoom-in duration-300">
            
            <div class="absolute inset-0 gradient-primary opacity-[0.03] -z-10"></div>
            
            <!-- Modal Header -->
            <div class="px-10 pt-10 pb-6 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-primary/10 text-primary">
                        <i data-lucide="edit-3" class="h-5 w-5"></i>
                    </div>
                    <div>
                        <h4 class="text-xl font-black uppercase tracking-tight text-foreground">Cập nhật chỉ số mới</h4>
                        <template x-if="!isFutureDate">
                            <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest">Dữ liệu sẽ được lưu tự động</p>
                        </template>
                        <template x-if="isFutureDate">
                            <div class="flex items-center gap-1 text-rose-500">
                                <i data-lucide="alert-circle" class="h-3 w-3"></i>
                                <p class="text-[10px] font-black uppercase tracking-widest">Lỗi: Chưa đến ngày này</p>
                            </div>
                        </template>
                    </div>
                </div>
                <button @click="showModal = false" class="h-10 w-10 flex items-center justify-center rounded-full hover:bg-muted transition-colors text-muted-foreground">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="px-10 pb-10">
                <div class="mb-8">
                    <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground ml-1 mb-2 block">Ngày ghi nhận dữ liệu</label>
                    <input type="date" name="recorded_at" x-model="selectedDate" @change="updateChartsData()" class="bg-card border border-border text-foreground px-4 py-3 rounded-xl font-bold text-sm uppercase tracking-widest outline-none focus:border-primary/40 cursor-pointer w-full md:w-1/3">
                </div>

                <form id="health-form" @input.debounce.500ms="saveMetrics()" class="grid gap-6 lg:grid-cols-4">
                    @csrf
                    <!-- Group 1: Vital Signs -->
                    <div class="p-6 rounded-[2rem] bg-muted/20 border border-border/50 space-y-5 lg:col-span-1">
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-primary">Chỉ số sinh tồn</p>
                        <div class="space-y-4">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground ml-1">Nhịp tim (bpm)</label>
                                <div class="relative">
                                    <input type="number" name="heart_rate" x-model="data.heart_rate" @input="updateLiveChart('heart')" class="w-full bg-background border border-border focus:border-primary/40 p-4 rounded-xl font-black text-lg outline-none transition-all pl-12">
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
                    <div class="p-6 rounded-[2rem] bg-muted/20 border border-border/50 space-y-5 lg:col-span-2">
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-primary">Chỉ số cơ thể</p>
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground ml-1">Cân nặng (kg)</label>
                                    <input type="number" step="0.1" name="weight" x-model="data.weight" @input="updateLiveChart('weight')" class="w-full bg-background border border-border focus:border-primary/40 p-4 rounded-xl font-black text-lg outline-none transition-all text-center">
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
                                    <input type="number" step="0.5" name="sleep_hours" x-model="data.sleep_hours" @input="updateLiveChart('sleep')" class="w-full bg-background border border-border focus:border-primary/40 p-4 rounded-xl font-black text-lg outline-none transition-all text-center">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Group 3: Activity Metrics -->
                    <div class="p-6 rounded-[2rem] bg-muted/20 border border-border/50 space-y-5 lg:col-span-1">
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-primary">Hoạt động</p>
                        <div class="space-y-4">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground ml-1">Calo tiêu thụ (kcal)</label>
                                <div class="relative">
                                    <input type="number" name="calories" x-model="data.calories" @input="updateLiveChart('calorie')" class="w-full bg-background border border-border focus:border-primary/40 p-4 rounded-xl font-black text-lg outline-none transition-all pl-12">
                                    <i data-lucide="flame" class="absolute left-4 top-1/2 -translate-y-1/2 h-4 w-4 text-orange-500"></i>
                                </div>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground ml-1">Bước chân (bước)</label>
                                <div class="relative">
                                    <input type="number" name="steps" x-model="data.steps" class="w-full bg-background border border-border focus:border-primary/40 p-4 rounded-xl font-black text-lg outline-none transition-all pl-12">
                                    <i data-lucide="footprints" class="absolute left-4 top-1/2 -translate-y-1/2 h-4 w-4 text-emerald-500"></i>
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
    <div class="grid grid-cols-2 gap-4 md:grid-cols-4 lg:grid-cols-7">
        @php
            $metrics = [
                ['id' => 'heart_rate', 'label' => 'Nhịp tim', 'unit' => 'bpm', 'icon' => 'heart', 'color' => 'from-rose-500 to-pink-400'],
                ['id' => 'spo2', 'label' => 'SpO₂', 'unit' => '%', 'icon' => 'wind', 'color' => 'from-blue-500 to-cyan-400'],
                ['id' => 'weight', 'label' => 'Cân nặng', 'unit' => 'kg', 'icon' => 'scale', 'color' => 'from-violet-500 to-purple-400'],
                ['id' => 'steps', 'label' => 'Bước chân', 'unit' => 'bước', 'icon' => 'footprints', 'color' => 'from-emerald-500 to-teal-400'],
                ['id' => 'calories', 'label' => 'Calo', 'unit' => 'kcal', 'icon' => 'flame', 'color' => 'from-orange-500 to-amber-400'],
                ['id' => 'water_intake', 'label' => 'Nước', 'unit' => 'L', 'icon' => 'droplets', 'color' => 'from-sky-500 to-blue-400'],
                ['id' => 'sleep_hours', 'label' => 'Giấc ngủ', 'unit' => 'h', 'icon' => 'moon', 'color' => 'from-indigo-500 to-violet-400'],
            ];
        @endphp

        @foreach($metrics as $m)
        <div class="bg-card border border-border rounded-3xl p-4 shadow-soft transition-all hover:border-primary/30 group text-center">
            <div class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br {{ $m['color'] }} text-white shadow-lg transition-transform group-hover:scale-110">
                <i data-lucide="{{ $m['icon'] }}" class="h-5 w-5"></i>
            </div>
            <p class="mt-3 text-[9px] font-black uppercase tracking-widest text-muted-foreground line-clamp-1">{{ $m['label'] }}</p>
            <p class="mt-1 text-xl font-black font-display tracking-tight text-foreground">
                <span x-text="['steps', 'calories'].includes('{{ $m['id'] }}') ? (parseInt(data.{{ $m['id'] }}) || 0).toLocaleString() : (data.{{ $m['id'] }})"></span>
                <span class="text-[8px] font-bold text-muted-foreground uppercase tracking-widest ml-0.5">{{ $m['unit'] }}</span>
            </p>
        </div>
        @endforeach
    </div>

    <!-- Charts area -->
    <div class="grid gap-8 lg:grid-cols-3 pt-4">
        <!-- Biểu đồ nhịp tim -->
        <div class="bg-card border border-border rounded-[2.5rem] p-8 shadow-soft min-h-[400px] flex flex-col relative overflow-hidden group">
            <div class="absolute inset-0 gradient-primary opacity-[0.02] group-hover:opacity-[0.05] transition-opacity"></div>
            <div class="relative z-10 flex flex-col h-full">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-sm font-black uppercase tracking-widest text-muted-foreground">Biểu đồ nhịp tim</h3>
                        <p class="text-2xl font-black text-foreground mt-1">
                            <span x-text="data.heart_rate || '—'"></span> <span class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest">bpm</span>
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <span class="h-2 w-2 rounded-full bg-primary animate-pulse mt-0.5"></span>
                        <span class="text-[10px] font-black text-primary uppercase">Live</span>
                    </div>
                </div>
                <div class="flex-1 relative w-full border-border rounded-[2rem] min-h-[250px] flex items-center justify-center">
                    <canvas id="hrChart" x-show="btConnected"></canvas>
                    <div x-show="!btConnected" class="flex flex-col items-center justify-center text-center p-6 space-y-4">
                        <div class="h-16 w-16 rounded-full bg-rose-500/10 flex items-center justify-center text-rose-500 animate-pulse">
                            <i data-lucide="bluetooth-off" class="h-8 w-8"></i>
                        </div>
                        <div>
                            <p class="text-sm font-black uppercase tracking-widest text-foreground">Chưa kết nối thiết bị</p>
                            <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest mt-1">Vui lòng kết nối để theo dõi nhịp tim</p>
                        </div>
                        <button @click="connectBluetooth()" class="px-6 py-2 rounded-xl bg-primary text-white text-[10px] font-black uppercase tracking-widest shadow-glow hover:scale-105 transition-transform">Kết nối Bluetooth</button>
                    </div>
                </div>

                <!-- AI Insight Area -->
                <div class="mt-6 pt-6 border-t border-border/50">
                    <template x-if="!aiState.hr.text && !aiState.hr.loading">
                        <button @click="runAiAnalysis('hr')" class="flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-primary hover:text-primary/80 transition-colors">
                            <i data-lucide="sparkles" class="h-3 w-3"></i>
                            AI Phân tích nhịp tim
                        </button>
                    </template>
                    <div x-show="aiState.hr.loading" class="flex items-center gap-3 text-muted-foreground">
                        <div class="flex gap-1">
                            <span class="w-1 h-1 rounded-full bg-primary animate-bounce"></span>
                            <span class="w-1 h-1 rounded-full bg-primary animate-bounce [animation-delay:-0.15s]"></span>
                            <span class="w-1 h-1 rounded-full bg-primary animate-bounce [animation-delay:-0.3s]"></span>
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-widest">HealthSync AI đang phân tích...</span>
                    </div>
                    <div x-show="aiState.hr.text" x-transition class="p-4 rounded-2xl bg-primary/5 border border-primary/10">
                        <div class="flex items-start gap-3">
                            <div class="mt-1 h-5 w-5 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                                <i data-lucide="brain-circuit" class="h-3 w-3"></i>
                            </div>
                            <p class="text-xs leading-relaxed font-medium text-foreground/90" x-text="aiState.hr.text"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Giấc ngủ -->
        <div class="bg-card border border-border rounded-[2.5rem] p-8 shadow-soft min-h-[400px] flex flex-col group">
             <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-black uppercase tracking-widest text-muted-foreground">Giấc ngủ tuần này</h3>
                <i data-lucide="moon" class="h-5 w-5 text-indigo-400"></i>
            </div>
            <div class="mb-6">
                <p class="text-3xl font-black font-display tracking-tight text-foreground">
                    <span x-text="data.sleep_hours || '0'"></span> <span class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest ml-1">giờ hôm nay</span>
                </p>
            </div>
            
            <div class="flex-1 relative w-full border-border rounded-[2rem] mt-2 min-h-[200px]">
                <canvas id="sleepChart"></canvas>
            </div>

            <!-- AI Insight Area -->
            <div class="mt-6 pt-6 border-t border-border/50">
                <template x-if="!aiState.sleep.text && !aiState.sleep.loading">
                    <button @click="runAiAnalysis('sleep')" class="flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-primary hover:text-primary/80 transition-colors">
                        <i data-lucide="sparkles" class="h-3 w-3"></i>
                        AI Phân tích giấc ngủ
                    </button>
                </template>
                <div x-show="aiState.sleep.loading" class="flex items-center gap-3 text-muted-foreground">
                    <div class="flex gap-1">
                        <span class="w-1 h-1 rounded-full bg-primary animate-bounce"></span>
                        <span class="w-1 h-1 rounded-full bg-primary animate-bounce [animation-delay:-0.15s]"></span>
                        <span class="w-1 h-1 rounded-full bg-primary animate-bounce [animation-delay:-0.3s]"></span>
                    </div>
                    <span class="text-[10px] font-bold uppercase tracking-widest">Đang phân tích giấc ngủ...</span>
                </div>
                <div x-show="aiState.sleep.text" x-transition class="p-4 rounded-2xl bg-primary/5 border border-primary/10">
                    <div class="flex items-start gap-3">
                        <div class="mt-1 h-5 w-5 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                            <i data-lucide="brain-circuit" class="h-3 w-3"></i>
                        </div>
                        <p class="text-xs leading-relaxed font-medium text-foreground/90" x-text="aiState.sleep.text"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cân nặng -->
        <div class="bg-card border border-border rounded-[2.5rem] p-8 shadow-soft min-h-[400px] flex flex-col group">
             <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-black uppercase tracking-widest text-muted-foreground">Cân nặng tuần này</h3>
                <i data-lucide="scale" class="h-5 w-5 text-cyan-400"></i>
            </div>
            <div class="mb-6">
                <p class="text-3xl font-black font-display tracking-tight text-foreground">
                    <span x-text="data.weight || '0'"></span> <span class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest ml-1">kg hôm nay</span>
                </p>
            </div>
            
            <div class="flex-1 relative w-full border-border rounded-[2rem] mt-2 min-h-[200px]">
                <canvas id="weightChart"></canvas>
            </div>

            <!-- AI Insight Area -->
            <div class="mt-6 pt-6 border-t border-border/50">
                <template x-if="!aiState.weight.text && !aiState.weight.loading">
                    <button @click="runAiAnalysis('weight')" class="flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-primary hover:text-primary/80 transition-colors">
                        <i data-lucide="sparkles" class="h-3 w-3"></i>
                        AI Phân tích cân nặng
                    </button>
                </template>
                <div x-show="aiState.weight.loading" class="flex items-center gap-3 text-muted-foreground">
                    <div class="flex gap-1">
                        <span class="w-1 h-1 rounded-full bg-primary animate-bounce"></span>
                        <span class="w-1 h-1 rounded-full bg-primary animate-bounce [animation-delay:-0.15s]"></span>
                        <span class="w-1 h-1 rounded-full bg-primary animate-bounce [animation-delay:-0.3s]"></span>
                    </div>
                    <span class="text-[10px] font-bold uppercase tracking-widest">Đang phân tích xu hướng cân nặng...</span>
                </div>
                <div x-show="aiState.weight.text" x-transition class="p-4 rounded-2xl bg-primary/5 border border-primary/10">
                    <div class="flex items-start gap-3">
                        <div class="mt-1 h-5 w-5 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                            <i data-lucide="brain-circuit" class="h-3 w-3"></i>
                        </div>
                        <p class="text-xs leading-relaxed font-medium text-foreground/90" x-text="aiState.weight.text"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 2: Calorie, Steps, and WHO Health Score -->
    <div class="grid gap-8 lg:grid-cols-3 pt-8">
        <!-- Calorie Chart -->
        <div class="bg-card border border-border rounded-[2.5rem] p-8 shadow-soft min-h-[400px] flex flex-col group">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-black uppercase tracking-widest text-muted-foreground">Lượng calo tuần này</h3>
                <i data-lucide="flame" class="h-5 w-5 text-orange-400"></i>
            </div>
            <div class="mb-6">
                <p class="text-3xl font-black font-display tracking-tight text-foreground">
                    <span x-text="data.calories || '0'"></span> <span class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest ml-1">kcal tiêu thụ</span>
                </p>
            </div>
            
            <div class="flex-1 relative w-full border-border rounded-[2rem] mt-2 min-h-[250px]">
                <canvas id="calorieChart"></canvas>
            </div>

            <!-- AI Insight Area -->
            <div class="mt-6 pt-6 border-t border-border/50">
                <template x-if="!aiState.calories.text && !aiState.calories.loading">
                    <button @click="runAiAnalysis('calories')" class="flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-primary hover:text-primary/80 transition-colors">
                        <i data-lucide="sparkles" class="h-3 w-3"></i>
                        AI Phân tích dinh dưỡng
                    </button>
                </template>
                <div x-show="aiState.calories.loading" class="flex items-center gap-3 text-muted-foreground">
                    <div class="flex gap-1">
                        <span class="w-1 h-1 rounded-full bg-primary animate-bounce"></span>
                        <span class="w-1 h-1 rounded-full bg-primary animate-bounce [animation-delay:-0.15s]"></span>
                        <span class="w-1 h-1 rounded-full bg-primary animate-bounce [animation-delay:-0.3s]"></span>
                    </div>
                    <span class="text-[10px] font-bold uppercase tracking-widest">Đang phân tích calo...</span>
                </div>
                <div x-show="aiState.calories.text" x-transition class="p-4 rounded-2xl bg-primary/5 border border-primary/10">
                    <div class="flex items-start gap-3">
                        <div class="mt-1 h-5 w-5 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                            <i data-lucide="brain-circuit" class="h-3 w-3"></i>
                        </div>
                        <p class="text-xs leading-relaxed font-medium text-foreground/90" x-text="aiState.calories.text"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step Chart -->
        <div class="bg-card border border-border rounded-[2.5rem] p-8 shadow-soft min-h-[400px] flex flex-col group">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-black uppercase tracking-widest text-muted-foreground">Số bước chân tuần này</h3>
                <i data-lucide="footprints" class="h-5 w-5 text-emerald-400"></i>
            </div>
            <div class="mb-6">
                <p class="text-3xl font-black font-display tracking-tight text-foreground">
                    <span x-text="parseInt(data.steps).toLocaleString() || '0'"></span> <span class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest ml-1">bước hôm nay</span>
                </p>
            </div>
            
            <div class="flex-1 relative w-full border-border rounded-[2rem] mt-2 min-h-[250px]">
                <canvas id="stepChart"></canvas>
            </div>

            <!-- AI Insight Area -->
            <div class="mt-6 pt-6 border-t border-border/50">
                <template x-if="!aiState.steps.text && !aiState.steps.loading">
                    <button @click="runAiAnalysis('steps')" class="flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-primary hover:text-primary/80 transition-colors">
                        <i data-lucide="sparkles" class="h-3 w-3"></i>
                        AI Phân tích vận động
                    </button>
                </template>
                <div x-show="aiState.steps.loading" class="flex items-center gap-3 text-muted-foreground">
                    <div class="flex gap-1">
                        <span class="w-1 h-1 rounded-full bg-primary animate-bounce"></span>
                        <span class="w-1 h-1 rounded-full bg-primary animate-bounce [animation-delay:-0.15s]"></span>
                        <span class="w-1 h-1 rounded-full bg-primary animate-bounce [animation-delay:-0.3s]"></span>
                    </div>
                    <span class="text-[10px] font-bold uppercase tracking-widest">Đang phân tích vận động...</span>
                </div>
                <div x-show="aiState.steps.text" x-transition class="p-4 rounded-2xl bg-primary/5 border border-primary/10">
                    <div class="flex items-start gap-3">
                        <div class="mt-1 h-5 w-5 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                            <i data-lucide="brain-circuit" class="h-3 w-3"></i>
                        </div>
                        <p class="text-xs leading-relaxed font-medium text-foreground/90" x-text="aiState.steps.text"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- WHO Health Score Card -->
        <div class="bg-card border border-border rounded-[2.5rem] p-8 shadow-soft flex flex-col items-center justify-center text-center space-y-6 relative overflow-hidden group">
            <!-- Background Decoration -->
            <div class="absolute -top-24 -right-24 w-48 h-48 bg-primary/10 blur-[80px] rounded-full group-hover:bg-primary/20 transition-all duration-700"></div>
            
            <div class="space-y-1">
                <h3 class="text-xs font-black uppercase tracking-[0.3em] text-primary">Điểm sức khỏe WHO</h3>
                <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest">Tiêu chuẩn quốc tế</p>
            </div>

            <!-- Health Score Gauge -->
            <div class="relative flex items-center justify-center">
                <svg class="w-48 h-48 transform -rotate-90">
                    <circle cx="96" cy="96" r="88" stroke="currentColor" stroke-width="12" fill="transparent" class="text-muted/10" />
                    <circle cx="96" cy="96" r="88" stroke="currentColor" stroke-width="12" fill="transparent"
                        stroke-dasharray="553"
                        :stroke-dashoffset="553 - (553 * calculateHealthScore() / 100)"
                        class="text-primary transition-all duration-1000 ease-out"
                        stroke-linecap="round" />
                </svg>
                <div class="absolute flex flex-col items-center">
                    <span class="text-5xl font-black font-display tracking-tighter text-foreground" x-text="calculateHealthScore()"></span>
                    <span class="text-[10px] font-black uppercase tracking-widest text-primary mt-1" 
                        x-text="calculateHealthScore() >= 90 ? 'Xuất sắc' : calculateHealthScore() >= 75 ? 'Tốt' : 'Trung bình'"></span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 w-full pt-4">
                <div class="p-4 rounded-3xl bg-muted/20 border border-border/50">
                    <p class="text-[10px] font-black uppercase tracking-widest text-muted-foreground mb-1">Cơ thể</p>
                    <p class="text-sm font-black text-foreground" x-text="(data.weight / ((data.height/100)*(data.height/100)) || 0).toFixed(1) + ' BMI'"></p>
                </div>
                <div class="p-4 rounded-3xl bg-muted/20 border border-border/50">
                    <p class="text-[10px] font-black uppercase tracking-widest text-muted-foreground mb-1">Mục tiêu</p>
                    <p class="text-sm font-black text-foreground" x-text="Math.round(calculateHealthScore()/100 * 10) + '/10'"></p>
                </div>
            </div>

            <p class="text-[10px] font-bold text-muted-foreground/60 italic leading-relaxed">
                * Công thức dựa trên khuyến nghị vận động (150-300p/tuần), giấc ngủ và BMI của WHO.
            </p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function healthTracker() {
    return {
        chartInstance: null,
        sleepChartInstance: null,
        weightChartInstance: null,
        calorieChartInstance: null,
        stepChartInstance: null,
        
        isSyncing: false,
        isFutureDate: false,
        selectedDate: (new Date().getFullYear()) + '-' + ((new Date().getMonth() + 1).toString().padStart(2, '0')) + '-' + (new Date().getDate().toString().padStart(2, '0')),
        history: @json($history->toArray() ?: (object)[]), 

        data: {
            heart_rate: '{{ $user->heart_rate ?? "" }}',
            spo2: '{{ $user->spo2 ?? "" }}',
            weight: '{{ $user->weight ?? "" }}',
            water_intake: '{{ $user->water_intake ?? "" }}',
            sleep_hours: '{{ $user->sleep_hours ?? "" }}',
            height: '{{ $user->height ?? "" }}',
            calories: '{{ $user->calories ?? "" }}',
            steps: '{{ $user->steps ?? "" }}',
        },

        // AI Analysis State
        aiState: {
            hr: { loading: false, text: '' },
            sleep: { loading: false, text: '' },
            weight: { loading: false, text: '' },
            calories: { loading: false, text: '' },
            steps: { loading: false, text: '' }
        },

        formatDate(date) {
            const y = date.getFullYear();
            const m = (date.getMonth() + 1).toString().padStart(2, '0');
            const d = date.getDate().toString().padStart(2, '0');
            return `${y}-${m}-${d}`;
        },

        getWeekDays() {
            const parts = this.selectedDate.split('-');
            const year = parseInt(parts[0]);
            const month = parseInt(parts[1]) - 1;
            const day = parseInt(parts[2]);
            
            // Dùng giờ trưa (12:00) để tránh lỗi nhảy ngày do múi giờ hoặc DST
            const curr = new Date(year, month, day, 12, 0, 0);
            const dayOfWeek = curr.getDay(); // 0 (CN) đến 6 (T7)
            
            // Thứ 2 là ngày bắt đầu tuần
            const diff = curr.getDate() - (dayOfWeek === 0 ? 6 : dayOfWeek - 1);
            
            const days = [];
            for (let i = 0; i < 7; i++) {
                const d = new Date(year, month, diff + i);
                const y = d.getFullYear();
                const m = (d.getMonth() + 1).toString().padStart(2, '0');
                const dd = d.getDate().toString().padStart(2, '0');
                days.push(`${y}-${m}-${dd}`);
            }
            return days; 
        },

        init() {
            // Chuẩn hóa history: chỉ giữ lại 10 ký tự đầu của key (YYYY-MM-DD)
            if (this.history) {
                const normalized = {};
                Object.keys(this.history).forEach(k => {
                    const cleanKey = k.substring(0, 10);
                    normalized[cleanKey] = this.history[k];
                });
                this.history = normalized;
            }

            this.initChart();
            this.initSleepChart();
            this.initWeightChart();
            this.initCalorieChart();
            this.initStepChart();
            this.$nextTick(() => this.refreshCharts()); 
            
            // Theo dõi data: Chỉ cập nhật lịch sử nếu thực sự có sự thay đổi giá trị
            this.$watch('data', (newVal, oldVal) => {
                this.updateLiveChart();
            }, { deep: true });

            this.$watch('selectedDate', () => {
                this.updateChartsData();
            });
        },

        initChart() {
            const ctx = document.getElementById('hrChart');
            if (!ctx) return;

            const weekDates = this.getWeekDays();
            const labels = ["T2", "T3", "T4", "T5", "T6", "T7", "CN"];
            const dataValues = weekDates.map(d => this.history[d] ? this.history[d].heart_rate : null);

            const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 250);
            gradient.addColorStop(0, 'rgba(244, 63, 94, 0.4)'); // rose-500 with opacity
            gradient.addColorStop(1, 'rgba(244, 63, 94, 0)');

            this.chartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Nhịp tim (bpm)',
                        data: dataValues,
                        borderColor: '#f43f5e', // rose-500
                        backgroundColor: gradient,
                        borderWidth: 2,
                        tension: 0.4, // smooth curve
                        fill: true,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: '#f43f5e',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            titleFont: { size: 13, family: 'Inter' },
                            bodyFont: { size: 14, weight: 'bold', family: 'Inter' },
                            displayColors: false,
                            cornerRadius: 8,
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false, drawBorder: false },
                            ticks: { color: '#888', font: { size: 12, family: 'Inter' } }
                        },
                        y: {
                            grid: {
                                color: 'rgba(150, 150, 150, 0.15)',
                                drawBorder: false,
                                borderDash: [5, 5]
                            },
                            ticks: { color: '#888', font: { size: 12, family: 'Inter' }, padding: 10 }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                }
            });
        },

        initSleepChart() {
            const ctx = document.getElementById('sleepChart');
            if (!ctx) return;

            const weekDates = this.getWeekDays();
            const labels = ["T2", "T3", "T4", "T5", "T6", "T7", "CN"];
            const hours = weekDates.map(d => this.history[d] ? this.history[d].sleep_hours : null);
            const qualities = weekDates.map(d => this.history[d] ? Math.floor(this.history[d].sleep_hours * 12) : null);

            this.sleepChartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Số giờ',
                            data: hours,
                            borderColor: '#818cf8', // indigo-400
                            backgroundColor: '#818cf8',
                            borderWidth: 3,
                            tension: 0.4,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Chất lượng',
                            data: qualities,
                            borderColor: '#10b981', // emerald-500
                            backgroundColor: '#10b981',
                            borderWidth: 3,
                            tension: 0.4,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { 
                            display: true, 
                            position: 'bottom',
                            labels: { color: '#888', usePointStyle: true, boxWidth: 8, font: { family: 'Inter', weight: 'bold' } } 
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            titleFont: { size: 13, family: 'Inter' },
                            bodyFont: { size: 14, weight: 'bold', family: 'Inter' },
                            cornerRadius: 8,
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false, drawBorder: false },
                            ticks: { color: '#888', font: { size: 12, family: 'Inter', weight: 'bold' } }
                        },
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            grid: {
                                color: 'rgba(150, 150, 150, 0.15)',
                                drawBorder: false,
                                borderDash: [5, 5]
                            },
                            ticks: { color: '#888', font: { size: 12, family: 'Inter' }, padding: 10 }
                        },
                        y1: {
                            type: 'linear',
                            display: false, // Hide secondary axis for cleaner look like Recharts
                            position: 'right',
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                }
            });
        },

        initWeightChart() {
            const ctx = document.getElementById('weightChart');
            if (!ctx) return;

            const weekDates = this.getWeekDays();
            const labels = ["T2", "T3", "T4", "T5", "T6", "T7", "CN"];
            const weights = weekDates.map(d => this.history[d] ? this.history[d].weight : null);

            const validWeights = weights.filter(w => w !== null);
            const min = validWeights.length ? Math.floor(Math.min(...validWeights) - 1) : 50;
            const max = validWeights.length ? Math.ceil(Math.max(...validWeights) + 1) : 100;

            this.weightChartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Cân nặng (kg)',
                        data: weights,
                        backgroundColor: '#06b6d4', // cyan-500
                        borderRadius: { topLeft: 6, topRight: 6, bottomLeft: 0, bottomRight: 0 },
                        barPercentage: 0.6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            titleFont: { size: 13, family: 'Inter' },
                            bodyFont: { size: 14, weight: 'bold', family: 'Inter' },
                            cornerRadius: 8,
                            displayColors: false,
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false, drawBorder: false },
                            ticks: { color: '#888', font: { size: 12, family: 'Inter', weight: 'bold' } }
                        },
                        y: {
                            min: min,
                            max: max,
                            grid: {
                                color: 'rgba(150, 150, 150, 0.15)',
                                drawBorder: false,
                                borderDash: [5, 5]
                            },
                            ticks: { color: '#888', font: { size: 12, family: 'Inter' }, padding: 10 }
                        }
                    },
                    interaction: { mode: 'index', intersect: false }
                }
            });
        },

        initCalorieChart() {
            const ctx = document.getElementById('calorieChart');
            if (!ctx) return;

            const weekDates = this.getWeekDays();
            const labels = ["T2", "T3", "T4", "T5", "T6", "T7", "CN"];
            const consumed = weekDates.map(d => this.history[d] ? this.history[d].calories : null);
            const burned = weekDates.map(d => this.history[d] ? (this.history[d].burned || 2000) : null);

            this.calorieChartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Nạp vào',
                            data: consumed,
                            backgroundColor: '#fbbf24', // amber-400
                            borderRadius: { topLeft: 4, topRight: 4, bottomLeft: 0, bottomRight: 0 },
                            barPercentage: 0.6
                        },
                        {
                            label: 'Đốt cháy',
                            data: burned,
                            backgroundColor: '#f43f5e', // rose-500
                            borderRadius: { topLeft: 4, topRight: 4, bottomLeft: 0, bottomRight: 0 },
                            barPercentage: 0.6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { 
                            display: true, 
                            position: 'bottom',
                            labels: { color: '#888', usePointStyle: true, boxWidth: 8, font: { family: 'Inter', weight: 'bold' } } 
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            titleFont: { size: 13, family: 'Inter' },
                            bodyFont: { size: 14, weight: 'bold', family: 'Inter' },
                            cornerRadius: 8,
                            displayColors: true,
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false, drawBorder: false },
                            ticks: { color: '#888', font: { size: 12, family: 'Inter', weight: 'bold' } }
                        },
                        y: {
                            grid: {
                                color: 'rgba(150, 150, 150, 0.15)',
                                drawBorder: false,
                                borderDash: [5, 5]
                            },
                            ticks: { color: '#888', font: { size: 12, family: 'Inter' }, padding: 10 }
                        }
                    },
                    interaction: { mode: 'index', intersect: false }
                }
            });
        },

        initStepChart() {
            const ctx = document.getElementById('stepChart');
            if (!ctx) return;

            const weekDates = this.getWeekDays();
            const labels = ["T2", "T3", "T4", "T5", "T6", "T7", "CN"];
            const steps = weekDates.map(d => this.history[d] ? this.history[d].steps : null);

            const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 250);
            gradient.addColorStop(0, 'rgba(16, 185, 129, 0.5)'); // emerald-500
            gradient.addColorStop(1, 'rgba(16, 185, 129, 0)');

            this.stepChartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Số bước chân',
                        data: steps,
                        backgroundColor: '#10b981', // emerald-500
                        borderRadius: 8,
                        barPercentage: 0.6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            titleFont: { size: 13, family: 'Inter' },
                            bodyFont: { size: 14, weight: 'bold', family: 'Inter' },
                            displayColors: false,
                            cornerRadius: 8,
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false, drawBorder: false },
                            ticks: { color: '#888', font: { size: 12, family: 'Inter', weight: 'bold' } }
                        },
                        y: {
                            grid: {
                                color: 'rgba(150, 150, 150, 0.15)',
                                drawBorder: false,
                                borderDash: [5, 5]
                            },
                            ticks: { color: '#888', font: { size: 12, family: 'Inter' }, padding: 10 }
                        }
                    },
                    interaction: { mode: 'index', intersect: false }
                }
            });
        },

        updateChartsData() {
            const today = this.formatDate(new Date());
            this.isFutureDate = this.selectedDate > today;

            const h = this.history[this.selectedDate] || {};
            // Gán trực tiếp để tránh kích hoạt watcher liên tục nếu không đổi
            this.data.heart_rate = h.heart_rate !== undefined ? h.heart_rate : '';
            this.data.steps = h.steps !== undefined ? h.steps : '';
            this.data.calories = h.calories !== undefined ? h.calories : '';
            this.data.weight = h.weight !== undefined ? h.weight : '';
            this.data.sleep_hours = h.sleep_hours !== undefined ? h.sleep_hours : '';
            this.data.spo2 = h.spo2 !== undefined ? h.spo2 : '';
            this.data.water_intake = h.water_intake !== undefined ? h.water_intake : '';
            
            this.$nextTick(() => this.refreshCharts());
        },

        refreshCharts() {
            const weekDates = this.getWeekDays();
            const labels = ["T2", "T3", "T4", "T5", "T6", "T7", "CN"];
            
            const getVal = (date, key) => {
                if (!this.history || !this.history[date]) return null;
                const v = this.history[date][key];
                return (v !== undefined && v !== null && v !== '') ? parseFloat(v) : null;
            };

            if (this.chartInstance) {
                this.chartInstance.data.labels = labels;
                this.chartInstance.data.datasets[0].data = weekDates.map(d => getVal(d, 'heart_rate'));
                this.chartInstance.update('none');
            }
            if (this.sleepChartInstance) {
                this.sleepChartInstance.data.labels = labels;
                this.sleepChartInstance.data.datasets[0].data = weekDates.map(d => getVal(d, 'sleep_hours'));
                this.sleepChartInstance.data.datasets[1].data = weekDates.map(d => {
                    const h = getVal(d, 'sleep_hours');
                    return h ? Math.floor(h * 12) : null;
                });
                this.sleepChartInstance.update('none');
            }
            if (this.weightChartInstance) {
                this.weightChartInstance.data.labels = labels;
                const weights = weekDates.map(d => getVal(d, 'weight'));
                this.weightChartInstance.data.datasets[0].data = weights;
                
                const validWeights = weights.filter(w => w !== null);
                if (validWeights.length) {
                    this.weightChartInstance.options.scales.y.min = Math.floor(Math.min(...validWeights) - 2);
                    this.weightChartInstance.options.scales.y.max = Math.ceil(Math.max(...validWeights) + 2);
                } else {
                    this.weightChartInstance.options.scales.y.min = 40;
                    this.weightChartInstance.options.scales.y.max = 120;
                }
                this.weightChartInstance.update('none');
            }
            if (this.calorieChartInstance) {
                this.calorieChartInstance.data.labels = labels;
                this.calorieChartInstance.data.datasets[0].data = weekDates.map(d => getVal(d, 'calories'));
                this.calorieChartInstance.data.datasets[1].data = weekDates.map(d => {
                    const v = getVal(d, 'burned');
                    const steps = getVal(d, 'steps');
                    // Nếu có bất kỳ dữ liệu nào của ngày đó thì mặc định 2000 calo tiêu thụ cơ bản
                    return v !== null ? v : (steps || getVal(d, 'calories') ? 2000 : null);
                });
                this.calorieChartInstance.update();
            }
            if (this.stepChartInstance) {
                this.stepChartInstance.data.labels = labels;
                this.stepChartInstance.data.datasets[0].data = weekDates.map(d => getVal(d, 'steps'));
                this.stepChartInstance.update('none');
            }
        },

        calculateSleepScore(hours, hr) {
            if (!hours) return null;
            const h = parseFloat(hours);
            const heartRate = hr ? parseInt(hr) : 70;

            // 1. Điểm thời lượng (Tối ưu 7.5 - 8.5 giờ)
            let durationScore = 0;
            if (h >= 7.5 && h <= 8.5) durationScore = 60;
            else if (h >= 6 && h < 7.5) durationScore = 45;
            else if (h > 8.5 && h <= 9.5) durationScore = 45;
            else durationScore = 30;

            // 2. Điểm hồi phục (Dựa trên nhịp tim nghỉ ngơi)
            let recoveryScore = 0;
            if (heartRate <= 65) recoveryScore = 40;
            else if (heartRate <= 75) recoveryScore = 30;
            else if (heartRate <= 85) recoveryScore = 20;
            else recoveryScore = 10;

            return durationScore + recoveryScore;
        },

        calculateHealthScore() {
            const steps = parseFloat(this.data.steps) || 0;
            const hours = parseFloat(this.data.sleep_hours) || 0;
            const hr = parseInt(this.data.heart_rate) || 75;
            const weight = parseFloat(this.data.weight) || 0;
            const height = parseFloat(this.data.height) || 0;

            // 1. Vận động (WHO: 8k-10k bước)
            const activityScore = Math.min(100, (steps / 8000) * 100);

            // 2. Giấc ngủ (WHO: 7-9 giờ)
            const sleepScore = this.calculateSleepScore(hours, hr) || 0;

            // 3. Chỉ số BMI (WHO Standard)
            let bmiScore = 70;
            if (weight > 0 && height > 0) {
                const bmi = weight / ((height/100) * (height/100));
                if (bmi >= 18.5 && bmi <= 24.9) bmiScore = 100;
                else if (bmi >= 25 && bmi <= 29.9) bmiScore = 80;
                else bmiScore = 50;
            }

            return Math.round((activityScore * 0.4) + (sleepScore * 0.4) + (bmiScore * 0.2));
        },

        getYesterdayDate(dateStr) {
            const date = new Date(dateStr);
            date.setDate(date.getDate() - 1);
            return this.formatDate(date);
        },

        getDailyDiff(key) {
            const today = this.selectedDate;
            const yesterday = this.getYesterdayDate(today);
            
            const todayVal = parseFloat(this.history[today]?.[key]) || 0;
            const yesterdayVal = parseFloat(this.history[yesterday]?.[key]) || 0;
            
            if (yesterdayVal === 0) return null;
            
            const diff = todayVal - yesterdayVal;
            const percent = Math.round((diff / yesterdayVal) * 100);
            
            return {
                value: diff,
                percent: percent,
                label: (diff > 0 ? '+' : '') + percent + '%',
                isPositive: diff > 0
            };
        },

        updateLiveChart() {
            const dateKey = this.selectedDate.substring(0, 10);
            
            const parseVal = (val, isFloat = false) => {
                if (val === '' || val === null || val === undefined) return null;
                const n = isFloat ? parseFloat(val) : parseInt(val);
                return isNaN(n) ? null : n;
            };

            const entry = {
                heart_rate: parseVal(this.data.heart_rate),
                steps: parseVal(this.data.steps),
                calories: parseVal(this.data.calories),
                weight: parseVal(this.data.weight, true),
                sleep_hours: parseVal(this.data.sleep_hours, true),
                spo2: parseVal(this.data.spo2),
                water_intake: parseVal(this.data.water_intake, true),
                burned: 2000
            };

            // Debug: log parsed entry for troubleshooting
            try { console.debug('updateLiveChart entry:', dateKey, entry); } catch(e) {}

            // Chỉ cập nhật nếu dữ liệu thực sự khác với bản ghi trong history
            const current = this.history[dateKey] || {};
            const hasChanged = Object.keys(entry).some(key => entry[key] !== current[key]);

            if (hasChanged) {
                this.history = { 
                    ...this.history, 
                    [dateKey]: {
                        ...(this.history[dateKey] || {}),
                        ...entry
                    }
                };
                this.$nextTick(() => this.refreshCharts());
            } else {
                // If no change detected, still refresh charts to reflect current data bindings
                this.$nextTick(() => this.refreshCharts());
            }
        }, 

        async runAiAnalysis(type) {
            this.aiState[type].loading = true;
            this.aiState[type].text = '';
            
            // Simulating AI thinking delay
            await new Promise(r => setTimeout(r, 1500));
            
            let insight = "";
            const currentVal = parseFloat(this.data[type === 'hr' ? 'heart_rate' : type === 'calories' ? 'calories' : type === 'steps' ? 'steps' : type === 'sleep' ? 'sleep_hours' : 'weight']);

            if (type === 'hr') {
                insight = currentVal < 60 ? "Nhịp tim nghỉ ngơi của bạn rất tốt, cho thấy khả năng hồi phục tim mạch cao. Hãy duy trì cường độ tập luyện hiện tại." 
                        : currentVal > 100 ? "Nhịp tim của bạn hơi cao. Có thể do căng thẳng hoặc thiếu ngủ. Hãy thử các bài tập hít thở sâu."
                        : "Nhịp tim của bạn đang ở mức ổn định. Đây là trạng thái lý tưởng để bắt đầu một ngày làm việc hiệu quả.";
            } else if (type === 'sleep') {
                insight = currentVal >= 7 ? "Giấc ngủ của bạn đạt chuẩn khoa học. Điều này giúp não bộ đào thải độc tố và tái tạo năng lượng tối ưu." 
                        : "Bạn đang ngủ ít hơn 7 tiếng. Thiếu ngủ kéo dài có thể làm giảm khả năng tập trung và tăng nguy cơ stress.";
            } else if (type === 'steps') {
                insight = currentVal >= 8000 ? "Tuyệt vời! Bạn đã vượt qua ngưỡng vận động tiêu chuẩn. Cơ bắp và hệ trao đổi chất đang hoạt động rất tích cực."
                        : "Hãy cố gắng đi bộ thêm một chút. 8.000 bước mỗi ngày là con số vàng để duy trì sức khỏe tim mạch lâu dài.";
            } else if (type === 'weight') {
                insight = "Xu hướng cân nặng của bạn đang ổn định. Để có kết quả tốt nhất, hãy kết hợp theo dõi chỉ số BMI và tỷ lệ mỡ cơ thể.";
            } else {
                insight = "Lượng calo nạp vào đang cân bằng với mức độ vận động. Tiếp tục duy trì chế độ dinh dưỡng lành mạnh này nhé!";
            }

            // Typing effect
            let i = 0;
            const typing = setInterval(() => {
                this.aiState[type].text += insight[i];
                i++;
                if (i >= insight.length) {
                    clearInterval(typing);
                    this.aiState[type].loading = false;
                }
            }, 30);
        },

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
            if (this.isFutureDate) return;
            
            this.saving = true;
            const payload = {
                ...this.data,
                recorded_at: this.selectedDate
            };

            // Update charts locally immediately to reflect user input
            try { this.updateLiveChart(); } catch(e) {}

            try {
                const res = await fetch('{{ route('health.update') }}', {
                    method: 'POST',
                    body: JSON.stringify(payload),
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                // After successful save, ensure charts reflect any server-normalized values
                if (res && res.ok) {
                    try { this.updateLiveChart(); } catch(e) {}
                }
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

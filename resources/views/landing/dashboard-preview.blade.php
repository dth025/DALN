<section id="dashboard" class="py-24 bg-gray-950 text-white relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-px bg-gradient-to-r from-transparent via-blue-500/50 to-transparent"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-16">
            <h2 class="text-blue-500 font-bold tracking-wide uppercase text-sm mb-3">Dashboard trực quan</h2>
            <p class="text-4xl font-extrabold mb-6 text-white">Quản lý sức khỏe trong lòng bàn tay</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Left Stats -->
            <div class="lg:col-span-4 space-y-6">
                <!-- BMI Card -->
                <div class="bg-gray-900/50 backdrop-blur-md border border-gray-800 p-6 rounded-3xl">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-gray-400 font-medium">BMI Index</span>
                        <span class="px-3 py-1 bg-green-500/20 text-green-500 rounded-full text-xs font-bold">Bình thường</span>
                    </div>
                    <div class="flex items-baseline space-x-2">
                        <span class="text-4xl font-bold">22.4</span>
                        <span class="text-gray-500">kg/m²</span>
                    </div>
                    <div class="mt-4 h-2 bg-gray-800 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-yellow-500 via-green-500 to-red-500 w-full opacity-30"></div>
                        <div class="h-full bg-white w-1 absolute" style="left: 45%"></div>
                    </div>
                </div>

                <!-- Heart Rate -->
                <div class="bg-gray-900/50 backdrop-blur-md border border-gray-800 p-6 rounded-3xl">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-gray-400 font-medium">Heart Rate</span>
                        <svg class="w-6 h-6 text-red-500 animate-pulse" fill="currentColor" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/></svg>
                    </div>
                    <div class="flex items-baseline space-x-2">
                        <span class="text-4xl font-bold">78</span>
                        <span class="text-gray-500">BPM</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Cập nhật 2 phút trước</p>
                </div>
            </div>

            <!-- Main Chart Mockup -->
            <div class="lg:col-span-8 bg-gray-900/50 backdrop-blur-md border border-gray-800 p-8 rounded-[2.5rem]">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="text-xl font-bold">Hoạt động trong tuần</h3>
                    <select class="bg-gray-800 border-none rounded-xl text-sm focus:ring-blue-500">
                        <option>7 ngày qua</option>
                        <option>30 ngày qua</option>
                    </select>
                </div>
                
                <!-- Simple Mockup Chart with CSS -->
                <div class="flex items-end justify-between h-64 px-4">
                    <div class="w-12 bg-blue-600/20 rounded-t-xl hover:bg-blue-600 transition-all duration-300 relative group" style="height: 60%">
                        <span class="absolute -top-8 left-1/2 -translate-x-1/2 text-xs font-bold opacity-0 group-hover:opacity-100 transition-opacity">650</span>
                        <span class="absolute -bottom-8 left-1/2 -translate-x-1/2 text-xs text-gray-500">T2</span>
                    </div>
                    <div class="w-12 bg-blue-600/20 rounded-t-xl hover:bg-blue-600 transition-all duration-300 relative group" style="height: 80%">
                        <span class="absolute -top-8 left-1/2 -translate-x-1/2 text-xs font-bold opacity-0 group-hover:opacity-100 transition-opacity">820</span>
                        <span class="absolute -bottom-8 left-1/2 -translate-x-1/2 text-xs text-gray-500">T3</span>
                    </div>
                    <div class="w-12 bg-blue-600/60 rounded-t-xl hover:bg-blue-600 transition-all duration-300 relative group" style="height: 100%">
                        <span class="absolute -top-8 left-1/2 -translate-x-1/2 text-xs font-bold opacity-0 group-hover:opacity-100 transition-opacity">1250</span>
                        <span class="absolute -bottom-8 left-1/2 -translate-x-1/2 text-xs text-gray-500 font-bold text-blue-500">T4</span>
                    </div>
                    <div class="w-12 bg-blue-600/20 rounded-t-xl hover:bg-blue-600 transition-all duration-300 relative group" style="height: 70%">
                        <span class="absolute -top-8 left-1/2 -translate-x-1/2 text-xs font-bold opacity-0 group-hover:opacity-100 transition-opacity">710</span>
                        <span class="absolute -bottom-8 left-1/2 -translate-x-1/2 text-xs text-gray-500">T5</span>
                    </div>
                    <div class="w-12 bg-blue-600/20 rounded-t-xl hover:bg-blue-600 transition-all duration-300 relative group" style="height: 50%">
                        <span class="absolute -top-8 left-1/2 -translate-x-1/2 text-xs font-bold opacity-0 group-hover:opacity-100 transition-opacity">480</span>
                        <span class="absolute -bottom-8 left-1/2 -translate-x-1/2 text-xs text-gray-500">T6</span>
                    </div>
                    <div class="w-12 bg-blue-600/20 rounded-t-xl hover:bg-blue-600 transition-all duration-300 relative group" style="height: 90%">
                        <span class="absolute -top-8 left-1/2 -translate-x-1/2 text-xs font-bold opacity-0 group-hover:opacity-100 transition-opacity">950</span>
                        <span class="absolute -bottom-8 left-1/2 -translate-x-1/2 text-xs text-gray-500">T7</span>
                    </div>
                    <div class="w-12 bg-blue-600/20 rounded-t-xl hover:bg-blue-600 transition-all duration-300 relative group" style="height: 40%">
                        <span class="absolute -top-8 left-1/2 -translate-x-1/2 text-xs font-bold opacity-0 group-hover:opacity-100 transition-opacity">320</span>
                        <span class="absolute -bottom-8 left-1/2 -translate-x-1/2 text-xs text-gray-500">CN</span>
                    </div>
                </div>

                <div class="mt-20 grid grid-cols-1 sm:grid-cols-3 gap-6 pt-8 border-t border-gray-800">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-500">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-medium">Hoạt động</p>
                            <p class="font-bold">4.5h</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-500/10 flex items-center justify-center text-purple-500">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-medium">Giấc ngủ</p>
                            <p class="font-bold">7h 20m</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-cyan-500/10 flex items-center justify-center text-cyan-500">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-medium">Nước</p>
                            <p class="font-bold">1.8L</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <span class="text-teal-600 font-medium text-sm mb-1">Welcome back, {{ Auth::user()->name }}</span>
            <h2 class="font-bold text-2xl text-gray-800 tracking-tight">
                {{ __('Dashboard') }}
            </h2>
        </div>
    </x-slot>

    <div class="pb-8">
        <div class="max-w-7xl mx-auto">
            
            <!-- Cards Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <!-- Card 1 -->
                <div class="bg-white/70 backdrop-blur-xl border border-white shadow-sm rounded-2xl overflow-hidden transition-all duration-300 hover:shadow-md">
                    <div class="p-5 flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500 font-bold tracking-wide uppercase mb-1">Total Kategori</p>
                            <h3 class="text-2xl font-extrabold text-gray-800">{{ $totalCategories }}</h3>
                        </div>
                        <div class="p-3 bg-teal-50 text-teal-600 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="bg-white/70 backdrop-blur-xl border border-white shadow-sm rounded-2xl overflow-hidden transition-all duration-300 hover:shadow-md">
                    <div class="p-5 flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500 font-bold tracking-wide uppercase mb-1">Total Supplier</p>
                            <h3 class="text-2xl font-extrabold text-gray-800">{{ $totalSuppliers }}</h3>
                        </div>
                        <div class="p-3 bg-teal-50 text-teal-600 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="bg-white/70 backdrop-blur-xl border border-white shadow-sm rounded-2xl overflow-hidden transition-all duration-300 hover:shadow-md">
                    <div class="p-5 flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500 font-bold tracking-wide uppercase mb-1">Total Barang</p>
                            <h3 class="text-2xl font-extrabold text-gray-800">{{ $totalItems }}</h3>
                        </div>
                        <div class="p-3 bg-teal-50 text-teal-600 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Banner & Details -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <!-- Low Stock Alert -->
                <div class="bg-white/70 backdrop-blur-xl border border-white shadow-sm rounded-2xl overflow-hidden flex flex-col">
                    <div class="p-5 flex-1">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-base font-bold text-gray-800 flex items-center">
                                <span class="w-2.5 h-2.5 rounded-full bg-teal-500 mr-2"></span>
                                Peringatan Stok
                            </h3>
                            <a href="{{ route('items.index') }}" class="text-xs font-semibold text-teal-600 bg-teal-50 px-2.5 py-1 rounded-lg hover:bg-teal-100 transition">Lihat Semua</a>
                        </div>

                        @if($lowStockItems->count() > 0)
                            <div class="space-y-3">
                                @foreach($lowStockItems->take(4) as $item)
                                    <div class="flex justify-between items-center bg-white/50 hover:bg-white p-3 rounded-xl border border-transparent hover:border-teal-100 transition-colors duration-200">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 rounded-lg bg-teal-50 flex items-center justify-center text-teal-600 mr-3 font-bold text-sm">
                                                {{ substr($item->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-800 text-sm">{{ $item->name }}</p>
                                                <p class="text-xs text-gray-500 font-medium">{{ $item->code }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-bold {{ $item->current_stock == 0 ? 'text-red-500' : 'text-teal-600' }} text-base">
                                                {{ $item->current_stock }}
                                            </p>
                                            <p class="text-[10px] text-gray-400 font-medium">/ {{ $item->minimum_stock }} Min</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center h-40 bg-teal-50/50 rounded-xl border border-teal-100 border-dashed">
                                <div class="w-12 h-12 bg-white text-teal-600 rounded-full flex items-center justify-center mb-2 shadow-sm">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <p class="text-gray-600 font-medium text-sm">Semua stok barang aman.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right Column: Recent Transactions & Banner -->
                <div class="flex flex-col gap-4">
                    <!-- Recent Transactions -->
                    <div class="bg-white/70 backdrop-blur-xl border border-white shadow-sm rounded-2xl overflow-hidden flex-1">
                        <div class="p-5">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-base font-bold text-gray-800">Riwayat Transaksi</h3>
                                <a href="{{ route('transactions.index') }}" class="text-xs font-semibold text-teal-600 bg-teal-50 px-2.5 py-1 rounded-lg hover:bg-teal-100 transition">Lihat Semua</a>
                            </div>

                            @if($recentTransactions->count() > 0)
                                <div class="space-y-3">
                                    @foreach($recentTransactions->take(3) as $trx)
                                        <div class="flex justify-between items-center bg-white/50 hover:bg-white p-3 rounded-xl border border-transparent hover:border-teal-100 transition-colors duration-200">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 rounded-lg {{ $trx->type == 'in' ? 'bg-teal-50 text-teal-600' : 'bg-gray-100 text-gray-600' }} flex items-center justify-center mr-3">
                                                    @if($trx->type == 'in')
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path></svg>
                                                    @else
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path></svg>
                                                    @endif
                                                </div>
                                                <div>
                                                    <p class="font-bold text-gray-800 text-sm">{{ optional($trx->item)->name }}</p>
                                                    <p class="text-[10px] text-gray-500 font-medium">{{ $trx->transaction_date->format('d M Y') }}</p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="font-bold text-base {{ $trx->type == 'in' ? 'text-teal-600' : 'text-gray-600' }}">
                                                    {{ $trx->type == 'in' ? '+' : '-' }}{{ $trx->qty }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="flex flex-col items-center justify-center h-28">
                                    <p class="text-gray-500 font-medium text-xs">Belum ada transaksi terekam.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- CTA Banner -->
                    <div class="bg-teal-600 rounded-2xl p-5 text-white shadow-sm relative overflow-hidden">
                        <!-- Decorative shapes -->
                        <div class="absolute top-0 right-0 -mr-6 -mt-6 w-24 h-24 rounded-full bg-white opacity-10"></div>
                        <div class="absolute bottom-0 right-8 -mb-8 w-16 h-16 rounded-full bg-white opacity-10"></div>
                        
                        <div class="relative z-10">
                            <p class="text-teal-100 text-[10px] font-bold mb-1.5 uppercase tracking-widest">Aksi Cepat</p>
                            <h3 class="text-xl font-bold mb-3 leading-tight">Kelola Transaksi <br/>Hari Ini</h3>
                            <a href="{{ route('transactions.create') }}" class="inline-block bg-white text-teal-700 font-bold px-4 py-2 text-xs rounded-xl shadow-sm hover:shadow-md transition-all transform hover:-translate-y-0.5">
                                Tambah Transaksi
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

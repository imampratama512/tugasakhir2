<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Transaksi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-gray-800">Informasi Transaksi #{{ $transaction->id }}</h3>
                        <span class="{{ $transaction->type == 'in' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} text-sm font-semibold px-3 py-1 rounded-full">
                            {{ $transaction->type == 'in' ? 'Barang Masuk' : 'Barang Keluar' }}
                        </span>
                    </div>

                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4 border-b pb-4">
                            <div>
                                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide mb-1">Tanggal Transaksi</p>
                                <p class="text-gray-800 font-medium">{{ $transaction->transaction_date->format('d F Y') }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide mb-1">Jumlah (Qty)</p>
                                <p class="text-2xl font-extrabold {{ $transaction->type == 'in' ? 'text-teal-600' : 'text-red-500' }}">
                                    {{ $transaction->type == 'in' ? '+' : '-' }}{{ $transaction->qty }}
                                </p>
                            </div>
                        </div>

                        <div class="border-b pb-4">
                            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide mb-1">Barang</p>
                            @if($transaction->item)
                                <p class="text-gray-800 font-medium">{{ $transaction->item->name }}</p>
                                <p class="text-xs text-gray-500">Kode: <span class="bg-gray-100 px-2 py-0.5 rounded">{{ $transaction->item->code }}</span></p>
                                <p class="text-xs text-gray-500 mt-1">Stok saat ini: <strong>{{ $transaction->item->current_stock }}</strong></p>
                            @else
                                <p class="text-gray-400 italic">Barang tidak ditemukan</p>
                            @endif
                        </div>

                        @if($transaction->type == 'in')
                        <div>
                            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide mb-1">Supplier</p>
                            <p class="text-gray-800">{{ optional($transaction->supplier)->name ?? '-' }}</p>
                        </div>
                        @endif

                        <div class="text-xs text-gray-400 pt-2 border-t">
                            Dibuat: {{ $transaction->created_at->format('d M Y, H:i') }} &bull;
                            Diperbarui: {{ $transaction->updated_at->format('d M Y, H:i') }}
                        </div>
                    </div>

                    <div class="flex items-center justify-between mt-6 pt-4 border-t">
                        <a href="{{ route('transactions.index') }}" class="text-gray-600 hover:underline text-sm">&larr; Kembali ke Daftar</a>
                        <div class="flex gap-2">
                            <a href="{{ route('transactions.edit', $transaction->id) }}" class="bg-teal-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-teal-700 transition font-semibold">Edit</a>
                            <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus transaksi ini? Stok akan dikembalikan ke kondisi sebelum transaksi.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-white text-red-600 border border-red-400 px-4 py-2 rounded-lg text-sm hover:bg-red-50 transition font-semibold">Hapus</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Transaksi Gudang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    
                    <div class="flex justify-between mb-4">
                        <h3 class="text-lg font-bold">Riwayat Transaksi</h3>
                        <a href="{{ route('transactions.create') }}" class="px-4 py-2 bg-teal-600 text-white rounded hover:bg-teal-700">Catat Transaksi Baru</a>
                    </div>

                    @if(session('success'))
                        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b">No</th>
                                    <th class="py-2 px-4 border-b">Tanggal</th>
                                    <th class="py-2 px-4 border-b">Barang</th>
                                    <th class="py-2 px-4 border-b">Jenis</th>
                                    <th class="py-2 px-4 border-b">Qty</th>
                                    <th class="py-2 px-4 border-b">Supplier</th>
                                    <th class="py-2 px-4 border-b text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $transaction)
                                    <tr>
                                        <td class="py-2 px-4 border-b">{{ $loop->iteration }}</td>
                                        <td class="py-2 px-4 border-b">{{ $transaction->transaction_date->format('d/m/Y') }}</td>
                                        <td class="py-2 px-4 border-b">{{ optional($transaction->item)->name }} ({{ optional($transaction->item)->code }})</td>
                                        <td class="py-2 px-4 border-b">
                                            @if($transaction->type == 'in')
                                                <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">Barang Masuk</span>
                                            @else
                                                <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded">Barang Keluar</span>
                                            @endif
                                        </td>
                                        <td class="py-2 px-4 border-b font-bold">{{ $transaction->qty }}</td>
                                        <td class="py-2 px-4 border-b">
                                            @if($transaction->type == 'in')
                                                {{ optional($transaction->supplier)->name ?? '-' }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="py-2 px-4 border-b text-center">
                                            <a href="{{ route('transactions.show', $transaction->id) }}" class="bg-teal-600 text-white px-3 py-1 rounded-lg text-xs hover:bg-teal-700 transition mr-1 font-semibold shadow-sm">Detail</a>
                                            <a href="{{ route('transactions.edit', $transaction->id) }}" class="bg-white text-teal-600 border border-teal-600 px-3 py-1 rounded-lg text-xs hover:bg-teal-50 transition mr-1 font-semibold shadow-sm">Edit</a>
                                            <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus transaksi ini? Stok akan dikembalikan.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-white text-red-500 border border-red-400 px-3 py-1 rounded-lg text-xs hover:bg-red-50 transition font-semibold shadow-sm">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-2 px-4 border-b text-center text-gray-500">Belum ada data transaksi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

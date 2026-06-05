<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Barang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    
                    <div class="flex justify-between mb-4">
                        <h3 class="text-lg font-bold">Daftar Barang</h3>
                        <a href="{{ route('items.create') }}" class="px-4 py-2 bg-teal-600 text-white rounded hover:bg-teal-700">Tambah Barang</a>
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
                                    <th class="py-2 px-4 border-b">Kode Barang</th>
                                    <th class="py-2 px-4 border-b">Nama Barang</th>
                                    <th class="py-2 px-4 border-b">Kategori</th>
                                    <th class="py-2 px-4 border-b">Stok</th>
                                    <th class="py-2 px-4 border-b">Status</th>
                                    <th class="py-2 px-4 border-b text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($items as $item)
                                    <tr>
                                        <td class="py-2 px-4 border-b">{{ $loop->iteration }}</td>
                                        <td class="py-2 px-4 border-b"><span class="bg-gray-200 px-2 py-1 rounded text-sm">{{ $item->code }}</span></td>
                                        <td class="py-2 px-4 border-b">{{ $item->name }}</td>
                                        <td class="py-2 px-4 border-b">{{ optional($item->category)->name }}</td>
                                        <td class="py-2 px-4 border-b font-bold">{{ $item->current_stock }}</td>
                                        <td class="py-2 px-4 border-b">
                                            @if($item->stock_status == 'Aman')
                                                <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">Aman</span>
                                            @elseif($item->stock_status == 'Menipis')
                                                <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded">Menipis</span>
                                            @else
                                                <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded">Habis</span>
                                            @endif
                                        </td>
                                        <td class="py-2 px-4 border-b text-center">
                                            <a href="{{ route('items.edit', $item->id) }}" class="bg-teal-600 text-white px-3 py-1 rounded-lg text-xs hover:bg-teal-700 transition mr-2 font-semibold shadow-sm">Edit</a>
                                            <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus barang ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-white text-teal-600 border border-teal-600 px-3 py-1 rounded-lg text-xs hover:bg-teal-50 transition font-semibold shadow-sm">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-2 px-4 border-b text-center text-gray-500">Belum ada data barang.</td>
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

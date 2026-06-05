<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Transaksi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">

                    @if(session('error'))
                        <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg text-sm text-yellow-700">
                        <strong>Perhatian:</strong> Mengedit transaksi akan otomatis menyesuaikan stok barang (stok lama dikembalikan, stok baru diterapkan).
                    </div>

                    <form action="{{ route('transactions.update', $transaction->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="transaction_date" class="block text-gray-700 text-sm font-bold mb-2">Tanggal Transaksi *</label>
                                <input type="date" name="transaction_date" id="transaction_date"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 @error('transaction_date') border-red-500 @enderror"
                                    value="{{ old('transaction_date', $transaction->transaction_date->format('Y-m-d')) }}" required>
                                @error('transaction_date')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="type" class="block text-gray-700 text-sm font-bold mb-2">Jenis Transaksi *</label>
                                <select name="type" id="type"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 @error('type') border-red-500 @enderror"
                                    required>
                                    <option value="in" {{ old('type', $transaction->type) == 'in' ? 'selected' : '' }}>Barang Masuk (Tambah Stok)</option>
                                    <option value="out" {{ old('type', $transaction->type) == 'out' ? 'selected' : '' }}>Barang Keluar (Kurangi Stok)</option>
                                </select>
                                @error('type')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4 md:col-span-2">
                                <label for="item_id" class="block text-gray-700 text-sm font-bold mb-2">Pilih Barang *</label>
                                <select name="item_id" id="item_id"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 @error('item_id') border-red-500 @enderror"
                                    required>
                                    <option value="">-- Cari Barang --</option>
                                    @foreach($items as $item)
                                        <option value="{{ $item->id }}" {{ old('item_id', $transaction->item_id) == $item->id ? 'selected' : '' }}>
                                            {{ $item->code }} - {{ $item->name }} (Stok saat ini: {{ $item->current_stock }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('item_id')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="qty" class="block text-gray-700 text-sm font-bold mb-2">Jumlah (Qty) *</label>
                                <input type="number" name="qty" id="qty"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 @error('qty') border-red-500 @enderror"
                                    value="{{ old('qty', $transaction->qty) }}" min="1" required>
                                @error('qty')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4 md:col-span-2" id="supplier-wrapper">
                                <label for="supplier_id" class="block text-gray-700 text-sm font-bold mb-2">Supplier *</label>
                                <select name="supplier_id" id="supplier_id"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 @error('supplier_id') border-red-500 @enderror">
                                    <option value="">-- Pilih Supplier --</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ old('supplier_id', $transaction->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-gray-500 text-xs mt-1">Hanya untuk barang masuk — dari supplier mana barang dibeli.</p>
                                @if($suppliers->isEmpty())
                                    <p class="text-amber-600 text-xs mt-2">Belum ada supplier. <a href="{{ route('suppliers.create') }}" class="underline">Tambah supplier</a> terlebih dahulu.</p>
                                @endif
                                @error('supplier_id')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('transactions.index') }}" class="mr-4 text-gray-600 hover:underline">Batal</a>
                            <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                                Update Transaksi
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const typeSelect = document.getElementById('type');
            const supplierWrapper = document.getElementById('supplier-wrapper');
            const supplierSelect = document.getElementById('supplier_id');

            function toggleSupplierField() {
                const isIncoming = typeSelect.value === 'in';
                supplierWrapper.classList.toggle('hidden', !isIncoming);
                supplierSelect.disabled = !isIncoming;
                if (!isIncoming) {
                    supplierSelect.value = '';
                }
            }

            typeSelect.addEventListener('change', toggleSupplierField);
            toggleSupplierField();
        });
    </script>
</x-app-layout>

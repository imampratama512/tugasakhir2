<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Item;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['item', 'supplier'])->latest('transaction_date')->get();
        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $items = Item::all();
        $suppliers = Supplier::orderBy('name')->get();
        return view('transactions.create', compact('items', 'suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'type' => 'required|in:in,out',
            'qty' => 'required|integer|min:1',
            'transaction_date' => 'required|date',
            'supplier_id' => $request->type === 'in'
                ? 'required|exists:suppliers,id'
                : 'nullable',
        ]);

        DB::beginTransaction();
        try {
            $item = Item::findOrFail($request->item_id);

            if ($request->type == 'out' && $item->current_stock < $request->qty) {
                DB::rollBack();
                return back()->withInput()->with('error', 'Stok barang tidak mencukupi untuk dikeluarkan.');
            }

            $data = $request->only(['item_id', 'type', 'qty', 'transaction_date']);
            $data['supplier_id'] = $request->type === 'in' ? $request->supplier_id : null;

            Transaction::create($data);

            if ($request->type == 'in') {
                $item->increment('current_stock', $request->qty);
            } else {
                $item->decrement('current_stock', $request->qty);
            }

            DB::commit();
            return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil disimpan dan stok terupdate.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['item', 'supplier']);
        return view('transactions.show', compact('transaction'));
    }

    public function edit(Transaction $transaction)
    {
        $items = Item::all();
        $suppliers = Supplier::orderBy('name')->get();
        return view('transactions.edit', compact('transaction', 'items', 'suppliers'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'type' => 'required|in:in,out',
            'qty' => 'required|integer|min:1',
            'transaction_date' => 'required|date',
            'supplier_id' => $request->type === 'in'
                ? 'required|exists:suppliers,id'
                : 'nullable',
        ]);

        DB::beginTransaction();
        try {
            $oldItem = Item::findOrFail($transaction->item_id);

            if ($transaction->type == 'in' && $oldItem->current_stock < $transaction->qty) {
                DB::rollBack();
                return back()->withInput()->with('error', 'Stok tidak cukup untuk membatalkan transaksi masuk ini. Stok saat ini lebih kecil dari jumlah transaksi.');
            }

            // Reverse stok dari transaksi lama
            if ($transaction->type == 'in') {
                $oldItem->decrement('current_stock', $transaction->qty);
            } else {
                $oldItem->increment('current_stock', $transaction->qty);
            }

            $newItem = Item::findOrFail($request->item_id);

            // Validasi stok jika transaksi baru adalah keluar
            if ($request->type == 'out' && $newItem->current_stock < $request->qty) {
                DB::rollBack();
                return back()->withInput()->with('error', 'Stok barang tidak mencukupi untuk dikeluarkan.');
            }

            // Terapkan stok transaksi baru
            if ($request->type == 'in') {
                $newItem->increment('current_stock', $request->qty);
            } else {
                $newItem->decrement('current_stock', $request->qty);
            }

            $data = $request->only(['item_id', 'type', 'qty', 'transaction_date']);
            $data['supplier_id'] = $request->type === 'in' ? $request->supplier_id : null;

            $transaction->update($data);

            DB::commit();
            return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil diupdate dan stok disesuaikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Transaction $transaction)
    {
        DB::beginTransaction();
        try {
            $item = Item::findOrFail($transaction->item_id);

            if ($transaction->type == 'in' && $item->current_stock < $transaction->qty) {
                DB::rollBack();
                return back()->with('error', 'Stok tidak cukup untuk menghapus transaksi masuk ini. Stok saat ini lebih kecil dari jumlah transaksi.');
            }

            // Reverse stok saat transaksi dihapus
            if ($transaction->type == 'in') {
                $item->decrement('current_stock', $transaction->qty);
            } else {
                $item->increment('current_stock', $transaction->qty);
            }

            $transaction->delete();

            DB::commit();
            return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dihapus dan stok dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::with('category')->get();
        return view('items.index', compact('items'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('items.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'code' => 'required|string|unique:items,code|max:50',
            'name' => 'required|string|max:255',
            'minimum_stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
        ]);

        Item::create($request->only(['category_id', 'code', 'name', 'minimum_stock', 'price']));
        return redirect()->route('items.index')->with('success', 'Barang berhasil ditambahkan');
    }

    public function edit(Item $item)
    {
        $categories = Category::all();
        return view('items.edit', compact('item', 'categories'));
    }

    public function update(Request $request, Item $item)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'code' => 'required|string|max:50|unique:items,code,' . $item->id,
            'name' => 'required|string|max:255',
            'minimum_stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
        ]);

        $item->update($request->only(['category_id', 'code', 'name', 'minimum_stock', 'price']));
        return redirect()->route('items.index')->with('success', 'Barang berhasil diupdate');
    }

    public function destroy(Item $item)
    {
        if ($item->transactions()->exists()) {
            return redirect()->route('items.index')->with('error', 'Barang tidak dapat dihapus karena masih memiliki riwayat transaksi.');
        }

        $item->delete();
        return redirect()->route('items.index')->with('success', 'Barang berhasil dihapus');
    }
}

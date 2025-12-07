<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockMovement;
use App\Models\Product;

class StockMovementController extends Controller
{
    // ==========================
    // Tampil semua pergerakan stok
    // ==========================
    public function index()
    {
        $movements = StockMovement::with('product')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('stockmovement.index', compact('movements'));
    }

    // ==========================
    // Form restock
    // ==========================
    public function createRestock()
    {
        $products = Product::all();
        return view('stockmovement.restock', compact('products'));
    }

    // Simpan restock
    public function storeRestock(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1',
            'unit_cost' => 'required|numeric|min:0',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Perbarui stok produk
        $product->stok += $request->qty;
        $product->save();

        // Simpan pergerakan stok
        StockMovement::create([
            'product_id' => $product->id,
            'type' => 'restock',
            'qty' => $request->qty,
            'unit_cost' => $request->unit_cost,
            'total_cost' => $request->qty * $request->unit_cost,
            'created_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Restock berhasil dicatat.');
    }

    // ==========================
    // Form damage
    // ==========================
    public function createDamage()
    {
        $products = Product::all();
        return view('stockmovement.damage', compact('products'));
    }

    // Simpan damage
    public function storeDamage(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Pastikan stok cukup
        if ($request->qty > $product->stok) {
            return back()->with('error', 'Jumlah damage melebihi stok.');
        }

        // Kurangi stok
        $product->stok -= $request->qty;
        $product->save();

        // Gunakan modal_per_produk sebagai unit_cost
        $unitCost = $product->modal_per_produk;

        // Simpan pergerakan stok (damage)
        StockMovement::create([
            'product_id' => $product->id,
            'type' => 'damage',
            'qty' => $request->qty,
            'unit_cost' => $unitCost,
            'total_cost' => $request->qty * $unitCost,
            'created_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Damage berhasil dicatat.');
    }

    public function create()
    {
        $products = Product::all();
        return view('owner.damage', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'qty' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        // catat movement
        StockMovement::create([
            'product_id' => $product->id,
            'type' => 'damage',
            'qty' => $request->qty,
            'unit_cost' => $product->modal_per_produk,
            'total_cost' => $product->modal_per_produk * $request->qty,
            'created_by' => auth()->id(),
        ]);

        // kurangi stok
        $product->stok -= $request->qty;
        $product->save();

        return redirect()->back()->with('success', 'Stock out berhasil dicatat.');
    }
}




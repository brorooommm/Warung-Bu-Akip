<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\TransactionHistory;
use App\Models\Sale;
use App\Models\ExpiredProduct;


class CashierController extends Controller
{
    // All Products
    public function products(Request $request)
{
    $categories = \App\Models\Category::all();

    // Mulai query
    $query = Product::query();

    // FILTER SEARCH
    if ($request->filled('search')) {
        $query->where('nama_produk', 'like', '%' . $request->search . '%');
    }

    // FILTER KATEGORI (pastikan blade mengirim 'category_id')
    if ($request->filled('category_id')) {
        $query->where('kategori_id', $request->category_id);
    }

    // SORTING sederhana untuk kasir: nama & stok asc/desc
    if ($request->filled('sort')) {
        switch ($request->sort) {
            case 'name_asc':
                $query->orderBy('nama_produk', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('nama_produk', 'desc');
                break;
            case 'stock_asc':
                $query->orderBy('stok', 'asc');
                break;
            case 'stock_desc':
                $query->orderBy('stok', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }
    } else {
        $query->orderBy('created_at', 'desc');
    }

    $products = $query->with('kategori')->get();

    return view('cashier.products', compact('products','categories'));
}



    // Laporan Expired
    public function showExpired()
    {
        $products = Product::where('expired_date', '<=', now()->addDays(5))->get();
        return view('cashier.expired', compact('products'));
    }

    public function updateExpired(Request $request)
    {
        $expiredInput = $request->input('expired', []);

        foreach($expiredInput as $productId => $expiredQty){
            $product = Product::find($productId);
            if($product && $expiredQty > 0){
                $product->stok = max(0, $product->stok - $expiredQty);
                $product->save();

                ExpiredProduct::create([
                    'product_id' => $product->id,
                    'entry_date' => now(),
                    'expired_date' => $product->expired_date,
                    'countdown_days' => 0,
                    'is_expired' => true,
                ]);
            }
        }

        return redirect()->route('cashier.expired')->with('success','Stok expired berhasil diperbarui.');
    }

   public function showTransaction()
    {
        $products = Product::all();
        $sales = Sale::with('products')->latest()->take(50)->get(); // ambil pivot products
        return view('cashier.transaction', compact('products','sales'));
    }

    // Simpan transaksi batch
  public function storeTransaction(Request $request)
{
    $request->validate([
        'products.*.product_id' => 'required|exists:products,id',
        'products.*.quantity' => 'required|integer|min:1',
        'amount_paid' => 'required|numeric|min:0',
    ]);

    $totalSale = 0;
    $sale = Sale::create([
        'total' => 0, // sementara, nanti update
        'cashier_id' => auth()->id() ?? null,
    ]);

    foreach ($request->products as $item) {
        $product = Product::findOrFail($item['product_id']);
        $qty = $item['quantity'];
        $subtotal = $product->harga * $qty;

        $sale->products()->attach($product->id, [
            'quantity' => $qty,
            'price' => $product->harga,
            'subtotal' => $subtotal,
        ]);

        $product->decrement('stok', $qty);
        $totalSale += $subtotal;
    }

    $sale->update(['total' => $totalSale]);

    return redirect()->back()->with('transaction_success', true);

}



    // Pengaturan Akun
    public function settings()
    {
        return view('cashier.settings');
    }
    public function product()
{
    return $this->belongsTo(Product::class, 'product_id');
}

}

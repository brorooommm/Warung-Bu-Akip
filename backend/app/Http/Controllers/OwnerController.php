<?php

namespace App\Http\Controllers;
use PDF;
use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OwnerController extends Controller
{
   public function exportPDF()
{
    $items = DB::table('sale_product')
        ->join('sales', 'sales.id', '=', 'sale_product.sale_id')
        ->join('products', 'products.id', '=', 'sale_product.product_id')
        ->select(
            'sale_product.*',
            'sales.created_at as sale_time',
            'products.nama_produk',
            
        )
        ->orderBy('sales.created_at', 'asc')
        ->get();

    // Hitung laba
    foreach ($items as $item) {

        // Laba Kotor = subtotal
        $item->laba_kotor = $item->subtotal;

        // modal per produk = modal paket / jumlah item dalam paket
        $modal_per_produk = $item->package_buy_price / $item->items_per_package;

        // total modal = modal per produk × quantity
        $total_modal = $modal_per_produk * $item->quantity;

        // laba bersih
        $item->laba_bersih = $item->laba_kotor - $total_modal;

        // format tanggal & waktu
        $time = \Carbon\Carbon::parse($item->sale_time);
        $item->tanggal = $time->format('d-m-Y');
        $item->waktu   = $time->format('H:i');
    }

    $pdf = \PDF::loadView('owner.export.products_pdf', compact('items'));
    return $pdf->download('laporan_produk.pdf');
}


    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | 1. Hitung Modal Total (JOIN sale_products + products + warehouse_package)
        |--------------------------------------------------------------------------
        */
        $totalModal = DB::table('sale_product')
            ->join('products', 'sale_product.product_id' , '=', 'products.id')
            ->selectRaw(
                    'sale_product.subtotal'
            )
            ->value('total_modal');


        /*
        |--------------------------------------------------------------------------
        | 2. Hitung Laba Kotor (subtotal sudah otomatis price * qty)
        |--------------------------------------------------------------------------
        */
        $grossProfit = DB::table('sale_product')
            ->selectRaw('SUM(subtotal) AS gross_profit')
            ->value('gross_profit');


        /*
        |--------------------------------------------------------------------------
        | 3. Hitung Laba Bersih
        |--------------------------------------------------------------------------
        */
        $netProfit = $grossProfit - $totalModal;


        /*
        |--------------------------------------------------------------------------
        | 4. Hitung Produk Terlaris
        |--------------------------------------------------------------------------
        */
        $productCount = DB::table('sale_product')
            ->join('products', 'sale_product.product_id', '=', 'products.id')
            ->select('products.nama_produk', DB::raw('SUM(sale_product.quantity) as total_qty'))
            ->groupBy('products.nama_produk')
            ->orderByDesc('total_qty')
            ->first();

        $topProduct = $productCount->nama_produk ?? '-';
        $topProductQty = $productCount->total_qty ?? 0;


        /*
        |--------------------------------------------------------------------------
        | 5. Hitung Total Transaksi
        |--------------------------------------------------------------------------
        */
        $totalTransactions = Sale::count();


        /*
        |--------------------------------------------------------------------------
        | 6. Penjualan Per Bulan (Chart)
        |--------------------------------------------------------------------------
        */
        $monthlySales = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlySales[] = Sale::whereMonth('created_at', $m)->sum('total');
        }


        /*
        |--------------------------------------------------------------------------
        | 7. Transaksi Terbaru
        |--------------------------------------------------------------------------
        */
        $recentTransactions = Sale::with('products')
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($sale) {
                return [
                    'date'   => $sale->created_at->format('d-m-Y H:i'),
                    'item'   => $sale->products->pluck('nama_produk')->implode(', '),
                    'amount' => $sale->total,
                ];
            })
            ->toArray();


        /*
        |--------------------------------------------------------------------------
        | Final: Data Dikembalikan ke Blade
        |--------------------------------------------------------------------------
        */
        $data = [
            'totalCost'         => $totalModal,
            'grossProfit'       => $grossProfit,
            'netProfit'         => $netProfit,
            'totalTransactions' => $totalTransactions,
            'monthlySales'      => $monthlySales,
            'recentTransactions'=> $recentTransactions,
            'topProduct'        => $topProduct,
            'topProductQty'     => $topProductQty,
        ];

        return view('owner', compact('data'));
    }


    /*
    |--------------------------------------------------------------------------
    | CRUD Produk
    |--------------------------------------------------------------------------
    */

    public function products(Request $request)
{
    $query = Product::query();

    // SEARCH
    if ($request->search) {
        $query->where('nama_produk', 'like', '%' . $request->search . '%');
    }

    // FILTER KATEGORI (kalau kamu punya)
    if ($request->kategori) {
        $query->where('kategori_id', $request->kategori);
    }

    $products = $query->get();// kalau tidak ada kategori, hapus ini

    return view('owner.products', compact('products'));

}

    public function updateProduct(Request $request, Product $product)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga'       => 'required|numeric',
            'stok'        => 'required|integer',
        ]);

        $product->update($request->only('nama_produk','harga','stok'));

        return redirect()->route('owner.products')->with('success', 'Produk berhasil diperbarui!');
    }

    public function deleteProduct(Product $product)
    {
        $product->delete();
        return redirect()->route('owner.products')->with('success', 'Produk berhasil dihapus!');
    }
    
}

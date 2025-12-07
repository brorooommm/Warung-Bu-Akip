<?php

namespace App\Http\Controllers;
use PDF;
use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OwnerController extends Controller
{
   public function exportPDF(Request $request)
{
    $start = $request->start
        ? \Carbon\Carbon::parse($request->start)->startOfDay()
        : \Carbon\Carbon::now()->subMonth()->startOfDay();

    $end = $request->end
        ? \Carbon\Carbon::parse($request->end)->endOfDay()
        : \Carbon\Carbon::now()->endOfDay();

    // =============== PEMASUKAN ===============
    $pemasukan = DB::table('sale_product')
        ->join('sales', 'sales.id', '=', 'sale_product.sale_id')
        ->join('products', 'products.id', '=', 'sale_product.product_id')
        ->whereBetween('sales.created_at', [$start, $end])
        ->select(
            'sale_product.sale_id as id',
            'products.nama_produk',
            'sale_product.qty as jumlah',
            DB::raw('sale_product.qty * sale_product.price as harga'),
            'products.modal_per_produk',
            'sales.created_at'
        )
        ->get()
        ->map(function ($item) {
            $item->tipe = 'Pemasukan';

            // Hitung laba per item
            $modal_total = ($item->modal_per_produk ?? 0) * ($item->jumlah ?? 0);
            $item->laba_kotor = $item->harga ?? 0;
            $item->laba_bersih = $item->laba_kotor - $modal_total;

            return $item;
        });

    // =============== PENGELUARAN ===============
    $pengeluaran = \App\Models\StockMovement::join('products', 'products.id', '=', 'stock_movements.product_id')
        ->whereBetween('stock_movements.created_at', [$start, $end])
        ->select(
            'stock_movements.id',
            'products.nama_produk',
            'stock_movements.qty as jumlah',
            'stock_movements.total_cost as harga',
            'stock_movements.created_at'
        )
        ->get()
        ->map(function ($item) {
            $item->tipe = 'Pengeluaran';
            $item->laba_kotor = 0;
            $item->laba_bersih = 0;
            return $item;
        });

    // Gabungkan timeline
    $timeline = $pemasukan->merge($pengeluaran)->sortBy('created_at')->values();

    // Hitung total untuk footer
    $totalPendapatan = $timeline->where('tipe', 'Pemasukan')->sum('harga');
    $totalPengeluaran = $timeline->where('tipe', 'Pengeluaran')->sum('harga');
    $totalLabaKotor = $timeline->sum('laba_kotor');
    $totalLabaBersih = $timeline->sum('laba_bersih');

    // Kirim ke view
    $pdf = \PDF::loadView('owner.export.products_pdf', [
        'timeline' => $timeline,
        'start' => $start,
        'end' => $end,
        'totalPendapatan' => $totalPendapatan,
        'totalPengeluaran' => $totalPengeluaran,
        'totalLabaKotor' => $totalLabaKotor,
        'totalLabaBersih' => $totalLabaBersih,
    ]);

    return $pdf->download('laporan_produk.pdf');
}

   public function index()
{
    $today = Carbon::today();

    // ==================== 1. Total Modal ====================
    $totalModalOverall = DB::table('sale_product')
        ->join('products', 'sale_product.product_id', '=', 'products.id')
        ->selectRaw('SUM(products.modal_per_produk * sale_product.qty) AS total_modal')
        ->value('total_modal') ?? 0;

    // ==================== 2. Laba Kotor ====================
    $grossProfit = DB::table('sale_product')
        ->selectRaw('SUM(subtotal) AS gross_profit')
        ->value('gross_profit') ?? 0;

    // ==================== 3. Laba Bersih ====================
    $netProfit = $grossProfit - $totalModalOverall;

    // ==================== 4. Produk Terlaris ====================
    $topProductData = DB::table('sale_product')
        ->join('products', 'sale_product.product_id', '=', 'products.id')
        ->select('products.nama_produk', DB::raw('SUM(sale_product.qty) as total_qty'))
        ->groupBy('products.nama_produk')
        ->orderByDesc('total_qty')
        ->first();

    $topProduct = $topProductData->nama_produk ?? '-';
    $topProductQty = $topProductData->total_qty ?? 0;

    // ==================== 5. Total Transaksi ====================
    $totalTransactions = Sale::count();

    // ==================== 6. Penjualan per bulan (Chart) ====================
    $monthlySales = [];
    for ($m = 1; $m <= 12; $m++) {
        $monthlySales[] = Sale::whereMonth('created_at', $m)->sum('total');
    }

    // ==================== 7. Transaksi Terbaru ====================
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
        })->toArray();

    // ==================== 8. Grafik Keuntungan & Kerugian 30 Hari ====================
    $dates = [];
    $profitData = [];
    $lossData = [];

    for ($i = 29; $i >= 0; $i--) {
        $date = $today->copy()->subDays($i)->format('Y-m-d');
        $dates[] = $today->copy()->subDays($i)->format('d M');

        // Ambil data dari sale_product JOIN sales untuk tanggal
        $dailyData = DB::table('sale_product')
            ->join('sales', 'sale_product.sale_id', '=', 'sales.id')
            ->join('products', 'sale_product.product_id', '=', 'products.id')
            ->whereDate('sales.created_at', $date)
            ->select(
                DB::raw('SUM(sale_product.qty * products.modal_per_produk) as total_modal'),
                DB::raw('SUM(sale_product.subtotal) as total_sales')
            )
            ->first();

        $dailyModal = $dailyData->total_modal ?? 0;
        $dailySales = $dailyData->total_sales ?? 0;

        $profitData[] = max($dailySales - $dailyModal, 0);
        $lossData[]   = max($dailyModal - $dailySales, 0);
    }

    // ==================== 9. Return ke Blade ====================
    return view('owner', [
        'totalCost'         => $totalModalOverall,
        'grossProfit'       => $grossProfit,
        'netProfit'         => $netProfit,
        'totalTransactions' => $totalTransactions,
        'monthlySales'      => $monthlySales,
        'recentTransactions'=> $recentTransactions,
        'topProduct'        => $topProduct,
        'topProductQty'     => $topProductQty,
        'dates'             => $dates,
        'profitData'        => $profitData,
        'lossData'          => $lossData,
    ]);
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
    public function create()
    {
        // Ambil kategori untuk dropdown
        $categories = Category::all();
        return view('owner.newproduct', compact('categories'));
    }

public function store(Request $request)
{
    $request->validate([
        'nama_produk' => 'required',
        'harga' => 'required|numeric',
        'stok' => 'required|integer',
        'kategori_id' => 'nullable|exists:categories,id',
        'kode_produk' => 'required|unique:products,kode_produk',
        'modal_per_produk' => 'required|numeric',
    ]);

    // Jika kategori baru diinput, buat kategori baru
    if ($request->kategori_baru) {
        $kategori = Category::create([
            'nama_kategori' => $request->kategori_baru
        ]);
        $request->merge(['kategori_id' => $kategori->id]);
    }

    // Simpan produk
    $product = Product::create([
        'nama_produk' => $request->nama_produk,
        'harga' => $request->harga,
        'stok' => $request->stok,
        'kategori_id' => $request->kategori_id,
        'kode_produk' => $request->kode_produk,
        'modal_per_produk' => $request->modal_per_produk,
    ]);

    // Catat stok awal
    StockMovement::create([
        'product_id' => $product->id,
        'type' => 'restock',
        'qty' => $product->stok,
        'unit_cost' => $product->modal_per_produk,
        'total_cost' => $product->stok * $product->modal_per_produk,
        'created_by' => auth()->id(),
    ]);

    return redirect()->route('owner.products')->with('success', 'Produk berhasil ditambahkan.');
}

}

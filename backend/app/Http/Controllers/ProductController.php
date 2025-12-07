<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\StockMovement;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    
public function exportPdf(Request $request)
{
    $start = $request->start;
    $end   = $request->end;

    /* ---------------------------
       1. AMBIL DATA PEMASUKAN
       --------------------------- */
    $pemasukan = DB::table('sale_product')
        ->join('sales', 'sales.id', '=', 'sale_product.sale_id')
        ->join('products', 'products.id', '=', 'sale_product.product_id')
        ->select(
            'sales.created_at',
            'products.nama_produk',
            'sale_product.qty',
            'sale_product.subtotal as nominal'
        )
        ->when($start && $end, function ($q) use ($start, $end) {
            return $q->whereBetween('sales.created_at', [
                $start . " 00:00:00",
                $end   . " 23:59:59"
            ]);
        })
        ->get()
        ->map(function ($row) {
            $time = \Carbon\Carbon::parse($row->created_at);
            $row->tanggal = $time->format('d-m-Y');
            $row->waktu   = $time->format('H:i');
            $row->tipe    = "Pemasukan";
            return $row;
        });

    /* ---------------------------
       2. AMBIL DATA PENGELUARAN
       --------------------------- */
    $pengeluaran = DB::table('stock_movements')
        ->join('products', 'products.id', '=', 'stock_movements.product_id')
        ->select(
            'stock_movements.created_at',
            'products.nama_produk',
            'stock_movements.qty',
            'stock_movements.total_cost as nominal'
        )
        ->when($start && $end, function ($q) use ($start, $end) {
            return $q->whereBetween('stock_movements.created_at', [
                $start . " 00:00:00",
                $end   . " 23:59:59"
            ]);
        })
        ->get()
        ->map(function ($row) {
            $time = \Carbon\Carbon::parse($row->created_at);
            $row->tanggal = $time->format('d-m-Y');
            $row->waktu   = $time->format('H:i');
            $row->tipe    = "Pengeluaran";
            return $row;
        });

    /* ---------------------------
       3. GABUNGKAN TIMELINE
       --------------------------- */
    $laporan = $pemasukan->merge($pengeluaran)->sortBy('created_at');

    /* ---------------------------
       4. HITUNG TOTAL
       --------------------------- */
    $totalPemasukan   = $pemasukan->sum('nominal');
    $totalPengeluaran = $pengeluaran->sum('nominal');
    $totalAkhir       = $totalPemasukan - $totalPengeluaran;

    $pdf = \PDF::loadView('admin.laporan_pdf', [
        'laporan'          => $laporan,
        'totalPemasukan'   => $totalPemasukan,
        'totalPengeluaran' => $totalPengeluaran,
        'totalAkhir'       => $totalAkhir
    ]);

    return $pdf->download('laporan_penjualan.pdf');
}




    public function index(Request $request)
{
    // Ambil semua kategori untuk filter dropdown
    $categories = Category::all();

    // Mulai query produk (kita akan leftJoin categories saat perlu urut berdasarkan nama kategori)
    $query = Product::query()->select('products.*');

    // Filter pencarian (opsional)
    if ($request->filled('search')) {
        $query->where('nama_produk', 'like', '%' . $request->search . '%')
              ->orWhere('kode_produk', 'like', '%' . $request->search . '%');
    }

    // Filter kategori (gunakan name input 'category_id' di blade)
    if ($request->filled('category_id')) {
        $query->where('kategori_id', $request->category_id);
    }

    // Sorting
    if ($request->filled('sort')) {
        switch ($request->sort) {
            case 'name_asc':
                $query->orderBy('nama_produk', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('nama_produk', 'desc');
                break;
            case 'kode_asc':
                $query->orderBy('kode_produk', 'asc');
                break;
            case 'kode_desc':
                $query->orderBy('kode_produk', 'desc');
                break;
            case 'stock_asc':
                $query->orderBy('stok', 'asc');
                break;
            case 'stock_desc':
                $query->orderBy('stok', 'desc');
                break;
            case 'category_asc':
                // untuk sortir berdasarkan nama kategori kita join tabel categories
                $query->leftJoin('categories', 'products.kategori_id', '=', 'categories.id')
                      ->orderBy('categories.nama_kategori', 'asc');
                break;
            case 'category_desc':
                $query->leftJoin('categories', 'products.kategori_id', '=', 'categories.id')
                      ->orderBy('categories.nama_kategori', 'desc');
                break;
            default:
                // fallback: urutkan terbaru
                $query->orderBy('created_at', 'desc');
                break;
        }
    } else {
        // default order
        $query->orderBy('created_at', 'desc');
    }

    // Ambil produk — gunakan paginate agar lebih aman di UI (opsional)
    $products = $query->with('kategori')->get();
    // Jika ingin pagination: ->paginate(15) dan sesuaikan blade

    return view('products.allproduct', compact('products', 'categories'));
}

    public function create()
    {
        // Ambil kategori untuk dropdown
        $categories = Category::all();
        return view('products.newproduct', compact('categories'));
    }

    public function stock()
    {
        $products = Product::all();
        return view('products.stockmanage', compact('products'));
    }
    public function manageStock(Request $request)
{
    $products = Product::all();
    $editId = $request->edit; // Ambil ID produk jika ada

   return view('products.stockmanage', compact('products', 'editId'));
}


   public function store(Request $request)
{
    // Validasi input
    $request->validate([
        'nama_produk' => 'required',
        'harga' => 'required|numeric',
        'stok' => 'required|integer',
        'kategori_id' => 'required|exists:categories,id',
        'kode_produk' => 'required|unique:products,kode_produk',
        'modal_per_produk' => 'required|numeric',
    ]);

    // Simpan produk baru ke variabel $product
    $product = Product::create([
        'nama_produk' => $request->nama_produk,
        'harga' => $request->harga,
        'stok' => $request->stok,
        'kategori_id' => $request->kategori_id,
        'kode_produk' => $request->kode_produk,
        'modal_per_produk' => $request->modal_per_produk,
    ]);

    // Catat pergerakan stok awal
    StockMovement::create([
        'product_id' => $product->id,
        'type' => 'restock', // type 'initial' tidak ada di enum, gunakan restock
        'qty' => $product->stok,
        'unit_cost' => $product->modal_per_produk,
        'total_cost' => $product->stok * $product->modal_per_produk,
        'created_by' => auth()->id(),
    ]);

    return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
}


    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'nama_produk' => 'required',
            'harga' => 'required|numeric',
            'stok' => 'required|integer|min:' . $product->stok,
        'stok.min' => 'Stok tidak boleh berkurang dari stok sebelumnya (' . $product->stok . ')',
            'kategori_id' => 'required|exists:categories,id',
        ]);



        $product->update($request->all());

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }

  public function updateStock(Request $request, $id)
{
    $product = Product::findOrFail($id);

    $request->validate([
        'stok' => 'required|integer|min:' . $product->stok,
    ], [
        'stok.min' => 'Stok tidak boleh kurang dari stok sebelumnya (' . $product->stok . ')',
    ]);

    // Hitung selisih stok
    $qty = $request->stok - $product->stok;

    // Update stok
    $product->stok = $request->stok;
    $product->save();

    // Catat pergerakan stok
    StockMovement::create([
        'product_id' => $product->id,
        'type' => 'restock',
        'qty' => $qty,
        'unit_cost' => $product->modal_per_produk,
        'total_cost' => $qty * $product->modal_per_produk,
        'created_by' => auth()->id(),
    ]);

    return back()->with('success', 'Stok berhasil diperbarui');
}


    public function showExpired()
{
    // ambil produk yang mendekati expired (misal <=5 hari)
    $products = Product::where('expired_date', '<=', now()->addDays(5))->get();

    return view('cashier.expired', compact('products'));
}

public function updateExpired(Request $request)
{
    $expiredInput = $request->input('expired', []);

    foreach($expiredInput as $productId => $expiredQty){
        $product = Product::find($productId);
        if($product && $expiredQty > 0){
            // kurangi stok
            $product->stok = max(0, $product->stok - $expiredQty);
            $product->save();

            // catat di expired_products jika mau
            \App\Models\ExpiredProduct::create([
                'product_id' => $product->id,
                'entry_date' => now(),
                'expired_date' => $product->expired_date,
                'countdown_days' => 0,
                'is_expired' => true,
            ]);
        }
    }

    return redirect()->route('cashier.expired')->with('success', 'Stok expired berhasil diperbarui.');
}
    public function showTransaction()
{
    $products = Product::all();
    $transactions = \App\Models\TransactionHistory::latest()->take(50)->get(); // tampilkan 50 terakhir
    return view('cashier.transaction', compact('products','transactions'));
}

public function storeTransaction(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1',
        'amount_paid' => 'nullable|integer|min:0',
    ]);

    $product = Product::findOrFail($request->product_id);

    $total = $product->harga * $request->quantity;

    // kurangi stok
    $product->stok = max(0, $product->stok - $request->quantity);
    $product->save();

    // simpan ke sales / transaction_history
    $sale = \App\Models\Sale::create([
        'product_id' => $product->id,
        'quantity' => $request->quantity,
        'price' => $product->harga,
        'total' => $total,
        'cashier_id' => auth()->id(),
    ]);

    \App\Models\TransactionHistory::create([
        'sale_id' => $sale->id,
        'product_id' => $product->id,
        'quantity' => $request->quantity,
        'price' => $product->harga,
        'subtotal' => $total,
        'cashier_id' => auth()->id(),
    ]);

    return redirect()->route('cashier.transaction')->with('success', 'Transaksi berhasil disimpan.');
}



public function laporan(Request $request)
{
    // 1. Definisikan Query
    $query = DB::table('sales')
        ->join('sale_product', 'sales.id', '=', 'sale_product.sale_id')
        ->join('products', 'products.id', '=', 'sale_product.product_id')
        // TAMBAHAN: Join ke tabel categories untuk ambil nama kategori
        ->leftJoin('categories', 'products.kategori_id', '=', 'categories.id')
        ->select(
            'sales.id as sale_id',
            'sales.created_at',
            
            // PERBAIKAN 1: Gunakan 'nama_produk', bukan 'name'
            'products.nama_produk as product_name', 
            
            // PERBAIKAN 2: Ambil nama kategori dari tabel categories
            'categories.nama_kategori as category_name', 
            
            'sale_product.quantity',

            // Laba Kotor
            DB::raw('sale_product.subtotal as laba_kotor'),

            // Modal (Handle division by zero & null)
             // Laba Bersih
         );

    // 2. Filter Tanggal (Gunakan whereDate agar akurat)
    if ($request->filled('start') && $request->filled('end')) {
        $query->whereDate('sales.created_at', '>=', $request->start)
              ->whereDate('sales.created_at', '<=', $request->end);
    }
    
    // Filter Jenis (Jika ada input jenis)
    if ($request->filled('jenis')) {
         // Tambahkan logika filter jenis jika diperlukan
    }

    // 3. Sorting & Eksekusi
    $laporan = $query->orderBy('sales.created_at', 'desc')->get();

    // 4. Hitung Summary
    $totalTransaksi  = $laporan->count();
    $totalPendapatan = $laporan->sum('laba_kotor');
    $totalLabaBersih = $laporan->sum('laba_bersih');

    return view('laporanAdmin', compact('laporan', 'totalTransaksi', 'totalPendapatan', 'totalLabaBersih'));
}
public function performanceCategory(Request $request)
{
    $laporan = DB::table('sale_product')
        ->join('sales', 'sales.id', '=', 'sale_product.sale_id')
        ->join('products', 'products.id', '=', 'sale_product.product_id')
        ->leftJoin('categories', 'products.kategori_id', '=', 'categories.id')
        ->select(
            'products.id as product_id',
            'products.nama_produk as product_name',
            'categories.nama_kategori as category_name',
            DB::raw('SUM(sale_product.qty) as total_qty'),
            DB::raw('SUM(sale_product.subtotal) as total_laba_kotor'),
            DB::raw('MIN(sales.created_at) as first_sold'),
            DB::raw('MAX(sales.created_at) as last_sold')
        );

    // 🔥 FILTER PER PERIODE (default: none)
    if ($request->periode) {
        $days = $request->periode * 30;
        $laporan->where('sales.created_at', '>=', now()->subDays($days));
    }

    $laporan = $laporan
        ->groupBy('products.id', 'products.nama_produk', 'categories.nama_kategori')
        ->orderBy('total_qty', 'desc')
        ->get();

    return view('perform', compact('laporan'));
}

public function riwayatTransaksi(Request $request)
{
    // Ambil semua riwayat transaksi (pemasukan)
    $history = DB::table('sale_product')
        ->join('sales', 'sales.id', '=', 'sale_product.sale_id')
        ->join('products', 'products.id', '=', 'sale_product.product_id')
        ->select(
            'sales.created_at as waktu',
            'products.nama_produk',
            'sale_product.qty',
            DB::raw('sale_product.qty * sale_product.price as total')
        )
        ->orderBy('sales.created_at', 'desc')
        ->get();

    return view('admin.transaction_history', compact('history'));
}


public function adminDashboard()
{
    // ===========================
    // 1. Pendapatan per Hari
    // ===========================
    $sales = DB::table('sales')
        ->selectRaw("DATE(created_at) as date, SUM(total) as total_harian")
        ->groupByRaw("DATE(created_at)")
        ->orderBy('date', 'ASC')
        ->get();

    $dates  = $sales->pluck('date');
    $totals = $sales->pluck('total_harian');

    // ===========================
    // 2. Total Penjualan Harian & Bulanan
    // ===========================
    $today      = now()->toDateString();
    $month      = now()->month;
    $year       = now()->year;

    $todaySales = DB::table('sale_product')
        ->join('sales', 'sales.id', '=', 'sale_product.sale_id')
        ->whereDate('sales.created_at', $today)
        ->sum('sale_product.subtotal');

    $monthSales = DB::table('sale_product')
        ->join('sales', 'sales.id', '=', 'sale_product.sale_id')
        ->whereMonth('sales.created_at', $month)
        ->whereYear('sales.created_at', $year)
        ->sum('sale_product.subtotal');

    // ===========================
    // 3. Total Transaksi Hari Ini
    // ===========================
    $transactionsToday = DB::table('sales')
        ->whereDate('created_at', $today)
        ->count();

    // ===========================
    // 4. Total Produk
    // ===========================
    $totalProducts = DB::table('products')->count();

    // ===========================
    // 5. Produk Terlaris
    // ===========================
    $bestProduct = DB::table('sale_product')
        ->join('products', 'sale_product.product_id', '=', 'products.id')
        ->select('products.nama_produk', DB::raw('SUM(sale_product.qty) as total_jual'))
        ->groupBy('products.id', 'products.nama_produk')
        ->orderByDesc('total_jual')
        ->first();

    // ===========================
    // Return ke View
    // ===========================
    return view('admin', compact(
        'dates',
        'totals',
        'todaySales',
        'monthSales',
        'transactionsToday',
        'totalProducts',
        'bestProduct'
    ));
}

}

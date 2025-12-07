<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->start 
            ? Carbon::parse($request->start)->startOfDay()
            : Carbon::now()->subMonth()->startOfDay();

        $end = $request->end
            ? Carbon::parse($request->end)->endOfDay()
            : Carbon::now()->endOfDay();

        // Hitung total pemasukan
        $totalPemasukan = DB::table('sale_product')
    ->join('sales', 'sales.id', '=', 'sale_product.sale_id')
    ->whereBetween('sales.created_at', [$start, $end])
    ->selectRaw('SUM(sale_product.qty * sale_product.price) as total')
    ->value('total');


        // Hitung total pengeluaran
        $totalPengeluaran = StockMovement::whereBetween('created_at', [$start, $end])
            ->sum('total_cost');

        // Laba rugi
        $labaRugi = $totalPemasukan - $totalPengeluaran;

        // Data timeline
        $pemasukan = DB::table('sale_product')
    ->join('sales', 'sales.id', '=', 'sale_product.sale_id')
    ->join('products', 'products.id', '=', 'sale_product.product_id')
    ->whereBetween('sales.created_at', [$start, $end])
    ->select(
        'sale_product.sale_id as id',
        'products.nama_produk',
        'sale_product.qty as jumlah',
        DB::raw('sale_product.qty * sale_product.price as harga'),
        'sales.created_at'
    )
    ->get()
    ->map(function ($item) {
        $item->tipe = 'Pemasukan';
        return $item;
    });


        $pengeluaran = StockMovement::whereBetween('stock_movements.created_at', [$start, $end])
            ->select('id', 'qty as jumlah', 'total_cost as harga', 'created_at')
            ->join('products', 'products.id', '=', 'stock_movements.product_id')
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
                return $item;
            });

        // Gabungkan timeline
        $timeline = $pemasukan->merge($pengeluaran)->sortBy('created_at');

        return view('laporanAdmin', [
    'timeline' => $timeline,
    'totalPemasukan' => $totalPemasukan,
    'totalPengeluaran' => $totalPengeluaran,
    'labaRugi' => $labaRugi,
    'start' => $start,
    'end' => $end,
        ]);
    }
    public function index2(Request $request)
    {
// Filter default: 1 bulan terakhir
        $start = $request->start 
            ? Carbon::parse($request->start)->startOfDay()
            : Carbon::now()->subMonth()->startOfDay();

        $end = $request->end
            ? Carbon::parse($request->end)->endOfDay()
            : Carbon::now()->endOfDay();

        // Hitung total pemasukan
        $totalPemasukan = DB::table('sale_product')
    ->join('sales', 'sales.id', '=', 'sale_product.sale_id')
    ->whereBetween('sales.created_at', [$start, $end])
    ->selectRaw('SUM(sale_product.qty * sale_product.price) as total')
    ->value('total');


        // Hitung total pengeluaran
        $totalPengeluaran = StockMovement::whereBetween('created_at', [$start, $end])
            ->sum('total_cost');

        // Laba rugi
        $labaRugi = $totalPemasukan - $totalPengeluaran;

        // Data timeline
        $pemasukan = DB::table('sale_product')
    ->join('sales', 'sales.id', '=', 'sale_product.sale_id')
    ->join('products', 'products.id', '=', 'sale_product.product_id')
    ->whereBetween('sales.created_at', [$start, $end])
    ->select(
        'sale_product.sale_id as id',
        'products.nama_produk',
        'sale_product.qty as jumlah',
        DB::raw('sale_product.qty * sale_product.price as harga'),
        'sales.created_at'
    )
    ->get()
    ->map(function ($item) {
        $item->tipe = 'Pemasukan';
        return $item;
    });


        $pengeluaran = StockMovement::whereBetween('stock_movements.created_at', [$start, $end])
            ->select('id', 'qty as jumlah', 'total_cost as harga', 'created_at')
            ->join('products', 'products.id', '=', 'stock_movements.product_id')
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
                return $item;
            });

        // Gabungkan timeline
        $timeline = $pemasukan->merge($pengeluaran)->sortBy('created_at');

        return view('owner_sale', [
    'timeline' => $timeline,
    'totalPemasukan' => $totalPemasukan,
    'totalPengeluaran' => $totalPengeluaran,
    'labaRugi' => $labaRugi,
    'start' => $start,
    'end' => $end,
]);

    }

    // ================================
    //          EXPORT LAPORAN
    // ================================
    public function export(Request $request)
{
    // Ambil data dari query yang sama dengan index
        $laporan = DB::table('sales')
    ->join('sale_product', 'sales.id', '=', 'sale_product.sale_id')
    ->join('products', 'products.id', '=', 'sale_product.product_id')
    ->join('categories', 'categories.id', '=', 'products.kategori_id')
    ->leftJoin('products.id')
    ->select(
        'sales.created_at',
        'products.nama_produk as product_name',
        'categories.nama_kategori as category_name',
        'sale_product.quantity',
        DB::raw('sale_product.subtotal as laba_kotor'),
      )
    ->orderBy('sales.created_at', 'desc')
    ->get();


    // Nama file
    $filename = "laporan-" . date('Y-m-d') . ".csv";

    // Header CSV
    $header = [
        "Tanggal/Waktu",
        "Produk",
        "Kategori",
        "Qty",
        "Pendapatan",
        "Modal",
        "Laba Bersih"
    ];

    // Convert data ke CSV string
    $rows = [];
    $rows[] = implode(",", $header);

    foreach ($laporan as $row) {
        $rows[] = implode(",", [
            $row->created_at,
            $row->product_name,
            $row->category_name,
            $row->quantity,
        ]);
    }

    $csvData = implode("\n", $rows);

    // Download response tanpa package
    return response($csvData)
        ->header('Content-Type', 'text/csv')
        ->header("Content-Disposition", "attachment; filename=$filename");
}

}
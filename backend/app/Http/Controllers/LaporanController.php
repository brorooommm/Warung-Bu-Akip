<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // 1. Inisialisasi Query Builder
        $query = DB::table('sales')
            ->join('sale_product', 'sales.id', '=', 'sale_product.sale_id')
            ->join('products', 'products.id', '=', 'sale_product.product_id')
            // Tambahkan join ke Categories untuk mengambil nama kategori
            ->leftJoin('categories', 'products.kategori_id', '=', 'categories.id')
            ->select(
                'sales.id as sale_id',
                'sales.created_at',
                
                // 🔥 FIX: Menggunakan nama kolom yang benar dari DB
                'products.nama_produk as product_name', 
                'categories.nama_kategori as category_name', 
                
                'sale_product.quantity',

                // Laba Kotor (Pendapatan)
                DB::raw('sale_product.subtotal as laba_kotor'),

                // Modal (Handle division by zero & null)
            );
        
        // 2. Terapkan Filter Tanggal
        if ($request->filled('start') && $request->filled('end')) {
            // 🔥 FIX: Menggunakan whereDate agar filtering timestamp akurat
            $query->whereDate('sales.created_at', '>=', $request->start)
                  ->whereDate('sales.created_at', '<=', $request->end);
        }

        // 3. Terapkan Filter Jenis (Jika ada fitur pengeluaran/pengembalian)
        if ($request->filled('jenis') && $request->jenis == 'pengeluaran') {
            // Logika filter pengeluaran (misalnya join ke tabel 'expenses')
            // ...
        }

        // 4. Eksekusi Query
        $laporan = $query->orderBy('sales.created_at', 'desc')->get();

        // 5. Hitung Summary
        $totalTransaksi  = $laporan->count();
        $totalPendapatan = $laporan->sum('laba_kotor');
        $totalLabaBersih = $laporan->sum('laba_bersih');

        return view('laporanAdmin', compact(
            'laporan',
            'totalTransaksi',
            'totalPendapatan',
            'totalLabaBersih'
        ));
    }
public function index2(Request $request)
    {
        $laporan = DB::table('sales')
            ->join('sale_product', 'sales.id', '=', 'sale_product.sale_id')
            ->join('products', 'products.id', '=', 'sale_product.product_id')
            ->select(
                'sales.id as sale_id',
                'sales.created_at',
                'products.nama_produk as product_name',
                'products.kategori_id as category_name',
                'sale_product.quantity',

                // Laba kotor tetap
                DB::raw('sale_product.subtotal as laba_kotor'),

                // Modal (aman jika warehouse null)
        

                // Laba bersih aman dari null
                
            )
            ->orderBy('sales.created_at', 'desc');

        // Filter tanggal
        if ($request->start && $request->end) {
            $laporan->whereBetween('sales.created_at', [$request->start, $request->end]);
        }

        $laporan = $laporan->get();

        // Summary
        $totalTransaksi = $laporan->count();
        $totalPendapatan = $laporan->sum('laba_kotor');
        $totalLabaBersih = $laporan->sum('laba_bersih');

        return view('owner_sale', compact(
            'laporan',
            'totalTransaksi',
            'totalPendapatan',
            'totalLabaBersih'
        ));
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
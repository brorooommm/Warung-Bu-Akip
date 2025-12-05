<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;

class CashierReceiptController extends Controller
{
    public function downloadLatest()
    {
        // Ambil transaksi terbaru
        $transaction = Sale::with('products')->latest()->first();

        if (!$transaction) {
            return back()->with('error', 'Tidak ada transaksi!');
        }

        // Load view struk
        $pdf = Pdf::loadView('cashier.receipt_pdf', compact('transaction'))
            ->setPaper('A4');

        return $pdf->download('struk-' . $transaction->id . '.pdf');
    }
}

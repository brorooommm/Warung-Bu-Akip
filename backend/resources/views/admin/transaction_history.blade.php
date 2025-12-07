
@extends('layouts.admin')

@section('content')

<style>
    /* ============================
        FORCE TABLE STYLE (OVERRIDE)
       ============================ */

    table.table {
        width: 100% !important;
        border-collapse: collapse !important;
        background: white !important;
        border: 1px solid #cfcfcf !important;
        font-size: 14px !important;
    }

    table.table thead th {
        background: #198754 !important; /* hijau admin */
        color: white !important;
        font-weight: bold !important;
        border: 1px solid #166a42 !important;
        padding: 10px !important;
        text-align: left !important;
    }

    table.table tbody td {
        border: 1px solid #ddd !important;
        padding: 10px !important;
        color: #333 !important;
    }

    table.table tbody tr:nth-child(even) {
        background: #f8f9fa !important;
    }

    table.table tbody tr:hover {
        background: #e9f7ee !important;
        transition: 0.2s !important;
    }

    .card {
        border-radius: 10px !important;
        overflow: hidden !important;
        border: none !important;
        box-shadow: 0px 2px 8px rgba(0,0,0,0.08) !important;
    }

    .card-header {
        background: #145c32 !important;
        color: white !important;
        font-weight: bold !important;
        padding: 15px !important;
        font-size: 16px !important;
    }
</style>


<div class="container mt-4">
    <h3 class="mb-3"><i class="fas fa-receipt me-2"></i> Riwayat Transaksi</h3>

    <div class="card">
        <div class="card-header">
            <strong>Data Transaksi</strong>
        

        <div class="card-body">

            @if($history->count() == 0)
                <p class="text-center text-muted">Belum ada transaksi.</p>
            @else

            <table class="table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Produk</th>
                        <th>Jumlah</th>
                        <th>Total</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($history as $row)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($row->waktu)->format('d M Y H:i') }}</td>
                        <td>{{ $row->nama_produk }}</td>
                        <td>{{ $row->qty }}</td>
                        <td>Rp {{ number_format($row->total, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>

            </table>

            @endif
        </div>
        </div>
    </div>
</div>



@endsection

@extends('layouts.admin')

@section('content')

<div id="sale-report" class="dashboard-container p-6">
    <h1 class="mt-4">Laporan Keuangan</h1>

    {{-- FILTER --}}
    <div class="filter-box">
        <form method="GET" class="filter-form">

            <div class="filter-group">
                <label for="start">Dari</label>
                <input type="date" id="start" name="start" value="{{ $start->format('Y-m-d') }}">
            

            
                <label for="end">Sampai</label>
                <input type="date" id="end" name="end" value="{{ $end->format('Y-m-d') }}">
            

            
                <button type="submit" class="btn btn-primary">Terapkan</button>
                <a href="{{ route('laporan.export.pdf', ['start'=>$start->format('Y-m-d'),'end'=>$end->format('Y-m-d')]) }}" class="btn btn-download">
                    Download PDF
                </a>
            </div>
        </form>
    </div>



    {{-- SUMMARY --}}
    <div class="row mb-4 summary-row">

    <div class="col-md-4">
        <div class="summary-card income">
            <div class="label">Total Pemasukan</div>
            <div class="value">Rp {{ number_format($totalPemasukan,0,',','.') }}</div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="summary-card expense">
            <div class="label">Total Pengeluaran</div>
            <div class="value">Rp {{ number_format($totalPengeluaran,0,',','.') }}</div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="summary-card profit">
            <div class="label">Laba / Rugi</div>
            <div class="value">Rp {{ number_format($labaRugi,0,',','.') }}</div>
        </div>
    </div>

</div>



    {{-- TABLE --}}
    <div class="card">
        <div class="card-header">
            <i class="fas fa-history me-2"></i> Riwayat Keuangan
        </div>

        <div class="card-body">

            @if($timeline->count() == 0)
                <p class="text-center text-muted mt-2">Tidak ada data dalam periode ini.</p>
            @else
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Tipe</th>
                        <th>Produk</th>
                        <th>Jumlah</th>
                        <th>Nominal</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($timeline as $row)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($row->created_at)->format('d M Y H:i') }}</td>
                        <td>
                            <span class="badge {{ $row->tipe == 'Pemasukan' ? 'bg-success' : 'bg-danger' }}">
                                {{ $row->tipe }}
                            </span>
                        </td>
                        <td>{{ $row->nama_produk }}</td>
                        <td>{{ $row->jumlah }}</td>
                        <td>Rp {{ number_format($row->harga,0,',','.') }}</td>
                    </tr>
                    @endforeach
                </tbody>

            </table>
            @endif

        </div>
    </div>

</div>
<style>
/* ============================= */
/* SUPER OVERRIDE CSS FIX FINAL  */
/* ============================= */

#sale-report * {
    all: unset;
    all: revert !important;
}

/* ----------- FILTER BOX ----------- */
#sale-report .filter-box {
    background: #ffffff !important;
    border-radius: 12px !important;
    padding: 22px !important;
    border: 1px solid #e5e5e5 !important;
    box-shadow: 0 3px 10px rgba(0,0,0,0.10) !important;
    margin-bottom: 25px !important;
}

#sale-report .filter-box label {
    font-weight: 600 !important;
    color: #333 !important;
    margin-bottom: 6px !important;
}

#sale-report input[type="date"] {
    background: #ffffff !important;
    color: #333 !important;
    padding: 10px 12px !important;
    height: 45px !important;
    border-radius: 8px !important;
    border: 1px solid #bbbbbb !important;
}

#sale-report input[type="date"]:focus {
    border-color: #467ce8 !important;
    box-shadow: 0 0 5px rgba(70,124,232,0.5) !important;
}

/* Buttons */
#sale-report button,
#sale-report .btn-primary,
#sale-report .btn-download {
    display: inline-block !important;
    padding: 10px 16px !important;
    font-weight: 600 !important;
    font-size: 15px !important;
    border-radius: 8px !important;
    border: none !important;
    text-align: center !important;
    cursor: pointer !important;
}

#sale-report .btn-primary {
    background: #5a8dee !important;
    color: #fff !important;
}
#sale-report .btn-primary:hover {
    background: #467ce8 !important;
}

#sale-report .btn-download {
    background: #28a745 !important;
    color: #fff !important;
}
#sale-report .btn-download:hover {
    background: #1e8837 !important;
}

/* ----------- SUMMARY CARDS ----------- */
#sale-report .card {
    border-radius: 14px !important;
    border: none !important;
    box-shadow: 0 3px 10px rgba(0,0,0,0.12) !important;
}
#sale-report .card-body {
    padding: 20px 24px !important;
}
#sale-report .card-title {
    font-weight: 600 !important;
    margin-bottom: 8px !important;
}
#sale-report .card-body h3 {
    font-size: 24px !important;
    font-weight: 700 !important;
}

/* ----------- TABLE ----------- */
#sale-report table {
    border-radius: 12px !important;
    overflow: hidden !important;
    width: 100% !important;
    border-collapse: collapse !important;
}

#sale-report thead tr {
    background: #344675 !important;
    color: white !important;
}

#sale-report th,
#sale-report td {
    padding: 14px !important;
    font-size: 15px !important;
    border-bottom: 1px solid #e6e6e6 !important;
}

#sale-report tbody tr:hover {
    background: #f4f6fa !important;
}
</style>
<style>
/* ============================= */
/* SUMMARY CARD FIX (compact)    */
/* ============================= */

#sale-report .summary-row {
    margin-top: 10px !important;
    margin-bottom: 10px !important;
}

#sale-report .summary-card {
    background: linear-gradient(135deg, #ffffff 0%, #f5f7fa 100%) !important;
    border-radius: 14px !important;
    padding: 16px 20px !important;
    box-shadow: 0 3px 8px rgba(0,0,0,0.1) !important;
    border-left: 6px solid #ccc !important;
    transition: 0.2s ease !important;
}

#sale-report .summary-card:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 16px rgba(0,0,0,0.15) !important;
}

#sale-report .summary-card .label {
    font-size: 14px !important;
    font-weight: 600 !important;
    color: #555 !important;
    margin-bottom: 6px !important;
}

#sale-report .summary-card .value {
    font-size: 22px !important;
    font-weight: 800 !important;
    color: #2d2d2d !important;
}

/* COLOR ACCENTS */
#sale-report .summary-card.income {
    border-left-color: #28a745 !important;
}
#sale-report .summary-card.expense {
    border-left-color: #dc3545 !important;
}
#sale-report .summary-card.profit {
    border-left-color: #007bff !important;
}
#sale-report .filter-box {
    background: #fff;
    border-radius: 10px;
    padding: 20px;
    border: 1px solid #dedede;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
}

#sale-report .filter-form {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    align-items: flex-end;
}

#sale-report .filter-group {
    display: flex;
    flex-direction: column;
}

#sale-report .filter-group label {
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 4px;
}

#sale-report .filter-group input[type="date"] {
    width: 180px;
    height: 40px;
    padding: 6px 10px;
    border-radius: 6px;
    border: 1px solid #c8c8c8;
    background: #fff;
    color: #333;
}
#sale-report .filter-group input[type="date"]:focus {
    border-color: #4a80ff;
    box-shadow: 0 0 5px rgba(74,128,255,0.4);
    outline: none;
}
#sale-report .filter-group first-child {
    margin-right: 20px;
}

#sale-report .filter-actions {
    display: flex;
    gap: 12px;
}

#sale-report .filter-actions .btn {
    height: 40px;
    padding: 6px 14px;
    border-radius: 6px;
    font-weight: 600;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
}

#sale-report .filter-group .btn-primary {
    background: #4a80ff;
    color: #fff;
    border: none;
}

#sale-report .filter-group .btn-download {
    background: #28a745;
    color: #fff;
    border: none;
    text-decoration: none;
}
#sale-report .filter-group .btn-primary:hover {
    background: #366edc;
}
</style>

@endsection
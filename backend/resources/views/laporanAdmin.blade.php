@extends('layouts.admin')

@section('title', 'Laporan')

@section('content')

<div class="w-full flex flex-col gap-6">

    {{-- PANEL PEMBUKA / HEADER --}}
    <div class="w-full bg-white shadow-md rounded-xl p-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Laporan Penjualan</h1>
            <p class="text-gray-500 text-sm mt-1">Ringkasan data penjualan & transaksi</p>
        </div>
        <a href="{{ route('laporan.export.pdf') }}"
   class="text-green-700 text-xl font-bold hover:text-green-800 hover:underline">
    <i class="fa-solid fa-file-invoice"></i> Laporan
</a>

    </div>

    {{-- SUMMARY CARD --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-5">
    <div class="bg-green-100 p-5 rounded-2xl shadow relative">
        <div class="text-gray-700 text-sm">Total Transaksi</div>
        <div class="text-3xl font-bold text-green-700 mt-1">{{ $totalTransaksi }}</div>
        <div class="absolute right-5 top-1/2 -translate-y-1/2 bg-green-700 text-white w-12 h-12 rounded-xl flex items-center justify-center">
            <i class="fa-solid fa-cash-register text-xl"></i>
        </div>
    </div>

    <div class="bg-white p-5 rounded-2xl shadow relative">
        <div class="text-gray-700 text-sm">Total Pendapatan</div>
        <div class="text-3xl font-bold text-green-700 mt-1">Rp {{ number_format($totalPendapatan,0,',','.') }}</div>
        <div class="absolute right-5 top-1/2 -translate-y-1/2 bg-green-700 text-white w-12 h-12 rounded-xl flex items-center justify-center">
            <i class="fa-solid fa-money-bill-wave text-xl"></i>
        </div>
    </div>

    <div class="bg-white p-5 rounded-2xl shadow relative">
        <div class="text-gray-700 text-sm">Laba Bersih</div>
        <div class="text-3xl font-bold text-green-700 mt-1">Rp 0</div>
        <div class="absolute right-5 top-1/2 -translate-y-1/2 bg-green-700 text-white w-12 h-12 rounded-xl flex items-center justify-center">
            <i class="fa-solid fa-coins text-xl"></i>
        </div>
    </div>
</div>

{{-- FILTER --}}
<form method="GET" action="{{ route('laporan.index') }}" class="bg-white rounded-xl shadow p-6">
    <h2 class="font-semibold text-gray-700 mb-4">Filter Laporan</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="text-sm text-gray-600">Tanggal Mulai</label>
            <input type="date" name="start" value="{{ request('start') }}" class="w-full mt-1 border-gray-300 rounded-lg p-2">
        </div>
        <div>
            <label class="text-sm text-gray-600">Tanggal Akhir</label>
            <input type="date" name="end" value="{{ request('end') }}" class="w-full mt-1 border-gray-300 rounded-lg p-2">
        </div>
        <div>
            <label class="text-sm text-gray-600">Jenis Laporan</label>
            <select name="jenis" class="w-full mt-1 border-gray-300 rounded-lg p-2">
                <option value="">Semua</option>
                <option value="penjualan" {{ request('jenis')=='penjualan'?'selected':'' }}>Penjualan</option>
                <option value="pengeluaran" {{ request('jenis')=='pengeluaran'?'selected':'' }}>Pengeluaran</option>
            </select>
        </div>
    </div>
    <div class="mt-4 flex justify-end">
        <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800">
            Terapkan Filter
        </button>
    </div>
</form>


       
    </div>

    {{-- TABEL LAPORAN --}}
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="font-semibold text-gray-700 mb-4">Data Laporan</h2>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b">
                        <th class="p-3">Tanggal</th>
                        <th class="p-3">Waktu</th>
                        <th class="p-3">Laba Kotor</th>
                        <th class="p-3">Modal/Pengeluaran</th>
                        <th class="p-3">Laba Bersih</th>
                        <th class="p-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
    @forelse ($laporan as $row)
        <tr class="border-b hover:bg-gray-100">
            <td class="p-3">{{ date('Y-m-d', strtotime($row->created_at)) }}</td>
            <td class="p-3">{{ date('H:i', strtotime($row->created_at)) }}</td>

            <td class="p-3">Rp {{ number_format($row->laba_kotor, 0, ',', '.') }}</td>

            <td class="p-3">
                Rp {{ number_format($row->modal ?? 0, 0, ',', '.') }}
            </td>

            <td class="p-3">
                Rp 0
            </td>

            <td class="p-3">
                <i class="fa-solid fa-pen-to-square mr-3 cursor-pointer text-blue-600"></i>
                <i class="fa-solid fa-trash cursor-pointer text-red-600"></i>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="6" class="text-center p-3 text-gray-500">
                Tidak ada data laporan.
            </td>
        </tr>
    @endforelse
</tbody>

            </table>
        </div>
    </div>

</div>

@endsection

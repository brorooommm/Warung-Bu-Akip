@extends('owner.layout')

@section('title', 'Laporan Penjualan')

@section('content')
<link rel="stylesheet" href="/css/owner_sale.css">

<div class="dashboard-container flex flex-col gap-6 p-6">

    {{-- FILTER + EXPORT --}}
    <div class="flex justify-between items-center mb-4 filter-bar">

        
        {{-- Filter kiri --}}
           <div class="flex items-center gap-3">
        <div class="relative">
            <input type="date"
                class="border border-gray-300 rounded-lg px-4 py-2 w-44 focus:ring-2 focus:ring-green-400 focus:border-green-400 transition">
            <i class="far fa-calendar-alt absolute right-3 top-1/2 -translate-y-1/2 text-gray-500"></i>
        </div>

        <button class="export-btn flex items-center gap-2">
            Terapkan
        </button>
    </div>

        {{-- Export kanan --}}
         <div class="flex">
        <a href="{{ route('owner.products.export.pdf') }}"
            class="export-btn flex items-center gap-2">
            Export PDF
        </a>
    </div>

</div>

    {{-- TABEL LAPORAN --}}
    <div class="bg-white rounded-lg shadow p-4">

        <table class="w-full border-collapse text-left">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-4 py-3 border-b">Tanggal</th>
                    <th class="px-4 py-3 border-b">Waktu</th>
                    <th class="px-4 py-3 border-b">Pendapatan</th>
                    <th class="px-4 py-3 border-b">Produk</th>
                    <th class="px-4 py-3 border-b">Laba Kotor</th>
                    <th class="px-4 py-3 border-b">Laba Bersih</th>
                    <th class="px-4 py-3 border-b">Aksi</th>
                </tr>
            </thead>

            <tbody class="text-gray-700">

                @forelse ($laporan as $item)
                <tr class="hover:bg-yellow-50 transition border-b">
                    <td class="px-4 py-2">{{ date('d-m-Y', strtotime($item->created_at)) }}</td>
                    <td class="px-4 py-2">{{ date('H:i', strtotime($item->created_at)) }}</td>

                    {{-- Pendapatan --}}
                    <td class="px-4 py-2">{{ number_format($item->laba_kotor) }}</td>

                    {{-- Diskon --}}
                    <td class="px-4 py-2">{{($item->product_name)}}</td>

                    {{-- Laba Kotor --}}
                    <td class="px-4 py-2">{{ number_format($item->laba_kotor) }}</td>

                    {{-- Laba Bersih --}}
                    <td class="px-4 py-2">0</td>

                    <td class="px-4 py-2 flex gap-2 action-icons">

                        <i class="fas fa-pen-to-square text-blue-600 hover:text-blue-800 cursor-pointer transition"></i>
                        <i class="fas fa-trash-can text-red-600 hover:text-red-800 cursor-pointer transition"></i>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-gray-500">
                        Tidak ada data untuk tanggal ini.
                    </td>
                </tr>
                @endforelse

            </tbody>
        </table>

        {{-- PAGINATION --}}
        <div class="flex justify-center mt-4 pagination">
            <nav class="flex gap-2">
                <button class="px-3 py-1 rounded bg-gray-200 hover:bg-gray-300 transition">Previous</button>
                <button class="px-3 py-1 rounded bg-gray-100 text-gray-700 font-semibold">1</button>
                <button class="px-3 py-1 rounded bg-gray-200 hover:bg-gray-300 transition">2</button>
                <button class="px-3 py-1 rounded bg-gray-200 hover:bg-gray-300 transition">3</button>
                <button class="px-3 py-1 rounded bg-gray-200 hover:bg-gray-300 transition">Next</button>
            </nav>
        </div>

    </div>

</div>
@endsection

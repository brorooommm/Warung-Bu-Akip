@extends('layouts.admin')

@section('title', 'Product Performance')

@section('content')

<div class="w-full flex flex-col gap-6">

    {{-- FILTER PERIODE --}}
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="font-semibold text-gray-700 mb-4">Filter Periode</h2>

        <form action="{{ route('perform') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                {{-- Select Periode --}}
                <div>
                    <label class="text-sm text-gray-600">Periode Waktu</label>
                    <select name="periode" class="w-full mt-2 border-gray-300 rounded-lg p-2">
                        <option value="">Semua</option>
                        <option value="1" {{ request('periode') == 1 ? 'selected' : '' }}>Bulanan (30 hari)</option>
                        <option value="3" {{ request('periode') == 3 ? 'selected' : '' }}>3 Bulanan (90 hari)</option>
                        <option value="6" {{ request('periode') == 6 ? 'selected' : '' }}>6 Bulanan (180 hari)</option>
                        <option value="12" {{ request('periode') == 12 ? 'selected' : '' }}>Tahunan (365 hari)</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 w-full md:w-auto">
                        Terapkan
                    </button>
                </div>

            </div>
        </form>
    </div>

    {{-- TABEL PERFORMA PRODUK --}}
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="font-semibold text-gray-700 mb-4">Top Produk Terlaris</h2>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b bg-gray-50">
                        <th class="p-3">Produk</th>
                        <th class="p-3">Kategori</th>
                        <th class="p-3">Stok Terjual</th>
                        <th class="p-3">Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($laporan as $row)
                        <tr class="border-b hover:bg-gray-100">

                            <td class="p-3">{{ $row->product_name }}</td>
                            <td class="p-3">{{ $row->category_name }}</td>
                            <td class="p-3">{{ $row->quantity }}</td>

                            <td class="p-3">
                                Rp {{ number_format($row->laba_kotor, 0, ',', '.') }}
                            </td>

                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>

</div>

@endsection

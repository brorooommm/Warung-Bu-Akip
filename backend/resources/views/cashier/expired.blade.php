@extends('layouts.cashier')

@section('title', 'Laporan Expired')
@section('topbar', 'Laporan Expired')

@section('content')
<div class="w-full min-h-full p-6 bg-gray-50 flex flex-col">
    <h2 class="text-3xl font-bold mb-6 text-gray-800">Laporan Tanggal Kadaluarsa</h2>

    <form method="POST" action="{{ route('cashier.expired.update') }}">
        @csrf

        <div class="flex-1 overflow-auto">
            <table class="w-full bg-white border border-gray-200 rounded-lg shadow-md text-lg">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold text-gray-700 border-b">#</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-700 border-b">Nama Produk</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-700 border-b">Kategori</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-700 border-b">Stok</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-700 border-b">Tanggal Expired</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-700 border-b">Jumlah Expired</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($products as $product)
                        @php
                            $daysLeft = now()->diffInDays($product->expired_date, false);
                        @endphp
                        <tr class="{{ $daysLeft <= 5 ? 'bg-red-100' : 'bg-white' }}">
                            <td class="px-6 py-4">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4">{{ $product->nama_produk }}</td>
                            <td class="px-6 py-4">{{ $product->kategori->nama_kategori ?? '-' }}</td>
                            <td class="px-6 py-4 font-semibold">{{ $product->stok }}</td>
                            <td class="px-6 py-4">{{ $product->expired_date->format('d-m-Y') }}</td>
                            <td class="px-6 py-4">
                                <input type="number" min="0" max="{{ $product->stok }}" name="expired[{{ $product->id }}]" value="0" class="border border-gray-300 rounded-lg px-2 py-1 w-20">
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-600">Tidak ada produk mendekati expired.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            <button type="submit" class="bg-red-500 text-white px-6 py-2 rounded-lg hover:bg-red-600 transition">Update Stok Expired</button>
        </div>
    </form>
</div>
@endsection

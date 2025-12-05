@extends('layouts.cashier')

@section('title', 'Produk')
@section('topbar', 'Daftar Produk')

@section('content')
<div class="card">
    <form method="GET" action="{{ route('cashier.products') }}" 
      class="flex flex-wrap items-center gap-4 mb-6">

    {{-- Input Search --}}
    <input type="text" 
           name="search" 
           placeholder="Cari produk..." 
           value="{{ request('search') }}"
           class="border border-gray-300 rounded-lg px-4 py-2 w-64
                  focus:ring-2 focus:ring-blue-400 focus:border-blue-400" />

    {{-- Filter Kategori --}}
    <select name="category_id" 
            class="border border-gray-300 rounded-lg px-4 py-2
                   focus:ring-2 focus:ring-blue-400">
        <option value="">Semua Kategori</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}" 
                {{ request('category_id') == $category->id ? 'selected' : '' }}>
                {{ $category->nama_kategori }}
            </option>
        @endforeach
    </select>

    {{-- Sorting --}}
    <select name="sort" 
            class="border border-gray-300 rounded-lg px-4 py-2
                   focus:ring-2 focus:ring-blue-400">
        <option value="">Urutkan</option>
        <option value="nama_asc" {{ request('sort')=='nama_asc'?'selected':'' }}>Nama A → Z</option>
        <option value="nama_desc" {{ request('sort')=='nama_desc'?'selected':'' }}>Nama Z → A</option>
        <option value="stok_asc" {{ request('sort')=='stok_asc'?'selected':'' }}>Stok Terendah</option>
        <option value="stok_desc" {{ request('sort')=='stok_desc'?'selected':'' }}>Stok Tertinggi</option>
    </select>

    {{-- Tombol Filter --}}
    <button type="submit" 
            class="bg-blue-600 text-white px-4 py-2 rounded-lg
                   hover:bg-blue-700 transition shadow-sm">
        Terapkan
    </button>

    {{-- Reset --}}
    <a href="{{ route('cashier.products') }}" 
       class="text-gray-600 hover:text-gray-800 font-semibold underline">
       Reset
    </a>

</form>



    <div class="table-container">
        <table class="min-w-full text-left border-collapse border border-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border-b">#</th>
                    <th class="px-4 py-2 border-b">Nama Produk</th>
                    <th class="px-4 py-2 border-b">Kategori</th>
                    <th class="px-4 py-2 border-b">Harga</th>
                    <th class="px-4 py-2 border-b">Stok</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr class="{{ $product->stok < 15 ? 'bg-red-100' : '' }}">
                    <td class="px-4 py-2 border-b">{{ $loop->iteration }}</td>
                    <td class="px-4 py-2 border-b">{{ $product->nama_produk }}</td>
                    <td class="px-4 py-2 border-b">{{ $product->kategori->nama_kategori ?? '-' }}</td>
                    <td class="px-4 py-2 border-b">Rp {{ number_format($product->harga,0,',','.') }}</td>
                    <td class="px-4 py-2 border-b font-semibold">{{ $product->stok }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-2 text-center text-gray-500">Tidak ada produk ditemukan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

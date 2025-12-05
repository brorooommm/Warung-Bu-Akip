@extends('layouts.admin')

@section('title', 'Semua Produk')

@section('content')
<div class="w-full min-h-screen p-6 bg-gray-50 flex flex-col">
    <h2 class="text-3xl font-bold mb-6 text-gray-800">Daftar Produk</h2>

    {{-- 🔍 Filter dan Pencarian --}}
    <form method="GET" action="{{ route('products.index') }}" 
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
            <option value="name_asc" {{ request('sort')=='name_asc'?'selected':'' }}>Nama A → Z</option>
            <option value="name_desc" {{ request('sort')=='name_desc'?'selected':'' }}>Nama Z → A</option>
            <option value="kode_asc" {{ request('sort')=='kode_asc'?'selected':'' }}>Kode Keatas</option>
            <option value="kode_desc" {{ request('sort')=='kode_desc'?'selected   ':'' }}>Kode Kebawah</option>
            <option value="stock_asc" {{ request('sort')=='stock_asc'?'selected':'' }}>Stok Terendah</option>
            <option value="stock_desc" {{ request('sort')=='stock_desc'?'selected':'' }}>Stok Tertinggi</option>
        </select>

        {{-- Tombol Filter --}}
        <button type="submit" 
                class="bg-blue-600 text-white px-4 py-2 rounded-lg
                       hover:bg-blue-700 transition shadow-sm">
            Terapkan
        </button>

        {{-- Reset --}}
        <a href="{{ route('products.index') }}" 
           class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg
                  hover:bg-red-600 transition shadow-sm">
           Reset
        </a>


        {{-- Tambah Produk --}}
        <a href="{{ route('products.create') }}" 
           class="ml-auto bg-green-600 text-white px-4 py-2 rounded-lg
                  hover:bg-gray-700 transition shadow-sm">
           + Tambah Produk
        </a>

    </form>

    {{-- 📦 Tabel Produk --}}
    <div class="flex-1 overflow-auto">
        <table class="w-full bg-white border border-gray-200 rounded-lg shadow-md text-lg">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700 border-b">#</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700 border-b">Nama Produk</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700 border-b">Kode Produk</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700 border-b">Kategori</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700 border-b">Harga</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700 border-b">Harga Modal</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700 border-b">Stok</th>
                    <th class="px-8 py-3 text-left font-semibold text-gray-700 border-b">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($products as $product)
                <tr 
                    class="transition duration-200 
                           hover:bg-red-600 
                           {{ $product->stok < 15 ? 'bg-red-500 text-black' : 'bg-white text-gray-800' }}">
                    <td class="px-6 py-4">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4">{{ $product->nama_produk }}</td>
                    <td class="px-6 py-4">{{ $product->kode_produk }}</td>
                    <td class="px-6 py-4">{{ $product->kategori->nama_kategori ?? '-' }}</td>
                    <td class="px-6 py-4">Rp {{ number_format($product->harga, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">Rp {{ number_format($product->modal_per_produk, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 font-semibold">{{ $product->stok }}</td>
                    <td class="px-6 py-4 flex flex-wrap gap-2">
                        @if($product->stok < 15)
                            <a href="{{ route('manage.stock', ['edit' => $product->id]) }}"
                           class="center bg-yellow-300 text-red px-4 py-2 rounded-lg
                                  hover:bg-yellow-800 transition shadow-sm">
                           Tambah </a>  
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-600">
                        Tidak ada produk ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('title', 'New Product')

@section('content')
<div class="w-full min-h-screen p-10 bg-gray-100/40 backdrop-blur-sm overflow-auto">
    <div class="max-w-5xl mx-auto rounded-2xl p-8 bg-white/10 shadow-xl border border-white/20">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900">Tambah Produk Baru</h2>
            <button type="submit" form="productForm"
                class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition">
                Simpan Produk
            </button>
        </div>

        <form id="productForm" action="{{ route('products.store') }}" method="POST" class="space-y-8">
            @csrf

            {{-- Nama Produk --}}
            <div class="flex flex-col md:flex-row md:space-x-8 space-y-6 md:space-y-0">
                <div class="flex justify-between items-center">
                    <label class="text-gray-800 font-semibold mb-2 text-lg">Nama Produk</label>
                    <input type="text" name="nama_produk" required
                       class="w-full px-5 py-3 bg-white text-gray-900 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 transition text-lg shadow-sm">
                </div>
                <div class="flex justify-between items-center mt-6">
                    <label class="text-gray-800 font-semibold mb-2 text-lg">Kode Produk</label>
                    <input type="text" name="kode_produk" required
                       class="w-full px-5 py-3 bg-white text-gray-900 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 transition text-lg shadow-sm">
                </div>
            </div>
                       {{-- Harga, Modal & Kategori --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-8">

    {{-- Harga --}}
    <div class="flex flex-col md:col-span-1">
        <label class="text-gray-800 font-semibold mb-2 text-lg">Harga</label>
        <input type="number" name="harga" required
               class="w-full px-5 py-3 bg-white text-gray-900 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 transition text-lg shadow-sm">
    </div>

    {{-- Modal Per Produk --}}
    <div class="flex flex-col md:col-span-1">
        <label class="text-gray-800 font-semibold mb-2 text-lg">Modal Per Produk</label>
        <input type="number" name="modal_per_produk" required
               class="w-full px-5 py-3 bg-white text-gray-900 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 transition text-lg shadow-sm">
    </div>

    {{-- Kategori --}}
    <div class="flex flex-col md:col-span-1">
        <label class="flex justify-between items-center text-gray-800 font-semibold mb-2 text-lg">
            <span>Kategori</span>
            <button type="button"
                class="text-sm text-blue-600 hover:text-blue-800 font-medium"
                onclick="addCategoryInput()">+ Tambah Kategori Baru</button>
        </label>
        <select name="kategori_id" id="kategoriSelect"
                class="w-full px-5 py-3 bg-white text-gray-900 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 transition text-lg shadow-sm">
            <option value="">-- Pilih Kategori --</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->nama_kategori }}</option>
            @endforeach
        </select>
    </div>

</div>


            {{-- Stok --}}
            <div class="flex flex-col">
                <label class="text-gray-800 font-semibold mb-2 text-lg">Stok</label>
                <input type="number" name="stok" required
                       class="w-full px-5 py-3 bg-white text-gray-900 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 transition text-lg shadow-sm">
            </div>
        </form>
    </div>
</div>

{{-- Script Tambah Kategori Baru --}}
<script>
function addCategoryInput() {
    const select = document.getElementById('kategoriSelect');
    const input = document.createElement('input');
    input.type = 'text';
    input.name = 'kategori_baru';
    input.placeholder = 'Masukkan kategori baru';
    input.className = 'w-full px-5 py-3 mt-3 bg-white text-gray-900 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 text-lg shadow-sm';
    select.insertAdjacentElement('afterend', input);
}
</script>
@endsection

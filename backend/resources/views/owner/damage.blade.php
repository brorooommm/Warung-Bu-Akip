@extends('owner.layout')

@section('title', 'Input Stock Out')

@section('content')


<div class="dashboard-container flex flex-col gap-6 p-6">



    {{-- FORM --}}
    <div class="bg-white rounded-lg shadow p-6">

        @if (session('success'))
            <div class="p-3 rounded bg-green-100 text-green-700 mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="p-3 rounded bg-red-100 text-red-700 mb-4">
                {{ session('error') }}
            </div>
        @endif

        {{-- FORM INPUT --}}
        <form action="{{ route('owner.damage.store') }}" method="POST" class="flex flex-col gap-5">
            @csrf

            {{-- PILIH PRODUK --}}
            <div>
                <label class="block mb-1 font-medium text-gray-700">Pilih Produk</label>
                <select name="product_id" id="productSelect"
                    class="border border-gray-300 px-4 py-2 rounded-lg w-full focus:ring-2 focus:ring-yellow-400">
                    <option value="">-- Pilih Produk --</option>
                    @foreach ($products as $p)
                        <option value="{{ $p->id }}"
                            data-stok="{{ $p->stok }}"
                            data-modal="{{ $p->modal_per_produk }}">
                            {{ $p->nama_produk }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- STOK SEKARANG --}}
            <div>
                <label class="block mb-1 font-medium text-gray-700">Stok Saat Ini</label>
                <input type="text" id="stokNow" readonly
                    class="border border-gray-300 px-4 py-2 rounded-lg w-full bg-gray-100 text-gray-600">
            </div>

            {{-- QTY --}}
            <div>
                <label class="block mb-1 font-medium text-gray-700">Jumlah pengurangan</label>
                <input type="number" name="qty" placeholder="Masukkan jumlah"
                    class="border border-gray-300 px-4 py-2 rounded-lg w-full focus:ring-2 focus:ring-yellow-400">
            </div>

            {{-- CATATAN --}}
            <div>
                <label class="block mb-1 font-medium text-gray-700">Catatan (Opsional)</label>
                <textarea name="note" rows="3"
                    class="border border-gray-300 px-4 py-2 rounded-lg w-full focus:ring-2 focus:ring-yellow-400"
                    placeholder="Contoh: Rusak, kadaluarsa, hilang, dll"></textarea>
            </div>

            {{-- TOMBOL --}}
            <div class="flex justify-end">
                <button type="submit"
                    class="export-btn bg-yellow-400 hover:bg-yellow-500 text-black font-semibold px-6 py-2 rounded-lg shadow">
                    Kurangi Stok
                </button>
            </div>
        </form>
    </div>

</div>

{{-- SCRIPT UNTUK AMBIL STOK --}}
<script>
    document.getElementById('productSelect').addEventListener('change', function () {
        let stok = this.options[this.selectedIndex].getAttribute('data-stok');
        document.getElementById('stokNow').value = stok ? stok : '';
    });
</script>

@endsection

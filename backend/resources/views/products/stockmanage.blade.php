@extends('layouts.admin')

@section('title', 'Manage Stock')

@section('content')
<div class="w-full max-w-6xl mx-auto p-6">
    <h2 class="text-3xl font-bold mb-6 text-gray-800 text-center">Stock Management</h2>

    <!-- Tabel Produk -->
    <table class="w-full border border-gray-300 rounded-lg shadow-sm overflow-hidden">
        <thead class="bg-gray-800 text-white">
            <tr>
                <th class="py-3 px-4 text-left">Nama Produk</th>
                <th class="py-3 px-4 text-left">Kategori</th>
                <th class="py-3 px-4 text-center">Harga</th>
                <th class="py-3 px-4 text-center">Stok</th>
            </tr>
        </thead>
        <tbody class="bg-gray-50">
            @foreach($products as $product)
                <tr class="border-b hover:bg-gray-100 transition">
                    <td class="py-3 px-4">{{ $product->nama_produk }}</td>
                    <td class="py-3 px-4">{{ $product->kategori->nama_kategori ?? '-' }}</td>
                    <td class="py-3 px-4 text-center">Rp {{ number_format($product->harga, 0, ',', '.') }}</td>

                    <td class="py-3 px-4 text-center">
                        <form action="{{ route('products.updateStock', $product->id) }}" 
                              method="POST" 
                              class="flex items-center justify-center space-x-2">
                            @csrf
                            @method('PATCH')

                            <!-- INPUT STOK HANYA BISA NAIK -->
                            <input type="number"
   name="stok"
   class="stok-input w-20 text-center rounded-lg border-gray-300 focus:ring focus:ring-indigo-300"
   value="{{ $product->stok }}"
   data-min="{{ $product->stok }}"
   min="{{ $product->stok }}">

                            <button type="submit"
                                class="px-3 py-1 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                Perbarui
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@if(request('edit'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    let id = "{{ request('edit') }}";
    let modal = document.getElementById('modal-' + id);

    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
});
</script>
@endif




<script>
function openModal(id) {
    document.getElementById(`modal-${id}`).classList.remove('hidden');
    document.getElementById(`modal-${id}`).classList.add('flex');
}
function closeModal(id) {
    document.getElementById(`modal-${id}`).classList.remove('flex');
    document.getElementById(`modal-${id}`).classList.add('hidden');
}

document.querySelectorAll('.stok-input').forEach(input => {
    const minValue = parseInt(input.dataset.min);

    // Cegah angka turun
    input.addEventListener('input', function() {
        if (parseInt(this.value) < minValue) {
            this.value = minValue;
        }
    });

    // Disable tombol panah ke bawah jika sudah ke minimum
    input.addEventListener('keydown', function(e) {
        if (e.key === "ArrowDown" && parseInt(this.value) <= minValue) {
            e.preventDefault();
        }
    });
});


</script>
@endsection

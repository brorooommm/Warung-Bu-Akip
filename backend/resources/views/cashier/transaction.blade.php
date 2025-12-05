@extends('layouts.cashier')

@section('title', 'Transaksi')
@section('topbar', 'Transaksi Harian')

@section('content')
<div class="w-full min-h-full p-6 bg-gray-50 flex flex-col gap-6">

    {{-- Input Transaksi --}}
    <div class="card p-6 bg-white rounded shadow">
        <h2 class="text-xl font-semibold mb-4">Input Transaksi</h2>
        <form method="POST" action="{{ route('cashier.transaction.store') }}" id="transactionForm">
            @csrf
            <div id="productList" class="space-y-4">
                <div class="product-item flex gap-4 items-end">
                    <div>
                        <label>Produk</label>
                        <select name="products[0][product_id]" class="product-select border rounded px-3 py-2">
                            <option value="">-- Pilih Produk --</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-price="{{ $product->harga }}">
                                    {{ $product->nama_produk }} (Stok: {{ $product->stok }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label>Jumlah</label>
                        <input type="number" name="products[0][quantity]" value="1" min="1" class="quantity border rounded px-3 py-2">
                    </div>
                    <div>
                        <label>Subtotal</label>
                        <input type="text" readonly class="subtotal border rounded px-3 py-2 bg-gray-100">
                    </div>
                    <button type="button" class="remove-product bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600">-</button>
                </div>
            </div>
            <button type="button" id="addProduct" class="mt-2 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Tambah Produk</button>

            <div class="mt-4 flex gap-4">
                <div>
                    <label>Total Bayar</label>
                    <input type="number" name="amount_paid" id="amountPaid" class="border rounded px-3 py-2 w-40">
                </div>
                <div>
                    <label>Kembalian</label>
                    <input type="text" id="change" readonly class="border rounded px-3 py-2 w-40 bg-gray-100">
                </div>
            </div>

            <button type="submit" class="mt-4 bg-green-500 text-white px-6 py-2 rounded hover:bg-green-600">Simpan Transaksi</button>
        </form>
    </div>

  

<script>
let productIndex = 1;

function updateSubtotal(container){
    const select = container.querySelector('.product-select');
    const qty = container.querySelector('.quantity');
    const subtotal = container.querySelector('.subtotal');
    const price = parseFloat(select.options[select.selectedIndex]?.dataset.price || 0);
    subtotal.value = (price * (parseInt(qty.value) || 0)).toLocaleString('id-ID');
    updateChange();
}

function updateChange(){
    let totalAll = 0;
    document.querySelectorAll('.subtotal').forEach(input=>{
        totalAll += parseFloat(input.value.replace(/\./g,'') || 0);
    });
    const paid = parseFloat(document.getElementById('amountPaid').value) || 0;
    const back = paid - totalAll;
    document.getElementById('change').value = back >= 0 ? back.toLocaleString('id-ID') : '0';
}

// Event untuk input awal
document.querySelectorAll('.product-item').forEach(item=>{
    updateSubtotal(item);
    const select = item.querySelector('.product-select');
    const qty = item.querySelector('.quantity');
    select.addEventListener('change',()=>updateSubtotal(item));
    qty.addEventListener('input',()=>updateSubtotal(item));
    item.querySelector('.remove-product').addEventListener('click',()=>{item.remove(); updateChange();});
});

// Tambah produk baru
document.getElementById('addProduct').addEventListener('click',()=>{
    const container = document.querySelector('.product-item').cloneNode(true);
    container.querySelectorAll('input,select').forEach(el=>{
        if(el.name) el.name = el.name.replace(/\d+/, productIndex);
        if(el.tagName==='INPUT') el.value = el.classList.contains('quantity')?1:'';
    });
    document.getElementById('productList').appendChild(container);
    productIndex++;
    container.querySelector('.product-select').addEventListener('change',()=>updateSubtotal(container));
    container.querySelector('.quantity').addEventListener('input',()=>updateSubtotal(container));
    container.querySelector('.remove-product').addEventListener('click',()=>{container.remove(); updateChange();});
});

document.getElementById('amountPaid').addEventListener('input', updateChange);

</script>
@if(session('transaction_success'))
<script>
    setTimeout(() => {
        if (confirm("Transaksi berhasil! Download struk sekarang?")) {
            window.location.href = "/cashier/receipt/download";
        }
    }, 500);
</script>
@endif

@endsection

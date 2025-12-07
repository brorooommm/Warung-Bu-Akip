@extends('owner.layout')

@section('title', 'Produk Owner')

@section('content')

<style>
    .owner-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        padding: 20px;
        margin-top: 15px;
    }

    .owner-table th {
        background: #019b0eff; /* dark navy */
        color: white;
        padding: 10px;
        font-weight: 600;
        text-align: left;
    }

    .owner-table td {
        padding: 10px;
        background: #fafafa;
    }

    .owner-table tr:nth-child(even) td {
        background: #f0f0f5;
    }

    .owner-table input {
        background: white;
        border: 1px solid #ccc;
        padding: 6px 8px;
        border-radius: 6px;
    }

    .btn-update {
        background: #3b5bdb; /* biru keunguan */
        color: white;
        padding: 6px 12px;
        border-radius: 6px;
        transition: 0.2s;
    }
    .btn-update:hover {
        background: #364fc7;
    }

    .btn-delete {
        background: #d6336c; 
        color: white;
        padding: 6px 14px;
        border-radius: 6px;
        transition: 0.2s;
    }
    .btn-delete:hover {
        background: #c2255c;
    }

    .search-box {
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 8px 12px;
        width: 90%;
    }
    .btn-cari {
        background: #9c0202ff;
        color: white;
        padding: 8px 16px;
    }
    .btn-cari :hover {
        background: #7a0101ff;
    }

/* Wrapper agar tabel bisa discroll jika layar kecil */
.table-wrapper {
    overflow-x: auto;
    width: 100%;
}

/* Tabel lebih ramping */
.compact-table th, 
.compact-table td {
    padding: 6px 8px !important;
    font-size: 14px !important;
    white-space: nowrap; /* mencegah kolom melebar */
}

/* Input diperkecil */
.compact-table input {
    width: 130px !important;
    padding: 5px 6px;
    font-size: 14px;
}

/* Baris tabel makin padat */
.compact-table tr {
    height: 38px;
}

/* Tombol update & delete lebih kecil */
.action-btns {
    display: flex;
    gap: 6px;
}

.btn-update, .btn-delete {
    padding: 4px 10px !important;
    font-size: 13px !important;
}
</style>

<div class="flex justify-between items-center mb-3">

    {{-- Search --}}
    <form action="{{ route('owner.products') }}" method="GET" class="flex items-center gap-2">
        <input type="text" name="search" placeholder="Cari produk..."
               value="{{ request('search') }}"
               class="search-box">
        <button class="btn-cari" type="submit">Cari</button>
    </form>

    {{-- Tambah Produk --}}
    <a href="{{ route('owner.products.create') }}" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800">
        + Tambah Produk
    </a>
</div>

@if(session('success'))
    <div class="owner-card mt-4 text-green-700 font-semibold">
        {{ session('success') }}
    </div>
@endif


    <div class="owner-card mt-6">

    <div class="table-wrapper">
        <table class="w-full owner-table compact-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Produk</th>
                    <th>Kode produk</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td>{{ $loop->iteration }}</td>

                    <form action="{{ route('owner.products.update', $product->id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <td>
                            <input type="text" name="nama_produk" value="{{ $product->nama_produk }}">
                        </td>
                        <td>
                            <input type="number" name="kode_produk" value="{{ $product->kode_produk }}">
                        </td>
                        <td>
                            <input type="number" name="harga" value="{{ $product->harga }}">
                        </td>
                        <td>
                            <input type="number" name="stok" value="{{ $product->stok }}">
                        </td>

                        <td class="action-btns">
                            <button type="submit" class="btn-update">Update</button>
                    </form>

                    <form action="{{ route('owner.products.delete', $product->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete"
                                onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</button>
                    </form>
                        </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>



</div>

@endsection

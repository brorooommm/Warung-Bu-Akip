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
</style>

<h1 class="text-3xl font-bold mb-4">📦 Daftar Produk</h1>

{{-- Search & Filter --}}
<div class="flex gap-3 items-center">
    <form action="{{ route('owner.products') }}" method="GET">
        <input type="text" name="search" placeholder="Cari produk..."
               value="{{ request('search') }}"
               class="search-box">
        <button class="btn-cari" type="submit">
            Cari
        </button>
    </form>
</div>

@if(session('success'))
    <div class="owner-card mt-4 text-green-700 font-semibold">
        {{ session('success') }}
    </div>
@endif

<div class="owner-card mt-6">
    <table class="w-full owner-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $loop->iteration }}</td>

                {{-- Form Update --}}
                <form action="{{ route('owner.products.update', $product->id) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <td>
                        <input type="text" name="nama_produk" value="{{ $product->nama_produk }}">
                    </td>
                    <td>
                        <input type="number" name="harga" value="{{ $product->harga }}">
                    </td>
                    <td>
                        <input type="number" name="stok" value="{{ $product->stok }}">
                    </td>
                    <td class="flex gap-2">
                        <button type="submit" class="btn-update">Update</button>
                </form>

                {{-- Delete --}}
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

@endsection

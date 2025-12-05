<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; }
        h2 { text-align: center; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td {
            border: 1px solid #444;
            padding: 8px;
            font-size: 12px;
        }
        th {
            background: #2b9348; 
            color: white;
        }
        tr:nth-child(even) { background: #f1f1f1; }
    </style>
</head>
<body>

    <h2>LAPORAN KEUANGAN</h2>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>Pendapatan</th>
                <th>Produk</th>
                <th>Laba Kotor</th>
                <th>Laba Bersih</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
<tr>
    <td>{{ date('d-m-Y', strtotime($item->sale_time)) }}</td>
    <td>{{ date('H:i', strtotime($item->sale_time)) }}</td>

    {{-- Pendapatan / Laba Kotor --}}
    <td>Rp {{ number_format($item->laba_kotor) }}</td>

    {{-- Diskon --}}
    <td>{{ ($item->nama_produk) }}</td>

    {{-- Laba Kotor --}}
    <td>Rp {{ number_format($item->laba_kotor) }}</td>

    {{-- Laba Bersih --}}
    <td>Rp {{ number_format($item->laba_bersih) }}</td>
</tr>
@endforeach

        </tbody>
    </table>

</body>
</html>

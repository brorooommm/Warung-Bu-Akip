<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: DejaVu Sans; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; }
        th { background: #ddd; }
        h2 { text-align: center; }
        .total-row { background: #f2f2f2; font-weight: bold; }
        .green { color: green; font-weight: bold; }
        .red { color: red; font-weight: bold; }
    </style>
</head>
<body>

<h2>Laporan Pemasukan & Pengeluaran</h2>

<table>
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Waktu</th>
            <th>Tipe</th>
            <th>Produk</th>
            <th>Jumlah</th>
            <th>Nominal</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($laporan as $row)
            <tr>
                <td>{{ $row->tanggal }}</td>
                <td>{{ $row->waktu }}</td>
                <td>
                    @if($row->tipe == "Pemasukan")
                        <span class="green">Pemasukan</span>
                    @else
                        <span class="red">Pengeluaran</span>
                    @endif
                </td>
                <td>{{ $row->nama_produk }}</td>
                <td>{{ $row->qty }}</td>
                <td>Rp {{ number_format($row->nominal, 0, ',', '.') }}</td>
            </tr>
        @endforeach

        <!-- ROW TOTAL -->
        <tr class="total-row">
            <td colspan="5">TOTAL PEMASUKAN</td>
            <td>Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</td>
        </tr>
        <tr class="total-row">
            <td colspan="5">TOTAL PENGELUARAN</td>
            <td>Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
        </tr>
        <tr class="total-row">
            <td colspan="5">TOTAL AKHIR</td>
            <td>Rp {{ number_format($totalAkhir, 0, ',', '.') }}</td>
        </tr>
    </tbody>
</table>

</body>
</html>

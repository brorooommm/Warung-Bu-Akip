<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: DejaVu Sans; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
        th { background: #e5e5e5; }
        h2 { text-align: center; margin-bottom: 10px; }
    </style>
</head>
<body>

<h2>Laporan Penjualan</h2>

<table>
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Waktu</th>
            <th>Laba Kotor</th>
            <th>Modal</th>
            <th>Laba Bersih</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($laporan as $row)

        <tr>
            <td>{{ $row->tanggal }}</td>
            <td>{{ $row->waktu }}</td>
            <td>Rp {{ number_format($row->total_penjualan, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>

</table>

</body>
</html>

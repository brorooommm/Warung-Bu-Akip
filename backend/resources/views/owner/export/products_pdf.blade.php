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

    <!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; }
        h2 { text-align: center; margin-bottom: 10px; }
        .range { text-align:center; margin-bottom:12px; font-size:12px; color:#555; }
        table { width: 100%; border-collapse: collapse; margin-top: 6px; }
        th, td {
            border: 1px solid #444;
            padding: 6px 8px;
            font-size: 12px;
            vertical-align: middle;
        }
        th {
            background: #2b9348;
            color: #fff;
            font-weight: 600;
        }
        tr:nth-child(even) td { background: #f7f7f7; }
        .text-right { text-align: right; }
        .bold { font-weight: 700; }
    </style>
</head>
<body>

    <h2>LAPORAN KEUANGAN</h2>
    <div class="range">
        Periode: {{ \Carbon\Carbon::parse($start)->format('d M Y') }} — {{ \Carbon\Carbon::parse($end)->format('d M Y') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>Tipe</th>
                <th>Produk</th>
                <th>Jumlah</th>
                <th class="text-right">Nominal (Rp)</th>
                <th class="text-right">Laba Kotor (Rp)</th>
                <th class="text-right">Laba Bersih (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($timeline as $row)
            <tr>
                <td>{{ \Carbon\Carbon::parse($row->created_at ?? $row->created_at ?? $row->sale_time ?? now())->format('d-m-Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($row->created_at ?? $row->sale_time ?? now())->format('H:i') }}</td>
                <td>
                    <span style="display:inline-block;padding:4px 8px;border-radius:4px;color:#fff;
                        background: {{ ($row->tipe ?? '') === 'Pemasukan' ? '#198754' : '#dc3545' }};">
                        {{ $row->tipe ?? '-' }}
                    </span>
                </td>
                <td>{{ $row->nama_produk ?? '-' }}</td>
                <td class="text-right">{{ number_format($row->jumlah ?? 0, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($row->harga ?? 0, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($row->laba_kotor ?? 0, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($row->laba_bersih ?? 0, 0, ',', '.') }}</td>
            </tr>
            @endforeach

            {{-- ROW TOTAL --}}
            <tr class="bold">
                <td colspan="5" style="text-align:right;">TOTAL</td>
                <td class="text-right">Rp {{ number_format($totalPendapatan,0,',','.') }}</td>
                <td class="text-right">Rp {{ number_format($totalLabaKotor,0,',','.') }}</td>
                <td class="text-right">Rp {{ number_format($totalLabaBersih,0,',','.') }}</td>
            </tr>

            {{-- OPTIONAL: total pengeluaran (terpisah) --}}
            <tr>
                <td colspan="5" style="text-align:right;">TOTAL PENGELUARAN</td>
                <td class="text-right">Rp {{ number_format($totalPengeluaran,0,',','.') }}</td>
                <td colspan="2"></td>
            </tr>

        </tbody>
    </table>

</body>
</html>

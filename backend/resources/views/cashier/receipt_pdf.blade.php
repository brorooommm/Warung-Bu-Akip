<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .title { text-align: center; font-weight: bold; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 4px; }
        .total { font-size: 14px; font-weight: bold; text-align: right; margin-top: 10px; }
        .line { border-top: 1px dashed #000; margin: 10px 0; }
    </style>
</head>
<body>

    <div class="title">
        <h3>STRUK PEMBELIAN</h3>
        <small>{{ $transaction->created_at }}</small>
    </div>

    <div class="line"></div>

    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Sub</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaction->products as $product)
                <tr>
                    <td>{{ $product->nama_produk }}</td>
                    <td>{{ $product->pivot->qty }}</td>
                    <td>{{ number_format($product->pivot->price) }}</td>
                    <td>{{ number_format($product->pivot->subtotal) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="line"></div>

    <div class="total">
        Total: Rp {{ number_format($transaction->total) }}
    </div>

    <p style="text-align:center; margin-top:10px;">Terima Kasih!</p>

</body>
</html>

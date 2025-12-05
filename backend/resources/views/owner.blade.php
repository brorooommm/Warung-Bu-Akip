<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Dashboard Owner</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
/* RESET */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}
body {
    font-family: 'Poppins', sans-serif;
    background: #f9f9f9;
}

/* SIDEBAR ADMIN */
.sidebar {
    width: 120px;
    position: fixed;
    top: 0; bottom: 0; left: 0;
    background: linear-gradient(140deg, rgba(22,97,14,0.99) 0%, #FED16A 73%, #FFF4A4 100%);
    padding-top: 20px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    box-shadow: 2px 0 8px rgba(0,0,0,0.15);
    transition: width 0.3s ease;
    z-index: 99;
}
.sidebar:hover { width: 220px; }

.sidebar ul {
    list-style: none;
    margin-top: 40px;
}
.sidebar ul li {
    padding: 15px 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.sidebar ul li img {
    width: 26px;
}
.sidebar ul li a {
    color: white;
    font-weight: 500;
    opacity: 0;
    text-decoration: none;
    transition: 0.3s ease;
}
.sidebar:hover ul li a { opacity: 1; }

/* LOGOUT */
.logout {
    padding: 20px;
    display: flex;
    gap: 10px;
    align-items: center;
}
.logout a {
    color: white;
    opacity: 0;
}
.sidebar:hover .logout a { opacity: 1; }

/* MAIN CONTENT */
main {
    margin-left: 136px;
    padding: 30px;
    margin-top: 20px;
}

/* HEADER */
header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}
header h1 {
    font-size: 26px;
    font-weight: 600;
    color: #16610E;
}
header img {
    width: 180px;
    border-radius: 12px;
}

/* STAT CARDS */
.stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}
.card {
    background: #fff;
    border-radius: 14px;
    padding: 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: 0.2s;
}
.card:hover { transform: scale(1.03); }
.card-info h3 { color: #666; font-size: 14px; }
.card-info p { font-size: 22px; font-weight: 600; }

.card img { width: 48px; }

/* CHART */
canvas {
    background: white;
    border-radius: 14px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-top: 30px;
}

/* TABLE */
table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    margin-top: 30px;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
th {
    background: #16610E;
    color: white;
    padding: 14px;
}
td {
    padding: 12px 15px;
    color: #333;
}
tr:nth-child(even) { background: #f5f5f5; }

</style>

</head>
<body>

<div class="owner-dashboard">

<aside class="sidebar">
    <div class="logo"><img src="{{ asset('Logo.png') }}" alt="Logo"></div>
    <nav class="menu">
        <ul>
            <li>
                <img src="{{ asset('dashboard-interface.png') }}" alt="Dashboard Icon">
                <a href="#">Dashboard</a>
            </li>
            <li>
                <img src="{{ asset('prod-icon.png') }}" alt="Keuangan">
                <a href="{{ route('owner.products') }}">Produk</a>
            </li>
            <li>
                <img src="{{ asset('jam_document.png') }}" alt="">
                <a href="{{ route('laporanowner') }}">Laporan Keuangan</a>
            </li>

        </ul>
    </nav>

    <div class="logout">
        <img src="{{ asset('logout_icon.png') }}" alt="Logout Icon">
        <a href="{{ url('/') }}">Keluar</a>
    </div>
</aside>


<main>
    <header>
        <h1>Dashboard Owner</h1>
        <img src="{{ asset('ownerBanner.png') }}" alt="Banner Owner">
    </header>

    <!-- ========== STAT KARTU ========== -->
    <section class="stats">
        <div class="card">
            <div class="card-info">
                <h3>Total Modal</h3>
                <p>Rp {{ number_format($data['totalCost'],0,',','.') }}</p>
            </div>
            <img src="{{ asset('Dollar.png') }}">
        </div>

        <div class="card">
            <div class="card-info">
                <h3>Laba Kotor</h3>
                <p>Rp {{ number_format($data['grossProfit'],0,',','.') }}</p>
            </div>
            <img src="{{ asset('Laba_Kotor.PNG') }}">
        </div>

        <div class="card">
            <div class="card-info">
                <h3>Laba Bersih</h3>
                <p>Rp 0</p>
            </div>
            <img src="{{ asset('Laba_Bersih.png') }}">
        </div>

        <div class="card">
            <div class="card-info">
                <h3>Produk Terlaris</h3>
                <p>{{ $data['topProduct'] }} ({{ $data['topProductQty'] }} pcs)</p>
            </div>
            <img src="{{ asset('Terlaris.png') }}">
        </div>

        <div class="card">
            <div class="card-info">
                <h3>Total Transaksi</h3>
                <p>{{ $data['totalTransactions'] }}</p>
            </div>
            <img src="{{ asset('Transaksi.png') }}">
        </div>
    </section>

    <section>
        <h2>Grafik Penjualan Bulanan</h2>
        <canvas id="salesChart" height="110"></canvas>
    </section>

    <section>
        <h2 style="margin-top:20px;">Transaksi Terbaru</h2>
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Item</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['recentTransactions'] as $trx)
                <tr>
                    <td>{{ $trx['date'] }}</td>
                    <td>{{ $trx['item'] }}</td>
                    <td>Rp {{ number_format($trx['amount'],0,',','.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </section>

</main>

</div>

<script>
const ctx=document.getElementById('salesChart').getContext('2d');
new Chart(ctx,{
    type:'line',
    data:{
        labels:['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
        datasets:[{
            label:'Penjualan',
            data:@json($data['monthlySales']),
            borderColor:'#2563eb',
            backgroundColor:'rgba(37, 99, 235, 0.2)',
            borderWidth:2,
            fill:true,
            tension:0.3
        }]
    },
    options:{
        responsive:true,
        plugins:{legend:{display:false}},
        scales:{y:{beginAtZero:true}}
    }
});
</script>

</body>
</html>

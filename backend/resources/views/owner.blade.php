@extends('owner.layout')

@section('content')
<header>
    <h1 style="margin-bottom: 20px;">Dashboard Owner</h1>
</header>

<!-- ===================== STAT CARDS ===================== -->
<section class="stats">
    <div class="card">
        <div class="card-info">
            <h3>Total Modal</h3>
            <p>Rp {{ number_format(array_sum($profitData) + array_sum($lossData),0,',','.') }}</p>
        </div>
        <img src="{{ asset('Dollar.png') }}" class="icon">
    </div>

    <div class="card">
        <div class="card-info">
            <h3>Total Keuntungan</h3>
            <p>Rp {{ number_format(array_sum($profitData),0,',','.') }}</p>
        </div>
        <img src="{{ asset('Laba_Kotor.PNG') }}" class="icon">
    </div>

    <div class="card">
        <div class="card-info">
            <h3>Total Kerugian</h3>
            <p>Rp {{ number_format(array_sum($lossData),0,',','.') }}</p>
        </div>
        <img src="{{ asset('Laba_Bersih.png') }}" class="icon">
    </div>
</section>

<!-- ===================== CHART ===================== -->
<section style="margin-top:30px;">
    <h2>Keuntungan & Kerugian 30 Hari Terakhir</h2>
    <canvas id="profitLossChart" height="100"></canvas>
</section>

<style>
/* ================== CARD AREA ================== */
.stats {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    margin-bottom: 30px;
}

.stats .card {
    flex: 1;
    min-width: 220px;
    background: #fff;
    border-radius: 12px;
    padding: 18px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: transform 0.2s, box-shadow 0.2s;
}

.stats .card:hover {
    transform: scale(1.02);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.stats .card .card-info h3 {
    font-size: 13px;
    color: #555;
    margin-bottom: 5px;
    font-weight: 600;
}

.stats .card .card-info p {
    font-size: 20px;
    font-weight: 700;
    color: #222;
}

/* ICON DALAM CARD */
.stats .card img.icon {
    width: 45px; 
    height: 45px;
    object-fit: contain;
    opacity: 0.9;
}

/* ================== CHART AREA ================== */
canvas {
    background:#fff;
    border-radius:12px;
    padding:20px;
    box-shadow:0 2px 8px rgba(0,0,0,0.1);
    width:100%;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('profitLossChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json($dates),
        datasets: [
            {
                label: 'Keuntungan',
                data: @json($profitData),
                borderColor: '#16a34a',
                backgroundColor: 'rgba(22,163,52,0.2)',
                borderWidth: 2,
                tension: 0.3,
                fill: true
            },
            {
                label: 'Kerugian',
                data: @json($lossData),
                borderColor: '#dc2626',
                backgroundColor: 'rgba(220,38,38,0.2)',
                borderWidth: 2,
                tension: 0.3,
                fill: true
            }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'top' } },
        scales: { y: { beginAtZero: true } }
    }
});
</script>
@endsection

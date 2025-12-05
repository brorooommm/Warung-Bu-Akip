<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <!-- INTERNAL CSS -->
    <style>
        @import "tailwindcss";
        @import "flowbite/src/themes/default";
        @plugin "flowbite/plugin";
        @source "../../node_modules/flowbite";

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: #fff;
        }

        /* Dashboard container */
        .admin-dashboard {
            display: flex;
            min-height: 100vh;
        }

        /* Notifikasi */
        .notice-bar {
            position: fixed;
            top: 0; left: 0;
            width: 100%;
            background: #FED16A;
            color: #16610E;
            font-family: 'Kumbh Sans', sans-serif;
            font-size: 18px;
            font-weight: 500;
            text-align: center;
            padding: 12px 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            z-index: 999;
            opacity: 1;
            transition: opacity 0.5s ease, transform 0.5s ease;
        }
        .notice-bar.hide {
            opacity: 0;
            transform: translateY(-100%);
        }

        /* Sidebar */
        .sidebar {
            width: 120px;
            position: fixed;
            top: 0; bottom: 0; left: 0;
            background: linear-gradient(140deg, rgba(22,97,14,0.99) 0%, #FED16A 73%, #FFF4A4 100%);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
            transition: width 0.3s ease, box-shadow 0.3s ease;
            z-index: 100;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }

        .sidebar:hover {
            width: 220px;
        }

        .sidebar .logo {
            text-align: center;
            padding: 15px 0;
        }

        .sidebar .menu ul {
            list-style: none;
            padding: 0;
            margin-top: 50px;
        }

        .sidebar .menu ul li {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px 20px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .sidebar .menu ul li img {
            width: 24px;
            height: 24px;
        }

        .sidebar .menu ul li a {
            color: #fff;
            font-weight: 500;
            opacity: 0;
            text-decoration: none;
            white-space: nowrap;
            transition: opacity 0.3s ease;
        }

        .sidebar:hover .menu ul li a {
            opacity: 1;
        }

        .sidebar .logout {
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .sidebar .logout a {
            color: #fff;
            font-weight: 600;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .sidebar:hover .logout a {
            opacity: 1;
        }

        .sidebar .menu li.dropdown { position: relative; }
        .sidebar .dropdown-menu {
            position: absolute;
            left: 40px;
            top: 0;
            background: #e0de5e;
            border-radius: 5px;
            display: none;
            min-width: 160px;
            flex-direction: column;
            box-shadow: 0 2px 5px rgba(0,0,0,0.3);
            z-index: 1000;
        }
        .sidebar .menu li.dropdown:hover .dropdown-menu {
            display: flex;
        }

        /* Topbar */
        .topbar {
            position: fixed;
            left: 136px;
            right: 0;
            top: 0;
            height: 80px;
            background: rgba(255,244,164,0.73);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            z-index: 90;
        }

        .topbar .top-illustration {
            position: absolute;
            right: 220px;
            top: 10px;
            height: 70px;
            opacity: 0.8;
        }

        .topbar .admin-name {
            font-size: 24px;
            font-weight: 600;
            color: #16610E;
        }

        /* Main area */
        .dashboard-main {
            margin-left: 136px;
            margin-top: 80px;
            padding: 20px;
        }

        /* Wrapper kiri-kanan */
        .main-wrapper {
            display: flex;
            gap: 20px;
            width: 100%;
        }

        .left-column {
            width: 20%;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .right-column {
            width: 80%;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .right-wrapper {
    display: flex;
    gap: 20px;
    width: 100%;
    margin-top: 20px;
}

        /* Card */
        .card {
            padding: 20px;
            background: #f5f5f5;
            border-radius: 12px;
            font-weight: 600;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: transform 0.2s;
            text-align: center;
            color : #16610E;
            text-decoration: none;
        }

        .card:hover {
            transform: scale(1.03);
        }

        .total-products { background: #CADCAE; }
        .low-stock { background: rgba(249,122,0,0.3); }
        .total-sales { background: rgba(224,106,109,0.39); }
        .transactions-today { background: rgba(254,209,106,0.6); }

        /* Kalender */
       /* ====== KALENDER ====== */
.calendar-container {
      width: 350px;
    background: white;
    padding: 20px;
    border-radius: 14px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    width: 100%;
    max-width: 450px;
    font-family: 'Kumbh Sans', sans-serif;
}

.calendar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
}

.calendar-header button {
    padding: 6px 12px;
    border: none;
    border-radius: 8px;
    background: #ffb74d;
    cursor: pointer;
    font-size: 16px;
}

.calendar-header button:hover {
    background: #ffa726;
}

#calendar-title {
    font-size: 18px;
    font-weight: 600;
}

.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 6px;
}

.calendar-grid div {
    padding: 10px;
    text-align: center;
    background: #f1f1f1;
    border-radius: 8px;
    font-size: 14px;
}

.calendar-grid .day-name {
    background: #ffe0b2;
    font-weight: bold;
}

.calendar-grid .today {
    background: #ffcc80 !important;
    font-weight: bold;
    border: 2px solid #fb8c00;
}

        /* Chart */
        #salesChart {
          flex : 1;
            width: 100% !important;
            height: 280px !important;
        }
    </style>
</head>

<body>

<!-- Notice bar -->
<div id="admin-notice" class="notice-bar">Berhasil Masuk Sebagai Admin.</div>

<!-- Dashboard container -->
<div class="admin-dashboard">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="logo">
            <img src="{{ asset('Logo.png') }}" alt="Logo">
        </div>

        <nav class="menu">
            <ul>
                <li>
                    <img src="{{ asset('dashboard-interface.png') }}" alt="">
                    <a href="#">Beranda</a>
                </li>

                <li class="dropdown">
                    <img src="{{ asset('prod-icon.png') }}" alt="">
                    <a href="#">Produk</a>

                    <ul class="dropdown-menu">
                        <li><a href="{{ route('products.index') }}">Daftar Produk</a></li>
                        <li><a href="{{ route('products.create') }}">Tambah Produk</a></li>
                        <li><a href="{{ route('manage.stock') }}">Atur Stok</a></li>
                    </ul>
                </li>

                 <li class="dropdown">
                <img src="{{ asset('jam_document.png') }}" alt="">
                <a href="#">Laporan</a>
                <ul class="dropdown-menu">
                    <li><a href="{{ route('laporanAdmin') }}">Laporan Penjualan</a></li>
                    <li><a href="{{ route('perform') }}">Performa Kategori</a></li>
                </ul>
            </li>

                <li>
                    <img src="{{ asset('uil_setting.png') }}" alt="">
                    <a href="#">Pengaturan</a>
                </li>
            </ul>
        </nav>

        <div class="logout">
            <img src="{{ asset('logout_icon.png') }}" alt="">
            <a href="{{ route('login.form') }}">Keluar</a>
        </div>
    </aside>

    <!-- TOPBAR -->
    <header class="topbar">
        <div>
            <div>Selamat Datang,</div>
            <div class="admin-name">MUHTAROM</div>
            <div id="current-date"></div>
        </div>

        <img src="{{ asset('Ilustrasiwelcomeadmin.png') }}" class="top-illustration">

        <div class="user-info">
            
            <span class="role">ADMINISTRATOR</span>

            <div class="profile">
                <img src="{{ asset('avatar.png') }}">
                <span>Muhtarom</span>
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="dashboard-main">

        <div class="main-wrapper">

            <!-- LEFT COLUMN -->
            <div class="left-column">
                <a class="card total-products" href="{{ route('perform') }}">Produk Terlaris</a>
                <a class="card low-stock" href="#">Produk Krisis</a>
                <a class="card total-sales" href="{{ route('laporanAdmin') }}">Laporan Harian</a>
                <a class="card transactions-today" href="#">Riwayat Transaksi</a>
            </div>

            <!-- RIGHT COLUMN -->
            <div class="right-column">
              <div class="right-wrapper">

                <!-- Kalender -->
                <div class="calendar-container">
    <div class="calendar-header">
        <button id="prev-month">&lt;</button>
        <div id="calendar-title"></div>
        <button id="next-month">&gt;</button>
    </div>
    <div class="calendar-grid" id="calendar-grid"></div>
</div>


                <!-- Chart -->
                <canvas id="salesChart"></canvas>
            </div>
            </div>

        </div>
    </main>

</div>

<!-- INTERNAL JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Hilangkan notice
    window.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => document.getElementById('admin-notice').classList.add('hide'), 3000);

        // Tanggal real-time
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('current-date').textContent = new Date().toLocaleDateString('id-ID', options);
    });

    // Kalender
    /* ====== KALENDER ====== */
const calendarTitle = document.getElementById("calendar-title");
const calendarGrid = document.getElementById("calendar-grid");

let currentDate = new Date();

function renderCalendar(date) {
    const year = date.getFullYear();
    const month = date.getMonth();

    const firstDay = new Date(year, month, 1).getDay();
    const lastDate = new Date(year, month + 1, 0).getDate();

    calendarTitle.textContent = 
        date.toLocaleDateString("id-ID", { month: "long", year: "numeric" });

    calendarGrid.innerHTML = "";

    const dayNames = ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"];
    dayNames.forEach(d => {
        const div = document.createElement("div");
        div.textContent = d;
        div.classList.add("day-name");
        calendarGrid.appendChild(div);
    });

    for (let i = 0; i < firstDay; i++) {
        const empty = document.createElement("div");
        empty.textContent = "";
        calendarGrid.appendChild(empty);
    }

    for (let dateNum = 1; dateNum <= lastDate; dateNum++) {
        const div = document.createElement("div");
        div.textContent = dateNum;

        const today = new Date();
        if (
            dateNum === today.getDate() &&
            month === today.getMonth() &&
            year === today.getFullYear()
        ) {
            div.classList.add("today");
        }

        calendarGrid.appendChild(div);
    }
}

document.getElementById("prev-month").addEventListener("click", () => {
    currentDate.setMonth(currentDate.getMonth() - 1);
    renderCalendar(currentDate);
});

document.getElementById("next-month").addEventListener("click", () => {
    currentDate.setMonth(currentDate.getMonth() + 1);
    renderCalendar(currentDate);
});

renderCalendar(currentDate);


   const ctx = document.getElementById('salesChart').getContext('2d');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json($dates),
        datasets: [{
            label: "Pendapatan Harian",
            data: @json($totals),
            borderWidth: 2,
            tension: 0.3,
            fill: false
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>

</body>
</html>

<aside class="sidebar">
    <div class="logo"><img src="{{ asset('Logo.png') }}" alt="Logo"></div>

    <nav class="menu">
        <ul>
            <li>
                <img src="{{ asset('dashboard-interface.png') }}" alt="Dashboard Icon">
                <a href="{{ route('owner.dashboard') }}">Dashboard</a>
            </li>
            <li>
                <img src="{{ asset('prod-icon.png') }}" alt="Produk Icon">
                <a href="{{ route('owner.products') }}">Produk</a>
            </li>
            <li>
                <img src="{{ asset('jam_document.png') }}" alt="Laporan Icon">
                <a href="{{ route('laporanowner') }}">Laporan Keuangan</a>
            </li>
          <li>
    <img src="{{ asset('jam_document.png') }}" alt="Stock Icon">
    <a href="{{ route('owner.damage') }}">Kurangi Stok</a>
</li>

        </ul>
    </nav>

    <div class="logout">
        <img src="{{ asset('logout_icon.png') }}" alt="Logout Icon">
        <a href="{{ url('/') }}">Keluar</a>
    </div>
</aside>

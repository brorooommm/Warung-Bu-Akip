<aside class="sidebar">
    <div class="logo"><img src="{{ asset('Logo.png') }}" alt="Logo"></div>
    <nav class="menu">
        <ul>
            <li>
                <img src="{{ asset('dashboard-interface.png') }}" alt="Dashboard Icon">
                <a href="{{ route('admin') }}">Beranda</a>
            </li>
            <li class="dropdown">
                <img src="{{ asset('prod-icon.png') }}" alt="Produk Icon">
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
                <img src="{{ asset('uil_setting.png') }}" alt="Settings Icon">
                <a href="{{route ('admin.settings')}}">Pengaturan</a>
            </li>
        </ul>
    </nav>
    <div class="logout">
        <img src="{{ asset('logout_icon.png') }}" alt="Logout Icon">
        <a href="{{ route('login.form') }}">Keluar</a>
    </div>
</aside>

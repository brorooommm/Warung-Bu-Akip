<header class="topbar">
    <div class="welcome-text">
        <div>Selamat Datang,</div>
        <div class="admin-name">MUHTAROM</div>
        <div class="date" id="current-date">{{ now()->format('l, d F Y') }}</div>
    </div>
    <div class="user-info">
        <img src="{{ asset('Ilustrasiwelcomeadmin.png') }}" alt="Ilustrasi" class="top-illustration">
        <span class="role">ADMINISTRATOR</span>
        <div class="profile">
            <img src="{{ asset('images/avatar.png') }}" alt="Profile">
            <span>Muhtarom</span>
        </div>
    </div>
</header>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Admin Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
</head>
<body>
<script>
window.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => document.getElementById('admin-notice').classList.add('hide'), 3000);
});
</script>
<style>
    /* --- RESET DAN DASAR --- */
body, html {
  margin: 0;
  padding: 0;
  height: 100%;
  width: 100%;
}

/* --- STRUKTUR DASHBOARD --- */
.admin-dashboard {
  display: flex;            /* Sidebar & area utama sejajar */
  width: 100%;
  min-height: 100vh;
  overflow: hidden;
}

/* --- SIDEBAR --- */
.sidebar {
  width: 145px;             /* atur lebar sesuai desain */
  flex-shrink: 0;           /* biar nggak mengecil */
  height: 100vh;
  background-color: #fff;
  border-right: 1px solid #ddd;
  position: fixed;          /* agar tetap di kiri saat scroll */
  left: 0;
  top: 0;
  bottom: 0;
}

/* --- KONTEN UTAMA --- */
.dashboard-main {
  flex: 1;
  margin-left: 250px;       /* memberi ruang untuk sidebar */
  padding: 20px;
  background-color: #f9f9f9;
  min-height: 100vh;
}

/* --- TOPBAR --- */


</style>

<div class="admin-dashboard">
    {{-- Sidebar --}}
    @include('partials.sidebar')



    {{-- Main Content --}}
    <main class="dashboard-main">
        @yield('content')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</body>
</html>

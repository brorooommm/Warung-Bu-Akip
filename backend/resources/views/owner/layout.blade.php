<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Owner Dashboard</title>

    {{-- CSS yang sama dengan admin --}}
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    {{-- Flowbite (kalau diperlukan) --}}
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
</head>

<body>

<style>
/* copy paste struktur layout admin biar identik */
body, html {
  margin: 0;
  padding: 0;
  height: 100%;
  width: 100%;
}

.admin-dashboard {
  display: flex;
  width: 100%;
  min-height: 100vh;
  overflow: hidden;
}

.sidebar {
  width: 145px;
  flex-shrink: 0;
  height: 100vh;
  background-color: #fff;
  border-right: 1px solid #ddd;
  position: fixed;
  left: 0;
  top: 0;
  bottom: 0;
}

.dashboard-main {
  flex: 1;
  margin-left: 250px;
  padding: 20px;
  background-color: #f9f9f9;
  min-height: 100vh;
}
</style>

<div class="admin-dashboard">

    {{-- Sidebar khusus owner --}}
    @include('owner.sidebar')

    {{-- Ruang konten yang sama persis dengan admin --}}
    <main class="dashboard-main">
        @yield('content')
    </main>

</div>

<script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
@stack('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>@yield('title', 'Dashboard Owner')</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
/* --- Copy CSS sidebar, table, cards, header, body, etc --- */
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family:'Poppins',sans-serif; background:#f9f9f9; }
.sidebar { width:120px; position:fixed; top:0; bottom:0; left:0; background:linear-gradient(140deg, rgba(22,97,14,0.99) 0%, #FED16A 73%, #FFF4A4 100%); padding-top:20px; display:flex; flex-direction:column; justify-content:space-between; box-shadow:2px 0 8px rgba(0,0,0,0.15); transition:0.3s; z-index:99; }
.sidebar:hover { width:220px; }
.sidebar ul { list-style:none; margin-top:40px; }
.sidebar ul li { padding:15px 20px; display:flex; align-items:center; gap:10px; }
.sidebar ul li img { width:26px; }
.sidebar ul li a { color:white; font-weight:500; opacity:0; text-decoration:none; transition:0.3s ease; }
.sidebar:hover ul li a { opacity:1; }
.logout { padding:20px; display:flex; gap:10px; align-items:center; }
.logout a { color:white; opacity:0; }
.sidebar:hover .logout a { opacity:1; }
main { margin-left:136px; padding:30px; margin-top:20px; }
header { display:flex; justify-content:space-between; align-items:center; margin-bottom:25px; }
header h1 { font-size:26px; font-weight:600; color:#16610E; }
header img { width:180px; border-radius:12px; }
.stats { display:grid; grid-template-columns:repeat(4,1fr); gap:20px; }
.card { background:#fff; border-radius:14px; padding:20px; display:flex; align-items:center; justify-content:space-between; box-shadow:0 2px 8px rgba(0,0,0,0.1); transition:0.2s; }
.card:hover { transform:scale(1.03); }
.card-info h3 { color:#666; font-size:14px; }
.card-info p { font-size:22px; font-weight:600; }
.card img { width:48px; }
canvas { background:white; border-radius:14px; padding:20px; box-shadow:0 2px 8px rgba(0,0,0,0.1); margin-top:30px; }
table { width:100%; border-collapse:collapse; background:white; margin-top:30px; border-radius:14px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.1); }
th { background:#16610E; color:white; padding:14px; }
td { padding:12px 15px; color:#333; }
tr:nth-child(even) { background:#f5f5f5; }
</style>

</head>
<body>

<div class="owner-dashboard">
    @include('owner.sidebar')

    <main>
        @yield('content')
    </main>
</div>

@stack('scripts')
</body>
</html>

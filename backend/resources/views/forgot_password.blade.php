<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="bg-white shadow-lg rounded-lg p-6 w-full max-w-md">

    <h2 class="text-xl font-bold mb-4 text-center">Lupa Password</h2>

    @if(session('success'))
        <div class="bg-green-200 text-green-700 p-3 rounded mb-3">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-200 text-red-700 p-3 rounded mb-3">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('forgot.submit') }}">
        @csrf

        <label class="block font-semibold mb-1">Email Akun</label>
        <input type="email" name="email" required class="w-full border px-3 py-2 rounded mb-4">

        <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded">
            Kirim Pemulihan
        </button>
    </form>

    <div class="text-center mt-4">
        <a href="/login" class="text-blue-600">Kembali ke Login</a>
    </div>

</div>

</body>
</html>

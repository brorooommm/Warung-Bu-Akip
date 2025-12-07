@extends('layouts.admin')

@section('content')

<style>
    /* Layout luar */
    .settings-container {
        max-width: 700px;
        margin: 30px auto;
        padding: 20px;
    }

    /* Card */
    .settings-card {
        background: #ffffff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
        border-left: 5px solid #0d6efd;
    }

    .settings-card-header {
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 15px;
        color: #0d6efd;
    }

    /* Form labels */
    .settings-label {
        display: block;
        font-weight: 600;
        margin-bottom: 5px;
        margin-top: 15px;
    }

    /* Input */
    .settings-input {
        width: 100%;
        padding: 10px;
        border-radius: 6px;
        border: 1px solid #ccc;
        font-size: 14px;
        transition: 0.2s;
    }

    .settings-input:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 5px rgba(13, 110, 253, 0.4);
        outline: none;
    }

    /* Button */
    .settings-btn {
        background: #0d6efd;
        color: #fff;
        border: none;
        padding: 10px 18px;
        border-radius: 6px;
        cursor: pointer;
        margin-top: 20px;
        font-weight: 600;
        transition: 0.2s;
    }

    .settings-btn:hover {
        background: #0b5ed7;
    }

    /* Alert */
    .alert-success {
        background: #d1e7dd;
        color: #0f5132;
        padding: 12px;
        border-radius: 6px;
        margin-top: 15px;
    }

    .alert-danger {
        background: #f8d7da;
        color: #842029;
        padding: 12px;
        border-radius: 6px;
        margin-top: 15px;
    }

</style>



<div class="settings-container">

    <h3><i class="fas fa-cog me-2"></i> Pengaturan Akun</h3>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert-danger">{{ session('error') }}</div>
    @endif

    <div class="settings-card">
        <div class="settings-card-header">
            Ubah Password
        </div>

        <form action="{{ route('admin.settings.updatePassword') }}" method="POST">
            @csrf

            <label class="settings-label">Password Lama</label>
            <input type="password" name="current_password" class="settings-input" required>

            <label class="settings-label">Password Baru</label>
            <input type="password" name="new_password" class="settings-input" required>

            <label class="settings-label">Konfirmasi Password Baru</label>
            <input type="password" name="new_password_confirmation" class="settings-input" required>

            <button class="settings-btn">Simpan Perubahan</button>
        </form>
    </div>

</div>

@endsection

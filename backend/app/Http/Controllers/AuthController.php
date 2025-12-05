<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showRegisterForm() {
    return view('index'); // index.blade.php = form daftar
}

public function register(Request $request) {
    $request->validate([
        'username' => 'required|string|max:255|unique:users,username',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6|confirmed',
        'role' => 'required|string|in:admin,owner,cashier',
    ]);

    User::create([
        'username' => $request->username,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $request->role,
    ]);

    // redirect ke login
    return redirect()->route('login.form')->with('success', 'Registrasi berhasil! Silakan login.');
}

    // Tampilkan form login
    public function showLoginForm() {
        return view('Login'); // pastikan ada resources/views/auth/login.blade.php
    }

    // Proses login
   public function login(Request $request)
{
    $username = $request->username;
    $password = $request->password;
    $role     = $request->role;

    // validasi dasar
    if (!$username || !$password) {
        return back()->with('error', 'Username dan Password wajib diisi!');
    }

    if (!$role) {
        return back()->with('error', 'Pilih role terlebih dahulu!');
    }

    /** 
     * Tidak ada pengecekan database
     * User dianggap valid selama mengisi semua field
     */

    // arahkan sesuai role
    if ($role === 'admin') {
        return redirect('/admin')->with('success', 'Login berhasil sebagai Admin!');
    }

    if ($role === 'owner') {
        return redirect('/owner/dashboard')->with('success', 'Login berhasil sebagai Owner!');
    }

    if ($role === 'cashier') {
        return redirect('/cashier/transaction')->with('success', 'Login berhasil sebagai Cashier!');
    }

    // fallback jika role aneh
    return redirect('/login')->with('error', 'Role tidak dikenali!');
}


    // Tampilkan form sign up
    public function showSignupForm() {
        return view('index'); // pastikan ada resources/views/auth/signup.blade.php
    }

    // Logout
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}

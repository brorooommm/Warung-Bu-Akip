<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgotPasswordMail;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    
    public function settings()
{
    return view('admin.settings');
}
public function showForgotForm()
{
    return view('forgot_password');
}

public function sendForgotPassword(Request $request)
{
    $request->validate([
        'email' => 'required|email'
    ]);

    $user = DB::table('users')->where('email', $request->email)->first();

    if (!$user) {
        return back()->withErrors(['email' => 'Email tidak ditemukan!']);
    }

    // 1. buat password baru
    $new_password = Str::random(10);

    // 2. update password hashed ke database
    DB::table('users')->where('id', $user->id)->update([
        'password' => Hash::make($new_password)
    ]);

    // 3. kirim email
    Mail::to($user->email)->send(
        new ForgotPasswordMail($user->username, $new_password)
    );

    return back()->with('success', 'Password baru telah dikirim ke email Anda!');
}


public function updatePassword(Request $request)
{
    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:6|confirmed',
    ]);

    $user = auth()->user();

    // cek password lama
    if (!\Hash::check($request->current_password, $user->password)) {
        return back()->with('error', 'Password lama salah.');
    }

    // update
    $user->password = bcrypt($request->new_password);
    $user->save();

    return back()->with('success', 'Password berhasil diubah.');
}

    // ==================== REGISTER ====================
    public function showRegisterForm() {
        return view('index'); // resources/views/auth/register.blade.php
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

        return redirect()->route('login.form')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    // ==================== LOGIN ====================
    public function showLoginForm() {
        return view('Login'); // resources/views/auth/login.blade.php
    }

 public function login(Request $request)
{
    $credentials = $request->only('username', 'password');

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        // redirect sesuai role
        switch (Auth::user()->role) {
            case 'admin':
                return redirect()->route('admin');
            case 'owner':
                return redirect()->route('owner.dashboard');
            case 'cashier':
                return redirect()->route('cashier.transaction');
        }
    }

    return redirect()->route('login.form')->with('error', 'Username atau password salah!');
}



    // ==================== LOGOUT ====================
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', 'Logout berhasil!');
    }
}

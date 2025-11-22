<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuruLoginController extends Controller
{
    // Menampilkan form login guru
    public function showLoginForm()
    {
        return view('guru.login_guru');
    }

    // Proses login guru
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        // Coba login menggunakan guard 'guru'
        if (Auth::guard('guru')->attempt([
            'email' => $request->email,
            'password' => $request->password
        ])) {
            return redirect()->route('guru.dashboard')->with('success', 'Selamat datang, Guru!');
        }

        // Jika gagal login
        return back()->with('error', 'NIP atau Password salah!');
    }

    // Logout guru
    public function logout(Request $request)
    {
        Auth::guard('guru')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('guru.login');
    }
}

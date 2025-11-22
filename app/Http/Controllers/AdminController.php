<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth; // ✅ Tambahkan ini!

class AdminController extends Controller
{
    // ==========================
    // TAMPILKAN FORM LOGIN
    // ==========================
    public function login()
    {
        // Form login di resources/views/auth/login.blade.php
        return view('auth.login');
    }

    // ==========================
    // PROSES LOGIN
    // ==========================
    public function auth(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'kode_admin' => 'required|string',
        ]);

        // Coba login pakai guard 'admin'
        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'username' => 'Nama Admin atau Kode Admin salah.',
        ])->withInput($request->except('kode_admin'));
    }

    

    // ==========================
    // LOGOUT
    // ==========================
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout(); // ✅ Logout sesuai guard
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login_admin'); // Kembali ke form login
    }

    // ==========================
    // DASHBOARD & HALAMAN ADMIN
    // ==========================
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function absen()
    {
        return view('admin.absenguru');
    }

    public function info()
    {
        return view('admin.infosekolah');
    }

    public function profile()
    {
        return view('admin.profile');
    }

    public function updateProfile(Request $request)
    {
        // Logika update profil admin
    }
}

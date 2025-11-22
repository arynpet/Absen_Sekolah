<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;

class AdminLoginController extends Controller
{
    // 1. Tampilkan Halaman Login
    public function showLoginForm()
    {
        return view('admin.login_admin');
    }

    // 2. Proses Login
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'kode_admin' => 'required|string',
        ]);

        // Cek user di database
        $admin = Admin::where('username', $request->username)->first();

        // Validasi manual (karena password tidak di-hash)
        if ($admin && $admin->kode_admin === $request->kode_admin) {
            
            // Login resmi menggunakan Guard 'admin'
            Auth::guard('admin')->login($admin);
            
            $request->session()->regenerate();

            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'username' => 'Username atau Kode Admin salah.',
        ])->withInput($request->only('username'));
    }

    // 3. Proses Logout
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
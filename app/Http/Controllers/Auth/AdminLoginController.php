<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;

class AdminLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login_admin');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'kode_admin' => 'required|string',
        ]);

        $admin = Admin::where('username', $request->username)->first();

        if ($admin && $admin->kode_admin === $request->kode_admin) {
            Auth::guard('admin')->login($admin);
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'username' => 'Username atau Kode Admin salah.',
        ])->withInput($request->only('username'));
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // ✅ PERBAIKAN: Redirect ke admin login
        return redirect()->route('login');
    }
}
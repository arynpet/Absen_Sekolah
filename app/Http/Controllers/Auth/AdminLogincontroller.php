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

        // Ambil admin berdasarkan username
        $admin = Admin::where('username', $request->username)->first();

        // Cek jika ada admin dan kode_admin cocok
        if ($admin && $request->kode_admin === $admin->kode_admin) {

            // Simpan admin ke session MANUAL
            session(['admin_id' => $admin->id]);

            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'username' => 'Nama admin atau kode admin salah.',
        ]);
    }

    public function logout(Request $request)
    {
        session()->forget('admin_id');
        return redirect()->route('admin.login');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
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

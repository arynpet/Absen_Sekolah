<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\AbsenGuru;
use App\Models\Guru;

class DashboardController extends Controller
{
    public function index()
    {
        $userName = Auth::check() ? (Auth::user()->name ?? Auth::user()->email) : 'Pengunjung';
        $kehadiran = AbsenGuru::with('guru')->orderBy('tanggal', 'desc')->get();
        $jumlahGuru = Guru::count();
        $jumlahAbsen = AbsenGuru::count();

        return view('dashboard', compact('userName', 'kehadiran', 'jumlahGuru', 'jumlahAbsen'));
    }
}

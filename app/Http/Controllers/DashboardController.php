<?php

namespace App\Http\Controllers;

use App\Models\Pemasukkan;
use App\Models\Pengeluaran;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index() {
        $user_id = Auth::id();
    
        $pemasukkan = Pemasukkan::where('user_id', $user_id)->get();
        $pengeluaran = Pengeluaran::where('user_id', $user_id)->get();

        $latestPemasukkan = Pemasukkan::where('user_id', $user_id)->latest()->take(5)->get();
        $latestPengeluaran = Pengeluaran::where('user_id', $user_id)->latest()->take(5)->get();
    
        $totalPemasukkan = (int) $pemasukkan->sum('jumlah');
        $totalPengeluaran = (int) $pengeluaran->sum('jumlah');
        $saldo = $totalPemasukkan - $totalPengeluaran;

        // Create chart data array
        $chartData = collect([
            'Total Pemasukkan' => $totalPemasukkan,
            'Total Pengeluaran' => $totalPengeluaran,
        ]);

        return view('dashboard', [
            'totalPemasukkan' => $totalPemasukkan,
            'totalPengeluaran' => $totalPengeluaran,
            'saldo' => $saldo,
            'chartData' => $chartData,
            'latestPemasukkan' => $latestPemasukkan,
            'latestPengeluaran' => $latestPengeluaran
        ]);
    }
}
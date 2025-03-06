<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengeluaranController extends Controller
{
    public function index() {
        $user_id = Auth::id();

        $pengeluaran = Pengeluaran::where('user_id', $user_id)->get();

        // Ambil total pengeluaran per kategori
        $chartData = $pengeluaran->groupBy('kategori')->map(function ($item) {
            return $item->sum('jumlah');
        });

        $latestPengeluaran = Pengeluaran::where('user_id', $user_id)->latest()->take(5)->get();

        $ambilKategori = Pengeluaran::where('user_id', $user_id)->select('kategori')->distinct()->get();

        return view('pengeluaran.index', [
            'pengeluaran' => $pengeluaran,
            'chartData' => $chartData,
            'latestPengeluaran' => $latestPengeluaran,
            'ambilKategori' => $ambilKategori
        ]);
    }

    public function create(Request $request) {
        $user = Auth::user();

        $request->validate([
            'tanggal' => 'required|date|before_or_equal:today',
            'jumlah' => 'required|numeric|min:1',
            'kategori' => 'required|string|max:255',
            'keterangan' => 'required|string|max:1000'
        ], [
            'tanggal.required' => 'Tanggal harus diisi',
            'tanggal.date' => 'Format tanggal tidak valid',
            'tanggal.before_or_equal' => 'Tanggal tidak boleh lebih dari hari ini',
            'jumlah.required' => 'Jumlah harus diisi',
            'jumlah.numeric' => 'Jumlah harus berupa angka',
            'jumlah.min' => 'Jumlah minimal 1 rupiah',
            'kategori.required' => 'Kategori harus diisi',
            'kategori.string' => 'Kategori harus berupa teks',
            'kategori.max' => 'Kategori maksimal 255 karakter',
            'keterangan.required' => 'Keterangan harus diisi',
            'keterangan.string' => 'Keterangan harus berupa teks',
            'keterangan.max' => 'Keterangan maksimal 1000 karakter'
        ]);

        $pengeluaran = new Pengeluaran($request->all());
        $pengeluaran->user_id = $user->id;
        $pengeluaran->save();

        return redirect()->route('pengeluaran')->with('success', 'pengeluaran berhasil ditambahkan');
    }

    public function destroy(Request $request) {
        $user = Auth::user();
        $pengeluaran = Pengeluaran::where('id', $request->id)->where('user_id', $user->id)->first();

        if ($pengeluaran) {
            $pengeluaran->delete();
            return redirect()->route('pengeluaran')->with('success', 'pengeluaran berhasil dihapus');
        } else {
            return redirect()->route('pengeluaran')->with('error', 'pengeluaran tidak ditemukan');
        }
    }
}

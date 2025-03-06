<?php

namespace App\Http\Controllers;

use App\Models\Pemasukkan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PemasukkanController extends Controller
{
    public function index() {
        $user_id = Auth::id();
        
        $pemasukkan = Pemasukkan::where('user_id',$user_id)->get();

        // Ambil total pemasukkan per kategori
        $chartData = $pemasukkan->groupBy('kategori')->map(function ($item) {
            return $item->sum('jumlah');
        });

        $latestPemasukkan = Pemasukkan::where('user_id', $user_id)->latest()->take(5)->get();

        $ambilKategori = Pemasukkan::where('user_id', $user_id)->select('kategori')->distinct()->get();

        return view('pemasukkan.index', [
            'pemasukkan' => $pemasukkan,
            'chartData' => $chartData,
            'latestPemasukkan' => $latestPemasukkan,
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

        $pemasukkan = new Pemasukkan($request->all());
        $pemasukkan->user_id = $user->id;
        $pemasukkan->save();

        return redirect()->route('pemasukkan')->with('success', 'Pemasukkan berhasil ditambahkan');
    }

    public function destroy(Request $request) {
        $user = Auth::user();
        $pemasukkan = Pemasukkan::where('id', $request->id)->where('user_id', $user->id)->first();

        if ($pemasukkan) {
            $pemasukkan->delete();
            return redirect()->route('pemasukkan')->with('success', 'Pemasukkan berhasil dihapus');
        } else {
            return redirect()->route('pemasukkan')->with('error', 'Pemasukkan tidak ditemukan');
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Tabungan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TabunganController extends Controller
{
    public function index()
    {
        $user_id = Auth::id();
        $modal = Tabungan::where('user_id', $user_id)->first();

        $tabungan = Tabungan::where('user_id', $user_id)
            ->select('id', 'nama_goal', 'tanggal', 'target_jumlah', 'jumlah_uang', 'kategori', 'keterangan')
            ->get()
            ->map(function ($item) {
                $progress = ($item->jumlah_uang / $item->target_jumlah) * 100;
                $item->progress = round($progress);
                return $item;
            });

        $ambilKategori = Tabungan::where('user_id', $user_id)
            ->select('kategori')
            ->distinct()
            ->get();

        $totalTabungan = $tabungan->sum('jumlah_uang');
        $averageProgress = round($tabungan->avg('progress'));

        return view('tabungan.index', [
            'ambilKategori' => $ambilKategori,
            'tabungan' => $tabungan,
            'totalTabungan' => $totalTabungan,
            'averageProgress' => $averageProgress
        ]);
    }

    public function create(Request $request) {
        $user = Auth::user();
    
        try {
            $validated = $request->validate([
                'nama_goal' => 'required|string|max:255',
                'tanggal' => 'required|date',
                'target_jumlah' => 'required|numeric|min:1000',
                'jumlah_uang' => 'required|numeric|min:0|lte:target_jumlah',
                'kategori' => 'required|string|max:255',
                'keterangan' => 'nullable|string|max:1000'
            ], [
                'nama_goal.required' => 'Nama goal harus diisi',
                'nama_goal.string' => 'Nama goal harus berupa teks',
                'nama_goal.max' => 'Nama goal maksimal 255 karakter',
                'tanggal.required' => 'Tanggal harus diisi',
                'tanggal.date' => 'Format tanggal tidak valid',
                'target_jumlah.required' => 'Target jumlah harus diisi',
                'target_jumlah.numeric' => 'Target jumlah harus berupa angka',
                'target_jumlah.min' => 'Target jumlah minimal 1000 rupiah',
                'jumlah_uang.required' => 'Jumlah uang harus diisi',
                'jumlah_uang.numeric' => 'Jumlah uang harus berupa angka',
                'jumlah_uang.min' => 'Jumlah uang tidak boleh negatif',
                'jumlah_uang.max' => 'Jumlah uang tidak boleh lebih dari target jumlah',
                'kategori.required' => 'Kategori harus diisi',
                'kategori.string' => 'Kategori harus berupa teks',
                'kategori.max' => 'Kategori maksimal 255 karakter',
                'keterangan.string' => 'Keterangan harus berupa teks',
                'keterangan.max' => 'Keterangan maksimal 1000 karakter'
            ]);
    
            $tabungan = new Tabungan($validated);
            $tabungan->user_id = $user->id;
            $tabungan->save();
    
            return redirect()->route('tabungan')->with('success', 'tabungan berhasil ditambahkan');
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'errors' => $e->errors()
            ], 422);
        }
    }

    public function update(Request $request, $id) {
        try {
            $user = Auth::user();
            $tabungan = Tabungan::where('user_id', $user->id)
                            ->where('id', $id)
                            ->firstOrFail();

            $request->validate([
                'nama_goal' => 'required|string|max:255',
                'tanggal' => 'required|date',
                'target_jumlah' => 'required|numeric|min:1000',
                'jumlah_uang' => 'required|numeric|min:0|lte:target_jumlah',
                'kategori' => 'required|string|max:255',
                'keterangan' => 'nullable|string|max:1000'
            ], [
                'nama_goal.required' => 'Nama goal harus diisi',
                'nama_goal.string' => 'Nama goal harus berupa teks',
                'nama_goal.max' => 'Nama goal maksimal 255 karakter',
                'tanggal.required' => 'Tanggal harus diisi',
                'tanggal.date' => 'Format tanggal tidak valid',
                'target_jumlah.required' => 'Target jumlah harus diisi',
                'target_jumlah.numeric' => 'Target jumlah harus berupa angka',
                'target_jumlah.min' => 'Target jumlah minimal 1000 rupiah',
                'jumlah_uang.required' => 'Jumlah uang harus diisi',
                'jumlah_uang.numeric' => 'Jumlah uang harus berupa angka',
                'jumlah_uang.min' => 'Jumlah uang tidak boleh negatif',
                'jumlah_uang.max' => 'Jumlah uang tidak boleh lebih dari target jumlah',
                'kategori.required' => 'Kategori harus diisi',
                'kategori.string' => 'Kategori harus berupa teks',
                'kategori.max' => 'Kategori maksimal 255 karakter',
                'keterangan.string' => 'Keterangan harus berupa teks',
                'keterangan.max' => 'Keterangan maksimal 1000 karakter'
            ]);

            $tabungan->update($request->all());

            return redirect()->route('tabungan')->with('success', 'tabungan berhasil diubah');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'errors' => $e->errors()
            ], 422);
        }
    }

    public function destroy(Request $request) {
        $user = Auth::user();
        $tabungan = Tabungan::where('id', $request->id)->where('user_id', $user->id)->first();

        if ($tabungan) {
            $tabungan->delete();
            return redirect()->route('tabungan')->with('success', 'tabungan berhasil dihapus');
        } else {
            return redirect()->route('tabungan')->with('error', 'tabungan tidak ditemukan');
        }
    }
}

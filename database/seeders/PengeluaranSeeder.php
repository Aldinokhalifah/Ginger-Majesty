<?php

namespace Database\Seeders;

use App\Models\Pengeluaran;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PengeluaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pengeluaran::create([
            'user_id' => 1,
            'tanggal' => now(),
            'jumlah' => 1500000,
            'kategori' => 'Makan',
            'keterangan' => 'Makan sebulan'
        ]);

        Pengeluaran::create([
            'user_id' => 1,
            'tanggal' => now(),
            'jumlah' => 500000,
            'kategori' => 'Transportasi',
            'keterangan' => 'Bensin dan transport umum'
        ]);

        Pengeluaran::create([
            'user_id' => 1,
            'tanggal' => now(),
            'jumlah' => 1500000,
            'kategori' => 'Kebutuhan Pokok',
            'keterangan' => 'Beras, minyak, gula, dan telur'
        ]);
    }
}

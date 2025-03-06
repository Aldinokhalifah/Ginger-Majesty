<?php

namespace Database\Seeders;

use App\Models\Pemasukkan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PemasukkanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pemasukkan::create([
            'user_id' => 1,
            'tanggal' => now(),
            'jumlah' => 5000000,
            'kategori' => 'Gaji',
            'keterangan' => 'Gaji bulan ini'
        ]);

        Pemasukkan::create([
            'user_id' => 1,
            'tanggal' => now(),
            'jumlah' => 2000000,
            'kategori' => 'Freelance',
            'keterangan' => 'Proyek desain website'
        ]);

        Pemasukkan::create([
            'user_id' => 1,
            'tanggal' => now(),
            'jumlah' => 2500000,
            'kategori' => 'Transportasi',
            'keterangan' => 'Transportasi online'
        ]);
    }
}

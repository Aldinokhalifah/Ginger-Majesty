<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TabunganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        DB::table('tabungans')->insert([  
            [  
                'user_id' => 1, 
                'nama_goal' => 'Liburan ke Bali',  
                'tanggal' => Carbon::createFromFormat('Y-m-d', '2025-12-01'),  
                'target_jumlah' => 5000000.00,  
                'jumlah_uang' => 1000000.00,  
                'kategori' => 'Liburan',  
                'keterangan' => 'Tabungan untuk liburan akhir tahun.',  
                'created_at' => now(),  
                'updated_at' => now(),  
            ],  
            [  
                'user_id' => 1, 
                'nama_goal' => 'Beli Mobil',  
                'tanggal' => Carbon::createFromFormat('Y-m-d', '2026-01-15'),  
                'target_jumlah' => 20000000.00,  
                'jumlah_uang' => 5000000.00,  
                'kategori' => 'Investasi',  
                'keterangan' => 'Tabungan untuk membeli mobil baru.',  
                'created_at' => now(),  
                'updated_at' => now(),  
            ],  
            [  
                'user_id' => 2, 
                'nama_goal' => 'Pendidikan',  
                'tanggal' => Carbon::createFromFormat('Y-m-d', '2025-08-30'),  
                'target_jumlah' => 10000000.00,  
                'jumlah_uang' => 2000000.00,  
                'kategori' => 'Pendidikan',  
                'keterangan' => 'Tabungan untuk biaya pendidikan.',  
                'created_at' => now(),  
                'updated_at' => now(),  
            ],  
        ]);  
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tabungan extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'nama_goal', 'tanggal', 'target_jumlah','jumlah_uang', 'kategori', 'keterangan'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

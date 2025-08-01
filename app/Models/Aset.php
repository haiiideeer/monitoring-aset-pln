<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aset extends Model
{
    use HasFactory;

    protected $fillable = ['bidang_id', 'nama_aset', 'jumlah_aset', 'lokasi', 'keterangan'];

    public function bidang()
    {
        return $this->belongsTo(Bidang::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Bidang extends Model
{
    use HasFactory;

    protected $fillable = [
    'nama_bidang',
    'kode_bidang',
    'slug'];

    public function asets()
    {
        return $this->hasMany(\App\Models\Aset::class);
    }

    protected static function booted()
    {
        static::creating(function ($bidang) {
            $bidang->slug = Str::slug($bidang->nama_bidang . '-' . uniqid());
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SportCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'slug',
        'deskripsi',
        'icon',
        'gambar',
        'aktif',
        'urutan',
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'urutan' => 'integer',
    ];

    public function lapangan()
    {
        return $this->hasMany(Lapangan::class);
    }

    public function lapanganTersedia()
    {
        return $this->hasMany(Lapangan::class)->where('tersedia', true);
    }

    public static function getAktif()
    {
        return self::where('aktif', true)->orderBy('urutan')->get();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lapangan extends Model
{
    use HasFactory;

    protected $table = 'lapangan';

    protected $fillable = [
        'sport_category_id',
        'nama',
        'kode_lapangan',
        'deskripsi',
        'tipe',
        'lokasi',
        'kota',
        'alamat',
        'harga_per_jam',
        'harga_weekend',
        'foto',
        'galeri',
        'fasilitas',
        'kapasitas',
        'ukuran',
        'aturan',
        'jumlah_lapangan',
        'jam_buka',
        'jam_tutup',
        'tersedia',
        'rating',
        'jumlah_review',
    ];

    protected $casts = [
        'tersedia' => 'boolean',
        'harga_per_jam' => 'decimal:2',
        'harga_weekend' => 'decimal:2',
        'galeri' => 'array',
        'fasilitas' => 'array',
        'rating' => 'integer',
        'jumlah_review' => 'integer',
        'kapasitas' => 'integer',
        'jumlah_lapangan' => 'integer',
    ];

    public function sportCategory()
    {
        return $this->belongsTo(SportCategory::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function getHargaByDay($isWeekend = false)
    {
        return $isWeekend && $this->harga_weekend ? $this->harga_weekend : $this->harga_per_jam;
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('sport_category_id', $categoryId);
    }

    public function scopeTersedia($query)
    {
        return $query->where('tersedia', true);
    }

    public function scopeByKota($query, $kota)
    {
        return $query->where('kota', 'like', '%' . $kota . '%');
    }
}

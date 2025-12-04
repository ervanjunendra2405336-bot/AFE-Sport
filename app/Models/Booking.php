<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'lapangan_id',
        'user_id',
        'booking_code',
        'nama_pemesan',
        'email',
        'no_hp',
        'telepon',
        'tanggal_booking',
        'jam_mulai',
        'jam_selesai',
        'durasi_jam',
        'total_harga',
        'status',
        'metode_pembayaran',
        'bukti_pembayaran',
        'catatan',
    ];

    protected $casts = [
        'tanggal_booking' => 'date',
        'total_harga' => 'decimal:2',
    ];

    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

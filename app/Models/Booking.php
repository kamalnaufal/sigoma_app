<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // [FIX] Tambahkan properti ini untuk mengizinkan Mass Assignment
    protected $fillable = [
        'user_id',
        'venue_id',
        'booking_date',
        'start_time',
        'end_time',
        'total_price',
        'status',
    ];

    /**
     * Mendefinisikan relasi ke model User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mendefinisikan relasi ke model Venue.
     */
    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function waitingLists()
{
    return $this->hasMany(WaitingList::class)->orderBy('created_at', 'asc'); // Selalu urutkan berdasarkan FIFO
}
}

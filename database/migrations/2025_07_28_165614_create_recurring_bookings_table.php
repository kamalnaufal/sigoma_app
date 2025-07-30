<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecurringBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'venue_id',
        'user_id',
        'day_of_week',
        'start_time',
        'end_time',
        'start_date',
        'end_date',
        'notes',
    ];

    // Definisikan relasi jika perlu
    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

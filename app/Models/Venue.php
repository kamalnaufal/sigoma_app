<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // [FIX] Tambahkan properti ini untuk mengizinkan Mass Assignment
    protected $fillable = [
        'name',
        'description',
        'photo',
        'price_per_hour',
    ];
}

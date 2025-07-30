<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Venue; // <-- Jangan lupa import modelnya

class VenueSeeder extends Seeder
{
    public function run(): void
    {
        Venue::create([
            'name' => 'Lapangan Futsal A',
            'description' => 'Lapangan futsal indoor dengan rumput sintetis kualitas terbaik.',
            'price_per_hour' => 150000.00,
        ]);

        Venue::create([
            'name' => 'Lapangan Badminton B',
            'description' => 'Lapangan badminton dengan karpet vinyl dan penerangan standar internasional.',
            'price_per_hour' => 75000.00,
        ]);

        Venue::create([
            'name' => 'Lapangan Basket C',
            'description' => 'Lapangan basket outdoor dengan ring dan lantai standar.',
            'price_per_hour' => 200000.00,
        ]);
    }
}


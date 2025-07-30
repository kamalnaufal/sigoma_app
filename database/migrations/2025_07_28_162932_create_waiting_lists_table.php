<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('waiting_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Siapa yang mengantre
            $table->foreignId('booking_id')->constrained()->onDelete('cascade'); // Mengantre untuk booking yang mana
            $table->timestamps(); // created_at akan menjadi penentu urutan FIFO
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('waiting_lists');
    }
};

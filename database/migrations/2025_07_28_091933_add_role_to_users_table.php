<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
        // Tambahkan kolom 'role' setelah kolom 'email'
        // Nilai defaultnya adalah 'customer'
        $table->string('role')->after('email')->default('customer');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('users', function (Blueprint $table) {
        // Perintah untuk menghapus kolom jika migrasi di-rollback
        $table->dropColumn('role');
    });
    }
};

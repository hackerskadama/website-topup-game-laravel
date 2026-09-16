<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            // Menambahkan kolom 'bukti_transfer' untuk menyimpan nama file foto
            $table->string('bukti_transfer')->nullable(); 
        });
    }

    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            // Jika migrasi dibatalkan (rollback), kolom ini akan dihapus
            $table->dropColumn('bukti_transfer');
        });
    }
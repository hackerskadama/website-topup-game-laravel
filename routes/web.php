<?php

use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;

// Halaman utama langsung ke /topup
Route::get('/', function () {
    return redirect('/topup');
});

// Grup Rute Topup
Route::prefix('topup')->group(function () {
    Route::get('/', [TransaksiController::class, 'index']);
    
    // Rute baru untuk halaman detail game dan harga (melebar)
    Route::get('/{gameName}', [TransaksiController::class, 'showGame'])->name('topup.game');
    
    Route::post('/pilih-pembayaran', [TransaksiController::class, 'pilihPembayaran']);
    Route::post('/kirim-bukti', [TransaksiController::class, 'kirimBukti']);
});

// Grup Rute Admin (Digabungkan agar rapi)
Route::prefix('admin')->group(function () {
    Route::get('/transaksi', [TransaksiController::class, 'adminIndex']);
    Route::post('/konfirmasi/{id}', [TransaksiController::class, 'konfirmasi']);
    Route::get('/cetak/{id}', [TransaksiController::class, 'cetakBukti']);
});
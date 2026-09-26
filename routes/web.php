<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KonservasiController;
use App\Http\Controllers\RekapExportController;

// Pengunjung dialihkan ke Landing Page SIDAK BKSDA
Route::get('/', function () {
    return view('landing');
})->name('landing');

// ROUTE SIDAK BKSDA (Wajib Login)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [KonservasiController::class, 'dashboard'])->name('konservasi.dashboard');
    
    Route::get('/konservasi', [KonservasiController::class, 'index'])->name('konservasi.index');
    Route::get('/konservasi/create', [KonservasiController::class, 'create'])->name('konservasi.create');
    
    // ROUTE REKAPITULASI (Mendukung Form Filter Query String)
    Route::get('/rekapitulasi', [KonservasiController::class, 'index'])->name('rekap.index');
    
    // ROUTE STORE
    Route::post('/konservasi', [KonservasiController::class, 'store'])->name('konservasi.store');
    Route::post('/sidak', [KonservasiController::class, 'store'])->name('sidak.store'); // Alias tambahan untuk form SIDAK
    Route::post('/sub-bidang/store', [KonservasiController::class, 'store'])->name('sub-bidang.store');
    
    // ROUTE TAMBAHAN UNTUK EDIT & UPDATE DATA
    Route::get('/konservasi/{id}/edit', [KonservasiController::class, 'edit'])->name('konservasi.edit');
    Route::put('/konservasi/{id}', [KonservasiController::class, 'update'])->name('konservasi.update');
    
    // ROUTE TAMBAHAN UNTUK HAPUS DATA
    Route::delete('/konservasi/{id}', [KonservasiController::class, 'destroy'])->name('konservasi.destroy');

    Route::get('/peta', [KonservasiController::class, 'peta'])->name('konservasi.peta');

    // Route AJAX untuk memuat Sub-Bidang
    Route::get('/get-sub-bidang/{bidang_id}', [KonservasiController::class, 'getSubBidang'])
        ->name('konservasi.sub-bidang');

    Route::get('/konservasi/export-pdf', [RekapExportController::class, 'pdf'])->name('konservasi.export.pdf');
    Route::get('/konservasi/export-excel', [RekapExportController::class, 'excel'])->name('konservasi.export.excel');
});

// Panggil file route autentikasi dari Breeze
require __DIR__.'/auth.php';
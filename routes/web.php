<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BidangController;
use App\Http\Controllers\AsetController;
use App\Http\Controllers\BidangBarcodeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AsetsExportController;

Route::get('/asets/export-excel/{bidang_id}', [AsetsExportController::class, 'exportExcel'])
 ->name('asets.export.excel');

// Halaman Utama
Route::get('/', function () {
    return view('welcome');
});

// Dashboard setelah login
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Resource: Aset dan Bidang
Route::middleware(['auth'])->group(function () {
    Route::resource('/aset', AsetController::class);
    Route::resource('/bidang', BidangController::class);
});

// ===== QR Code & Export per Bidang =====
// QR Code Bidang yang mengarahkan ke daftar aset
Route::get('bidang/{slug}/qrcode', [BidangBarcodeController::class, 'generateQR'])->name('bidang.qrcode');

// Menampilkan daftar aset milik bidang (hasil dari QR Code)
Route::get('bidang/{slug}/asets', [BidangBarcodeController::class, 'showAsets'])->name('bidang.show');

// Export data aset per bidang
Route::get('bidang/{slug}/export/excel', [BidangBarcodeController::class, 'exportExcel'])->name('bidang.export.excel');
Route::get('bidang/{slug}/export/pdf', [BidangBarcodeController::class, 'exportPDF'])->name('bidang.export.pdf');

// (Opsional) QR Code View khusus bidang
Route::get('bidang/barcode/{slug}', [BidangBarcodeController::class, 'show'])->name('bidang.barcode.show');
Route::get('bidang/barcode/{slug}/qrcode', [BidangBarcodeController::class, 'generateQR'])->name('bidang.barcode.qrcode');

// Export semua aset (tanpa filter bidang)
Route::get('/asets/export/excel', [AsetController::class, 'exportAllExcel'])->name('asets.export.all');

// Auth routes
require __DIR__.'/auth.php';

// Route fallback
Route::fallback(function () {
    return 'Halaman tidak ditemukan. Route fallback aktif.';
});

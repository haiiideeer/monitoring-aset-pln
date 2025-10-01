<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BidangController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\BidangBarcodeController;
use App\Http\Controllers\Laporan\LaporanController;
use App\Http\Controllers\AsetsExportController;
use App\Http\Controllers\QRCodeController;

Route::get('/', function () {
    return view('welcome');
});

// 🔹 Refresh CAPTCHA
Route::get('/refresh-captcha', function () {
    return response()->json([
        'captcha' => captcha_img('flat')
    ]);
})->name('captcha.login.refresh');

// Autentikasi
require __DIR__ . '/auth.php';

// Route yang memerlukan auth
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');
    Route::get('/api/dashboard/stats', [DashboardController::class, 'getStats']);
    Route::get('/api/dashboard/chart-data', [DashboardController::class, 'getChartData']);
    // Profile Management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    // ========== BIDANG ROUTES ==========
    Route::resource('bidang', BidangController::class)->except(['show']);
    Route::prefix('bidang')->name('bidang.')->group(function () {
        Route::get('{kode_bidang}/detail-assets', [BidangController::class, 'showAssetsById'])->name('assets');
        Route::get('{slug}/asets', [BidangBarcodeController::class, 'showAsets'])->name('show');
        Route::get('{slug}/export/excel', [BidangBarcodeController::class, 'exportExcel'])->name('export.excel');
        Route::get('{slug}/export/pdf', [BidangBarcodeController::class, 'exportPDF'])->name('export.pdf');
        Route::get('{id}/detail-assets', [BidangController::class, 'showAssetsById'])->name('detail.assets');
        Route::get('{kode_bidang}/qr-data', [BidangController::class, 'showQrData'])->name('qr-data');
        Route::get('{kode_bidang}/assets-qr', [BidangController::class, 'showAssetsFromQR'])->name('assets-qr');
});

// Route untuk scanner QR code (tambahkan di luar grup bidang)
        Route::get('/scan', [BidangController::class, 'showScanner'])->name('scan');

    // ========== ASSETS ROUTES ==========
    Route::resource('assets', AssetController::class);
    Route::prefix('assets')->name('assets.')->group(function () {
        Route::get('export', [AssetController::class, 'export'])->name('export');
        Route::get('export/excel', [AssetController::class, 'exportExcel'])->name('export.excel');
        Route::get('export/pdf', [AssetController::class, 'exportPdf'])->name('export.pdf');
        Route::get('api/data', [AssetController::class, 'apiIndex'])->name('api.data');
        Route::delete('hapus-massal', [AssetController::class, 'destroyMultiple'])->name('hapus.massal');
        Route::get('generate-kode', [AssetController::class, 'generateKode'])->name('generate.kode');
        Route::post('validate-kode', [AssetController::class, 'validateKode'])->name('validate.kode');
        Route::post('{asset}/generate-qr', [AssetController::class, 'generateQrCode'])->name('generate-qr');
      Route::post('{asset}/regenerate-qr', [AssetController::class, 'regenerateQrCode'])->name('regenerate-qr');

    });

    // ========== LAPORAN ROUTES ==========
   Route::middleware(['auth'])->group(function () {
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('index');
        Route::post('/export-pdf', [LaporanController::class, 'exportPdf'])->name('exportPdf');
        Route::post('/preview', [LaporanController::class, 'preview'])->name('preview');
        Route::post('/summary', [LaporanController::class, 'getSummary'])->name('summary');
        
        // Route debug sementara untuk troubleshooting
        Route::get('/debug-bidang', [LaporanController::class, 'debugBidang'])->name('debug');
    });
});
});

    // ========== QR CODE ROUTES ==========
    Route::prefix('qrcode')->name('qrcode.')->group(function () {
        Route::get('/', [QRCodeController::class, 'index'])->name('index');
        Route::post('/generate/{type}/{id}', [QRCodeController::class, 'generate'])->name('generate');
        Route::post('/generate-all', [QRCodeController::class, 'generateAll'])->name('generate-all');
        Route::get('/download/{type}/{id}', [QRCodeController::class, 'download'])->name('download');
        Route::get('/scanner', [QRCodeController::class, 'scanner'])->name('scanner');
        
        
        Route::get('/bidang/scan-qr', function () {
            return view('bidang.qr-scanner');})->name('bidang.scan-qr');
    });

    // Public QR View (tanpa auth jika diperlukan untuk scanning)
    Route::get('/qr/{type}/{id}', [QRCodeController::class, 'scan'])->name('qr.view');


// Fallback Route
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
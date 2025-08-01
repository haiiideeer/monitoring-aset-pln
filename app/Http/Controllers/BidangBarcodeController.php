<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\Aset;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AsetsExport;
use Barryvdh\DomPDF\Facade\Pdf;

class BidangBarcodeController extends Controller
{
    // Menampilkan halaman QR Code untuk bidang
    public function show($slug)
    {
        $bidang = Bidang::where('slug', $slug)->firstOrFail();
        return view('bidang.barcode-show', compact('bidang'));
    }

    // Menampilkan daftar aset berdasarkan bidang
    public function showAsets($slug)
    {
        $bidang = Bidang::where('slug', $slug)->firstOrFail();
        $asets = Aset::where('bidang_id', $bidang->id)->get();
        return view('bidang.barcode-show', compact('bidang', 'asets'));
    }

    // Generate QR Code untuk bidang
    public function generateQR($slug)
    {
        $bidang = Bidang::where('slug', $slug)->firstOrFail();
        $url = route('bidang.show', $bidang->slug);
        return view('bidang.generate-qrcode', compact('bidang', 'url'));
    }

    // Export aset bidang ke Excel
    public function exportExcel($slug)
    {
        $bidang = Bidang::where('slug', $slug)->firstOrFail();
        return Excel::download(new AsetsExport($bidang->id), 'aset_bidang_' . $bidang->slug . '.xlsx');
    }

    // Export aset bidang ke PDF
    public function exportPDF($slug)
    {
        $bidang = Bidang::where('slug', $slug)->firstOrFail();
        $asets = Aset::where('bidang_id', $bidang->id)->get();
        $pdf = Pdf::loadView('bidang.export-pdf', compact('bidang', 'asets'));
        return $pdf->download('aset_bidang_' . $bidang->slug . '.pdf');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use App\Models\Asset;
use App\Models\Bidang;

class QRCodeController extends Controller
{
    /**
     * Display QR codes for all assets and bidangs
     */
    public function index()
    {
        try {
            $assets = Asset::with('bidang')->get();
            $bidangs = Bidang::with('assets')->get();
            
            return view('qrcode.index', compact('assets', 'bidangs'));
        } catch (\Exception $e) {
            return view('qrcode.index', [
                'assets' => collect(),
                'bidangs' => collect(),
                'error' => 'Terjadi kesalahan saat memuat data: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Generate QR Code for asset or bidang
     */
    public function generate(Request $request, $type, $id)
    {
        try {
            if ($type === 'asset') {
                $model = Asset::findOrFail($id);
                $url = route('qrcode.scan', ['type' => 'asset', 'id' => $id]);
                $filename = "qr_asset_{$id}.png";
            } elseif ($type === 'bidang') {
                $model = Bidang::findOrFail($id);
                $url = route('qrcode.scan', ['type' => 'bidang', 'id' => $id]);
                $filename = "qr_bidang_{$id}.png";
            } else {
                return response()->json(['message' => 'Tipe tidak valid'], 400);
            }

            // Generate QR Code
            $qrCode = QrCode::format('png')
                ->size(300)
                ->margin(2)
                ->generate($url);

            // Save QR Code to storage
            $path = "qrcodes/{$filename}";
            Storage::disk('public')->put($path, $qrCode);

            // Update model with QR code info
            $model->update([
                'qr_code' => $url,
                'qr_code_path' => $path
            ]);

            return response()->json([
                'message' => 'QR Code berhasil dibuat!',
                'path' => $path
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal membuat QR Code: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download QR Code
     */
    public function download($type, $id)
    {
        try {
            if ($type === 'asset') {
                $model = Asset::findOrFail($id);
                $filename = "QR_Asset_" . str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '_', $model->nama_aset) . ".png";
            } elseif ($type === 'bidang') {
                $model = Bidang::findOrFail($id);
                $filename = "QR_Bidang_" . str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '_', $model->nama_bidang) . ".png";
            } else {
                abort(404);
            }

            if (!$model->qr_code_path || !Storage::disk('public')->exists($model->qr_code_path)) {
                return redirect()->back()->with('error', 'QR Code tidak ditemukan');
            }

            // Get the file path
            $filePath = Storage::disk('public')->path($model->qr_code_path);
            
            // Check if file exists
            if (!file_exists($filePath)) {
                return redirect()->back()->with('error', 'File QR Code tidak ditemukan');
            }

            // Return download response
            return response()->download($filePath, $filename, [
                'Content-Type' => 'image/png',
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengunduh QR Code: ' . $e->getMessage());
        }
    }

    /**
     * Scan QR Code and display details
     */
    public function scan($type, $id)
    {
        try {
            if ($type === 'asset') {
                $asset = Asset::with('bidang')->findOrFail($id);
                return view('qrcode.scan-asset', compact('asset'));
            } elseif ($type === 'bidang') {
                $bidang = Bidang::with('assets')->findOrFail($id);
                return view('qrcode.scan-bidang', compact('bidang'));
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            abort(404, 'Data tidak ditemukan');
        }
    }

    /**
     * Generate all QR codes at once
     */
    public function generateAll()
    {
        try {
            $generated = 0;
            $errors = [];

            // Generate for all assets
            $assets = Asset::whereNull('qr_code')->get();
            foreach ($assets as $asset) {
                try {
                    $url = route('qrcode.scan', ['type' => 'asset', 'id' => $asset->id]);
                    $filename = "qr_asset_{$asset->id}.png";
                    
                    $qrCode = QrCode::format('png')
                        ->size(300)
                        ->margin(2)
                        ->generate($url);

                    $path = "qrcodes/{$filename}";
                    Storage::disk('public')->put($path, $qrCode);

                    $asset->update([
                        'qr_code' => $url,
                        'qr_code_path' => $path
                    ]);
                    
                    $generated++;
                } catch (\Exception $e) {
                    $errors[] = "Asset {$asset->nama_aset}: " . $e->getMessage();
                }
            }

            // Generate for all bidangs
            $bidangs = Bidang::whereNull('qr_code')->get();
            foreach ($bidangs as $bidang) {
                try {
                    $url = route('qrcode.scan', ['type' => 'bidang', 'id' => $bidang->id]);
                    $filename = "qr_bidang_{$bidang->id}.png";
                    
                    $qrCode = QrCode::format('png')
                        ->size(300)
                        ->margin(2)
                        ->generate($url);

                    $path = "qrcodes/{$filename}";
                    Storage::disk('public')->put($path, $qrCode);

                    $bidang->update([
                        'qr_code' => $url,
                        'qr_code_path' => $path
                    ]);
                    
                    $generated++;
                } catch (\Exception $e) {
                    $errors[] = "Bidang {$bidang->nama_bidang}: " . $e->getMessage();
                }
            }

            $message = "Berhasil generate {$generated} QR Code";
            if (!empty($errors)) {
                $message .= ". Error: " . implode(', ', $errors);
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal generate QR Code: ' . $e->getMessage());
        }
    }

    /**
     * Show QR Code scanner page
     */
    public function scanner()
    {
        return view('qrcode.scanner');
    }
}
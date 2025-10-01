<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BidangController extends Controller
{
    public function index()
    {
        $bidangs = Bidang::all();
        return view('bidang.index', compact('bidangs'));
    }

    public function create()
    {
        return view('bidang.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_bidang' => [
                'required',
                'string',
                'max:255',
                Rule::unique('bidangs')->where(function ($query) use ($request) {
                    return $query->where('nama_bidang', $request->nama_bidang);
                })->ignore($request->id)
            ],
            'kode_bidang' => [
                'required',
                'string',
                'max:50',
                'unique:bidangs,kode_bidang',
                'regex:/^[A-Z0-9]+$/'
            ],
        ], [
            'nama_bidang.required' => 'Nama bidang wajib diisi.',
            'nama_bidang.unique' => 'Nama bidang sudah terdaftar.',
            'kode_bidang.required' => 'Kode bidang wajib diisi.',
            'kode_bidang.unique' => 'Kode bidang sudah terdaftar. Silakan gunakan kode lain.',
            'kode_bidang.regex' => 'Kode bidang hanya boleh mengandung huruf kapital dan angka.',
        ]);

        $bidang = Bidang::create($validated);
        
        // Generate QR code untuk bidang baru
        $this->generateQrCode($bidang);

        return redirect()
            ->route('bidang.index')
            ->with('success', 'Bidang berhasil ditambahkan dengan QR code.');
    }

    public function edit(Bidang $bidang)
    {
        return view('bidang.edit', compact('bidang'));
    }

    public function update(Request $request, Bidang $bidang)
    {
        $validated = $request->validate([
            'nama_bidang' => [
                'required',
                'string',
                'max:255',
                Rule::unique('bidangs')->ignore($bidang->id)
            ],
            'kode_bidang' => [
                'required',
                'string',
                'max:50',
                Rule::unique('bidangs')->ignore($bidang->id),
                'regex:/^[A-Z0-9]+$/'
            ],
        ], [
            'nama_bidang.required' => 'Nama bidang wajib diisi.',
            'nama_bidang.unique' => 'Nama bidang sudah terdaftar.',
            'kode_bidang.required' => 'Kode bidang wajib diisi.',
            'kode_bidang.unique' => 'Kode bidang sudah terdaftar. Silakan gunakan kode lain.',
            'kode_bidang.regex' => 'Kode bidang hanya boleh mengandung huruf kapital dan angka.',
        ]);

        $oldKode = $bidang->kode_bidang;
        $bidang->update($validated);
        
        // Generate QR code jika belum ada atau kode bidang berubah
        if (!$bidang->has_qr_code || $oldKode !== $bidang->kode_bidang) {
            $this->generateQrCode($bidang);
        }

        return redirect()
            ->route('bidang.index')
            ->with('success', 'Bidang berhasil diperbarui.');
    }

    public function destroy(Bidang $bidang)
    {
        try {
            // Hapus file QR code jika ada
            if ($bidang->qr_code_path) {
                $filePath = str_replace('storage/', '', $bidang->qr_code_path);
                Storage::disk('public')->delete($filePath);
            }
            
            $bidang->delete();
            return redirect()
                ->route('bidang.index')
                ->with('success', 'Bidang berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()
                ->route('bidang.index')
                ->with('error', 'Gagal menghapus bidang: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan daftar aset berdasarkan kode bidang (untuk QR scan)
     */
    public function showAssets($kode_bidang)
    {
        $bidang = Bidang::where('kode_bidang', $kode_bidang)->firstOrFail();
        
        // Query builder untuk filter
        $query = $bidang->assets();
        
        // Filter berdasarkan search
        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('nama_aset', 'like', '%' . $search . '%')
                  ->orWhere('kode_aset', 'like', '%' . $search . '%')
                  ->orWhere('lokasi', 'like', '%' . $search . '%');
            });
        }
        
        // Filter berdasarkan kondisi
        if (request('kondisi')) {
            $query->where('kondisi', request('kondisi'));
        }
        
        // Filter berdasarkan lokasi
        if (request('lokasi')) {
            $query->where('lokasi', 'like', '%' . request('lokasi') . '%');
        }
        
        // Ambil aset dengan pagination
        $assets = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Data statistik
        $stats = $bidang->getAssetStats();
        
        // Ambil daftar lokasi unik untuk filter
        $lokasis = $bidang->assets()
            ->select('lokasi')
            ->whereNotNull('lokasi')
            ->where('lokasi', '!=', '')
            ->distinct()
            ->pluck('lokasi');
        
        return view('bidang.assets', compact('bidang', 'assets', 'stats', 'lokasis'));
    }

    /**
     * Menampilkan daftar aset berdasarkan ID bidang
     */
    public function showAssetsById($id)
    {
        $bidang = Bidang::findOrFail($id);
        
        // Query builder untuk filter
        $query = $bidang->assets();
        
        // Filter berdasarkan search
        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('nama_aset', 'like', '%' . $search . '%')
                  ->orWhere('kode_aset', 'like', '%' . $search . '%')
                  ->orWhere('lokasi', 'like', '%' . $search . '%');
            });
        }
        
        // Filter berdasarkan kondisi
        if (request('kondisi')) {
            $query->where('kondisi', request('kondisi'));
        }
        
        // Filter berdasarkan lokasi
        if (request('lokasi')) {
            $query->where('lokasi', 'like', '%' . request('lokasi') . '%');
        }
        
        // Ambil aset dengan pagination
        $assets = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Data statistik
        $stats = $bidang->getAssetStats();
        
        // Ambil daftar lokasi unik untuk filter
        $lokasis = $bidang->assets()
            ->select('lokasi')
            ->whereNotNull('lokasi')
            ->where('lokasi', '!=', '')
            ->distinct()
            ->pluck('lokasi');
        
        return view('bidang.assets', compact('bidang', 'assets', 'stats', 'lokasis'));
    }

    /**
     * API endpoint untuk mendapatkan data aset (untuk AJAX)
     */
    public function getAssetsData($kode_bidang)
    {
        $bidang = Bidang::where('kode_bidang', $kode_bidang)->firstOrFail();
        
        $assets = $bidang->assets()->get();
        
        return response()->json([
            'bidang' => $bidang,
            'assets' => $assets,
            'stats' => $bidang->getAssetStats()
        ]);
    }

    /**
     * Menampilkan halaman scanner QR code
     */
    public function showScanner()
    {
        return view('bidang.scanner');
    }

    /**
     * Menampilkan data aset dari hasil scan QR code
     */
    public function showAssetsFromQR($kode_bidang)
    {
        // Cari bidang berdasarkan kode
        $bidang = Bidang::where('kode_bidang', $kode_bidang)->first();
        
        if (!$bidang) {
            abort(404, 'Bidang tidak ditemukan');
        }
        
        // Ambil semua aset yang terkait dengan bidang ini
        $assets = Asset::where('bidang_id', $bidang->id)->get();
        
        return view('bidang.assets-qr', compact('bidang', 'assets'));
    }

    /**
     * API endpoint untuk data QR code (JSON)
     */
    public function showQrData($kode_bidang)
    {
        // Cari bidang berdasarkan kode
        $bidang = Bidang::where('kode_bidang', $kode_bidang)->first();
        
        if (!$bidang) {
            return response()->json(['error' => 'Bidang tidak ditemukan'], 404);
        }
        
        // Ambil semua aset yang terkait dengan bidang ini
        $assets = Asset::where('bidang_id', $bidang->id)->get();
        
        return response()->json([
            'bidang' => [
                'nama_bidang' => $bidang->nama_bidang,
                'kode_bidang' => $bidang->kode_bidang
            ],
            'assets' => $assets
        ]);
    }

    /**
     * Helper function untuk generate QR code
     */
    private function generateQrCode($bidang)
    {
        // Generate QR code yang mengarah ke halaman assets
        $url = route('bidang.assets-qr', $bidang->kode_bidang);
        $qrCode = QrCode::format('svg')->size(200)->generate($url);
        
        // Hapus QR code lama jika ada
        if ($bidang->qr_code_path) {
            $oldFilePath = str_replace('storage/', '', $bidang->qr_code_path);
            Storage::disk('public')->delete($oldFilePath);
        }
        
        // Simpan QR code ke storage
        $fileName = 'qrcodes/bidang_' . $bidang->kode_bidang . '_' . time() . '.svg';
        Storage::disk('public')->put($fileName, $qrCode);
        
        // Simpan path QR code ke database
        $bidang->qr_code_path = 'storage/' . $fileName;
        $bidang->has_qr_code = true;
        $bidang->save();
    }
}
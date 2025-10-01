<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Bidang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Validator;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        try {
            $search   = $request->get('search');
            $bidang   = $request->get('bidang');
            $kondisi  = $request->get('kondisi');
            $perPage  = $request->get('per_page', 10);
            $perPage  = min($perPage, 100);

            $query = Asset::with('bidang');

            if ($search && !empty(trim($search))) {
                $searchTerm = trim($search);
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('nama_aset', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('lokasi', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('kondisi', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('unit', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('kode_aset', 'LIKE', "%{$searchTerm}%")
                        ->orWhereHas('bidang', function ($q) use ($searchTerm) {
                            $q->where('nama_bidang', 'LIKE', "%{$searchTerm}%");
                        });
                });
            }

            if ($bidang && !empty($bidang)) {
                $query->where('bidang_id', $bidang);
            }

            if ($kondisi && !empty($kondisi)) {
                $query->where('kondisi', $kondisi);
            }

            $query->orderBy('created_at', 'desc');
            $assets = $query->paginate($perPage)->appends($request->query());

            $bidangs        = Bidang::orderBy('nama_bidang')->get();
            $kondisiOptions = Asset::getKondisiOptions();

            return view('assets.index', compact(
                'assets',
                'bidangs',
                'kondisiOptions',
                'search',
                'bidang',
                'kondisi',
                'perPage'
            ));
        } catch (\Exception $e) {
            Log::error('Error in AssetController@index: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat data aset: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $bidangs = Bidang::orderBy('nama_bidang')->get();
            if ($bidangs->isEmpty()) {
                return back()->with('error', 'Tidak ada data bidang tersedia. Silakan tambahkan bidang terlebih dahulu.');
            }
            return view('assets.create', compact('bidangs'));
        } catch (\Exception $e) {
            Log::error('Error in AssetController@create: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nama_aset'        => 'required|string|max:255|min:2',
                'bidang_id'        => 'required|exists:bidangs,id',
                'unit'             => 'required|string|max:255',
                'jumlah_aset'      => 'required|integer|min:1|max:999999',
                'harga'            => 'required|numeric|min:0',
                'lokasi'           => 'required|string|max:255|min:2',
                'kondisi'          => 'required|string|max:100',
                'tanggal_perolehan'=> 'required|date|before_or_equal:today',
                'foto_aset.*'      => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048|dimensions:max_width=4000,max_height=4000'
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $validated = $validator->validated();
            $asset = new Asset();
            $asset->kode_aset = Asset::generateKodeAset();
            $asset->fill($validated);

            $photoPaths = [];
            if ($request->hasFile('foto_aset')) {
                foreach ((array) $request->file('foto_aset') as $file) {
                    if ($file && $file->isValid()) {
                        $filename = 'asset_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                        $path     = $file->storeAs('assets', $filename, 'public');
                        $photoPaths[] = $path;
                    }
                }
            }

            if (!empty($photoPaths)) {
                $asset->foto_aset = json_encode($photoPaths);
            }

            $asset->save();

            // Generate QR Code dengan error handling yang lebih baik
            $qrResult = $this->generateQrCodeForAsset($asset);
            
                        if ($qrResult['success']) {
                return redirect()->route('assets.index')->with(
                    'success',
                    'Aset ' . $asset->nama_aset . ' berhasil ditambahkan.'
                );
            } else {
                return redirect()->route('assets.index')->with(
                    'warning',
                    'Aset ' . $asset->nama_aset . ' berhasil ditambahkan, tetapi gagal menghasilkan QR Code: ' . $qrResult['message']
                );
            }
        } catch (\Exception $e) {
            Log::error('Error in AssetController@store: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menyimpan aset: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $asset = Asset::with('bidang')->findOrFail($id);
        $bidangs = Bidang::orderBy('nama_bidang')->get();

        return view('assets.show', compact('asset', 'bidangs'));
    }

    public function edit(Asset $asset)
    {
        $bidangs = Bidang::orderBy('nama_bidang')->get();
        return view('assets.partials.edit-modal', compact('asset', 'bidangs'));
    }

    public function update(Request $request, Asset $asset)
    {
        $validator = Validator::make($request->all(), [
            'nama_aset'        => 'required|string|max:255|min:2',
            'bidang_id'        => 'required|exists:bidangs,id',
            'unit'             => 'required|string|max:255',
            'jumlah_aset'      => 'required|integer|min:1|max:999999',
            'harga'            => 'required|numeric|min:0',
            'lokasi'           => 'required|string|max:255|min:2',
            'kondisi'          => 'required|string|max:100',
            'tanggal_perolehan'=> 'required|date|before_or_equal:today',
            'foto_aset.*'      => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048|dimensions:max_width=4000,max_height=4000'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        if ($request->hasFile('foto_aset')) {
            $photoPaths = [];
            foreach ((array) $request->file('foto_aset') as $file) {
                if ($file && $file->isValid()) {
                    $filename = 'asset_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $path     = $file->storeAs('assets', $filename, 'public');
                    $photoPaths[] = $path;
                }
            }

            if (!empty($photoPaths)) {
                $this->deleteAssetPhotos($asset);
                $validated['foto_aset'] = json_encode($photoPaths);
            }
        }

        $asset->update($validated);

        // Regenerate QR Code
        $qrResult = $this->generateQrCodeForAsset($asset);
                if ($qrResult['success']) {
                return redirect()->route('assets.index')->with(
                    'success',
                    'Aset ' . $asset->nama_aset . ' Data berhasil diperbarui.'
                );
            } else {
                return redirect()->route('assets.index')->with(
                    'warning',
                    'Aset ' . $asset->nama_aset . ' berhasil diperbarui, tetapi gagal menghasilkan ulang QR Code: ' . $qrResult['message']
                );
            }
    }

    public function destroy(Asset $asset)
    {
        $this->deleteAssetPhotos($asset);
        if ($asset->qr_code_path && Storage::disk('public')->exists($asset->qr_code_path)) {
            Storage::disk('public')->delete($asset->qr_code_path);
        }
        $asset->delete();
       return redirect()->route('assets.index')->with('success','Aset ' . $asset->nama_aset . ' berhasil dihapus.'
);
    }

    private function deleteAssetPhotos(Asset $asset)
    {
        if ($asset->foto_aset) {
            $photos = json_decode($asset->foto_aset, true);
            foreach ((array) $photos as $photo) {
                if (Storage::disk('public')->exists($photo)) {
                    Storage::disk('public')->delete($photo);
                }
            }
        }
    }

    private function generateQrCodeForAsset(Asset $asset)
    {
        try {
            // Pastikan direktori qrcodes ada
            $qrcodesPath = 'qrcodes';
            $publicPath = storage_path('app/public/' . $qrcodesPath);
            
            if (!file_exists($publicPath)) {
                mkdir($publicPath, 0755, true);
            }

            // Hapus QR code lama jika ada
            if ($asset->qr_code_path && Storage::disk('public')->exists($asset->qr_code_path)) {
                Storage::disk('public')->delete($asset->qr_code_path);
            }

            // Data untuk QR Code
            $qrUrl = route('assets.show', $asset->id);

            // Generate QR Code menggunakan Simple QR Code
            $qrFileName = 'qr_asset_' . $asset->id . '_' . time() . '.svg';
            $qrPath = $qrcodesPath . '/' . $qrFileName;
            $fullPath = $publicPath . '/' . $qrFileName;

            // Generate QR Code dan simpan ke file
            $qrCode = QrCode::size(200)
                            ->errorCorrection('M')
                            ->generate($qrUrl);

            // Simpan file SVG
            file_put_contents($fullPath, $qrCode);

            if (!file_exists($fullPath)) {
                throw new \Exception('Gagal menghasilkan file QR code');
            }

            // Update asset dengan QR path
            $asset->update([
                'qr_code_path' => $qrPath,
                'has_qr_code' => true
            ]);

            Log::info("QR Code berhasil dibuat untuk asset {$asset->id}: {$qrPath}");

            return [
                'success' => true,
                'message' => 'QR Code berhasil dibuat',
                'path' => $qrPath
            ];

        } catch (\Exception $e) {
            Log::error('QR Code generation failed for asset ' . $asset->id . ': ' . $e->getMessage(), [
                'asset_id' => $asset->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Set has_qr_code to false
            $asset->update(['has_qr_code' => false]);

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * EXPORT PDF - COMPLETELY REWRITTEN with proper photo handling
     */
    public function exportPdf(Request $request)
    {
        try {
            Log::info('Export PDF started', [
                'search' => $request->get('search'),
                'bidang' => $request->get('bidang'),
                'kondisi' => $request->get('kondisi')
            ]);

            $query = Asset::with('bidang');

            // Apply filters
            if ($request->filled('search')) {
                $search = trim($request->get('search'));
                $query->where(function ($q) use ($search) {
                    $q->where('nama_aset', 'LIKE', "%{$search}%")
                        ->orWhere('lokasi', 'LIKE', "%{$search}%")
                        ->orWhere('kondisi', 'LIKE', "%{$search}%")
                        ->orWhere('unit', 'LIKE', "%{$search}%")
                        ->orWhereHas('bidang', function ($q) use ($search) {
                            $q->where('nama_bidang', 'LIKE', "%{$search}%");
                        });
                });
            }

            if ($request->filled('bidang')) {
                $query->where('bidang_id', $request->get('bidang'));
            }

            if ($request->filled('kondisi')) {
                $query->where('kondisi', $request->get('kondisi'));
            }

            $assets = $query->orderBy('created_at', 'desc')->get();

            Log::info('Assets fetched for PDF', ['count' => $assets->count()]);

            // Process each asset to add base64 photo data
            foreach ($assets as $asset) {
                // Initialize foto_base64 as null
                $asset->foto_base64 = null;
                
                Log::info('Processing asset photo', [
                    'asset_id' => $asset->id,
                    'foto_aset' => $asset->foto_aset,
                    'has_foto_aset' => !empty($asset->foto_aset)
                ]);
                
                if ($asset->foto_aset) {
                    $photos = json_decode($asset->foto_aset, true);
                    
                    // Take the first photo if multiple photos exist
                    $firstPhoto = null;
                    if (is_array($photos) && !empty($photos)) {
                        $firstPhoto = $photos[0];
                    } elseif (is_string($photos)) {
                        $firstPhoto = $photos;
                    }
                    
                    Log::info('First photo identified', [
                        'asset_id' => $asset->id,
                        'first_photo' => $firstPhoto,
                        'is_array' => is_array($photos)
                    ]);
                    
                    if ($firstPhoto) {
                        // Try multiple possible paths
                        $possiblePaths = [
                            storage_path('app/public/' . $firstPhoto),
                            storage_path('app/public/assets/' . $firstPhoto),
                            public_path('storage/' . $firstPhoto),
                            public_path('storage/assets/' . $firstPhoto),
                            storage_path('app/' . $firstPhoto),
                            public_path($firstPhoto)
                        ];
                        
                        foreach ($possiblePaths as $photoPath) {
                            Log::info('Checking photo path', [
                                'asset_id' => $asset->id,
                                'path' => $photoPath,
                                'exists' => file_exists($photoPath),
                                'readable' => file_exists($photoPath) && is_readable($photoPath)
                            ]);
                            
                            if (file_exists($photoPath) && is_readable($photoPath)) {
                                try {
                                    $imageData = file_get_contents($photoPath);
                                    if ($imageData !== false && strlen($imageData) > 0) {
                                        // Validate it's actually an image
                                        $imageInfo = @getimagesizefromstring($imageData);
                                        if ($imageInfo !== false) {
                                            $extension = strtolower(pathinfo($photoPath, PATHINFO_EXTENSION));
                                            $mimeType = match($extension) {
                                                'png' => 'image/png',
                                                'jpg', 'jpeg' => 'image/jpeg',
                                                'gif' => 'image/gif',
                                                'webp' => 'image/webp',
                                                'bmp' => 'image/bmp',
                                                default => $imageInfo['mime'] ?? 'image/jpeg'
                                            };
                                            
                                            $asset->foto_base64 = 'data:' . $mimeType . ';base64,' . base64_encode($imageData);
                                            
                                            Log::info('Photo processed successfully', [
                                                'asset_id' => $asset->id,
                                                'path' => $photoPath,
                                                'mime_type' => $mimeType,
                                                'data_length' => strlen($imageData)
                                            ]);
                                            
                                            break; // Stop searching once we find a valid image
                                        }
                                    }
                                } catch (Exception $e) {
                                    Log::warning("Failed to process photo for asset {$asset->id}: " . $e->getMessage(), [
                                        'path' => $photoPath,
                                        'error' => $e->getMessage()
                                    ]);
                                    continue;
                                }
                            }
                        }
                    }
                }
                
                // Set foto field for template compatibility
                if ($asset->foto_base64) {
                    $asset->foto = $asset->foto_base64; // Add this for template compatibility
                }
            }

            $bidangName = $request->filled('bidang') ? Bidang::find($request->get('bidang'))?->nama_bidang : null;

            $data = [
                'assets'      => $assets,
                'search'      => $request->get('search'),
                'bidangName'  => $bidangName,
                'kondisi'     => $request->get('kondisi'),
                'exportDate'  => Carbon::now()->format('d/m/Y H:i:s'),
                'totalAssets' => $assets->count()
            ];

            Log::info('Creating PDF with data', [
                'total_assets' => $data['totalAssets'],
                'assets_with_photos' => $assets->where('foto_base64', '!=', null)->count()
            ]);

            $pdf = PDF::loadView('assets.export-pdf', $data)
                     ->setPaper('A4', 'landscape')
                     ->setOption('isRemoteEnabled', true)
                     ->setOption('isHtml5ParserEnabled', true)
                     ->setOption('defaultFont', 'Arial');
                     
            $filename = 'Data_Aset_' . Carbon::now()->format('Y-m-d_H-i-s') . '.pdf';

            Log::info('PDF export completed successfully');

            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            Log::error('Error in exportPdf: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Terjadi kesalahan saat mengekspor PDF: ' . $e->getMessage());
        }
    }

    /**
     * Regenerate QR Code for existing asset
     */
    public function regenerateQrCode(Asset $asset)
    {
        $result = $this->generateQrCodeForAsset($asset);
        
        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => 'QR Code berhasil dibuat ulang'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat QR Code: ' . $result['message']
            ], 500);
        }
    }

    /**
     * DEBUG METHOD - Remove in production
     */
    public function debugPhotoStorage()
    {
        $assets = Asset::whereNotNull('foto_aset')->take(5)->get();
        
        $debug = [];
        foreach ($assets as $asset) {
            $photos = json_decode($asset->foto_aset, true);
            $debugInfo = [
                'id' => $asset->id,
                'nama_aset' => $asset->nama_aset,
                'foto_aset_raw' => $asset->foto_aset,
                'photos_decoded' => $photos,
                'is_array' => is_array($photos),
                'file_checks' => []
            ];
            
            if (is_array($photos)) {
                foreach ($photos as $photo) {
                    $paths = [
                        'storage_public' => storage_path('app/public/' . $photo),
                        'public_storage' => public_path('storage/' . $photo),
                    ];
                    
                    foreach ($paths as $name => $path) {
                        $debugInfo['file_checks'][] = [
                            'name' => $name,
                            'path' => $path,
                            'exists' => file_exists($path),
                            'readable' => file_exists($path) && is_readable($path),
                            'size' => file_exists($path) ? filesize($path) : 0
                        ];
                    }
                }
            }
            
            $debug[] = $debugInfo;
        }
        
        return response()->json($debug, 200, [], JSON_PRETTY_PRINT);
    }
}
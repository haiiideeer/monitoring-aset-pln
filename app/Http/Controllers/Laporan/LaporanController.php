<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\Bidang;
use Carbon\Carbon;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class LaporanController extends Controller
{
    public function index()
    {
        try {
            $bidangs = Bidang::orderBy('nama_bidang')->get();
            $startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
            $endDate = Carbon::now()->format('Y-m-d');
            
            return view('laporan.index', compact('startDate', 'endDate', 'bidangs'));
        } catch (Exception $e) {
            Log::error('Error in laporan index: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat halaman laporan.');
        }
    }

    public function exportPdf(Request $request)
    {
        try {
            Log::info('Export PDF started', [
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'jenis_laporan' => $request->jenis_laporan,
                'bidang_id' => $request->bidang_id
            ]);

            // Enhanced validation
            $rules = [
                'start_date' => 'nullable|date|before_or_equal:today',
                'end_date' => 'nullable|date|after_or_equal:start_date|before_or_equal:today',
                'jenis_laporan' => 'required|in:semua,per_bidang'
            ];

            // Conditional validation for bidang_id
            if ($request->jenis_laporan === 'per_bidang') {
                $rules['bidang_id'] = 'required|exists:bidangs,id';
            }

            $messages = [
                'start_date.before_or_equal' => 'Tanggal mulai tidak boleh lebih dari hari ini.',
                'end_date.after_or_equal' => 'Tanggal akhir harus setelah atau sama dengan tanggal mulai.',
                'end_date.before_or_equal' => 'Tanggal akhir tidak boleh lebih dari hari ini.',
                'bidang_id.required' => 'Bidang harus dipilih untuk laporan per bidang.',
                'bidang_id.exists' => 'Bidang yang dipilih tidak valid.',
                'jenis_laporan.required' => 'Jenis laporan harus dipilih.',
                'jenis_laporan.in' => 'Jenis laporan tidak valid.'
            ];

            $validated = $request->validate($rules, $messages);

            // Parse dates
            $start = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : null;
            $end = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : null;

            // Get bidang if specified
            $bidang = null;
            if ($request->jenis_laporan === 'per_bidang' && $request->bidang_id) {
                $bidang = Bidang::find($request->bidang_id);
                if (!$bidang) {
                    return back()->withInput()->withErrors(['bidang_id' => 'Bidang tidak ditemukan.']);
                }
            }

            // Get assets based on filters
            $asets = $this->getAsets($start, $end, $request->jenis_laporan, $request->bidang_id);

            Log::info('Assets fetched for PDF', ['count' => $asets->count()]);

            // Check if there's data to export
            if ($asets->isEmpty()) {
                $message = 'Tidak ada data aset ditemukan untuk ';
                if ($request->jenis_laporan === 'per_bidang') {
                    $message .= 'bidang ' . ($bidang ? $bidang->nama_bidang : 'yang dipilih') . ' ';
                }
                $message .= 'periode yang dipilih.';
                
                return back()->withInput()->with('warning', $message);
            }

            // Process each asset to add base64 photo data
            $assetsWithPhotos = 0;
            foreach ($asets as $asset) {
                // Initialize foto_base64 as null
                $asset->foto_base64 = null;
                
                Log::info('Processing asset photo', [
                    'asset_id' => $asset->id,
                    'nama_aset' => $asset->nama_aset,
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
                            Log::debug('Checking photo path', [
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
                                            $asset->foto = $asset->foto_base64; // Add for template compatibility
                                            $assetsWithPhotos++;
                                            
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
                        
                        // If no valid path found, log it
                        if (!$asset->foto_base64) {
                            Log::warning('No valid photo path found for asset', [
                                'asset_id' => $asset->id,
                                'first_photo' => $firstPhoto,
                                'checked_paths' => $possiblePaths
                            ]);
                        }
                    }
                }
            }

            Log::info('Photo processing completed', [
                'total_assets' => $asets->count(),
                'assets_with_photos' => $assetsWithPhotos
            ]);

            // Calculate additional data for the updated template
            $total_nilai_aset = $this->calculateTotalValue($asets);
            $kondisi_stats = $this->getKondisiStatistics($asets);

            // Prepare data for PDF
            $data = [
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'jenis_laporan' => $request->jenis_laporan,
                'bidang' => $bidang,
                'asets' => $asets,
                'total_aset' => $asets->sum('jumlah_aset'),
                'total_items' => $asets->count(),
                'total_nilai_aset' => $total_nilai_aset,
                'kondisi_stats' => $kondisi_stats,
                'tanggal_cetak' => Carbon::now()->format('d-m-Y H:i:s')
            ];

            Log::info('Creating PDF with data', [
                'total_assets' => $data['total_items'],
                'total_units' => $data['total_aset'],
                'total_nilai' => $total_nilai_aset,
                'assets_with_photos' => $assetsWithPhotos
            ]);

            // Generate PDF with landscape orientation for more columns
            $pdf = PDF::loadView('laporan.export_pdf', $data)
                ->setPaper('a4', 'landscape')
                ->setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                    'defaultFont' => 'Arial',
                    'enable_php' => false,
                    'dpi' => 150,
                    'defaultPaperSize' => 'a4',
                    'chroot' => [public_path(), storage_path()],
                ]);

            // Generate filename
            $filename = $this->generateFilename($request->jenis_laporan, $bidang, $start, $end);
            
            Log::info('PDF Export completed successfully', [
                'jenis' => $request->jenis_laporan,
                'total_data' => $asets->count(),
                'total_nilai' => number_format($total_nilai_aset),
                'filename' => $filename
            ]);
            
            return $pdf->download($filename);

        } catch (Exception $e) {
            Log::error('Error generating PDF report: ' . $e->getMessage(), [
                'request' => $request->all(),
                'stack_trace' => $e->getTraceAsString()
            ]);
            
            return back()->withInput()->with('error', 'Terjadi kesalahan saat menggenerate laporan PDF: ' . $e->getMessage());
        }
    }

    /**
     * Get assets based on filters with proper handling for both report types
     * Updated to include all necessary fields for the new template
     */
    private function getAsets($start, $end, $jenis_laporan, $bidang_id = null)
    {
        $query = Asset::with(['bidang' => function($q) {
            $q->select('id', 'nama_bidang');
        }])
        ->select([
            'id',
            'nama_aset',
            'kode_aset',
            'bidang_id',
            'unit',
            'jumlah_aset',
            'harga',
            'lokasi',
            'kondisi',
            'tanggal_perolehan',
            'foto_aset',
            'created_at',
            'updated_at'
        ]);

        // Apply date filters if provided
        if ($start) {
            $query->where('created_at', '>=', $start);
        }
        if ($end) {
            $query->where('created_at', '<=', $end);
        }

        // Apply bidang filter based on report type
        if ($jenis_laporan === 'per_bidang') {
            if ($bidang_id) {
                $query->where('bidang_id', $bidang_id);
            } else {
                // This should not happen due to validation, but just in case
                $query->whereRaw('1 = 0'); // Return empty result
            }
        }
        // For 'semua' type, don't filter by bidang_id

        return $query->orderBy('bidang_id')
                    ->orderBy('created_at', 'desc')
                    ->get();
    }

    /**
     * Calculate total value of assets (harga * jumlah_aset)
     */
    private function calculateTotalValue($asets)
    {
        $total = 0;
        foreach ($asets as $asset) {
            if ($asset->harga && $asset->jumlah_aset) {
                $total += ($asset->harga * $asset->jumlah_aset);
            }
        }
        return $total;
    }

    /**
     * Get kondisi statistics for assets
     */
    private function getKondisiStatistics($asets)
    {
        $kondisiStats = [];
        $totalUnits = $asets->sum('jumlah_aset');
        
        $grouped = $asets->groupBy(function($item) {
            return $item->kondisi ?: 'Tidak Diketahui';
        });

        foreach ($grouped as $kondisi => $items) {
            $totalItemUnits = $items->sum('jumlah_aset');
            $kondisiStats[] = [
                'kondisi' => $kondisi,
                'jumlah_item' => $items->count(),
                'total_unit' => $totalItemUnits,
                'persentase' => $totalUnits > 0 ? round(($totalItemUnits / $totalUnits) * 100, 1) : 0
            ];
        }

        return $kondisiStats;
    }

    /**
     * Generate descriptive filename for PDF export
     */
    private function generateFilename($jenis_laporan, $bidang, $start, $end)
    {
        $prefix = 'laporan-aset';
        
        // Report type
        if ($jenis_laporan === 'per_bidang' && $bidang) {
            $type = 'bidang-' . $this->slugify($bidang->nama_bidang);
        } else {
            $type = 'semua';
        }
        
        // Date range
        $startStr = $start ? $start->format('Ymd') : 'awal';
        $endStr = $end ? $end->format('Ymd') : 'sekarang';
        $dateRange = $startStr . '-' . $endStr;
        
        // Timestamp
        $timestamp = now()->format('His');
        
        return "{$prefix}-{$type}-{$dateRange}-{$timestamp}.pdf";
    }

    /**
     * Create URL-friendly slug from string
     */
    private function slugify($text)
    {
        // Replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        
        // Remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);
        
        // Trim
        $text = trim($text, '-');
        
        // Remove duplicate -
        $text = preg_replace('~-+~', '-', $text);
        
        // Lowercase
        $text = strtolower($text);
        
        if (empty($text)) {
            return 'unknown';
        }
        
        return $text;
    }

    /**
     * Get summary statistics for reports (Updated with new fields)
     */
    public function getSummary(Request $request)
    {
        try {
            $start = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : null;
            $end = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : null;

            $allAssets = $this->getAsets($start, $end, 'semua');
            $totalValue = $this->calculateTotalValue($allAssets);
            
            $summary = [
                'total_all_assets' => $allAssets->sum('jumlah_aset'),
                'total_all_items' => $allAssets->count(),
                'total_nilai_aset' => $totalValue,
                'by_bidang' => [],
                'by_kondisi' => $this->getKondisiStatistics($allAssets)
            ];

            // Group by bidang for summary
            $byBidang = $allAssets->groupBy('bidang_id');
            foreach ($byBidang as $bidangId => $assets) {
                $bidang = $assets->first()->bidang;
                $bidangValue = $this->calculateTotalValue($assets);
                
                $summary['by_bidang'][] = [
                    'bidang_id' => $bidangId,
                    'nama_bidang' => $bidang ? $bidang->nama_bidang : 'Unknown',
                    'total_assets' => $assets->sum('jumlah_aset'),
                    'total_items' => $assets->count(),
                    'total_nilai' => $bidangValue
                ];
            }

            return response()->json($summary);
        } catch (Exception $e) {
            Log::error('Error getting summary: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan'], 500);
        }
    }

    /**
     * Preview data before export (Updated with new fields)
     */
    public function preview(Request $request)
    {
        try {
            $rules = [
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'jenis_laporan' => 'required|in:semua,per_bidang'
            ];

            if ($request->jenis_laporan === 'per_bidang') {
                $rules['bidang_id'] = 'required|exists:bidangs,id';
            }

            $request->validate($rules);

            $start = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : null;
            $end = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : null;

            $asets = $this->getAsets($start, $end, $request->jenis_laporan, $request->bidang_id);
            $bidang = $request->bidang_id ? Bidang::find($request->bidang_id) : null;
            $totalValue = $this->calculateTotalValue($asets);

            return response()->json([
                'success' => true,
                'data' => [
                    'total_items' => $asets->count(),
                    'total_aset' => $asets->sum('jumlah_aset'),
                    'total_nilai_aset' => $totalValue,
                    'bidang' => $bidang ? $bidang->nama_bidang : null,
                    'preview' => $asets->take(10)->map(function($asset) {
                        return [
                            'kode_aset' => $asset->kode_aset,
                            'nama_aset' => $asset->nama_aset,
                            'bidang' => $asset->bidang->nama_bidang ?? 'N/A',
                            'unit' => $asset->unit,
                            'jumlah_aset' => $asset->jumlah_aset,
                            'harga' => $asset->harga ? 'Rp ' . number_format($asset->harga, 0, ',', '.') : '-',
                            'lokasi' => $asset->lokasi,
                            'kondisi' => $asset->kondisi,
                            'tanggal_perolehan' => $asset->tanggal_perolehan ? Carbon::parse($asset->tanggal_perolehan)->format('d/m/Y') : '-',
                            'created_at' => $asset->created_at->format('d/m/Y')
                        ];
                    })
                ]
            ]);
        } catch (Exception $e) {
            Log::error('Error in preview: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export to Excel (Optional - if you want Excel export too)
     */
    public function exportExcel(Request $request)
    {
        try {
            // Similar validation as PDF export
            $rules = [
                'start_date' => 'nullable|date|before_or_equal:today',
                'end_date' => 'nullable|date|after_or_equal:start_date|before_or_equal:today',
                'jenis_laporan' => 'required|in:semua,per_bidang'
            ];

            if ($request->jenis_laporan === 'per_bidang') {
                $rules['bidang_id'] = 'required|exists:bidangs,id';
            }

            $request->validate($rules);

            // Get data
            $start = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : null;
            $end = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : null;
            $asets = $this->getAsets($start, $end, $request->jenis_laporan, $request->bidang_id);
            $bidang = $request->bidang_id ? Bidang::find($request->bidang_id) : null;

            if ($asets->isEmpty()) {
                return back()->withInput()->with('warning', 'Tidak ada data untuk diekspor.');
            }

            // You can implement Excel export using Laravel Excel package
            // For now, return JSON data that can be processed by frontend
            return response()->json([
                'success' => true,
                'data' => $asets->map(function($asset) {
                    return [
                        'Kode Aset' => $asset->kode_aset,
                        'Nama Aset' => $asset->nama_aset,
                        'Bidang' => $asset->bidang->nama_bidang ?? 'N/A',
                        'Unit' => $asset->unit,
                        'Jumlah' => $asset->jumlah_aset,
                        'Harga' => $asset->harga,
                        'Total Nilai' => $asset->harga * $asset->jumlah_aset,
                        'Lokasi' => $asset->lokasi,
                        'Kondisi' => $asset->kondisi,
                        'Tanggal Perolehan' => $asset->tanggal_perolehan,
                        'Tanggal Input' => $asset->created_at->format('Y-m-d')
                    ];
                })
            ]);

        } catch (Exception $e) {
            Log::error('Error exporting to Excel: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
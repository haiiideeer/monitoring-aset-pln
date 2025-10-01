<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Aset - Export PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 0;
            font-size: 12px;
            line-height: 1.4;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 20px;
            color: #333;
            font-weight: bold;
        }
        
        .header h2 {
            margin: 5px 0;
            font-size: 14px;
            color: #666;
            font-weight: normal;
        }
        
        .info-section {
            margin-bottom: 20px;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }
        
        .info-section p {
            margin: 5px 0;
            font-size: 12px;
        }
        
        .filters {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: #fff;
        }
        
        .filters h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #333;
            font-weight: bold;
        }
        
        .filter-item {
            display: inline-block;
            margin-right: 20px;
            margin-bottom: 5px;
            font-size: 11px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            border: 1px solid #333;
        }
        
        table th {
            background-color: #343a40;
            color: white;
            padding: 8px 4px;
            text-align: center;
            font-size: 10px;
            font-weight: bold;
            border: 1px solid #333;
            vertical-align: middle;
        }
        
        table td {
            padding: 6px 4px;
            border: 1px solid #dee2e6;
            font-size: 9px;
            vertical-align: middle;
            word-wrap: break-word;
        }
        
        table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        table tr:hover {
            background-color: #e9ecef;
        }
        
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            color: white;
            font-size: 8px;
            font-weight: bold;
            text-align: center;
            white-space: nowrap;
        }
        
        .badge-primary {
            background-color: #007bff;
        }
        
        .badge-info {
            background-color: #17a2b8;
        }
        
        .badge-success {
            background-color: #28a745;
        }
        
        .badge-warning {
            background-color: #ffc107;
            color: #333;
        }
        
        .badge-danger {
            background-color: #dc3545;
        }
        
        .badge-secondary {
            background-color: #6c757d;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-left {
            text-align: left;
        }
        
        .text-right {
            text-align: right;
        }
        
        .no-data {
            text-align: center;
            padding: 30px;
            color: #666;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        
        /* FOTO STYLING - OPTIMIZED FOR PDF */
        .foto-container {
            text-align: center;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }
        
        .asset-image {
            max-width: 45px;
            max-height: 45px;
            width: 45px;
            height: 45px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #ddd;
            display: block;
        }
        
        .no-image {
            width: 45px;
            height: 45px;
            background-color: #f8f9fa;
            border: 1px dashed #ccc;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            font-size: 7px;
            text-align: center;
            line-height: 1.2;
        }
        
        /* QR Code styling - OPTIMIZED */
        .qr-container {
            text-align: center;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }
        
        .qr-image {
            max-width: 40px;
            max-height: 40px;
            width: 40px;
            height: 40px;
            object-fit: contain;
            border: 1px solid #ddd;
            border-radius: 2px;
            display: block;
        }
        
        .no-qr {
            font-size: 7px;
            color: #999;
            text-align: center;
            width: 40px;
            height: 40px;
            border: 1px dashed #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1.1;
            background-color: #f8f9fa;
        }
        
        .summary-container {
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            font-size: 12px;
        }
        
        .summary-item {
            margin: 5px 0;
            font-weight: bold;
        }
        
        @media print {
            body { 
                margin: 15px;
                font-size: 11px;
            }
            .header { 
                page-break-after: avoid;
            }
            table {
                page-break-inside: avoid;
            }
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
            
            /* Ensure images print properly */
            .qr-image, .asset-image {
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
        
        /* Column widths - optimized for landscape with Unit column */
        .col-no { width: 3%; }
        .col-foto { width: 6%; }
        .col-nama { width: 15%; }
        .col-bidang { width: 8%; }
        .col-unit { width: 8%; }
        .col-jumlah { width: 5%; }
        .col-harga { width: 8%; }
        .col-lokasi { width: 12%; }
        .col-kondisi { width: 8%; }
        .col-qr { width: 6%; }
        .col-tanggal { width: 8%; }
        .col-keterangan { width: 13%; }
        
        /* Responsive text sizing */
        .asset-name {
            font-weight: bold;
            font-size: 9px;
        }
        
        .location-text {
            font-size: 8px;
            line-height: 1.3;
        }
        
        .unit-text {
            font-size: 8px;
            text-align: center;
            line-height: 1.3;
        }
    </style>
</head>
<body>

@php
    /**
     * Helper function to get QR Code as base64
     * Tries multiple paths and handles errors gracefully
     */
    function getQRCodeBase64($asset) {
        // Return null if no QR code is supposed to exist
        if (!$asset->has_qr_code || !$asset->qr_code_path) {
            return null;
        }
        
        // Define possible paths where QR code might exist
        $possiblePaths = [
            storage_path('app/public/' . $asset->qr_code_path),
            public_path('storage/' . $asset->qr_code_path),
            storage_path('app/' . $asset->qr_code_path),
            public_path($asset->qr_code_path)
        ];
        
        foreach ($possiblePaths as $path) {
            if (file_exists($path) && is_readable($path)) {
                try {
                    $imageData = file_get_contents($path);
                    if ($imageData !== false && strlen($imageData) > 0) {
                        // Detect mime type based on file extension
                        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                        $mimeType = match($extension) {
                            'png' => 'image/png',
                            'jpg', 'jpeg' => 'image/jpeg',
                            'gif' => 'image/gif',
                            'svg' => 'image/svg+xml',
                            default => 'image/png'
                        };
                        
                        return 'data:' . $mimeType . ';base64,' . base64_encode($imageData);
                    }
                } catch (Exception $e) {
                    // Continue to next path if this one fails
                    continue;
                }
            }
        }
        
        return null;
    }

    /**
     * Helper function to get Asset Photo as base64
     * FIXED VERSION - Now checks all the right fields and paths
     */
    function getAssetPhotoBase64($asset) {
        // Priority 1: Check if foto_base64 already processed by controller
        if (isset($asset->foto_base64) && !empty($asset->foto_base64)) {
            if (strpos($asset->foto_base64, 'data:') === 0) {
                return $asset->foto_base64;
            } else {
                return 'data:image/jpeg;base64,' . $asset->foto_base64;
            }
        }
        
        // Priority 2: Check if foto field exists (for compatibility)
        if (isset($asset->foto) && !empty($asset->foto)) {
            if (strpos($asset->foto, 'data:') === 0) {
                return $asset->foto;
            }
        }
        
        // Priority 3: Process foto_aset field directly
        $photoFile = null;
        if (isset($asset->foto_aset) && !empty($asset->foto_aset)) {
            $photos = json_decode($asset->foto_aset, true);
            if (is_array($photos) && !empty($photos)) {
                $photoFile = $photos[0]; // Take first photo
            } elseif (is_string($photos)) {
                $photoFile = $photos;
            } elseif (is_string($asset->foto_aset)) {
                // Maybe it's not JSON, just a string path
                $photoFile = $asset->foto_aset;
            }
        }
        
        if (!$photoFile) {
            return null;
        }
        
        // Define possible paths where photo might exist
        $possiblePaths = [
            // Standard Laravel storage paths
            storage_path('app/public/' . $photoFile),
            storage_path('app/public/assets/' . $photoFile),
            storage_path('app/public/photos/' . $photoFile),
            storage_path('app/public/images/' . $photoFile),
            
            // Public paths
            public_path('storage/' . $photoFile),
            public_path('storage/assets/' . $photoFile),
            public_path('storage/photos/' . $photoFile),
            public_path('storage/images/' . $photoFile),
            
            // Direct public paths
            public_path('assets/' . $photoFile),
            public_path('photos/' . $photoFile),
            public_path('images/' . $photoFile),
            public_path($photoFile),
            
            // App storage without public
            storage_path('app/assets/' . $photoFile),
            storage_path('app/photos/' . $photoFile),
            storage_path('app/images/' . $photoFile),
        ];
        
        foreach ($possiblePaths as $path) {
            if (file_exists($path) && is_readable($path)) {
                try {
                    $imageData = file_get_contents($path);
                    if ($imageData !== false && strlen($imageData) > 0) {
                        // Validate it's actually an image
                        $imageInfo = @getimagesizefromstring($imageData);
                        if ($imageInfo !== false) {
                            $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                            $mimeType = match($extension) {
                                'png' => 'image/png',
                                'jpg', 'jpeg' => 'image/jpeg',
                                'gif' => 'image/gif',
                                'webp' => 'image/webp',
                                'bmp' => 'image/bmp',
                                default => $imageInfo['mime'] ?? 'image/jpeg'
                            };
                            
                            return 'data:' . $mimeType . ';base64,' . base64_encode($imageData);
                        }
                    }
                } catch (Exception $e) {
                    continue;
                }
            }
        }
        
        return null;
    }
    
    // Calculate totals
    $totalUnit = 0;
    $totalHarga = 0;
    
    if(isset($assets) && $assets->count() > 0) {
        foreach($assets as $asset) {
            $totalUnit += $asset->jumlah_aset;
            $harga = isset($asset->harga) ? $asset->harga : 0;
            $totalHarga += $harga * $asset->jumlah_aset;
        }
    }
@endphp

    <div class="header">
        <h1>LAPORAN DATA ASET</h1>
        <h2>Sistem Informasi Manajemen Aset</h2>
    </div>

    <div class="info-section">
        <p><strong>Tanggal Export:</strong> {{ $exportDate }}</p>
        <p><strong>Total Data:</strong> {{ $totalAssets }} aset</p>
        <p><strong>Status:</strong> 
            @if($search || $bidangName || $kondisi)
                Data yang difilter
            @else
                Semua data aset
            @endif
        </p>
    </div>

    @if($search || $bidangName || $kondisi)
    <div class="filters">
        <h3>Filter yang Diterapkan:</h3>
        @if($search)
            <div class="filter-item">
                <strong>Pencarian:</strong> "{{ $search }}"
            </div>
        @endif
        @if($bidangName)
            <div class="filter-item">
                <strong>Bidang:</strong> {{ $bidangName }}
            </div>
        @endif
        @if($kondisi)
            <div class="filter-item">
                <strong>Kondisi:</strong> {{ $kondisi }}
            </div>
        @endif
    </div>
    @endif

    @if($assets->count() > 0)
        <table>
            <thead>
                <tr>
                    <th class="col-no">No</th>
                    <th class="col-foto">Foto</th>
                    <th class="col-nama">Nama Aset</th>
                    <th class="col-bidang">Bidang</th>
                    <th class="col-unit">Unit</th>
                    <th class="col-jumlah">Jumlah</th>
                    <th class="col-harga">Harga (Rp)</th>
                    <th class="col-lokasi">Lokasi</th>
                    <th class="col-kondisi">Kondisi</th>
                    <th class="col-qr">QR Code</th>
                    <th class="col-tanggal">Tgl. Perolehan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($assets as $index => $asset)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    
                    {{-- FOTO CELL - COMPLETELY FIXED --}}
                    <td class="text-center">
                        <div class="foto-container">
                            @php
                                $photoBase64 = getAssetPhotoBase64($asset);
                            @endphp
                            
                            @if($photoBase64)
                                <img src="{{ $photoBase64 }}" 
                                     class="asset-image" 
                                     alt="Foto {{ $asset->nama_aset }}"
                                     style="display: block;">
                            @else
                                <div class="no-image">
                                    No<br>Image
                                </div>
                            @endif
                        </div>
                    </td>
                    
                    {{-- NAMA ASET --}}
                    <td>
                        <div class="asset-name">{{ $asset->nama_aset }}</div>
                        @if($asset->keterangan)
                            <div style="font-size: 7px; color: #666; margin-top: 2px;">
                                {{ Str::limit($asset->keterangan, 50) }}
                            </div>
                        @endif
                    </td>
                    
                    {{-- BIDANG --}}
                    <td class="text-center">
                        <span class="badge badge-info">
                            {{ $asset->bidang->nama_bidang ?? 'N/A' }}
                        </span>
                    </td>
                    
                    {{-- UNIT --}}
                    <td class="text-center">
                        <div class="unit-text">{{ $asset->unit ?? '-' }}</div>
                    </td>
                    
                    {{-- JUMLAH --}}
                    <td class="text-center">
                        <span class="badge badge-primary">{{ number_format($asset->jumlah_aset) }}</span>
                    </td>
                    
                    {{-- HARGA --}}
                    <td class="text-right">
                        @if(isset($asset->harga) && $asset->harga > 0)
                            {{ number_format($asset->harga, 0, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>
                    
                    {{-- LOKASI --}}
                    <td>
                        <div class="location-text">{{ $asset->lokasi }}</div>
                    </td>
                    
                    {{-- KONDISI --}}
                    <td class="text-center">
                        @php
                            $kondisiClass = 'secondary';
                            switch(strtolower($asset->kondisi)) {
                                case 'baik':
                                case 'sangat baik':
                                    $kondisiClass = 'success';
                                    break;
                                case 'cukup':
                                case 'sedang':
                                case 'perlu perbaikan':
                                    $kondisiClass = 'warning';
                                    break;
                                case 'rusak':
                                case 'buruk':
                                case 'rusak berat':
                                    $kondisiClass = 'danger';
                                    break;
                            }
                        @endphp
                        <span class="badge badge-{{ $kondisiClass }}">
                            {{ $asset->kondisi }}
                        </span>
                    </td>
                    
                    {{-- QR CODE CELL --}}
                    <td class="text-center">
                        <div class="qr-container">
                            @php
                                $qrBase64 = getQRCodeBase64($asset);
                            @endphp
                            
                            @if($qrBase64)
                                <img src="{{ $qrBase64 }}" 
                                     alt="QR Code {{ $asset->nama_aset }}" 
                                     class="qr-image"
                                     style="display: block; margin: 0 auto;">
                            @else
                                <div class="no-qr">
                                    @if($asset->has_qr_code)
                                        QR<br>Missing
                                    @else
                                        No<br>QR
                                    @endif
                                </div>
                            @endif
                        </div>
                    </td>
                    
                    {{-- TANGGAL PEROLEHAN --}}
                    <td class="text-center">
                        <div class="date-text">
                            {{ \Carbon\Carbon::parse($asset->tanggal_perolehan)->format('d/m/Y') }}
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        {{-- SUMMARY SECTION --}}
        <div class="summary-container">
            <div class="summary-item">Total Unit Aset: {{ number_format($totalUnit) }}</div>
            <div class="summary-item">Total Harga Aset: Rp {{ number_format($totalHarga, 0, ',', '.') }}</div>
        </div>
    @else
        <div class="no-data">
            <h3>Tidak Ada Data</h3>
            <p>Tidak ada aset yang sesuai dengan kriteria yang dipilih.</p>
            @if($search || $bidangName || $kondisi)
                <p><strong>Filter aktif:</strong>
                    @if($search) Pencarian: "{{ $search }}" @endif
                    @if($bidangName) Bidang: {{ $bidangName }} @endif  
                    @if($kondisi) Kondisi: {{ $kondisi }} @endif
                </p>
            @endif
        </div>
    @endif

    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh sistem pada {{ $exportDate }}</p>
        <p>© {{ date('Y') }} Sistem Informasi Manajemen Aset</p>
        @if($assets->count() > 0)
            <p>Halaman 1 dari 1 | Total {{ $assets->count() }} aset ditampilkan</p>
        @endif
    </div>
</body>
</html>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Aset - {{ ucfirst(str_replace('_', ' ', $jenis_laporan)) }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 15px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 15px;
        }

        .company-info {
            margin-bottom: 10px;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 3px;
        }

        .company-address {
            font-size: 10px;
            color: #666;
            margin-bottom: 8px;
        }

        .title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }

        .subtitle {
            font-size: 14px;
            color: #555;
            margin-bottom: 3px;
        }

        .period-info {
            font-size: 11px;
            margin-bottom: 8px;
            color: #666;
            background-color: #f8f9fa;
            padding: 8px;
            border-radius: 4px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 4px 6px;
            vertical-align: middle;
            font-size: 9px;
        }

        th {
            background-color: #f8f9fa;
            text-align: center;
            font-weight: bold;
            color: #495057;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
            display: inline-block;
        }

        .badge-primary {
            background-color: #007bff;
            color: white;
        }

        .badge-secondary {
            background-color: #6c757d;
            color: white;
        }

        .badge-success {
            background-color: #28a745;
            color: white;
        }

        .badge-warning {
            background-color: #ffc107;
            color: #212529;
        }

        .badge-danger {
            background-color: #dc3545;
            color: white;
        }

        .badge-baik {
            background-color: #28a745;
            color: white;
        }

        .badge-rusak {
            background-color: #dc3545;
            color: white;
        }

        .badge-kurang {
            background-color: #ffc107;
            color: #212529;
        }

        .foto-thumbnail {
            width: 50px;
            height: 40px;
            object-fit: cover;
            border-radius: 3px;
            border: 1px solid #ddd;
            display: block;
            margin: 0 auto;
        }

        .summary {
            margin-top: 20px;
            padding: 12px;
            background-color: #f8f9fa;
            border-radius: 4px;
            border: 1px solid #dee2e6;
        }

        .summary-title {
            font-weight: bold;
            margin-bottom: 8px;
            color: #495057;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 4px;
        }

        .summary-table {
            border: none;
            margin: 0;
        }

        .summary-table td {
            border: none;
            padding: 3px 8px;
            font-size: 11px;
        }

        .footer {
            margin-top: 25px;
            padding-top: 12px;
            border-top: 1px solid #dee2e6;
            font-size: 9px;
            color: #6c757d;
        }

        .signature-section {
            margin-top: 30px;
            display: flex;
            justify-content: flex-end;
            align-items: flex-start;
        }

        .signature-box {
            text-align: center;
            width: 200px;
        }

        .signature-title {
            font-size: 11px;
            margin-bottom: 50px;
        }

        .signature-name {
            font-size: 11px;
            font-weight: bold;
            border-top: 1px solid #333;
            padding-top: 5px;
        }

        .no-data {
            text-align: center;
            font-style: italic;
            color: #6c757d;
            padding: 20px;
        }

        .harga-text {
            font-size: 8px;
            font-weight: bold;
            color: #007bff;
        }

        /* Print optimizations */
        @media print {
            body {
                margin: 0;
                padding: 15px;
                font-size: 8px;
            }
            
            .page-break {
                page-break-before: always;
            }

            th, td {
                font-size: 7px;
                padding: 2px 4px;
            }
        }

        @page {
            size: A4 landscape;
            margin: 1cm;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="company-info">
            <div class="company-name">Laporan Pengelolaan Aset dan Inventaris - Data Lengkap</div>
            <div class="company-address">
               
            </div>
        </div>
        
        <div class="subtitle">
            {{ $jenis_laporan === 'per_bidang' ? 'LAPORAN PER BIDANG' : 'LAPORAN SEMUA ASET' }}
        </div>

        <div class="period-info">
            <div class="info-row">
                <span><strong>Periode:</strong></span>
                <span>
                    {{ $start_date ? date('d/m/Y', strtotime($start_date)) : 'Awal' }}
                    s/d {{ $end_date ? date('d/m/Y', strtotime($end_date)) : 'Sekarang' }}
                </span>
            </div>
            <div class="info-row">
                <span><strong>Jenis Laporan:</strong></span>
                <span>{{ ucfirst(str_replace('_', ' ', $jenis_laporan)) }}</span>
            </div>
            @if($bidang)
                <div class="info-row">
                    <span><strong>Bidang:</strong></span>
                    <span>{{ $bidang->nama_bidang }}</span>
                </div>
            @endif
            <div class="info-row">
                <span><strong>Total Data:</strong></span>
                <span>{{ number_format($total_items, 0, ',', '.') }} Item</span>
            </div>
            <div class="info-row">
                <span><strong>Total Nilai Aset:</strong></span>
                <span>Rp {{ number_format($total_nilai_aset, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    @if($asets->count() > 0)
        <table>
            <thead>
                <tr>
                    <th width="3%">No</th>
                    <th width="6%">Kode Aset</th>
                    <th width="4%">Tgl Input</th>
                    <th width="8%">Nama Aset</th>
                    @if($jenis_laporan === 'semua')
                        <th width="6%">Bidang</th>
                    @endif
                    <th width="12%">Unit</th>
                    <th width="4%">Jumlah</th>
                    <th width="6%">Harga</th>
                    <th width="10%">Lokasi</th>
                    <th width="6%">Kondisi</th>
                    <th width="4%">Tgl Perolehan</th>
                    <th width="6%">Foto</th>
                </tr>
            </thead>
            <tbody>
                @php 
                    $no = 1; 
                    $currentBidang = null;
                    $bidangTotal = 0;
                    $bidangNilai = 0;
                @endphp
                @foreach($asets as $item)
                    @if($jenis_laporan === 'semua' && $currentBidang !== $item->bidang_id)
                        @if($currentBidang !== null)
                            <!-- Subtotal for previous bidang -->
                            <tr style="background-color: #e3f2fd; font-weight: bold;">
                                <td colspan="{{ $jenis_laporan === 'semua' ? '6' : '5' }}" class="text-right">
                                    <strong>Subtotal {{ $asets->where('bidang_id', $currentBidang)->first()->bidang->nama_bidang ?? 'Unknown' }}:</strong>
                                </td>
                                <td class="text-center">
                                    <strong>{{ number_format($asets->where('bidang_id', $currentBidang)->sum('jumlah_aset'), 0, ',', '.') }}</strong>
                                </td>
                                <td class="text-right harga-text">
                                    <strong>Rp {{ number_format($asets->where('bidang_id', $currentBidang)->sum(function($item) { return $item->harga * $item->jumlah_aset; }), 0, ',', '.') }}</strong>
                                </td>
                                <td colspan="4"></td>
                            </tr>
                        @endif
                        @php $currentBidang = $item->bidang_id; @endphp
                    @endif

                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td class="text-center">{{ $item->kode_aset ?? '-' }}</td>
                        <td class="text-center">{{ date('d/m/Y', strtotime($item->created_at)) }}</td>
                        <td class="text-center">{{ $item->nama_aset }}</td>
                       @if($jenis_laporan === 'semua')
                            <td style="text-align: center; vertical-align: middle;">
                                {{ $item->bidang->nama_bidang ?? 'N/A' }}
                            </td>
                        @endif
                        <td class="text-center">{{ $item->unit ?? '-' }}</td>
                        <td class="text-center">{{ number_format($item->jumlah_aset, 0, ',', '.') }}</td>
                        <td class="text-right harga-text">
                            @if($item->harga)
                                Rp {{ number_format($item->harga, 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $item->lokasi ?? '-' }}</td>
                        <td class="text-center">
                            @if($item->kondisi)
                                @php
                                    $kondisiLower = strtolower($item->kondisi);
                                    $badgeClass = 'badge-secondary';
                                    if(in_array($kondisiLower, ['baik', 'sangat baik', 'berfungsi'])) {
                                        $badgeClass = 'badge-baik';
                                    } elseif(in_array($kondisiLower, ['rusak', 'rusak berat', 'tidak berfungsi'])) {
                                        $badgeClass = 'badge-rusak';
                                    } elseif(in_array($kondisiLower, ['kurang baik', 'perlu perbaikan', 'rusak ringan'])) {
                                        $badgeClass = 'badge-kurang';
                                    }
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ ucfirst($item->kondisi) }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-center">
                            @if($item->tanggal_perolehan)
                                {{ date('d/m/Y', strtotime($item->tanggal_perolehan)) }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-center">
                            @if(isset($item->foto_base64) && $item->foto_base64)
                                <img src="{{ $item->foto_base64 }}" alt="Foto Aset" class="foto-thumbnail">
                            @elseif(isset($item->foto) && $item->foto)
                                <img src="{{ $item->foto }}" alt="Foto Aset" class="foto-thumbnail">
                            @elseif($item->foto_aset)
                                <span class="badge badge-warning" style="font-size: 7px;">Photo Error</span>
                            @else
                                <span class="badge badge-secondary" style="font-size: 7px;">No Photo</span>
                            @endif
                        </td>
                    </tr>
                @endforeach

                @if($jenis_laporan === 'semua' && $currentBidang !== null)
                    <!-- Last bidang subtotal -->
                    <tr style="background-color: #e3f2fd; font-weight: bold;">
                        <td colspan="6" class="text-right">
                            <strong>Subtotal {{ $asets->where('bidang_id', $currentBidang)->first()->bidang->nama_bidang ?? 'Unknown' }}:</strong>
                        </td>
                        <td class="text-center">
                            <strong>{{ number_format($asets->where('bidang_id', $currentBidang)->sum('jumlah_aset'), 0, ',', '.') }}</strong>
                        </td>
                        <td class="text-right harga-text">
                            <strong>Rp {{ number_format($asets->where('bidang_id', $currentBidang)->sum(function($item) { return $item->harga * $item->jumlah_aset; }), 0, ',', '.') }}</strong>
                        </td>
                        <td colspan="4"></td>
                    </tr>
                @endif
            </tbody>
        </table>
    @else
        <div class="no-data">
            <p><strong>Tidak ada data aset ditemukan untuk kriteria yang dipilih.</strong></p>
            <p>Silakan ubah filter periode atau bidang untuk mencari data lainnya.</p>
        </div>
    @endif

    @if($asets->count() > 0)
        <div class="summary">
            <div class="summary-title">RINGKASAN LAPORAN</div>
            <table class="summary-table">
                <tr>
                    <td width="70%" class="text-right"><strong>Total Keseluruhan Item Aset:</strong></td>
                    <td width="30%" class="text-right"><strong>{{ number_format($total_items, 0, ',', '.') }} Item</strong></td>
                </tr>
                <tr>
                    <td class="text-right"><strong>Total Keseluruhan Jumlah Aset:</strong></td>
                    <td class="text-right"><strong>{{ number_format($total_aset, 0, ',', '.') }} Unit</strong></td>
                </tr>
                <tr>
                    <td class="text-right"><strong>Total Nilai Aset:</strong></td>
                    <td class="text-right"><strong>Rp {{ number_format($total_nilai_aset, 0, ',', '.') }}</strong></td>
                </tr>
                @if($jenis_laporan === 'per_bidang' && $bidang)
                    <tr>
                        <td class="text-right"><strong>Bidang:</strong></td>
                        <td class="text-right"><strong>{{ $bidang->nama_bidang }}</strong></td>
                    </tr>
                @endif
                @if($jenis_laporan === 'semua')
                    <tr>
                        <td class="text-right"><strong>Jumlah Bidang Terlibat:</strong></td>
                        <td class="text-right"><strong>{{ $asets->groupBy('bidang_id')->count() }} Bidang</strong></td>
                    </tr>
                @endif
            </table>
        </div>

        <!-- Summary kondisi aset -->
        <div class="summary" style="margin-top: 15px;">
            <div class="summary-title">RINGKASAN KONDISI ASET</div>
            <table style="width: 100%; border: 1px solid #ddd;">
                <thead>
                    <tr>
                        <th width="15%" style="border: 1px solid #ddd; padding: 5px;">Kondisi</th>
                        <th width="25%" style="border: 1px solid #ddd; padding: 5px;">Jumlah Item</th>
                        <th width="30%" style="border: 1px solid #ddd; padding: 5px;">Total Unit</th>
                        <th width="30%" style="border: 1px solid #ddd; padding: 5px;">Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $kondisiStats = $asets->groupBy('kondisi');
                        $totalUnits = $asets->sum('jumlah_aset');
                    @endphp
                    @foreach($kondisiStats as $kondisi => $items)
                        <tr>
                            <td style="border: 1px solid #ddd; padding: 5px; text-align: center;">
                                @php
                                    $kondisiLower = strtolower($kondisi ?: 'tidak diketahui');
                                    $badgeClass = 'badge-secondary';
                                    if(in_array($kondisiLower, ['baik', 'sangat baik', 'berfungsi'])) {
                                        $badgeClass = 'badge-baik';
                                    } elseif(in_array($kondisiLower, ['rusak', 'rusak berat', 'tidak berfungsi'])) {
                                        $badgeClass = 'badge-rusak';
                                    } elseif(in_array($kondisiLower, ['kurang baik', 'perlu perbaikan', 'rusak ringan'])) {
                                        $badgeClass = 'badge-kurang';
                                    }
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ ucfirst($kondisi ?: 'Tidak Diketahui') }}</span>
                            </td>
                            <td style="border: 1px solid #ddd; padding: 5px; text-align: center;">{{ $items->count() }}</td>
                            <td style="border: 1px solid #ddd; padding: 5px; text-align: center;">{{ number_format($items->sum('jumlah_aset'), 0, ',', '.') }}</td>
                            <td style="border: 1px solid #ddd; padding: 5px; text-align: center;">
                                {{ $totalUnits > 0 ? number_format(($items->sum('jumlah_aset') / $totalUnits) * 100, 1) : 0 }}%
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($jenis_laporan === 'semua' && $asets->count() > 0)
            <!-- Summary per bidang for 'semua' report type -->
            <div class="summary" style="margin-top: 15px;">
                <div class="summary-title">RINCIAN PER BIDANG</div>
                <table style="width: 100%; border: 1px solid #ddd;">
                    <thead>
                        <tr>
                            <th width="5%" style="border: 1px solid #ddd; padding: 5px;">No</th>
                            <th width="45%" style="border: 1px solid #ddd; padding: 5px;">Nama Bidang</th>
                            <th width="15%" style="border: 1px solid #ddd; padding: 5px;">Jumlah Item</th>
                            <th width="15%" style="border: 1px solid #ddd; padding: 5px;">Total Unit</th>
                            <th width="20%" style="border: 1px solid #ddd; padding: 5px;">Total Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $bidangNo = 1; @endphp
                        @foreach($asets->groupBy('bidang_id') as $bidangId => $bidangAssets)
                            <tr>
                                <td style="border: 1px solid #ddd; padding: 5px; text-align: center;">{{ $bidangNo++ }}</td>
                                <td style="border: 1px solid #ddd; padding: 5px;">{{ $bidangAssets->first()->bidang->nama_bidang ?? 'N/A' }}</td>
                                <td style="border: 1px solid #ddd; padding: 5px; text-align: center;">{{ $bidangAssets->count() }}</td>
                                <td style="border: 1px solid #ddd; padding: 5px; text-align: center;">{{ number_format($bidangAssets->sum('jumlah_aset'), 0, ',', '.') }}</td>
                                <td style="border: 1px solid #ddd; padding: 5px; text-align: right;">
                                    Rp {{ number_format($bidangAssets->sum(function($item) { return $item->harga * $item->jumlah_aset; }), 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @endif

    <div class="footer">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <strong>Dicetak pada:</strong> {{ $tanggal_cetak }}
            </div>
            <div>
                <strong>Halaman:</strong> 1 dari 1
            </div>
        </div>

        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-title">
                    {{ date('d F Y', strtotime($tanggal_cetak)) }}<br>
                    Penanggung Jawab
                </div>
                <div class="signature-name">
                    (_________________________)
                </div>
            </div>
        </div>
    </div>
</body>

</html>
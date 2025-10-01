@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="fas fa-qrcode me-2"></i>Data Aset Bidang: {{ $bidang->nama_bidang }}</h4>
            <div>
                <a href="{{ route('scan') }}" class="btn btn-light btn-sm me-2">
                    <i class="fas fa-camera me-1"></i> Scan Lagi
                </a>
                <a href="{{ route('bidang.index') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h5 class="card-title">Informasi Bidang</h5>
                            <table class="table table-sm">
                                <tr>
                                    <th width="120">Nama Bidang</th>
                                    <td>{{ $bidang->nama_bidang }}</td>
                                </tr>
                                <tr>
                                    <th>Kode Bidang</th>
                                    <td>{{ $bidang->kode_bidang }}</td>
                                </tr>
                                <tr>
                                    <th>Jumlah Aset</th>
                                    <td><span class="badge bg-primary">{{ $assets->count() }}</span></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
<div class="col-md-6">
    <div class="card bg-light">
        <div class="card-body">
            <h5 class="card-title text-center">QR Code Bidang</h5>
            @if($bidang->has_qr_code && $bidang->qr_code_path)
                <div class="d-flex justify-content-center">
                    <img src="{{ asset($bidang->qr_code_path) }}" 
                         alt="QR Code {{ $bidang->nama_bidang }}" 
                         class="img-fluid mb-2"
                         style="max-width: 150px;">
                </div>
                <p class="small text-muted text-center">
                    Scan QR code ini untuk mengakses halaman ini
                </p>
            @else
                <div class="bg-light rounded d-flex align-items-center justify-content-center mx-auto" 
                     style="width: 150px; height: 150px;">
                    <span class="text-muted">No QR Code</span>
                </div>
            @endif
        </div>
    </div>
</div>

            </div>
            
            @if($assets->count() > 0)
                <h5 class="border-bottom pb-2 mb-3">Daftar Aset</h5>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th>Nama Aset</th>
                                <!-- <th>Kode Aset</th> -->
                                <th>Jumlah</th>
                                <th>Lokasi</th>
                                <th>Kondisi</th>
                                <th>Tanggal Perolehan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assets as $asset)
                                <tr>
                                    <td>{{ $asset->nama_aset }}</td>
                                    <!-- <td><strong class="text-primary">{{ $asset->kode_aset }}</strong></td> -->
                                    <td>{{ $asset->jumlah_aset }}</td>
                                    <td>{{ $asset->lokasi }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($asset->kondisi === 'baik') bg-success
                                            @elseif($asset->kondisi === 'rusak') bg-danger
                                            @elseif($asset->kondisi === 'perbaikan') bg-warning
                                            @else bg-secondary
                                            @endif">
                                            {{ $asset->kondisi }}
                                        </span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($asset->tanggal_perolehan)->format('d/m/Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info text-center py-4">
                    <i class="fas fa-info-circle fa-3x mb-3 text-info"></i>
                    <h5>Tidak ada aset untuk bidang {{ $bidang->nama_bidang }}</h5>
                    <p class="mb-0">Silakan tambahkan aset melalui menu Kelola Aset</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
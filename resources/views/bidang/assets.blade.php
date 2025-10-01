@extends('layouts.app')

@section('title', 'Aset ' . e($bidang->nama_bidang))

@section('content')
<div class="container mt-4">
    {{-- Header Bidang --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-1 text-primary h3">
                        <i class="fas fa-building me-2" aria-hidden="true"></i>{{ e($bidang->nama_bidang) }}
                    </h1>
                    <p class="text-muted mb-0">
                        <strong>Kode Bidang:</strong> {{ e($bidang->kode_bidang) }}
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="d-flex justify-content-end gap-2 flex-wrap">
                        <a href="{{ route('bidang.index') }}" class="btn btn-outline-secondary mb-2 mb-md-0">
                            <i class="fas fa-arrow-left me-1" aria-hidden="true"></i> Kembali
                        </a>
                        <button type="button" class="btn btn-info" onclick="window.print()" aria-label="Cetak halaman">
                            <i class="fas fa-print me-1" aria-hidden="true"></i> Print
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistik Cards - Improved responsiveness --}}
    <div class="row mb-4 g-3">
        <div class="col-6 col-md-2">
            <div class="card bg-primary text-white h-100">
                <div class="card-body text-center py-3">
                    <i class="fas fa-box fa-2x mb-2" aria-hidden="true"></i>
                    <h4>{{ number_format($stats['total']) }}</h4>
                    <p class="mb-0 small">Item Aset</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="card bg-info text-white h-100">
                <div class="card-body text-center py-3">
                    <i class="fas fa-cubes fa-2x mb-2" aria-hidden="true"></i>
                    <h4>{{ number_format($stats['total_jumlah']) }}</h4>
                    <p class="mb-0 small">Total Unit</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card bg-success text-white h-100">
                <div class="card-body text-center py-3">
                    <i class="fas fa-check-circle fa-2x mb-2" aria-hidden="true"></i>
                    <h4>{{ number_format($stats['baik']) }}</h4>
                    <p class="mb-0 small">Kondisi Baik</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card bg-warning text-white h-100">
                <div class="card-body text-center py-3">
                    <i class="fas fa-exclamation-triangle fa-2x mb-2" aria-hidden="true"></i>
                    <h4>{{ number_format($stats['rusak_ringan']) }}</h4>
                    <p class="mb-0 small">Rusak Ringan</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="card bg-danger text-white h-100">
                <div class="card-body text-center py-3">
                    <i class="fas fa-times-circle fa-2x mb-2" aria-hidden="true"></i>
                    <h4>{{ number_format($stats['rusak_berat']) }}</h4>
                    <p class="mb-0 small">Rusak Berat</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter & Search --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ request()->url() }}" class="row g-3">
                <div class="col-md-4">
                    <label for="searchInput" class="form-label">Cari Aset</label>
                    <input type="text" id="searchInput" name="search" class="form-control" 
                           placeholder="Nama aset, kode, atau lokasi..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label for="kondisiSelect" class="form-label">Kondisi</label>
                    <select id="kondisiSelect" name="kondisi" class="form-select">
                        <option value="">Semua Kondisi</option>
                        <option value="baik" {{ request('kondisi') == 'baik' ? 'selected' : '' }}>Baik</option>
                        <option value="rusak" {{ request('kondisi') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                        <option value="perlu perbaikan" {{ request('kondisi') == 'perlu perbaikan' ? 'selected' : '' }}>Perlu Perbaikan</option>
                        <option value="hilang" {{ request('kondisi') == 'hilang' ? 'selected' : '' }}>Hilang</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="lokasiSelect" class="form-label">Lokasi</label>
                    <select id="lokasiSelect" name="lokasi" class="form-select">
                        <option value="">Semua Lokasi</option>
                        @foreach($lokasis as $lokasi)
                            <option value="{{ e($lokasi) }}" {{ request('lokasi') == $lokasi ? 'selected' : '' }}>
                                {{ e($lokasi) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-1" aria-hidden="true"></i> Filter
                    </button>
                </div>
                @if(request()->hasAny(['search', 'kondisi', 'lokasi']))
                <div class="col-12">
                    <a href="{{ request()->url() }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-times me-1"></i> Hapus Filter
                    </a>
                    <small class="text-muted ms-2">
                        Menampilkan {{ $assets->total() }} hasil filter
                    </small>
                </div>
                @endif
            </form>
        </div>
    </div>

    {{-- Summary info --}}
    @if($assets->total() > 0)
    <div class="alert alert-info d-flex justify-content-between align-items-center">
        <span>
            Menampilkan <strong>{{ $assets->count() }}</strong> dari <strong>{{ number_format($assets->total()) }}</strong> aset
        </span>
    </div>
    @endif

    {{-- Mobile Card View --}}
    <div class="d-block d-lg-none">
        @forelse($assets as $asset)
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <div class="row">
                        {{-- Foto --}}
                        <div class="col-4">
                            @if($asset->foto_aset)
                                <img src="{{ asset('storage/' . $asset->foto_aset) }}" 
                                     alt="Foto {{ e($asset->nama_aset) }}" 
                                     class="img-fluid rounded"
                                     style="height: 80px; object-fit: cover; cursor: pointer;"
                                     data-bs-toggle="modal" 
                                     data-bs-target="#assetModal{{ $asset->id }}"
                                     aria-label="Lihat foto {{ e($asset->nama_aset) }}">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                     style="height: 80px;"
                                     aria-hidden="true">
                                    <i class="fas fa-image text-muted fa-2x"></i>
                                </div>
                            @endif
                        </div>
                        
                        {{-- Info --}}
                        <div class="col-8">
                            <h6 class="card-title mb-1">{{ e($asset->nama_aset) }}</h6>
                            <p class="text-primary mb-1"><small><strong>{{ e($asset->kode_aset) }}</strong></small></p>
                            
                            <div class="d-flex flex-wrap gap-1 mb-2">
                                {{-- Kondisi Badge --}}
                                @if($asset->kondisi == 'baik')
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1" aria-hidden="true"></i>Baik
                                    </span>
                                @elseif($asset->kondisi == 'rusak')
                                    <span class="badge bg-warning">
                                        <i class="fas fa-exclamation-triangle me-1" aria-hidden="true"></i>Rusak
                                    </span>
                                @elseif($asset->kondisi == 'perlu perbaikan')
                                    <span class="badge bg-danger">
                                        <i class="fas fa-times-circle me-1" aria-hidden="true"></i>Perlu Perbaikan
                                    </span>
                                      <span class="badge bg-warning">
                                            <i class="fas fa-exclamation-triangle me-1" aria-hidden="true"></i>Hilang
                                        </span>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-question-circle me-1" aria-hidden="true"></i>Tidak Diketahui
                                    </span>
                                @endif
                                
                                {{-- Jumlah Badge --}}
                                <span class="badge bg-info">{{ $asset->jumlah_aset }} unit</span>
                            </div>
                            
                            {{-- Detail Info --}}
                            <div class="small text-muted">
                                <div><i class="fas fa-map-marker-alt me-1" aria-hidden="true"></i> 
                                    {{ $asset->lokasi ? e($asset->lokasi) : 'Tidak diketahui' }}
                                </div>
                                @if($asset->tanggal_perolehan)
                                    <div><i class="fas fa-calendar-alt me-1" aria-hidden="true"></i> 
                                        {{ \Carbon\Carbon::parse($asset->tanggal_perolehan)->format('d/m/Y') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent py-2">
                    <button class="btn btn-sm btn-outline-primary w-100" 
                            data-bs-toggle="modal" 
                            data-bs-target="#assetModal{{ $asset->id }}">
                        <i class="fas fa-eye me-1"></i> Detail
                    </button>
                </div>
            </div>
        @empty
            <div class="card">
                <div class="card-body text-center text-muted py-5">
                    <i class="fas fa-inbox fa-3x mb-3 d-block text-muted" aria-hidden="true"></i>
                    <p>Belum ada aset yang terdaftar di bidang ini.</p>
                    @if(request()->hasAny(['search', 'kondisi', 'lokasi']))
                        <a href="{{ request()->url() }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-times me-1"></i> Hapus Filter
                        </a>
                    @endif
                </div>
            </div>
        @endforelse
        
        {{-- Pagination untuk Mobile --}}
        @if($assets->hasPages())
            <div class="mt-3">
                {{ $assets->onEachSide(1)->links() }}
            </div>
        @endif
    </div>

    {{-- Desktop Table View --}}
    <div class="card shadow-sm d-none d-lg-block">
        <div class="card-header bg-transparent">
            <h5 class="mb-0">
                <i class="fas fa-list me-2" aria-hidden="true"></i>Daftar Aset - {{ e($bidang->nama_bidang) }}
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>Foto</th>
                            <th>Nama Aset</th>
                            <th>Jumlah</th>
                            <th>Lokasi</th>
                            <th>Kondisi</th>
                            <th>Tanggal Perolehan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assets as $index => $asset)
                            <tr>
                                <td class="align-middle">{{ $assets->firstItem() + $index }}</td>
                                <td class="align-middle">
                                    @if($asset->foto_aset)
                                        <img src="{{ asset('storage/' . $asset->foto_aset) }}" 
                                             alt="Foto {{ e($asset->nama_aset) }}" 
                                             width="60" 
                                             height="45"
                                             class="rounded object-fit-cover"
                                             style="cursor: pointer;"
                                             data-bs-toggle="modal" 
                                             data-bs-target="#assetModal{{ $asset->id }}"
                                             aria-label="Lihat foto {{ e($asset->nama_aset) }}">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                             style="width: 60px; height: 45px;"
                                             aria-hidden="true">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    <div>
                                        <strong>{{ e($asset->nama_aset) }}</strong>
                                        <div class="small text-muted">{{ e($asset->kode_aset) }}</div>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <span class="badge bg-info">{{ $asset->jumlah_aset }} unit</span>
                                </td>
                                <td class="align-middle">
                                    <i class="fas fa-map-marker-alt text-muted me-1" aria-hidden="true"></i>
                                    {{ $asset->lokasi ? e($asset->lokasi) : 'Tidak diketahui' }}
                                </td>
                                <td class="align-middle">
                                    @if($asset->kondisi == 'baik')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle me-1" aria-hidden="true"></i>Baik
                                        </span>
                                    @elseif($asset->kondisi == 'rusak')
                                        <span class="badge bg-warning">
                                            <i class="fas fa-exclamation-triangle me-1" aria-hidden="true"></i>Rusak
                                        </span>
                                    @elseif($asset->kondisi == 'perlu perbaikan')
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times-circle me-1" aria-hidden="true"></i>Perlu Prbaikan
                                             @elseif($asset->kondisi == 'hilang')
                                        <span class="badge bg-warning">
                                            <i class="fas fa-exclamation-triangle me-1" aria-hidden="true"></i>Hilang
                                        </span>
                                        
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-question-circle me-1" aria-hidden="true"></i>Tidak Diketahui
                                        </span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    @if($asset->tanggal_perolehan)
                                        <i class="fas fa-calendar-alt text-muted me-1" aria-hidden="true"></i>
                                        {{ \Carbon\Carbon::parse($asset->tanggal_perolehan)->format('d/m/Y') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    <button class="btn btn-sm btn-outline-primary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#assetModal{{ $asset->id }}"
                                            aria-label="Detail {{ e($asset->nama_aset) }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-3x mb-3 d-block text-muted" aria-hidden="true"></i>
                                    Belum ada aset yang terdaftar di bidang ini.
                                    @if(request()->hasAny(['search', 'kondisi', 'lokasi']))
                                        <br>
                                        <a href="{{ request()->url() }}" class="btn btn-sm btn-outline-primary mt-2">
                                            <i class="fas fa-times me-1"></i> Hapus Filter
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($assets->hasPages())
            <div class="card-footer bg-transparent">
                {{ $assets->onEachSide(1)->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Modal untuk Asset Detail (digunakan oleh kedua view) --}}
@foreach($assets as $asset)
<div class="modal fade" id="assetModal{{ $asset->id }}" tabindex="-1" aria-labelledby="assetModalLabel{{ $asset->id }}">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assetModalLabel{{ $asset->id }}">{{ e($asset->nama_aset) }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    @if($asset->foto_aset)
                    <div class="col-md-6 text-center mb-3 mb-md-0">
                        <img src="{{ asset('storage/' . $asset->foto_aset) }}" 
                             alt="Foto {{ e($asset->nama_aset) }}" 
                             class="img-fluid rounded">
                    </div>
                    @endif
                    <div class="@if($asset->foto_aset) col-md-6 @else col-12 @endif">
                        <div class="row g-3">
                            <div class="col-6">
                                <strong>Kode Aset:</strong><br>
                                {{ e($asset->kode_aset) }}
                            </div>
                            <div class="col-6">
                                <strong>Jumlah:</strong><br>
                                {{ $asset->jumlah_aset }} unit
                            </div>
                            <div class="col-6">
                                <strong>Kondisi:</strong><br>
                                @if($asset->kondisi == 'baik')
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1" aria-hidden="true"></i>Baik
                                    </span>
                                @elseif($asset->kondisi == 'rusak')
                                    <span class="badge bg-warning">
                                        <i class="fas fa-exclamation-triangle me-1" aria-hidden="true"></i>Rusak 
                                    </span>
                                @elseif($asset->kondisi == 'prlu perbaikan')
                                    <span class="badge bg-danger">
                                        <i class="fas fa-times-circle me-1" aria-hidden="true"></i>Perlu Perbaikan
                                    </span>
                                      <span class="badge bg-warning">
                                            <i class="fas fa-exclamation-triangle me-1" aria-hidden="true"></i>Hilang
                                        </span>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-question-circle me-1" aria-hidden="true"></i>Tidak Diketahui
                                    </span>
                                @endif
                            </div>
                            <div class="col-6">
                                <strong>Tanggal Perolehan:</strong><br>
                                {{ $asset->tanggal_perolehan ? \Carbon\Carbon::parse($asset->tanggal_perolehan)->format('d/m/Y') : '-' }}
                            </div>
                            <div class="col-12">
                                <strong>Lokasi:</strong><br>
                                {{ $asset->lokasi ? e($asset->lokasi) : 'Tidak diketahui' }}
                            </div>
                            <div class="col-12">
                                <strong>Bidang:</strong><br>
                                {{ e($bidang->nama_bidang) }} ({{ e($bidang->kode_bidang) }})
                            </div>
                            @if($asset->keterangan)
                            <div class="col-12">
                                <strong>Keterangan:</strong><br>
                                {{ e($asset->keterangan) }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                @if(Route::has('assets.edit'))
                <a href="{{ route('assets.edit', $asset->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-1"></i> Edit
                </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endforeach

@push('styles')
<style>
.object-fit-cover {
    object-fit: cover;
}

@media print {
    .btn, .card-header, .pagination, .modal, .alert, 
    [aria-label~="Print"], [onclick*="print"] { 
        display: none !important; 
    }
    .card { 
        border: none !important; 
        box-shadow: none !important; 
    }
    .table { 
        font-size: 12px; 
    }
    .container {
        width: 100%;
        max-width: 100%;
    }
}

@media (max-width: 768px) {
    .stats-card .card-body {
        padding: 1rem 0.5rem;
    }
    
    .stats-card h4 {
        font-size: 1.25rem;
    }
    
    .stats-card .fa-2x {
        font-size: 1.5em;
    }
}

/* Hover effect untuk foto */
img[data-bs-toggle="modal"] {
    transition: transform 0.2s;
}

img[data-bs-toggle="modal"]:hover {
    transform: scale(1.05);
    cursor: pointer;
}

/* Badge styling */
.badge {
    font-size: 0.75rem;
}
</style>
@endpush

@endsection
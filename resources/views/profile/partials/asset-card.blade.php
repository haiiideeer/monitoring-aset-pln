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
                    @include('partials.condition-badge', ['condition' => $asset->kondisi])
                    <span class="badge bg-info">{{ $asset->jumlah_aset }} unit</span>
                </div>
                
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
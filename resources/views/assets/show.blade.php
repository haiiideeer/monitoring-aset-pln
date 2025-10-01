@extends('layouts.app')

@section('title', 'Detail Aset')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <!-- Header -->
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-eye me-2"></i>Detail Aset: {{ $asset->nama_aset }}
                    </h5>
                    <div class="d-flex gap-2">
                      <!-- Tombol Hapus di Header -->
                    <button type="button" 
                            class="btn btn-danger btn-sm"
                            data-bs-toggle="modal" 
                            data-bs-target="#deleteModal{{ $asset->id }}">
                        <i class="fas fa-trash me-1"></i>Hapus
                    </button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row g-4">
                        <!-- Kolom Kiri: Foto -->
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h6 class="text-muted mb-3">
                                        <i class="fas fa-image me-1"></i>Foto Aset
                                    </h6>
                                    <div class="row g-3">
                                        @php
                                            $fotos = json_decode($asset->foto_aset, true); 
                                        @endphp
                                        @if(is_array($fotos))
                                            @foreach($fotos as $foto)
                                                <div class="col-12">
                                                    <img src="{{ asset('storage/' . $foto) }}" 
                                                         alt="Foto {{ $asset->nama_aset }}" 
                                                         class="img-fluid rounded shadow-sm"
                                                         style="max-height: 250px; width: 100%; object-fit: cover;">
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="col-12">
                                                <img src="{{ asset('storage/' . $fotos) }}" 
                                                     alt="Foto {{ $asset->nama_aset }}" 
                                                     class="img-fluid rounded shadow-sm"
                                                     style="max-height: 250px; width: 100%; object-fit: cover;">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Ringkasan -->
                            <div class="card border-0 shadow-sm mt-3">
                                <div class="card-body text-center">
                                    <h6 class="text-muted mb-3">
                                        <i class="fas fa-chart-bar me-1"></i>Ringkasan
                                    </h6>
                                    <div class="row">
                                        <div class="col-6 border-end">
                                            <h4 class="text-primary mb-0">{{ number_format($asset->jumlah_aset) }}</h4>
                                            <small class="text-muted">Jumlah</small>
                                        </div>
                                        <div class="col-6">
                                            <span class="badge bg-{{ $asset->kondisi_status['class'] }} fs-6">
                                                {{ $asset->kondisi_status['text'] }}
                                            </span>
                                            <br><small class="text-muted">Kondisi</small>
                                        </div>
                                    </div>
                                    @if(isset($asset->harga) && $asset->harga > 0)
                                        <hr>
                                        <h5 class="text-success mb-1">
                                            Rp {{ number_format($asset->harga, 0, ',', '.') }}
                                        </h5>
                                        <small class="text-muted">Harga per Unit</small>
                                        <div class="mt-2">
                                            <h5 class="text-primary mb-1">
                                                Rp {{ number_format($asset->harga * $asset->jumlah_aset, 0, ',', '.') }}
                                            </h5>
                                            <small class="text-muted">Total Nilai</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Kanan: Detail -->
                        <div class="col-md-8">
                            <div class="card border-0 shadow-sm mb-3">
                                <div class="card-body">
                                    <h6 class="text-muted mb-3">
                                        <i class="fas fa-info-circle me-1"></i>Informasi Dasar
                                    </h6>
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td class="text-muted" style="width: 40%;">ID Aset:</td>
                                            <td><span class="badge bg-secondary">#{{ $asset->id }}</span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Nama Aset:</td>
                                            <td><strong>{{ $asset->nama_aset }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Bidang:</td>
                                            <td><span class="badge bg-info">{{ $asset->bidang->nama_bidang ?? 'N/A' }}</span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Lokasi:</td>
                                            <td><i class="fas fa-map-marker-alt text-danger me-1"></i>{{ $asset->lokasi }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="card border-0 shadow-sm mb-3">
                                <div class="card-body">
                                    <h6 class="text-muted mb-3">
                                        <i class="fas fa-calendar me-1"></i>Informasi Waktu
                                    </h6>
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td class="text-muted" style="width: 40%;">Tanggal Perolehan:</td>
                                            <td>
                                                @if($asset->tanggal_perolehan)
                                                    <i class="fas fa-calendar-check text-success me-1"></i>
                                                    {{ $asset->tanggal_perolehan_formatted }}
                                                @else
                                                    <span class="text-muted">Tidak ada data</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Dibuat:</td>
                                            <td>{{ $asset->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Diperbarui:</td>
                                            <td>{{ $asset->updated_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <!-- Informasi Tambahan -->
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h6 class="text-muted mb-3">
                                        <i class="fas fa-cogs me-1"></i>Informasi Tambahan
                                    </h6>
                                    <p>{{ $asset->bidang->keterangan ?? 'Tidak ada keterangan tambahan.' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="{{ route('assets.index') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                        <div class="btn-group">
                           <a class="dropdown-item text-primary" href="#" data-bs-toggle="modal" data-bs-target="#editAssetModal{{ $asset->id }}">
                                <i class="fas fa-edit me-2"></i>Edit
                            </a>
                                        <button type="button" 
                                    class="btn btn-info dropdown-toggle dropdown-toggle-split" 
                                    data-bs-toggle="dropdown">
                                <span class="visually-hidden">Toggle</span>
                            </button>
                            <ul class="dropdown-menu">
                               <li>
                                    <a class="dropdown-item" href="#" onclick="printAsset('{{ route('assets.show', $asset->id) }}')">
                                        <i class="fas fa-print me-2"></i>Print
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" onclick="copyAssetUrl('{{ route('assets.show', $asset->id) }}')">
                                        <i class="fas fa-link me-2"></i>Copy URL
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Fungsi untuk cetak halaman detail aset
    function printAsset(url) {
        let printWindow = window.open(url, "_blank", "width=800,height=600");
        printWindow.onload = function () {
            printWindow.print();
        };
    }

    // Fungsi untuk copy URL aset ke clipboard
    function copyAssetUrl(url) {
        navigator.clipboard.writeText(url).then(() => {
            // Pakai alert sederhana (bisa diganti toast/sweetalert biar keren)
            alert("URL berhasil disalin:\n" + url);
        }).catch(err => {
            console.error("Gagal menyalin URL: ", err);
        });
    }
</script>

@include('assets.partials.edit-modal')

@include('assets.partials.delete-modal')

@endsection

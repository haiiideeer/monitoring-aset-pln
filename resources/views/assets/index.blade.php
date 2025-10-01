@extends('layouts.app')

@section('title', 'Data Aset')

@push('styles')
<style>
    :root {
        --primary-color: #0d6efd;
        --secondary-color: #6c757d;
        --info-color: #0dcaf0;
        --success-color: #198754;
        --warning-color: #ffc107;
        --danger-color: #dc3545;
        --light-bg: #f8f9fa;
        --dark-text: #212529;
        --muted-text: #6c757d;
    }

    body {
        background-color: var(--light-bg);
    }

    .card {
        border: none;
        border-radius: 1rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08) !important;
    }

    /* Header */
    .header-card .card-body {
        background: linear-gradient(to right, #e0f2ff, #cce5ff);
        border-radius: 1rem;
        padding: 2.5rem;
    }

    .header-card h2 {
        font-size: clamp(1.5rem, 3vw, 2.25rem);
        font-weight: 700;
        color: var(--primary-color);
    }

    .header-card .btn {
        font-weight: 600;
    }

    /* Foto aset kecil */
    .photo-gallery .main-photo {
        width: 70px;
        height: 70px;
        aspect-ratio: 1/1;
        object-fit: cover;
        border-radius: 0.75rem;
        border: 2px solid rgba(0, 0, 0, 0.1);
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .photo-gallery .main-photo:hover {
        transform: scale(1.1);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
    }

    /* Badge kecil */
    .custom-badge {
        font-size: 0.8rem;
        padding: 0.4em 0.8em;
        border-radius: 0.5rem;
        font-weight: 600;
    }
    
    .bg-info-custom {
        background-color: #5bc0de !important;
    }

    /* Tombol aksi */
    .action-buttons {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .action-buttons .btn {
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.2s ease;
    }
    
    .action-buttons .btn:hover {
        transform: scale(1.1);
    }

    /* Modal image */
    .modal-image {
        max-height: 80vh;
        width: auto;
        display: block;
        margin: auto;
        object-fit: contain;
    }
    .carousel-item img {
        max-height: 80vh;
        width: auto;
        display: block;
        margin: auto;
        object-fit: contain;
    }
    
    /* Table */
    .modern-table {
        border-collapse: separate;
        border-spacing: 0 10px;
        width: 100%;
    }
    
    .modern-table thead th {
        background-color: var(--primary-color);
        color: #fff;
        padding: 1rem 0.75rem;
        border-bottom: none !important;
    }
    
    .modern-table thead th:first-child {
        border-top-left-radius: 0.75rem;
    }
    
    .modern-table thead th:last-child {
        border-top-right-radius: 0.75rem;
    }

    .modern-table tbody tr {
        background-color: #fff;
        border-radius: 0.75rem;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        transition: all 0.2s ease;
    }
    
    .modern-table tbody tr:hover {
        background-color: #e9ecef;
        transform: scale(1.01);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .modern-table td, .modern-table th {
        vertical-align: middle;
        padding: 1.25rem 0.75rem;
        border-top: none;
    }

    /* Responsiveness */
    @media (max-width: 991.98px) {
        .modern-table {
            display: block;
            overflow-x: auto;
            white-space: nowrap;
        }
    }

    /* Fix for modal backdrop */
    .modal-backdrop {
        z-index: 1040;
    }
    
    .modal {
        z-index: 1050;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-5">
    <div class="row">
        <div class="col-12">
            
            <div class="card border-0 shadow-lg mb-4 header-card">
                <div class="card-body">
                    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-4">
                        <div>
                            <h2 class="mb-2 text-nowrap">
                             <i class="fas fa-boxes me-2"></i> Data Aset </h2>
                        </div>
                        <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center gap-4">
                            <a href="{{ route('assets.export.pdf', request()->query()) }}" class="btn btn-outline-danger">
                                <i class="fas fa-file-pdf me-2"></i> Export PDF
                            </a>
                           <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#createAssetModal">
                                <i class="fas fa-plus me-2"></i> Tambah Aset Baru
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-lg">
                <div class="card-body p-0">

                    {{-- Alert SweetAlert2 --}}
                    @if(session('success'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: '{{ session('success') }}',
                                showConfirmButton: false,
                                timer: 3000
                            });
                        });
                    </script>
                    @endif

                    @if(session('error'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: '{{ session('error') }}',
                                showConfirmButton: true,
                                confirmButtonText: 'Tutup'
                            });
                        });
                    </script>
                    @endif
                    
                    @if(session('warning'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Peringatan!',
                                text: '{{ session('warning') }}',
                                showConfirmButton: true,
                                confirmButtonText: 'Oke'
                            });
                        });
                    </script>
                    @endif

                    <div class="table-responsive">
                        <table class="table modern-table mb-0">
                            <thead class="text-center">
                                <tr>
                                    <th>No</th>
                                    <th>Foto</th>
                                    <th>Info Aset</th>
                                    <th>Bidang</th>
                                    <th>Unit</th>
                                    <th>Jumlah</th>
                                    <th>Nilai</th>
                                    <th>Lokasi</th>
                                    <th>Kondisi</th>
                                    <th>Tanggal</th>
                                    <th>QR Code</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($assets ?? [] as $asset)
                                    @php
                                        if (!$asset || !is_object($asset)) continue;
                                        $assetId = $asset->id ?? '';
                                        $assetName = $asset->nama_aset ?? 'N/A';
                                        $assetCode = $asset->kode_aset ?? '';
                                        $assetUnit = $asset->unit ?? '-';
                                        $assetQuantity = (int)($asset->jumlah_aset ?? 0);
                                        $assetPrice = (float)($asset->harga ?? 0);
                                        $assetLocation = $asset->lokasi ?? '-';
                                        $assetCondition = $asset->kondisi ?? '-';
                                        $assetDate = $asset->tanggal_perolehan ?? null;
                                        $bidangName = $asset->bidang->nama_bidang ?? '-';

                                        $images = [];
                                        if (isset($asset->foto_aset)) {
                                            $decoded = json_decode($asset->foto_aset, true);
                                            $images = is_array($decoded) ? $decoded : [$asset->foto_aset];
                                        }

                                        $formattedDate = $assetDate ? \Carbon\Carbon::parse($assetDate)->format('d M Y') : '-';
                                        $relativeDate = $assetDate ? \Carbon\Carbon::parse($assetDate)->diffForHumans() : '-';
                                        $displayNumber = isset($assets) && method_exists($assets, 'currentPage')
                                            ? ($assets->currentPage() - 1) * $assets->perPage() + $loop->iteration
                                            : $loop->iteration;
                                    @endphp
                                    <tr>
                                        <td class="text-center fw-bold">{{ $displayNumber }}</td>
                                        <td class="text-center">
                                            @if(count($images) > 0)
                                                <div class="photo-gallery">
                                                    @php
                                                        $imageUrl = str_starts_with($images[0], 'http') ? $images[0] : asset('storage/' . $images[0]);
                                                    @endphp
                                                    <img src="{{ $imageUrl }}"
                                                         class="main-photo" 
                                                         data-bs-toggle="modal" 
                                                         data-bs-target="#imageModal{{ $assetId }}" 
                                                         alt="Foto Aset"
                                                         onerror="this.src='{{ asset('images/no-image.png') }}'; this.onerror=null;">
                                                </div>
                                            @else
                                                <i class="fas fa-image fa-2x text-muted"></i>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $assetName }}</div>
                                            @if($assetCode)
                                                <small class="text-muted"><i class="fas fa-barcode me-1"></i>{{ $assetCode }}</small>
                                            @endif
                                        </td>
                                        <td class="text-center"><span class="badge custom-badge bg-primary">{{ $bidangName }}</span></td>
                                        <td class="text-center"><span class="badge custom-badge bg-secondary">{{ $assetUnit }}</span></td>
                                        <td class="fw-bold text-center">{{ number_format($assetQuantity) }}</td>
                                        <td>
                                            <div class="fw-bold">Rp {{ number_format($assetPrice, 0, ',', '.') }}</div>
                                            @if($assetQuantity > 1)
                                                <small class="text-muted">Total: Rp {{ number_format($assetPrice * $assetQuantity, 0, ',', '.') }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $assetLocation }}</td>
                                        <td class="text-center"><span class="badge custom-badge bg-info-custom">{{ $assetCondition }}</span></td>
                                        <td class="text-center">
                                            <div>{{ $formattedDate }}</div>
                                            <small class="text-muted">{{ $relativeDate }}</small>
                                        </td>
                                        <td class="text-center">
                                            {!! QrCode::size(70)->generate(route('assets.show', $assetId)) !!}
                                        </td>
                                        <td class="text-center">
                                            <div class="action-buttons">
                                                <a href="{{ route('assets.show', $assetId) }}" class="btn btn-info btn-sm text-white" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                               <button type="button" class="btn btn-warning btn-sm text-white" 
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#editAssetModal{{ $asset->id }}" 
                                                            title="Edit">
                                                        <i class="fas fa-edit"></i>
                                               </button>
                                                <button type="button" class="btn btn-danger btn-sm" 
                                                             data-bs-toggle="modal" 
                                                             data-bs-target="#deleteModal{{ $assetId }}" 
                                                             title="Hapus">
                                                         <i class="fas fa-trash"></i>
                                                 </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-center p-5 bg-white">
                                            <i class="fas fa-folder-open fa-4x text-muted mb-4"></i>
                                            <h4 class="fw-bold text-muted">Belum ada data aset.</h4>
                                            <p class="text-muted mb-4">Mulai kelola aset Anda dengan menambahkan data pertama.</p>
                                            <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#createAssetModal">
                                                  <i class="fas fa-plus me-2"></i> Tambah Aset Pertama
                                            </button>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if(isset($assets) && method_exists($assets, 'links'))
                        <div class="d-flex justify-content-center p-4">
                            {{ $assets->links() }}
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Modal Create Asset --}}
<div class="modal fade" id="createAssetModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-body p-0">
                {{-- Import isi form dari create.blade.php --}}
                @include('assets.partials.create')
            </div>
        </div>
    </div>
</div>

{{-- Modal Success --}}
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-check-circle me-2"></i> Sukses!
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <p class="text-muted" id="successMessage"></p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">
                    <i class="fas fa-thumbs-up me-1"></i> Selesai
                </button>
            </div>
        </div>
    </div>
</div>


{{-- Modal Edit Asset per baris --}}
@if(isset($assets) && $assets->count() > 0)
    @foreach($assets as $asset)
        @if($asset && is_object($asset))
            {{-- Modal Edit --}}
            @include('assets.partials.edit-modal', ['asset' => $asset])
            
            {{-- Modal Image Gallery --}}
            @if($asset->foto_aset)
                @php
                    $images = json_decode($asset->foto_aset, true) ?: [$asset->foto_aset];
                @endphp
                <div class="modal fade" id="imageModal{{ $asset->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Foto {{ $asset->nama_aset }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body text-center">
                                @if(count($images) > 1)
                                    <div id="imageCarousel{{ $asset->id }}" class="carousel slide" data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            @foreach($images as $index => $image)
                                                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                                    @php
                                                        $imageUrl = str_starts_with($image, 'http') ? $image : asset('storage/' . $image);
                                                    @endphp
                                                    <img src="{{ $imageUrl }}" class="modal-image" alt="Foto Aset">
                                                </div>
                                            @endforeach
                                        </div>
                                        <button class="carousel-control-prev" type="button" data-bs-target="#imageCarousel{{ $asset->id }}" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon"></span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#imageCarousel{{ $asset->id }}" data-bs-slide="next">
                                            <span class="carousel-control-next-icon"></span>
                                        </button>
                                    </div>
                                @else
                                    @php
                                        $imageUrl = str_starts_with($images[0], 'http') ? $images[0] : asset('storage/' . $images[0]);
                                    @endphp
                                    <img src="{{ $imageUrl }}" class="modal-image" alt="Foto Aset">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Modal Delete --}}
            <div class="modal fade" id="deleteModal{{ $asset->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Hapus
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                           <p>Apakah Anda yakin ingin menghapus aset <strong>&ldquo;{{ $asset->nama_aset }}&rdquo;</strong>?</p>

                            <p class="text-muted small">Data yang dihapus tidak dapat dikembalikan.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <form action="{{ route('assets.destroy', $asset->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash me-1"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endif

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
document.addEventListener("DOMContentLoaded", function () {
    // --- Preview Foto Aset ---
    const fotoInput = document.getElementById("foto_aset");
    if (fotoInput) {
        fotoInput.addEventListener("change", function (event) {
            const previewContainer = document.getElementById("previewContainer");
            if (previewContainer) {
                previewContainer.innerHTML = ""; // clear dulu
                Array.from(event.target.files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const img = document.createElement("img");
                        img.src = e.target.result;
                        img.classList.add("img-thumbnail", "m-2");
                        img.style.maxWidth = "150px";
                        previewContainer.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                });
            }
        });
    }

    // --- Delete Button dengan Swal ---
    const deleteButtons = document.querySelectorAll(".btn-delete-confirm");
    deleteButtons.forEach(btn => {
        btn.addEventListener("click", function (e) {
            e.preventDefault();
            const form = this.closest("form");
            Swal.fire({
                title: "Apakah anda yakin?",
                text: "Data akan dihapus permanen!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, hapus!"
            }).then((result) => {
                if (result.isConfirmed && form) {
                    form.submit();
                }
            });
        });
    });

    // --- Sidebar Toggle ---
    const sidebarToggle = document.getElementById("sidebarToggle");
    const sidebar = document.getElementById("sidebar");
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener("click", function () {
            sidebar.classList.toggle("active");
        });
    }
});
</script>
@endpush
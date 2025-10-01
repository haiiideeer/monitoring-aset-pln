@extends('layouts.app')

@section('content')
<div class="container mt-4">
    {{-- Header --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body d-flex justify-content-between align-items-center">
            <h4 class="mb-0 text-primary"><i class="fas fa-sitemap me-2"></i>Daftar Bidang</h4>
            <div>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahBidangModal">
                    + Tambah Bidang
                </button>
            </div>
        </div>
    </div>

    {{-- Success Notification --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- Responsive Table for Bidang Data --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 responsive-table" id="bidangTable">
                    <thead class="table-primary">
                        <tr>
                            <th>Nama Bidang</th>
                            <th>Kode Bidang</th>
                            <th class="text-center">QR Code</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bidangs as $bidang)
                        <tr>
                            <td data-label="Nama Bidang">{{ $bidang->nama_bidang }}</td>
                            <td data-label="Kode Bidang"><strong class="text-primary">{{ $bidang->kode_bidang }}</strong></td>
                            <td data-label="QR Code" class="text-center">
                                @if($bidang->has_qr_code && $bidang->qr_code_path)
                                <img src="{{ asset($bidang->qr_code_path) }}"
                                     alt="QR Code {{ $bidang->nama_bidang }}"
                                     width="80"
                                     style="cursor: pointer;"
                                     data-bs-toggle="modal"
                                     data-bs-target="#qrModal{{ $bidang->id }}">
                                @else
                                <span class="text-muted">No QR</span>
                                @endif
                            </td>
                            <td data-label="Aksi" class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    {{-- Lihat Aset --}}
                                    <a href="{{ route('bidang.assets', $bidang->kode_bidang) }}"
                                       class="btn btn-info btn-sm"
                                       title="Lihat Aset">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    {{-- Edit --}}
                                    <button type="button" class="btn btn-warning btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editBidangModal{{ $bidang->id }}"
                                            title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    {{-- Hapus --}}
                                    <form action="{{ route('bidang.destroy', $bidang->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus bidang ini?')"
                                                title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        {{-- Modal untuk menampilkan QR Code Besar --}}
                        @if($bidang->has_qr_code && $bidang->qr_code_path)
                        <div class="modal fade" id="qrModal{{ $bidang->id }}" tabindex="-1">
                            <div class="modal-dialog modal-sm modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h6 class="modal-title">QR Code - {{ $bidang->nama_bidang }}</h6>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <img src="{{ asset($bidang->qr_code_path) }}"
                                             alt="QR Code {{ $bidang->nama_bidang }}"
                                             class="img-fluid mb-2">
                                        <p class="small text-muted mb-2">Kode: {{ $bidang->kode_bidang }}</p>
                                        <a href="{{ asset($bidang->qr_code_path) }}"
                                           download="QR-{{ $bidang->kode_bidang }}.svg"
                                           class="btn btn-primary btn-sm">
                                            <i class="fas fa-download me-1"></i> Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @include('bidang.partials.edit-modal')
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-5">
                                <i class="fas fa-sitemap fa-3x mb-3 d-block text-muted"></i>
                                Tidak ada data bidang.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Memanggil partials --}}
@include('bidang.partials.create_modal')
@endsection

@push('styles')
<style>
/* Responsive Table CSS */
@media (max-width: 991.98px) {
    .table.responsive-table {
        border: 0;
    }
    .table.responsive-table thead {
        display: none; /* Sembunyikan header tabel */
    }
    .table.responsive-table tr {
        display: block; /* Buat baris menjadi blok */
        margin-bottom: 0.625rem;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        background-color: #fff;
    }
    .table.responsive-table td {
        display: block; /* Buat sel menjadi blok */
        text-align: right;
        padding-left: 50%; /* Beri ruang untuk label */
        position: relative;
        border: none;
        word-wrap: break-word; /* Mengatasi kata-kata yang terlalu panjang */
    }
    .table.responsive-table td::before {
        content: attr(data-label); /* Tampilkan label dari atribut data */
        position: absolute;
        left: 0;
        width: 45%;
        padding-left: 0.75rem;
        white-space: nowrap;
        text-align: left;
        font-weight: bold;
        color: #495057;
    }
    .table.responsive-table td:last-child {
        text-align: left; /* Atur kembali perataan teks untuk kolom aksi */
    }
    .table.responsive-table td .d-flex {
        justify-content: flex-start !important;
    }
}

/* Penyesuaian UI Lainnya */
.card-header, .modal-header {
    border-bottom: none;
}
.card-body.p-0 table {
    border-top: none;
}
/* Hover effect untuk QR Code */
img[data-bs-toggle="modal"] {
    transition: transform 0.2s;
}
img[data-bs-toggle="modal"]:hover {
    transform: scale(1.1);
}
</style>
@endpush

@push('scripts')
<script>
    // Script untuk menangani modal edit yang otomatis muncul saat ada error
    document.addEventListener('DOMContentLoaded', function() {
        const modalId = `{{ old('_modal') === 'edit' ? '#editBidangModal' . old('_id') : '' }}`;
        if (modalId) {
            const myModal = new bootstrap.Modal(document.querySelector(modalId));
            myModal.show();
        }
    });
</script>
@endpush
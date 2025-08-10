@extends('layouts.app')


@section('content')
<div class="container mt-4">
    {{-- Card untuk Judul --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body d-flex justify-content-between align-items-center">
            <h4 class="mb-0 text-primary"><i class="fas fa-sitemap mr-3 text-lg"></i>Daftar Bidang</h4>
            <a href="{{ route('bidang.create') }}" class="btn btn-primary">
                + Tambah Bidang
            </a>
        </div>
    </div>

    {{-- Notifikasi sukses --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    {{-- Card Tabel --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th>Nama Bidang</th>
                            <th>Kode Bidang</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bidangs as $bidang)
                            <tr>
                                <td>{{ $bidang->nama_bidang }}</td>
                                <td>{{ $bidang->kode_bidang }}</td>
                              
                               
                                <td class="text-center">
                                    <a href="{{ route('bidang.edit', $bidang->id) }}" class="btn btn-sm btn-warning me-1">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('bidang.destroy', $bidang->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button onclick="return confirm('Apakah Anda yakin ingin menghapus bidang ini?')" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash-alt"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Tidak ada data bidang.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
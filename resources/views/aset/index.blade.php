@extends('layouts.app')

@section('content')
<div class="container mt-4">
    {{-- Card untuk Judul --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body d-flex justify-content-between align-items-center">
            <h4 class="mb-0 text-primary"><i class="fas fa-boxes me-2"></i>Daftar Aset</h4>
            <a href="{{ route('aset.create') }}" class="btn btn-primary">
                + Tambah Aset
            </a>
        </div>
    </div>

    

    {{-- Notifikasi sukses --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Card Tabel --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th>Nama Aset</th>
                            <th>Jumlah Aset</th>
                            <th>Bidang</th>
                            <th>Lokasi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($asets as $aset)
                            <tr>
                                <td>{{ $aset->nama_aset }}</td>
                                <td>{{ $aset->jumlah_aset }}</td>
                                <td>{{ $aset->bidang->nama_bidang }}</td>
                                <td>{{ $aset->lokasi }}</td>
                                <td class="text-center">
                                    <a href="{{ route('aset.edit', $aset->id) }}" class="btn btn-sm btn-warning me-1">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('aset.destroy', $aset->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button onclick="return confirm('Yakin hapus aset ini?')" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash-alt"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Belum ada data aset.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

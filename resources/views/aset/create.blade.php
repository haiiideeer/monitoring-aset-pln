@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Tambah Aset</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('aset.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nama Aset</label>
                    <input type="text" name="nama_aset" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jumlah Aset</label>
                    <input type="number" name="jumlah_aset" class="form-control" required min="1">
                </div>

                <div class="mb-3">
                    <label class="form-label">Bidang</label>
                    <select name="bidang_id" class="form-select" required>
                        <option value="">-- Pilih Bidang --</option>
                        @foreach($bidangs as $bidang)
                            <option value="{{ $bidang->id }}">{{ $bidang->nama_bidang }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Lokasi</label>
                    <input type="text" name="lokasi" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tanggal Perolehan</label>
                    <input type="date" name="tanggal_perolehan" class="form-control" required>
                    <small class="text-muted">Format: Hari/Bulan/Tahun</small>
                </div>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('aset.index') }}" class="btn btn-secondary me-2">Kembali</a>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
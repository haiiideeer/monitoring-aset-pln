@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow rounded-4">
        <div class="card-header bg-primary text-white rounded-top-4">
            <h4 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Tambah Data Bidang</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('bidang.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nama Bidang</label>
                    <input type="text" name="nama_bidang" class="form-control rounded-3" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kode Bidang</label>
                    <input type="text" name="kode_bidang" class="form-control rounded-3" required>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="{{ route('bidang.index') }}" class="btn btn-secondary rounded-3">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-success rounded-3">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

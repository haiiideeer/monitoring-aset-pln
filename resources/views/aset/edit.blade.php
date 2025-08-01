@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-warning text-white">
            <h4 class="mb-0">Edit Aset</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('aset.update', $aset->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label>Nama Aset</label>
                    <input type="text" name="nama_aset" value="{{ $aset->nama_aset }}" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Jumlah Aset</label>
                    <input type="text" name="jumlah_aset" value="{{ $aset->jumlah_aset }}" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Bidang</label>
                    <select name="bidang_id" class="form-control" required>
                        @foreach($bidangs as $bidang)
                            <option value="{{ $bidang->id }}" {{ $bidang->id == $aset->bidang_id ? 'selected' : '' }}>
                                {{ $bidang->nama_bidang }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label>Lokasi</label>
                    <input type="text" name="lokasi" value="{{ $aset->lokasi }}" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Keterangan</label>
                    <textarea name="keterangan" class="form-control">{{ $aset->keterangan }}</textarea>
                </div>
                <button class="btn btn-primary">Update</button>
                <a href="{{ route('aset.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>
@endsection

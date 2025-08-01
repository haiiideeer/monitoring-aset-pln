@extends('layouts.app')

@section('title', 'Detail Aset')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Detail Aset</h2>

    <div class="card">
        <div class="card-header bg-primary text-white">
            Informasi Aset
        </div>
        <div class="card-body">
            <p><strong>Nama Aset:</strong> {{ $aset->nama }}</p>
            <p><strong>Jumlah Aset:</strong> {{ $aset->jumlah }}</p>
            <p><strong>Bidang:</strong> {{ $aset->bidang->nama ?? '-' }}</p>
            <p><strong>Lokasi:</strong> {{ $aset->lokasi }}</p>
            <p><strong>Tanggal Masuk:</strong> {{ \Carbon\Carbon::parse($aset->tanggal_masuk)->translatedFormat('d F Y') }}</p>
            <p><strong>Kondisi:</strong> {{ $aset->kondisi }}</p>
            <p><strong>Keterangan:</strong> {{ $aset->keterangan ?? '-' }}</p>
        </div>
    </div>

    <a href="{{ route('aset.index') }}" class="btn btn-secondary mt-3">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>
@endsection

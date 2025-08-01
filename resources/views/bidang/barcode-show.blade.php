@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Daftar Aset - {{ $bidang->nama }}</h3>
    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Aset</th>
                <th>Jumlah</th>
                <th>Lokasi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($asets as $index => $aset)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $aset->nama_aset }}</td>
                <td>{{ $aset->jumlah_aset }}</td>
                <td>{{ $aset->lokasi }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('bidang.export.excel', $bidang->slug) }}" class="btn btn-success mt-3">Export Excel</a>
    <a href="{{ route('bidang.export.pdf', $bidang->slug) }}" class="btn btn-danger mt-3">Export PDF</a>
</div>
@endsection

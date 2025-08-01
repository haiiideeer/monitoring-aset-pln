@extends('layouts.app')

@section('content')
<div class="container text-center mt-5">
    <h3 class="mb-4">QR Code Bidang: {{ $bidang->nama }}</h3>

    <div class="d-flex justify-content-center">
        <div class="p-4 border rounded bg-white shadow">
            <img src="https://api.qrserver.com/v1/create-qr-code/?data={{ urlencode($url) }}&size=200x200" alt="QR Code">
            <p class="mt-3"><strong>Scan untuk melihat aset milik bidang ini.</strong></p>
            <p class="text-muted">{{ $url }}</p>
        </div>
    </div>
</div>
@endsection

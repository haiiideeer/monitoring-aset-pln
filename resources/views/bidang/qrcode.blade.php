@extends('layouts.app')

@section('content')
<div class="container">
    <h3>QR Code untuk Bidang: {{ $bidang->nama }}</h3>
    <div class="mt-4">
        {!! $qrCode !!}
    </div>
    <p class="mt-3">Scan QR ini untuk melihat aset milik bidang ini.</p>
</div>
@endsection

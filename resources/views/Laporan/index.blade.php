@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i> Laporan Aset</h5>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('laporan.exportPdf') }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        
                        <div class="mb-4">
                            <label for="jenis_laporan" class="form-label fw-bold">Jenis Laporan</label>
                            <select name="jenis_laporan" id="jenis_laporan" class="form-select form-select-lg" required>
                                <option value="semua">Semua Aset</option>
                                <option value="per_bidang">Per Bidang</option>
                            </select>
                            <div class="invalid-feedback">
                                Silakan pilih jenis laporan
                            </div>
                        </div>

                        <div class="mb-4" id="bidang-group" style="display: none;">
                            <label for="bidang_id" class="form-label fw-bold">Pilih Bidang</label>
                            <select name="bidang_id" id="bidang_id" class="form-select form-select-lg">
                                <option value="">-- Pilih Bidang --</option>
                                @foreach($bidangs as $bidang)
                                    <option value="{{ $bidang->id }}">{{ $bidang->nama_bidang }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row mb-4 g-3">
                            <div class="col-md-6">
                                <label for="start_date" class="form-label fw-bold">Tanggal Mulai</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                    <input type="date" name="start_date" id="start_date" 
                                           class="form-control form-control-lg" 
                                           value="{{ $startDate ?? now()->startOfMonth()->format('Y-m-d') }}" required>
                                    <div class="invalid-feedback">
                                        Silakan isi tanggal mulai
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="end_date" class="form-label fw-bold">Tanggal Akhir</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                    <input type="date" name="end_date" id="end_date" 
                                           class="form-control form-control-lg" 
                                           value="{{ $endDate ?? now()->format('Y-m-d') }}" required>
                                    <div class="invalid-feedback">
                                        Silakan isi tanggal akhir
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <button type="submit" class="btn btn-danger btn-lg px-4">
                                <i class="fas fa-file-pdf me-2"></i> Export PDF
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        border-radius: 10px;
        overflow: hidden;
    }
    
    .card-header {
        border-bottom: none;
    }
    
    .form-select-lg, .form-control-lg {
        padding: 0.75rem 1rem;
        font-size: 1.05rem;
    }
    
    .input-group-text {
        background-color: #f8f9fa;
    }
    
    .btn-lg {
        padding: 0.75rem 1.5rem;
        font-size: 1.1rem;
    }
    
    .needs-validation .form-control:invalid, 
    .needs-validation .form-select:invalid {
        border-color: #dc3545;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Form validation
        (function() {
            'use strict'
            
            var forms = document.querySelectorAll('.needs-validation')
            
            Array.prototype.slice.call(forms)
                .forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        
                        form.classList.add('was-validated')
                    }, false)
                })
        })()
        
        // Toggle bidang selection
        $('#jenis_laporan').change(function() {
            if ($(this).val() === 'per_bidang') {
                $('#bidang-group').slideDown();
                $('#bidang_id').prop('required', true);
            } else {
                $('#bidang-group').slideUp();
                $('#bidang_id').prop('required', false).val('');
            }
        });
    });
</script>
@endpush
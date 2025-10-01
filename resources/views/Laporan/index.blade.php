@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>{{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif



            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i> Laporan Aset</h5>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('laporan.exportPdf') }}" method="POST" class="needs-validation" novalidate id="reportForm">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="jenis_laporan" class="form-label fw-bold">Jenis Laporan <span class="text-danger">*</span></label>
                            <select name="jenis_laporan" id="jenis_laporan" class="form-select form-select-lg @error('jenis_laporan') is-invalid @enderror" required>
                                <option value="">-- Pilih Jenis Laporan --</option>
                                <option value="semua" {{ old('jenis_laporan') == 'semua' ? 'selected' : '' }}>Semua Aset</option>
                                <option value="per_bidang" {{ old('jenis_laporan') == 'per_bidang' ? 'selected' : '' }}>Per Bidang</option>
                            </select>
                            @error('jenis_laporan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <div class="invalid-feedback">Silakan pilih jenis laporan</div>
                            @enderror
                        </div>

                        <!-- Bidang Selection - Always visible in HTML, controlled by CSS/JS -->
                        <div class="mb-4" id="bidang-group" style="display: none;">
                            <label for="bidang_id" class="form-label fw-bold">
                                Pilih Bidang <span class="text-danger" id="bidang-required">*</span>
                            </label>
                            <select name="bidang_id" id="bidang_id" class="form-select form-select-lg @error('bidang_id') is-invalid @enderror">
                                <option value="">-- Pilih Bidang --</option>
                                @if(isset($bidangs) && count($bidangs) > 0)
                                    @foreach($bidangs as $bidang)
                                        <option value="{{ $bidang->id }}" {{ old('bidang_id') == $bidang->id ? 'selected' : '' }}>
                                            {{ $bidang->nama_bidang }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="" disabled>Tidak ada data bidang tersedia</option>
                                @endif
                            </select>
                            @error('bidang_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <div class="invalid-feedback">Silakan pilih bidang</div>
                            @enderror
                            
                            <!-- Status info untuk debugging -->
                            <small class="text-muted" id="bidang-debug">
                                Status: Hidden | Available options: {{ isset($bidangs) ? $bidangs->count() : 0 }}
                            </small>
                        </div>

                        <div class="row mb-4 g-3">
                            <div class="col-md-6">
                                <label for="start_date" class="form-label fw-bold">Tanggal Mulai</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                    <input type="date" name="start_date" id="start_date" 
                                           class="form-control form-control-lg @error('start_date') is-invalid @enderror" 
                                           value="{{ old('start_date', $startDate ?? '') }}"
                                           max="{{ date('Y-m-d') }}">
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-muted">Kosongkan untuk mulai dari awal</small>
                            </div>
                            <div class="col-md-6">
                                <label for="end_date" class="form-label fw-bold">Tanggal Akhir</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                    <input type="date" name="end_date" id="end_date" 
                                           class="form-control form-control-lg @error('end_date') is-invalid @enderror" 
                                           value="{{ old('end_date', $endDate ?? '') }}"
                                           max="{{ date('Y-m-d') }}">
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-muted">Kosongkan untuk sampai sekarang</small>
                            </div>
                        </div>

                        <div class="d-flex gap-2 justify-content-end">
                            <!-- <button type="button" class="btn btn-info btn-lg px-4" id="previewBtn">
                                <i class="fas fa-eye me-2"></i> Preview
                            </button> -->
                            <button type="submit" class="btn btn-danger btn-lg px-4" id="exportBtn">
                                <i class="fas fa-file-pdf me-2"></i> Export PDF
                            </button>
                        </div>
                    </form>

                    <!-- Preview Section -->
                    <div id="preview-section" style="display: none;" class="mt-4">
                        <hr>
                        <h6 class="fw-bold mb-3">Preview Data:</h6>
                        <div id="preview-content">
                            <!-- Preview content will be loaded here -->
                        </div>
                    </div>
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

    .preview-table {
        font-size: 0.9rem;
    }

    .preview-table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }

    /* Improved animation for bidang group */
    #bidang-group {
        transition: all 0.4s ease-in-out;
        overflow: hidden;
    }

    #bidang-group.show {
        display: block !important;
        opacity: 1;
        max-height: 200px;
    }

    #bidang-group.hide {
        display: none !important;
        opacity: 0;
        max-height: 0;
    }
</style>
@endpush

@push('scripts')
<!-- Load jQuery if not already loaded -->
<script>
    if (typeof $ === 'undefined') {
        var script = document.createElement('script');
        script.src = 'https://code.jquery.com/jquery-3.7.1.min.js';
        script.integrity = 'sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=';
        script.crossOrigin = 'anonymous';
        document.head.appendChild(script);
        
        // Wait for jQuery to load
        script.onload = function() {
            console.log('jQuery loaded dynamically');
            document.getElementById('jquery-status').textContent = '✓ Loaded (Dynamic)';
            // Reinitialize jQuery-dependent features
            if (typeof initializeJQueryFeatures === 'function') {
                initializeJQueryFeatures();
            }
        };
    }
</script>
<script>
    // Use vanilla JS first to ensure it works, then enhance with jQuery
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM Content Loaded - Starting initialization...');
        
        const jenisLaporanSelect = document.getElementById('jenis_laporan');
        const bidangGroup = document.getElementById('bidang-group');
        const bidangSelect = document.getElementById('bidang_id');
        const bidangRequired = document.getElementById('bidang-required');
        
        // Debugging function
        function updateDebugInfo() {
            // Debug function removed - no longer needed
        }
        
        // Function to show bidang group
        function showBidangGroup() {
            console.log('Showing bidang group...');
            bidangGroup.style.display = 'block';
            bidangGroup.classList.remove('hide');
            bidangGroup.classList.add('show');
            bidangSelect.setAttribute('required', 'required');
            bidangRequired.style.display = 'inline';
        }
        
        // Function to hide bidang group
        function hideBidangGroup() {
            console.log('Hiding bidang group...');
            bidangGroup.style.display = 'none';
            bidangGroup.classList.remove('show');
            bidangGroup.classList.add('hide');
            bidangSelect.removeAttribute('required');
            bidangSelect.value = '';
            bidangRequired.style.display = 'none';
        }
        
        // Main change handler
        function handleJenisLaporanChange() {
            const selectedValue = jenisLaporanSelect.value;
            console.log('Jenis laporan changed to:', selectedValue);
            
            if (selectedValue === 'per_bidang') {
                showBidangGroup();
            } else {
                hideBidangGroup();
                // Hide preview when switching away from per_bidang
                const previewSection = document.getElementById('preview-section');
                if (previewSection) {
                    previewSection.style.display = 'none';
                }
            }
            
            // Clear validation state
            bidangSelect.classList.remove('is-valid', 'is-invalid');
            const form = document.querySelector('.needs-validation');
            if (form) {
                form.classList.remove('was-validated');
            }
        }
        
        // Add event listener
        jenisLaporanSelect.addEventListener('change', handleJenisLaporanChange);
        
        // Handle initial state (for old values)
        if (jenisLaporanSelect.value === 'per_bidang') {
            showBidangGroup();
        } else {
            hideBidangGroup();
        }
        
        console.log('Vanilla JS initialization completed');
        console.log('Available bidang options:', bidangSelect.options.length - 1);
        console.log('Current jenis_laporan value:', jenisLaporanSelect.value);
        
        // Enhanced jQuery functionality (if available)
        function initializeJQueryFeatures() {
            if (typeof $ !== 'undefined') {
                $(document).ready(function() {
                    console.log('jQuery ready - Adding enhanced functionality');
                    
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

                    // Date validation
                    $('#start_date').on('change', function() {
                        const startDate = $(this).val();
                        if (startDate) {
                            $('#end_date').attr('min', startDate);
                        } else {
                            $('#end_date').removeAttr('min');
                        }
                    });

                    $('#end_date').on('change', function() {
                        const endDate = $(this).val();
                        if (endDate) {
                            $('#start_date').attr('max', endDate);
                        } else {
                            $('#start_date').attr('max', '{{ date("Y-m-d") }}');
                        }
                    });

                    // Preview functionality
                    $('#previewBtn').on('click', function() {
                        const form = $('#reportForm')[0];
                        
                        if (!form.checkValidity()) {
                            form.classList.add('was-validated');
                            return;
                        }

                        const formData = {
                            jenis_laporan: $('#jenis_laporan').val(),
                            bidang_id: $('#bidang_id').val(),
                            start_date: $('#start_date').val(),
                            end_date: $('#end_date').val(),
                            _token: $('input[name="_token"]').val()
                        };

                        $('#preview-section').show();
                        $('#preview-content').html(`
                            <div class="text-center">
                                <div class="spinner-border spinner-border-sm" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <span class="ms-2">Memuat preview...</span>
                            </div>
                        `);

                        $.ajax({
                            url: '{{ route("laporan.preview") }}',
                            method: 'POST',
                            data: formData,
                            success: function(response) {
                                if (response.success) {
                                    let html = `
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <strong>Total Items:</strong> ${response.data.total_items.toLocaleString()}
                                            </div>
                                            <div class="col-md-4">
                                                <strong>Total Aset:</strong> ${response.data.total_aset.toLocaleString()}
                                            </div>
                                            ${response.data.bidang ? `<div class="col-md-4"><strong>Bidang:</strong> ${response.data.bidang}</div>` : ''}
                                        </div>
                                    `;

                                    if (response.data.preview.length > 0) {
                                        html += `
                                            <div class="table-responsive">
                                                <table class="table table-sm preview-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Nama Aset</th>
                                                            <th>Bidang</th>
                                                            <th>Lokasi</th>
                                                            <th>Jumlah</th>
                                                            <th>Tanggal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                        `;
                                        
                                        response.data.preview.forEach(function(item) {
                                            html += `
                                                <tr>
                                                    <td>${item.nama_aset}</td>
                                                    <td>${item.bidang}</td>
                                                    <td>${item.lokasi}</td>
                                                    <td class="text-end">${item.jumlah_aset.toLocaleString()}</td>
                                                    <td class="text-center">${item.created_at}</td>
                                                </tr>
                                            `;
                                        });
                                        
                                        html += `
                                                    </tbody>
                                                </table>
                                            </div>
                                            <small class="text-muted">* Menampilkan 10 data teratas</small>
                                        `;
                                    } else {
                                        html += '<p class="text-muted">Tidak ada data yang ditemukan.</p>';
                                    }

                                    $('#preview-content').html(html);
                                } else {
                                    $('#preview-content').html(`
                                        <div class="alert alert-warning">
                                            ${response.message}
                                        </div>
                                    `);
                                }
                            },
                            error: function(xhr) {
                                let message = 'Terjadi kesalahan saat memuat preview.';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    message = xhr.responseJSON.message;
                                }
                                
                                $('#preview-content').html(`
                                    <div class="alert alert-danger">
                                        ${message}
                                    </div>
                                `);
                            }
                        });
                    });

                    // Auto-hide alerts after 5 seconds
                    setTimeout(function() {
                        $('.alert').fadeOut();
                    }, 5000);
                });
            } else {
                console.warn('jQuery not available - some features may be limited');
                // Provide fallback functionality without jQuery
                
                // Add basic preview functionality without jQuery
                const previewBtn = document.getElementById('previewBtn');
                if (previewBtn) {
                    previewBtn.addEventListener('click', function() {
                        alert('Preview membutuhkan jQuery. Silakan gunakan tombol Export PDF langsung.');
                    });
                }
            }
        }
        
        // Try to initialize jQuery features
        initializeJQueryFeatures();
        
        // If jQuery was loaded dynamically, this function will be called again
    });
</script>
@endpush
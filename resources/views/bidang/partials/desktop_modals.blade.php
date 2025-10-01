{{-- Modal untuk menampilkan QR Code Besar (Desktop) --}}
@if($bidang->has_qr_code && $bidang->qr_code_path)
    <div class="modal fade" id="qrModalDesktop{{ $bidang->id }}" tabindex="-1">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">QR Code - {{ $bidang->nama_bidang }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="{{ asset($bidang->qr_code_path) }}" 
                            alt="QR Code {{ $bidang->nama_bidang }}" 
                            class="img-fluid">
                    <p class="mt-2 text-muted">Kode: {{ $bidang->kode_bidang }}</p>
                    <a href="{{ asset($bidang->qr_code_path) }}" 
                       download="QR-{{ $bidang->kode_bidang }}.svg" 
                       class="btn btn-primary btn-sm mt-2">
                        <i class="fas fa-download me-1"></i> Download
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif

{{-- Modal Edit (Desktop) --}}
<div class="modal fade" id="editBidangModalDesktop{{ $bidang->id }}" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i> Edit Bidang</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('bidang.update', $bidang->id) }}" method="POST">
                @csrf @method('PUT')
                <input type="hidden" name="_modal" value="edit">
                <input type="hidden" name="_id" value="{{ $bidang->id }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Bidang</label>
                        <input type="text" name="nama_bidang"
                                value="{{ old('_id') == $bidang->id ? old('nama_bidang') : $bidang->nama_bidang }}"
                                class="form-control @if(old('_id') == $bidang->id && $errors->has('nama_bidang')) is-invalid @endif" required>
                        @if(old('_id') == $bidang->id && $errors->has('nama_bidang'))
                            <div class="invalid-feedback">{{ $errors->first('nama_bidang') }}</div>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kode Bidang</label>
                        <input type="text" name="kode_bidang"
                                value="{{ old('_id') == $bidang->id ? old('kode_bidang') : $bidang->kode_bidang }}"
                                class="form-control @if(old('_id') == $bidang->id && $errors->has('kode_bidang')) is-invalid @endif" required>
                        @if(old('_id') == $bidang->id && $errors->has('kode_bidang'))
                            <div class="invalid-feedback">{{ $errors->first('kode_bidang') }}</div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
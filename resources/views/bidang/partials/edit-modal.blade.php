<div class="modal fade" id="editBidangModal{{ $bidang->id }}" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i> Edit Bidang</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('bidang.update', $bidang->id) }}" method="POST">
                @csrf
                @method('PUT')
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
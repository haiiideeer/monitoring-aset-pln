<div class="modal fade" id="tambahBidangModal" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i> Tambah Bidang Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('bidang.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Bidang</label>
                        <input type="text" name="nama_bidang"
                               class="form-control @error('nama_bidang') is-invalid @enderror"
                               value="{{ old('nama_bidang') }}" required>
                        @error('nama_bidang')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kode Bidang</label>
                        <input type="text" name="kode_bidang"
                               class="form-control @error('kode_bidang') is-invalid @enderror"
                               value="{{ old('kode_bidang') }}" required>
                        @error('kode_bidang')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
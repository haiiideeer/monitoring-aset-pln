<table class="table table-hover table-striped mb-0">
    <thead class="table-primary">
        <tr>
            <th>Nama Aset</th>
            <th>Jumlah</th>
            <th>Bidang</th>
            <th>Lokasi</th>
            <th>Tanggal</th>
            <th class="text-center">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($asets as $aset)
            <tr>
                <td>{{ $aset->nama_aset }}</td>
                <td>{{ $aset->jumlah_aset }}</td>
                <td>{{ $aset->bidang->nama_bidang }}</td>
                <td>{{ $aset->lokasi }}</td>
                <td>{{ \Carbon\Carbon::parse($aset->tanggal_perolehan)->format('d-m-Y') }}</td>
                <td class="text-center">
                    {{-- Tombol Edit --}}
                    <button type="button" class="btn btn-warning btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#editAsetModal{{ $aset->id }}">
                        <i class="fas fa-edit"></i>
                    </button>

                    {{-- Tombol Hapus --}}
                    <button type="button" class="btn btn-danger btn-sm btn-delete"
                            data-id="{{ $aset->id }}">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            </tr>

            {{-- Modal Edit --}}
            <div class="modal fade custom-fade" id="editAsetModal{{ $aset->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-zoom">
                    <div class="modal-content border-0 shadow-lg rounded-3">
                        <div class="modal-header bg-warning text-white border-0">
                            <h5 class="modal-title">
                                <i class="fas fa-edit me-2"></i> Edit Aset
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('aset.update', $aset->id) }}" method="POST">
                            @csrf @method('PUT')
                            <div class="modal-body p-4">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Bidang</label>
                                        <select name="bidang_id" class="form-select shadow-sm" required>
                                            @foreach(\App\Models\Bidang::all() as $bidang)
                                                <option value="{{ $bidang->id }}" {{ $bidang->id == $aset->bidang_id ? 'selected' : '' }}>
                                                    {{ $bidang->nama_bidang }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Nama Aset</label>
                                        <input type="text" name="nama_aset" value="{{ $aset->nama_aset }}" class="form-control shadow-sm" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Jumlah</label>
                                        <input type="number" name="jumlah_aset" value="{{ $aset->jumlah_aset }}" class="form-control shadow-sm" min="1" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Lokasi</label>
                                        <input type="text" name="lokasi" value="{{ $aset->lokasi }}" class="form-control shadow-sm" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Tanggal Perolehan</label>
                                        <input type="date" name="tanggal_perolehan"
                                            value="{{ \Carbon\Carbon::parse($aset->tanggal_perolehan)->format('Y-m-d') }}"
                                            class="form-control shadow-sm" required>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer border-0 p-3">
                                <button type="button" class="btn btn-light border shadow-sm" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-1"></i> Batal
                                </button>
                                <button type="submit" class="btn btn-primary shadow-sm">
                                    <i class="fas fa-save me-1"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <tr>
                <td colspan="6" class="text-center text-muted">Tidak ada data aset.</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{-- Pagination --}}
<div class="mt-3">
    {{ $asets->appends(['search' => request('search')])->links() }}
</div>

{{-- Script SweetAlert2 Delete --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-delete').forEach(function (button) {
        button.addEventListener('click', function () {
            let id = this.getAttribute('data-id');
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: "Apakah Anda yakin ingin menghapus data ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = document.createElement('form');
                    form.action = `/aset/${id}`;
                    form.method = 'POST';

                    let token = document.createElement('input');
                    token.type = 'hidden';
                    token.name = '_token';
                    token.value = '{{ csrf_token() }}';
                    form.appendChild(token);

                    let method = document.createElement('input');
                    method.type = 'hidden';
                    method.name = '_method';
                    method.value = 'DELETE';
                    form.appendChild(method);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });
});
</script>

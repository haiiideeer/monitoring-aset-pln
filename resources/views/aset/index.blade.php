@extends('layouts.app')

@section('content')
<div class="container mt-4">
    {{-- Header & Search --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body d-flex justify-content-between align-items-center">
            <form method="GET" action="{{ route('aset.index') }}" class="d-flex w-50">
                <input type="text" name="search" value="{{ request('search') }}" 
                       class="form-control form-control-sm me-2" placeholder="Cari aset...">
                <button class="btn btn-sm btn-outline-primary" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </form>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahAsetModal">
                + Tambah Aset
            </button>
        </div>
    </div>

    {{-- Table --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover table-striped mb-0" id="asetTable">
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
                            <td>{{ $aset->bidang->nama_bidang ?? '-' }}</td>
                            <td>{{ $aset->lokasi }}</td>
                            <td>{{ \Carbon\Carbon::parse($aset->tanggal_perolehan)->format('d-m-Y') }}</td>
                            <td class="text-center">
                                {{-- Edit --}}
                                <button type="button" class="btn btn-warning btn-sm" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editAsetModal{{ $aset->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>

                                {{-- Delete --}}
                                <button type="button" class="btn btn-danger btn-sm btn-delete" 
                                        data-id="{{ $aset->id }}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>

                        {{-- Modal Edit --}}
                        <div class="modal fade" id="editAsetModal{{ $aset->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content border-0 shadow-lg">
                                    <div class="modal-header bg-warning text-white">
                                        <h5 class="modal-title"><i class="fas fa-edit me-2"></i> Edit Aset</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('aset.update', $aset->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Bidang</label>
                                                    <select name="bidang_id" class="form-select" required>
                                                        @foreach(\App\Models\Bidang::all() as $bidang)
                                                            <option value="{{ $bidang->id }}" 
                                                                {{ $bidang->id == $aset->bidang_id ? 'selected' : '' }}>
                                                                {{ $bidang->nama_bidang }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Nama Aset</label>
                                                    <input type="text" name="nama_aset" value="{{ $aset->nama_aset }}" class="form-control" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Jumlah</label>
                                                    <input type="number" name="jumlah_aset" value="{{ $aset->jumlah_aset }}" class="form-control" min="1" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Lokasi</label>
                                                    <input type="text" name="lokasi" value="{{ $aset->lokasi }}" class="form-control" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Tanggal Perolehan</label>
                                                    <input type="date" name="tanggal_perolehan" value="{{ \Carbon\Carbon::parse($aset->tanggal_perolehan)->format('Y-m-d') }}" class="form-control" required>
                                                </div>
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
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Tidak ada data aset.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-3">
        {{ $asets->appends(['search' => request('search')])->links() }}
    </div>
</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="tambahAsetModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i> Tambah Aset Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('aset.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Bidang</label>
                            <select name="bidang_id" class="form-select" required>
                                <option value="">-- Pilih Bidang --</option>
                                @foreach(\App\Models\Bidang::all() as $bidang)
                                    <option value="{{ $bidang->id }}">{{ $bidang->nama_bidang }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Aset</label>
                            <input type="text" name="nama_aset" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jumlah</label>
                            <input type="number" name="jumlah_aset" class="form-control" min="1" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Lokasi</label>
                            <input type="text" name="lokasi" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Perolehan</label>
                            <input type="date" name="tanggal_perolehan" class="form-control" required>
                        </div>
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
@endsection

@section('scripts')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    // Inisialisasi DataTable
    $('#asetTable').DataTable({
        "paging": false,
        "searching": false,
        "info": false
    });

    // Fungsi untuk menghapus data
    $('.btn-delete').on('click', function() {
        var id = $(this).data('id');
        var url = "{{ route('aset.destroy', ':id') }}".replace(':id', id);
        var token = "{{ csrf_token() }}";
        
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: token,
                        _method: 'DELETE'
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Terhapus!',
                            text: 'Data berhasil dihapus.',
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat menghapus data.',
                            icon: 'error'
                        });
                    }
                });
            }
        });
    });

    // Tampilkan pesan sukses jika ada
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            timer: 2000,
            showConfirmButton: false
        });
    @endif
});
</script>
@endsection
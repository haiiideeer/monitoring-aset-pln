@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Tambah Aset</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('aset.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nama Aset</label>
                    <input type="text" name="nama_aset" class="form-control" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Jumlah Aset</label>
                    <input type="number" name="jumlah_aset" class="form-control" required min="1">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Bidang</label>
                    <select name="bidang_id" class="form-select" required>
                        <option value="">-- Pilih Bidang --</option>
                        @foreach($bidangs as $bidang)
                            <option value="{{ $bidang->id }}">{{ $bidang->nama_bidang }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Lokasi</label>
                    <input type="text" name="lokasi" class="form-control" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Tanggal Perolehan</label>
                    <input type="date" name="tanggal_perolehan" class="form-control" required>
                    <small class="text-muted">Format: Hari/Bulan/Tahun</small>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Foto Aset</label>
                    <input type="file" name="foto" class="form-control" accept="image/*">
                    <small class="text-muted">Format yang didukung: JPG, JPEG, PNG, GIF (Maksimal 2MB)</small>
                    
                    <!-- Preview foto -->
                    <div class="mt-2">
                        <img id="preview" src="" alt="Preview foto" style="display: none; max-width: 200px; max-height: 200px; border: 1px solid #ddd; border-radius: 4px; padding: 5px;">
                    </div>
                </div>
                
                <div class="d-flex justify-content-end">
                    <a href="{{ route('aset.index') }}" class="btn btn-secondary me-2">Kembali</a>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fotoInput = document.querySelector('input[name="foto"]');
    const preview = document.getElementById('preview');
    
    fotoInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        
        if (file) {
            // Validasi ukuran file (2MB = 2 * 1024 * 1024 bytes)
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file terlalu besar. Maksimal 2MB.');
                this.value = '';
                preview.style.display = 'none';
                return;
            }
            
            // Validasi tipe file
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!allowedTypes.includes(file.type)) {
                alert('Tipe file tidak didukung. Gunakan JPG, JPEG, PNG, atau GIF.');
                this.value = '';
                preview.style.display = 'none';
                return;
            }
            
            // Preview foto
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    });
});
</script>
@endsection
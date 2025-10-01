{{-- File: resources/views/assets/partials/edit-modal.blade.php --}}

<div class="modal fade" id="editAssetModal{{ $asset->id }}" tabindex="-1" aria-labelledby="editAssetModalLabel{{ $asset->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editAssetModalLabel{{ $asset->id }}">
                    <i class="fas fa-edit me-2"></i>Edit Aset
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('assets.update', $asset->id) }}" method="POST" enctype="multipart/form-data" id="editAssetForm{{ $asset->id }}">
                @csrf
                @method('PUT')

                <div class="modal-body">
                    <div class="row g-4">
                        <!-- Left Column -->
                        <div class="col-lg-8">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-light border-bottom">
                                    <h6 class="mb-0 text-primary">
                                        <i class="fas fa-info-circle me-2"></i>Informasi Dasar
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <!-- Nama Aset -->
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input type="text" 
                                                       class="form-control" 
                                                       id="nama_aset_edit_{{ $asset->id }}" 
                                                       name="nama_aset" 
                                                       value="{{ old('nama_aset', $asset->nama_aset) }}" 
                                                       required>
                                                <label for="nama_aset_edit_{{ $asset->id }}">Nama Aset</label>
                                                <div class="invalid-feedback">Nama aset wajib diisi.</div>
                                            </div>
                                        </div>

                                        <!-- Bidang -->
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <select class="form-select" id="bidang_id_edit_{{ $asset->id }}" name="bidang_id" required>
                                                    <option value="">Pilih Bidang</option>
                                                    @foreach($bidangs as $bidang)
                                                        <option value="{{ $bidang->id }}" {{ old('bidang_id', $asset->bidang_id) == $bidang->id ? 'selected' : '' }}>
                                                            {{ $bidang->nama_bidang }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <label for="bidang_id_edit_{{ $asset->id }}">Bidang</label>
                                                <div class="invalid-feedback">Bidang wajib dipilih.</div>
                                            </div>
                                        </div>

                                        <!-- Unit -->
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <select class="form-select" id="unit_edit_{{ $asset->id }}" name="unit" required>
                                                    <option value="">Pilih Unit</option>
                                                    @foreach([
                                                        'Kantor Wilayah UIW Maluku & Maluku Utara',
                                                        'UP3 Ambon','UP3 Masohi','UP3 Tual','UP3 Saumlaki',
                                                        'UP3 Ternate','UP3 Sofifi','UP3 Tobelo',
                                                        'UPK Maluku','UP3B Maluku','UP2K Maluku','UP2K Maluku Utara'
                                                    ] as $unit)
                                                        <option value="{{ $unit }}" {{ old('unit', $asset->unit) == $unit ? 'selected' : '' }}>{{ $unit }}</option>
                                                    @endforeach
                                                </select>
                                                <label for="unit_edit_{{ $asset->id }}">Unit</label>
                                                <div class="invalid-feedback">Unit wajib dipilih.</div>
                                            </div>
                                        </div>

                                        <!-- Jumlah -->
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input type="number" 
                                                       class="form-control" 
                                                       id="jumlah_aset_edit_{{ $asset->id }}" 
                                                       name="jumlah_aset" 
                                                       value="{{ old('jumlah_aset', $asset->jumlah_aset) }}" 
                                                       min="1"
                                                       max="999999"
                                                       required>
                                                <label for="jumlah_aset_edit_{{ $asset->id }}">Jumlah</label>
                                                <div class="invalid-feedback">Jumlah harus antara 1-999999.</div>
                                            </div>
                                        </div>

                                        <!-- Harga -->
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input type="number" 
                                                       class="form-control" 
                                                       id="harga_edit_{{ $asset->id }}" 
                                                       name="harga" 
                                                       value="{{ old('harga', $asset->harga) }}" 
                                                       min="0"
                                                       step="0.01"
                                                       required>
                                                <label for="harga_edit_{{ $asset->id }}">Harga (Rp)</label>
                                                <div class="invalid-feedback">Harga wajib diisi dan tidak boleh negatif.</div>
                                            </div>
                                        </div>

                                        <!-- Kondisi -->
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <select class="form-select" id="kondisi_edit_{{ $asset->id }}" name="kondisi" required>
                                                    <option value="">Pilih Kondisi</option>
                                                    <option value="Baik" {{ old('kondisi', $asset->kondisi) == 'Baik' ? 'selected' : '' }}>Baik</option>
                                                    <option value="Rusak" {{ old('kondisi', $asset->kondisi) == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                                                    <option value="Perlu Perbaikan" {{ old('kondisi', $asset->kondisi) == 'Perlu Perbaikan' ? 'selected' : '' }}>Perlu Perbaikan</option>
                                                    <option value="Hilang" {{ old('kondisi', $asset->kondisi) == 'Hilang' ? 'selected' : '' }}>Hilang</option>
                                                </select>
                                                <label for="kondisi_edit_{{ $asset->id }}">Kondisi</label>
                                                <div class="invalid-feedback">Kondisi wajib dipilih.</div>
                                            </div>
                                        </div>

                                        <!-- Lokasi -->
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input type="text" 
                                                       class="form-control" 
                                                       id="lokasi_edit_{{ $asset->id }}" 
                                                       name="lokasi" 
                                                       value="{{ old('lokasi', $asset->lokasi) }}" 
                                                       minlength="2"
                                                       required>
                                                <label for="lokasi_edit_{{ $asset->id }}">Lokasi</label>
                                                <div class="invalid-feedback">Lokasi wajib diisi minimal 2 karakter.</div>
                                            </div>
                                        </div>

                                        <!-- Tanggal -->
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input type="date" 
                                                       class="form-control" 
                                                       id="tanggal_perolehan_edit_{{ $asset->id }}" 
                                                       name="tanggal_perolehan" 
                                                       value="{{ old('tanggal_perolehan', $asset->tanggal_perolehan ? $asset->tanggal_perolehan->format('Y-m-d') : '') }}" 
                                                       max="{{ date('Y-m-d') }}"
                                                       required>
                                                <label for="tanggal_perolehan_edit_{{ $asset->id }}">Tanggal Perolehan</label>
                                                <div class="invalid-feedback">Tanggal perolehan wajib diisi dan tidak boleh di masa depan.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-lg-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header bg-light border-bottom">
                                    <h6 class="mb-0 text-primary">
                                        <i class="fas fa-camera me-2"></i>Foto Aset
                                    </h6>
                                </div>
                                <div class="card-body text-center">
                                    <div class="mb-3" id="currentPhotos{{ $asset->id }}">
                                        @if($asset->foto_aset)
                                            @php
                                                $images = json_decode($asset->foto_aset, true) ?: [$asset->foto_aset];
                                            @endphp
                                            @foreach($images as $index => $image)
                                                @php
                                                    $imageUrl = str_starts_with($image, 'http') ? $image : asset('storage/' . $image);
                                                @endphp
                                                <div class="current-photo-item mb-2" style="position: relative; display: inline-block;">
                                                    <img src="{{ $imageUrl }}" 
                                                         class="img-fluid rounded" 
                                                         style="max-height:120px; max-width: 120px; object-fit: cover;"
                                                         alt="Foto {{ $index + 1 }}"
                                                         onerror="this.src='{{ asset('images/no-image.png') }}';">
                                                    @if($index == 0)
                                                        <small class="text-muted d-block">Foto Utama</small>
                                                    @else
                                                        <small class="text-muted d-block">Foto {{ $index + 1 }}</small>
                                                    @endif
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="text-muted">
                                                <i class="fas fa-camera fa-3x mb-2"></i>
                                                <p>Belum ada foto</p>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="foto_aset_edit_{{ $asset->id }}" class="form-label">Upload Foto Baru (Opsional)</label>
                                        <input type="file" 
                                               class="form-control" 
                                               id="foto_aset_edit_{{ $asset->id }}" 
                                               name="foto_aset[]" 
                                               accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                               multiple>
                                        <div class="form-text text-muted">
                                            <small>
                                                <i class="fas fa-info-circle me-1"></i>
                                                Format: JPEG, PNG, JPG, GIF, WEBP<br>
                                                Ukuran maksimal: 2MB per file<br>
                                                Upload foto baru akan mengganti foto lama
                                            </small>
                                        </div>
                                    </div>
                                    
                                    <!-- Preview area for new photos -->
                                    <div id="newPhotoPreview{{ $asset->id }}" class="mt-2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary" id="submitBtn{{ $asset->id }}">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Photo preview for edit modal {{ $asset->id }}
    const photoInput{{ $asset->id }} = document.getElementById('foto_aset_edit_{{ $asset->id }}');
    const previewContainer{{ $asset->id }} = document.getElementById('newPhotoPreview{{ $asset->id }}');
    
    if (photoInput{{ $asset->id }}) {
        photoInput{{ $asset->id }}.addEventListener('change', function() {
            previewContainer{{ $asset->id }}.innerHTML = '';
            
            if (this.files && this.files.length > 0) {
                const previewTitle = document.createElement('div');
                previewTitle.className = 'text-primary fw-bold mb-2';
                previewTitle.innerHTML = '<i class="fas fa-eye me-1"></i>Preview Foto Baru:';
                previewContainer{{ $asset->id }}.appendChild(previewTitle);
                
                Array.from(this.files).forEach((file, index) => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const previewDiv = document.createElement('div');
                            previewDiv.className = 'mb-2';
                            previewDiv.style.position = 'relative';
                            previewDiv.style.display = 'inline-block';
                            
                            previewDiv.innerHTML = `
                                <img src="${e.target.result}" 
                                     class="img-fluid rounded border" 
                                     style="max-height:100px; max-width: 100px; object-fit: cover;" 
                                     alt="Preview ${index + 1}">
                                <small class="text-muted d-block">Preview ${index + 1}</small>
                            `;
                            previewContainer{{ $asset->id }}.appendChild(previewDiv);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
        });
    }
    
    // Form validation for edit form {{ $asset->id }}
    const editForm{{ $asset->id }} = document.getElementById('editAssetForm{{ $asset->id }}');
    if (editForm{{ $asset->id }}) {
        editForm{{ $asset->id }}.addEventListener('submit', function(e) {
            let isValid = true;
            const submitBtn = document.getElementById('submitBtn{{ $asset->id }}');
            
            // Reset previous validation states
            this.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            
            // Validate required fields
            const requiredFields = this.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                }
            });
            
            // Validate specific fields
            const namaAset = document.getElementById('nama_aset_edit_{{ $asset->id }}');
            if (namaAset && namaAset.value.trim().length < 2) {
                namaAset.classList.add('is-invalid');
                isValid = false;
            }
            
            const jumlahAset = document.getElementById('jumlah_aset_edit_{{ $asset->id }}');
            if (jumlahAset && (parseInt(jumlahAset.value) < 1 || parseInt(jumlahAset.value) > 999999)) {
                jumlahAset.classList.add('is-invalid');
                isValid = false;
            }
            
            const harga = document.getElementById('harga_edit_{{ $asset->id }}');
            if (harga && parseFloat(harga.value) < 0) {
                harga.classList.add('is-invalid');
                isValid = false;
            }
            
            const lokasi = document.getElementById('lokasi_edit_{{ $asset->id }}');
            if (lokasi && lokasi.value.trim().length < 2) {
                lokasi.classList.add('is-invalid');
                isValid = false;
            }
            
            const tanggal = document.getElementById('tanggal_perolehan_edit_{{ $asset->id }}');
            if (tanggal && new Date(tanggal.value) > new Date()) {
                tanggal.classList.add('is-invalid');
                isValid = false;
            }
            
            // Validate photo files if selected
            const photoInput = document.getElementById('foto_aset_edit_{{ $asset->id }}');
            if (photoInput && photoInput.files.length > 0) {
                Array.from(photoInput.files).forEach(file => {
                    if (!file.type.startsWith('image/')) {
                        photoInput.classList.add('is-invalid');
                        isValid = false;
                    }
                    if (file.size > 2 * 1024 * 1024) { // 2MB
                        photoInput.classList.add('is-invalid');
                        isValid = false;
                    }
                });
            }
            
            if (!isValid) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Form Tidak Valid',
                    text: 'Mohon perbaiki field yang ditandai dengan warna merah.',
                    confirmButtonText: 'OK'
                });
                return;
            }
            
            // Show loading state
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...';
            }
        });
    }
});
</script>
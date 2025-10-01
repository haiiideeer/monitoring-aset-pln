<form id="createAssetForm" action="{{ route('assets.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row g-4">
        <!-- Left Column - Basic Information -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                 <div class="modal-header bg-primary text-white">
               <h5 class="modal-title" id="createAssetModalLabel">
    <i class="fas fa-plus me-2"></i>Tambah Aset
</h5>

                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                    <h5 class="card-title mb-0 text-primary">
                        <i class="fas fa-info-circle me-2"></i>Informasi Dasar
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <!-- Nama Aset -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" 
                                       class="form-control @error('nama_aset') is-invalid @enderror" 
                                       id="nama_aset" 
                                       name="nama_aset" 
                                       value="{{ old('nama_aset') }}"
                                       placeholder="Masukkan nama aset" required>
                                <label for="nama_aset" class="required">Nama Aset</label>
                                @error('nama_aset')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Bidang -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select @error('bidang_id') is-invalid @enderror" 
                                        id="bidang_id" name="bidang_id" required>
                                    <option value="">Pilih Bidang</option>
                                    @foreach($bidangs as $bidang)
                                        <option value="{{ $bidang->id }}" {{ old('bidang_id') == $bidang->id ? 'selected' : '' }}>
                                            {{ $bidang->nama_bidang }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="bidang_id" class="required">Bidang</label>
                                @error('bidang_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Unit -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select @error('unit') is-invalid @enderror" 
                                        id="unit" name="unit" required>
                                    <option value="">Pilih Unit</option>
                                    <option value="Kantor Wilayah UIW Maluku & Maluku Utara">Kantor Wilayah UIW Maluku & Maluku Utara</option>
                                    <option value="UP3 Ambon">UP3 Ambon</option>
                                    <option value="UP3 Masohi">UP3 Masohi</option>
                                    <option value="UP3 Tual">UP3 Tual</option>
                                    <option value="UP3 Saumlaki">UP3 Saumlaki</option>
                                    <option value="UP3 Ternate">UP3 Ternate</option>
                                    <option value="UP3 Sofifi">UP3 Sofifi</option>
                                    <option value="UP3 Tobelo">UP3 Tobelo</option>
                                    <option value="UPK Maluku">UPK Maluku</option>
                                    <option value="UP3B Maluku">UP3B Maluku</option>
                                    <option value="UP2K Maluku">UP2K Maluku</option>
                                    <option value="UP2K Maluku Utara">UP2K Maluku Utara</option>
                                </select>
                                <label for="unit" class="required">Unit</label>
                                @error('unit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Jumlah -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="number" 
                                       class="form-control @error('jumlah_aset') is-invalid @enderror" 
                                       id="jumlah_aset" 
                                       name="jumlah_aset" 
                                       value="{{ old('jumlah_aset', 1) }}" 
                                       min="1" required>
                                <label for="jumlah_aset" class="required">Jumlah</label>
                                @error('jumlah_aset')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Harga -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="number" 
                                       class="form-control @error('harga') is-invalid @enderror" 
                                       id="harga" 
                                       name="harga" 
                                       value="{{ old('harga') }}" 
                                       min="0" step="1000"
                                       placeholder="Masukkan harga" required>
                                <label for="harga" class="required">Harga (Rp)</label>
                                @error('harga')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Kondisi -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select @error('kondisi') is-invalid @enderror" 
                                        id="kondisi" name="kondisi" required>
                                    <option value="">Pilih Kondisi</option>
                                    <option value="Baik" {{ old('kondisi')=='Baik'?'selected':'' }}>Baik</option>
                                    <option value="Rusak" {{ old('kondisi')=='Rusak'?'selected':'' }}>Rusak</option>
                                    <option value="Perlu Perbaikan" {{ old('kondisi')=='Perlu Perbaikan'?'selected':'' }}>Perlu Perbaikan</option>
                                    <option value="Hilang" {{ old('kondisi')=='Hilang'?'selected':'' }}>Hilang</option>
                                    
                                </select>
                                <label for="kondisi" class="required">Kondisi</label>
                                @error('kondisi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Lokasi -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" 
                                       class="form-control @error('lokasi') is-invalid @enderror" 
                                       id="lokasi" 
                                       name="lokasi" 
                                       value="{{ old('lokasi') }}"
                                       placeholder="Masukkan lokasi" required>
                                <label for="lokasi" class="required">Lokasi</label>
                                @error('lokasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Tanggal Perolehan -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="date" 
                                       class="form-control @error('tanggal_perolehan') is-invalid @enderror" 
                                       id="tanggal_perolehan" 
                                       name="tanggal_perolehan" 
                                       value="{{ old('tanggal_perolehan', date('Y-m-d')) }}" 
                                       required>
                                <label for="tanggal_perolehan" class="required">Tanggal Perolehan</label>
                                @error('tanggal_perolehan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Photo Upload -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0 text-primary">
                        <i class="fas fa-camera me-2"></i>Foto Aset
                    </h5>
                </div>
                <div class="card-body p-4">
                    <!-- Upload Area -->
                    <div class="image-preview-container" id="imagePreviewContainer">
                        <div class="photo-counter" id="photoCounter" style="display: none;">0 foto</div>
                        <div class="upload-placeholder" id="uploadPlaceholder">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <!-- <h6>Drag & Drop foto di sini</h6> -->
                            <p class="text-muted mb-0">atau klik untuk memilih file</p>
                        </div>
                        <div class="image-preview-grid" id="imagePreviewGrid" style="display: none;"></div>
                        <div class="loading-overlay" id="loadingOverlay" style="display: none;">
                            <div class="text-center">
                                <div class="spinner-border text-primary mb-2" role="status"></div>
                                <p class="mb-0">Memproses foto...</p>
                            </div>
                        </div>
                    </div>

                    <!-- File Input -->
                    <input type="file" class="d-none" id="foto_aset" name="foto_aset[]" accept="image/*" multiple>
                    
                    <!-- Upload Button -->
                    <div class="mt-3">
                        <button type="button" class="btn btn-outline-primary w-100 mb-2" onclick="document.getElementById('foto_aset').click()">
                            <i class="fas fa-plus me-2"></i>Pilih Foto
                        </button>
                        <small class="text-muted d-block text-center">
                            Format: JPG, PNG, GIF, WEBP<br>
                            Maksimal 2MB per file<br>
                            
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="row mt-4">
        <div class="col-12 text-end">
            @if(request()->ajax())
                {{-- Kalau dipakai modal --}}
                <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Batal
                </button>
            @else
                {{-- Kalau standalone --}}
                <a href="{{ route('assets.index') }}" class="btn btn-secondary me-2">
                    <i class="fas fa-arrow-left me-2"></i>Batal
                </a>
            @endif

            <button type="submit" class="btn btn-primary px-4" id="submitBtn">
                <i class="fas fa-save me-2"></i>Simpan Aset
            </button>
        </div>
    </div>
</form>

@push('styles')
<style>
    .required::after { content: ' *'; color: red; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const fotoInput = document.getElementById("foto_aset");
    const previewGrid = document.getElementById("imagePreviewGrid");
    const uploadPlaceholder = document.getElementById("uploadPlaceholder");
    const photoCounter = document.getElementById("photoCounter");

    // Pastikan event listener hanya ditambahkan sekali
    if (fotoInput && !fotoInput.hasAttribute('data-listener-added')) {
        fotoInput.setAttribute('data-listener-added', 'true');
        
        fotoInput.addEventListener("change", function (event) {
            const files = event.target.files;

            // Reset tampilan sepenuhnya
            previewGrid.innerHTML = "";
            previewGrid.style.display = "none";
            uploadPlaceholder.style.display = "block";
            photoCounter.style.display = "none";

            if (files.length > 0) {
                previewGrid.style.display = "grid";
                uploadPlaceholder.style.display = "none";
                photoCounter.style.display = "block";
                photoCounter.textContent = files.length + " foto";

                // Gunakan Set untuk menghindari file duplikat berdasarkan nama dan ukuran
                const processedFiles = new Set();

                Array.from(files).forEach((file, index) => {
                    // Buat identifier unik untuk file
                    const fileId = `${file.name}_${file.size}_${file.lastModified}`;
                    
                    // Skip jika file sudah diproses
                    if (processedFiles.has(fileId)) {
                        return;
                    }
                    
                    processedFiles.add(fileId);

                    if (file.type.startsWith("image/")) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            // Buat container untuk setiap gambar
                            const imgContainer = document.createElement("div");
                            imgContainer.classList.add("position-relative", "d-inline-block");
                            imgContainer.setAttribute('data-file-id', fileId);

                            const img = document.createElement("img");
                            img.src = e.target.result;
                            img.classList.add("img-thumbnail");
                            img.style.width = "120px";
                            img.style.height = "120px";
                            img.style.objectFit = "cover";

                            // Tambahkan tombol hapus
                            const deleteBtn = document.createElement("button");
                            deleteBtn.type = "button";
                            deleteBtn.classList.add("btn", "btn-danger", "btn-sm", "position-absolute", "top-0", "end-0");
                            deleteBtn.style.transform = "translate(50%, -50%)";
                            deleteBtn.innerHTML = '<i class="fas fa-times"></i>';
                            
                            deleteBtn.addEventListener('click', function() {
                                imgContainer.remove();
                                updatePhotoCounter();
                            });

                            imgContainer.appendChild(img);
                            imgContainer.appendChild(deleteBtn);
                            previewGrid.appendChild(imgContainer);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
        });
    }

    // Function untuk update counter foto
    function updatePhotoCounter() {
        const remainingImages = previewGrid.querySelectorAll('img').length;
        if (remainingImages > 0) {
            photoCounter.textContent = remainingImages + " foto";
        } else {
            previewGrid.style.display = "none";
            uploadPlaceholder.style.display = "block";
            photoCounter.style.display = "none";
        }
    }
});
</script>
@endpush

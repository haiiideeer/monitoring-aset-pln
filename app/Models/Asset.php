<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_aset', 
        'kode_aset', 
        'bidang_id',
        'unit',
        'jumlah_aset', 
        'harga',
        'lokasi', 
        'kondisi', 
        'tanggal_perolehan', 
        'foto_aset', 
        'qr_code_path',
        'has_qr_code'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            // Generate kode aset if not provided
            if (empty($model->kode_aset)) {
                $model->kode_aset = self::generateKodeAset();
            }
        });

        static::saving(function ($model) {
            // Validasi bidang_id harus ada
            if (empty($model->bidang_id)) {
                throw new \Exception("Bidang harus dipilih");
            }
            
            // Validasi jumlah aset minimal 1
            if ($model->jumlah_aset < 1) {
                throw new \Exception("Jumlah aset minimal 1");
            }
            
            // Validasi harga tidak negatif
            if ($model->harga < 0) {
                throw new \Exception("Harga tidak boleh negatif");
            }
        });

        static::deleting(function ($model) {
            // Delete photo files when asset is deleted
            if ($model->foto_aset) {
                $photos = $model->foto_array;
                if (is_array($photos)) {
                    foreach ($photos as $photo) {
                        if (Storage::disk('public')->exists($photo)) {
                            Storage::disk('public')->delete($photo);
                        }
                    }
                }
            }
            
            // Delete QR Code file
            if ($model->qr_code_path && Storage::disk('public')->exists($model->qr_code_path)) {
                Storage::disk('public')->delete($model->qr_code_path);
            }
        });
    }

    protected $dates = [
        'tanggal_perolehan',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'tanggal_perolehan' => 'date',
        'jumlah_aset' => 'integer',
        'harga' => 'decimal:0',
        'has_qr_code' => 'boolean'
        
    ];

    // Relationships
    public function bidang()
    {
        return $this->belongsTo(Bidang::class);
    }

    // Accessors - PERBAIKAN UTAMA
    public function getFotoArrayAttribute()
    {
        if (!$this->foto_aset) {
            return [];
        }
        
        // If it's already an array, return it
        if (is_array($this->foto_aset)) {
            return $this->foto_aset;
        }
        
        // If it's a JSON string, decode it
        $decoded = json_decode($this->foto_aset, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }
        
        // If it's a single photo path (not empty/null), wrap it in array
        return !empty($this->foto_aset) ? [$this->foto_aset] : [];
    }

    public function getFirstPhotoUrlAttribute()
    {
        $photos = $this->foto_array;
        
        if (empty($photos)) {
            return null;
        }
        
        $firstPhoto = $photos[0];
        return str_starts_with($firstPhoto, 'http') 
            ? $firstPhoto 
            : asset('storage/' . $firstPhoto);
    }

    public function getPhotoUrlsAttribute()
    {
        $photos = $this->foto_array;
        $urls = [];
        
        foreach ($photos as $photo) {
            $urls[] = str_starts_with($photo, 'http') 
                ? $photo 
                : asset('storage/' . $photo);
        }
        
        return $urls;
    }

    public function getFotoUrlsAttribute()
    {
        $photos = $this->foto_array;
        return array_map(function($photo) {
            return str_starts_with($photo, 'http') ? $photo : asset('storage/' . $photo);
        }, $photos);
    }

    public function getMainFotoUrlAttribute()
    {
        $photos = $this->foto_array;
        if (empty($photos)) {
            return null;
        }
        
        $mainPhoto = $photos[0];
        return str_starts_with($mainPhoto, 'http') ? $mainPhoto : asset('storage/' . $mainPhoto);
    }

    public function getTanggalPerolehanFormattedAttribute()
    {
        if ($this->tanggal_perolehan) {
            return Carbon::parse($this->tanggal_perolehan)->format('d/m/Y');
        }
        return null;
    }

    public function getHargaFormattedAttribute()
    {
        return 'Rp ' . number_format($this->harga ?? 0, 0, ',', '.');
    }

    public function getTotalHargaAttribute()
    {
        return ($this->harga ?? 0) * $this->jumlah_aset;
    }

    public function getTotalHargaFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_harga, 0, ',', '.');
    }

    public function getKondisiStatusAttribute()
    {
        $kondisi = strtolower($this->kondisi);
        
        switch ($kondisi) {
            case 'sangat_baik':
            case 'sangat baik':
                return [
                    'class' => 'success',
                    'text' => 'Sangat Baik'
                ];
            case 'baik':
                return [
                    'class' => 'success',
                    'text' => 'Baik'
                ];
            case 'cukup':
                return [
                    'class' => 'info',
                    'text' => 'Cukup'
                ];
            case 'rusak_ringan':
            case 'rusak ringan':
            case 'perlu_perbaikan':
            case 'perlu perbaikan':
                return [
                    'class' => 'warning',
                    'text' => 'Perlu Perbaikan'
                ];
            case 'rusak_berat':
            case 'rusak berat':
            case 'dalam_perbaikan':
            case 'dalam perbaikan':
                return [
                    'class' => 'danger',
                    'text' => 'Rusak'
                ];
            case 'hilang':
                return [
                    'class' => 'dark',
                    'text' => 'Hilang'
                ];
            default:
                return [
                    'class' => 'secondary',
                    'text' => ucfirst($this->kondisi)
                ];
        }
    }

    public function getKondisiLabelAttribute()
    {
        $options = array_flip(self::getKondisiOptions());
        return $options[$this->kondisi] ?? $this->kondisi;
    }

    public function getUsiaAttribute()
    {
        if (!$this->tanggal_perolehan) {
            return null;
        }
        
        return Carbon::now()->diffInYears($this->tanggal_perolehan);
    }

    // Scopes
    public function scopeSearch($query, $search)
    {
        if (!$search) {
            return $query;
        }
        
        return $query->where(function ($q) use ($search) {
            $q->where('nama_aset', 'LIKE', "%{$search}%")
              ->orWhere('lokasi', 'LIKE', "%{$search}%")
              ->orWhere('unit', 'LIKE', "%{$search}%")
              ->orWhere('kondisi', 'LIKE', "%{$search}%")
              ->orWhere('kode_aset', 'LIKE', "%{$search}%")
              ->orWhereHas('bidang', function ($q) use ($search) {
                  $q->where('nama_bidang', 'LIKE', "%{$search}%");
              });
        });
    }

    public function scopeByBidang($query, $bidangId)
    {
        if ($bidangId) {
            return $query->where('bidang_id', $bidangId);
        }
        return $query;
    }

    public function scopeByKondisi($query, $kondisi)
    {
        if ($kondisi) {
            return $query->where('kondisi', $kondisi);
        }
        return $query;
    }

    public function scopePerluMaintenance($query)
    {
        return $query->whereIn('kondisi', ['rusak_ringan', 'rusak_berat', 'perlu_perbaikan', 'dalam_perbaikan']);
    }

    public function scopeTua($query, $tahun = 5)
    {
        return $query->where('tanggal_perolehan', '<=', Carbon::now()->subYears($tahun));
    }

    // Static methods
    public static function generateKodeAset()
    {
        $prefix = 'AST';
        $year = date('Y');
        $month = date('m');
        
        $lastAsset = self::whereYear('created_at', $year)
                        ->whereMonth('created_at', $month)
                        ->latest()
                        ->first();
        
        $sequence = 1;
        if ($lastAsset) {
            $lastCode = $lastAsset->kode_aset;
            $lastSequence = (int) substr($lastCode, -4);
            $sequence = $lastSequence + 1;
        }
        
        return $prefix . $year . $month . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    // PERBAIKAN: Method getKondisiOptions() yang sudah diperbaiki
    public static function getKondisiOptions()
    {
        return [
            'Baik' => 'Baik',
            'Rusak' => 'Rusak',
            'Perlu Perbaikan' => 'Perlu Perbaikan',
            'Hilang' => 'Hilang'
        ];
    }

    public static function getTotalAssets()
    {
        return self::count();
    }

    public static function getTotalByBidang()
    {
        return self::with('bidang:id,nama_bidang')
            ->selectRaw('bidang_id, count(*) as total')
            ->groupBy('bidang_id')
            ->get();
    }

    public static function getTotalByKondisi()
    {
        return self::selectRaw('kondisi, count(*) as total')
            ->groupBy('kondisi')
            ->get();
    }

    public static function getKondisiColor($kondisi)
    {
        switch (strtolower($kondisi)) {
            case 'sangat_baik':
            case 'baik':
                return 'success';
            case 'cukup':
                return 'info';
            case 'rusak_ringan':
            case 'perlu_perbaikan':
                return 'warning';
            case 'rusak_berat':
            case 'dalam_perbaikan':
                return 'danger';
            case 'hilang':
                return 'dark';
            default:
                return 'secondary';
        }
    }

    // Business logic methods
    public function needsMaintenance()
    {
        $problematicConditions = ['rusak_ringan', 'rusak_berat', 'perlu_perbaikan', 'dalam_perbaikan'];
        return in_array($this->kondisi, $problematicConditions);
    }

    public function isOld($tahun = 5)
    {
        return $this->usia !== null && $this->usia > $tahun;
    }

//    public function generateQrCode()
// {
//     try {
//         // Ensure qrcodes directory exists
//         $qrcodesPath = storage_path('app/public/qrcodes');
//         if (!file_exists($qrcodesPath)) {
//             mkdir($qrcodesPath, 0755, true);
//         }

//         // Generate QR code using phpqrcode (pure PHP)
//         require_once base_path('vendor/phpqrcode/phpqrcode/qrlib.php');
        
//         $fileName = 'asset_' . $this->id . '_' . time() . '.png';
//         $fullPath = $qrcodesPath . '/' . $fileName;
//         $relativePath = 'qrcodes/' . $fileName;
        
//         // Generate QR Code
//         \QRcode::png(route('assets.show', $this->id), $fullPath, QR_ECLEVEL_M, 6, 2);
        
//         if (file_exists($fullPath)) {
//             $this->qr_code_path = $relativePath;
//             $this->has_qr_code = true;
//             $this->save();
//             return true;
//         }
        
//         return false;
//     } catch (\Exception $e) {
//         Log::error('Gagal generate QR code untuk asset ' . $this->id . ': ' . $e->getMessage());
//         return false;
//     }
// }

    // Helper methods for multiple photos
    public function addPhoto($photoPath)
    {
        $currentPhotos = $this->foto_array;
        $currentPhotos[] = $photoPath;
        $this->foto_aset = json_encode($currentPhotos);
    }

    public function removePhoto($photoPath)
    {
        $currentPhotos = $this->foto_array;
        $currentPhotos = array_filter($currentPhotos, function($photo) use ($photoPath) {
            return $photo !== $photoPath;
        });
        $this->foto_aset = json_encode(array_values($currentPhotos));
    }

    public function setPhotos($photoPaths)
    {
        if (is_array($photoPaths)) {
            $this->foto_aset = json_encode($photoPaths);
        } else {
            $this->foto_aset = $photoPaths;
        }
    }
}
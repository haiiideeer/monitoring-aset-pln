<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\Bidang;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class AsetController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Aset::with('bidang')->latest();

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nama_aset', 'like', "%{$search}%")
                      ->orWhere('lokasi', 'like', "%{$search}%")
                      ->orWhereHas('bidang', function ($sub) use ($search) {
                          $sub->where('nama_bidang', 'like', "%{$search}%");
                      });
                });
            }

            $asets = $query->paginate(10);
            return view('aset.index', compact('asets'));
            
        } catch (\Exception $e) {
            Log::error('Error in AsetController@index: ' . $e->getMessage());
            return redirect()->route('aset.index')->with('error', 'Terjadi kesalahan saat memuat data aset.');
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'bidang_id' => 'required|exists:bidangs,id',
                'nama_aset' => 'required|string|max:255|unique:asets,nama_aset',
                'jumlah_aset' => 'required|integer|min:1',
                'lokasi' => 'required|string|max:255',
                'tanggal_perolehan' => 'required|date|before_or_equal:today',
            ]);

            $validated['tanggal_perolehan'] = Carbon::parse($validated['tanggal_perolehan'])->format('Y-m-d');
            
            Aset::create($validated);

            return redirect()->route('aset.index')
                ->with('success', 'Data aset berhasil ditambahkan.');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
                
        } catch (\Exception $e) {
            Log::error('Error in AsetController@store: ' . $e->getMessage());
            return redirect()->route('aset.index')
                ->with('error', 'Gagal menambahkan data aset. Silakan coba lagi.');
        }
    }

    public function update(Request $request, Aset $aset)
    {
        try {
            $validated = $request->validate([
                'bidang_id' => 'required|exists:bidangs,id',
                'nama_aset' => 'required|string|max:255|unique:asets,nama_aset,'.$aset->id,
                'jumlah_aset' => 'required|integer|min:1',
                'lokasi' => 'required|string|max:255',
                'tanggal_perolehan' => 'required|date|before_or_equal:today',
            ]);

            $validated['tanggal_perolehan'] = Carbon::parse($validated['tanggal_perolehan'])->format('Y-m-d');
            
            $aset->update($validated);

            return redirect()->route('aset.index')
                ->with('success', 'Aset berhasil diperbarui.');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
                
        } catch (\Exception $e) {
            Log::error('Error in AsetController@update: ' . $e->getMessage());
            return redirect()->route('aset.index')
                ->with('error', 'Gagal memperbarui data aset. Silakan coba lagi.');
        }
    }

    public function destroy(Aset $aset)
    {
        try {
            // Tambahkan pengecekan authorization jika diperlukan
            // if (!Gate::allows('delete-aset', $aset)) {
            //     abort(403, 'Unauthorized action.');
            // }

            $aset->delete();

            return redirect()->route('aset.index')
                ->with('success', 'Aset berhasil dihapus.');
                
        } catch (\Exception $e) {
            Log::error('Error in AsetController@destroy: ' . $e->getMessage());
            return redirect()->route('aset.index')
                ->with('error', 'Gagal menghapus data aset. Silakan coba lagi.');
        }
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\Bidang;
use Illuminate\Http\Request;
use App\Exports\AsetsExport;
use Maatwebsite\Excel\Facades\Excel;

class AsetController extends Controller
{

    public function show($id)
{
    $aset = Aset::findOrFail($id);
    return view('aset.show', compact('aset'));
}
    public function index()
    {
        $asets = Aset::with('bidang')->get();
        return view('aset.index', compact('asets'));
    }

    public function create()
    {
        $bidangs = Bidang::all();
        return view('aset.create', compact('bidangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bidang_id' => 'required|exists:bidangs,id',
            'nama_aset' => 'required',
            'jumlah_aset' => 'required',
            'lokasi' => 'required',
        ]);

        Aset::create($request->all());

        return redirect()->route('aset.index')->with('success', 'Aset berhasil ditambahkan.');
    }

    public function edit(Aset $aset)
    {
        $bidangs = Bidang::all();
        return view('aset.edit', compact('aset', 'bidangs'));
    }

    public function update(Request $request, Aset $aset)
    {
        $request->validate([
            'bidang_id' => 'required|exists:bidangs,id',
            'nama_aset' => 'required',
            'jumlah_aset' => 'required',
            'lokasi' => 'required',
        ]);

        $aset->update($request->all());

        return redirect()->route('aset.index')->with('success', 'Aset berhasil diperbarui.');
    }

    public function destroy(Aset $aset)
    {
        $aset->delete();
        return redirect()->route('aset.index')->with('success', 'Aset berhasil dihapus.');
    }
    
 public function exportExcel($bidang_id)
{
    return Excel::download(new AsetsExport($bidang_id), 'data_aset_bidang_'.$bidang_id.'.xlsx');
}
}

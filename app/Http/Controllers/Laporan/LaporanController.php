<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Models\Aset;
use App\Models\Bidang;
use Carbon\Carbon;
use PDF;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index()
    {
        $bidangs = Bidang::all();
        $startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        return view('laporan.index', compact('startDate', 'endDate', 'bidangs'));
    }

    public function exportPdf(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'bidang_id' => 'nullable|exists:bidangs,id',
            'jenis_laporan' => 'required|in:semua,per_bidang'
        ]);

        $start = $request->start_date ? Carbon::parse($request->start_date) : null;
        $end = $request->end_date ? Carbon::parse($request->end_date) : null;

        $asets = $this->getAsets($start, $end, $request->bidang_id);

        $bidang = null;
        if ($request->bidang_id) {
            $bidang = Bidang::find($request->bidang_id);
        }

        $data = [
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'jenis_laporan' => $request->jenis_laporan,
            'bidang' => $bidang,
            'asets' => $asets,
            'total_aset' => $asets->sum('jumlah_aset'),
            'tanggal_cetak' => Carbon::now()->format('d-m-Y H:i:s')
        ];

        $pdf = PDF::loadView('laporan.export_pdf', $data)
            ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-aset-' . now()->format('Y-m-d-His') . '.pdf');
    }

    private function getAsets($start, $end, $bidang_id = null)
    {
        $query = Aset::with('bidang');

        if ($start) {
            $query->whereDate('created_at', '>=', $start);
        }
        if ($end) {
            $query->whereDate('created_at', '<=', $end);
        }
        if ($bidang_id) {
            $query->where('bidang_id', $bidang_id);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }
}
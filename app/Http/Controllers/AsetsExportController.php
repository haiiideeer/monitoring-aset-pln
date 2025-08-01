<?php
namespace App\Http\Controllers;

use App\Exports\AsetsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class AsetsExportController extends Controller
{
    public function exportExcel($bidang_id)
    {
        return Excel::download(new AsetsExport($bidang_id), 'asets-' . $bidang_id . '.xlsx');
    }
}

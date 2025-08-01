<?php

namespace App\Exports;

use App\Models\Aset;
use Maatwebsite\Excel\Concerns\FromCollection;

class AsetsExport implements FromCollection
{
    protected $bidang_id;

    public function __construct($bidang_id)
    {
        $this->bidang_id = $bidang_id;
    }

    public function collection()
    {
        return Aset::where('bidang_id', $this->bidang_id)->get();
    }
}

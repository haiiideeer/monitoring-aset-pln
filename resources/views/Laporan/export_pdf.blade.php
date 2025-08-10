<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Aset</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }

        .title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .subtitle {
            font-size: 14px;
            color: #555;
            margin-bottom: 5px;
        }

        .period {
            font-size: 12px;
            margin-bottom: 10px;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            vertical-align: top;
        }

        th {
            background-color: #f5f5f5;
            text-align: center;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .badge {
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            display: inline-block;
        }

        .badge-primary {
            background-color: #007bff;
            color: white;
        }

        .badge-secondary {
            background-color: #6c757d;
            color: white;
        }

        .summary {
            margin-top: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 4px;
            border: 1px solid #eee;
        }

        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #eee;
            text-align: right;
            font-size: 10px;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="title">LAPORAN INVENTARIS ASET</div>

        <div class="period">
            Periode: {{ $start_date ? date('d/m/Y', strtotime($start_date)) : 'Awal' }}
            s/d {{ $end_date ? date('d/m/Y', strtotime($end_date)) : 'Sekarang' }}
            | Jenis Laporan: {{ ucfirst(str_replace('_', ' ', $jenis_laporan)) }}
            @if($bidang)
                | Bidang: {{ $bidang->nama_bidang }}
            @endif
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Tanggal Input</th>
                <th width="20%">Nama Aset</th>
                <th width="15%">Bidang</th>
                <th width="15%">Lokasi</th>
                <th width="10%">Jumlah</th>

                
                
                
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($asets as $item)
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td class="text-center">{{ date('d/m/Y', strtotime($item->created_at)) }}</td>
                    <td>{{ $item->nama_aset }}</td>
                    <td>{{ $item->bidang->nama_bidang }}</td>
                    <td>{{ $item->lokasi }}</td>
                    <td class="text-center">{{ number_format($item->jumlah_aset, 0, ',', '.') }}</td>
                    
                    
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <table style="border: none;">
            <tr>
                <td width="80%" class="text-right"><strong>Total Seluruh Aset:</strong></td>
                <td width="20%" class="text-right">{{ number_format($total_aset, 0, ',', '.') }} Unit</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Dicetak pada: {{ $tanggal_cetak }}
    </div>
</body>

</html>
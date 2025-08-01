<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Aset - {{ $bidang->nama }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }
        h3 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table th, table td {
            border: 1px solid #444;
            padding: 6px;
            text-align: left;
        }
        table th {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
    <h3>Daftar Aset - {{ $bidang->nama }}</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Aset</th>
                <th>Jumlah</th>
                <th>Lokasi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($asets as $index => $aset)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $aset->nama_aset }}</td>
                <td>{{ $aset->jumlah_aset }}</td>
                <td>{{ $aset->lokasi }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Patroli Keselamatan</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        h2 {
            text-align: center;
        }
    </style>
</head>
<body>
    <h2>Laporan Patroli Keselamatan</h2>
    <p style="text-align: center;">Unit Kerja: {{ $data['tim'] }}</p>
    <p style="text-align: center;">Tanggal: {{ $data['patrol']->tanggal }}</p>
    <p style="text-align: center;">Tim Inspeksi: {{ $data['nama'] }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Temuan</th>
                <th>Keterangan</th>
                <th>Rekomendasi Perbaikan</th>
                <th>PIC</th>
                <th>Target</th>
                <th>Dokumentasi Perbaikan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data['perbaikan'] as $key => $item)
                <tr>
                    <td>{{ $data['no'][$key] }}</td>
                    <td>
                        <img src="{{ storage_path('storage/' . $item->temuan) }}" alt="Temuan" style="width:50px;height:auto;">
                    </td>
                    <td>{{ $item->keterangan }}</td>
                    <td>{{ $item->perbaikan }}</td>
                    <td>{{ $data['nama'] }}</td>
                    <td>{{ $item->target }}</td>
                    <td>
                        <img src="{{ storage_path('storage/' . $item->dokumentasi) }}" alt="Dokumentasi" style="width:50px;height:auto;">
                    </td>
                    <td>{{ $item->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

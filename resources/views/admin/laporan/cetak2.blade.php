<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Patroli Keselamatan</title>
    <style>
        @page {
            size: A4;
            margin: 15mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 100%;
            margin: 0 auto;
            text-align: center;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .header img {
            max-width: 80px;
            height: auto;
            margin-right: 15px;
        }

        .header .info {
            text-align: left;
        }

        .header .info div {
            font-size: 10px;
            line-height: 1.2;
        }

        .table-wrapper {
            max-width: 90%;
            /* Membatasi lebar tabel */
            margin: 20px auto;
            /* Memposisikan tabel di tengah */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 auto;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
            font-size: 9px;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        td img {
            max-width: 70px;
            height: auto;
            display: block;
            margin: 0 auto;
            border: 1px solid #ccc;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 9px;
            color: #777;
        }

        .status-label {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
            color: white;
            font-size: 12px;
        }

        /* Warna status */
        .status-label.danger {
            background-color: #d9534f;
            /* Warna merah */
        }

        .status-label.warning {
            background-color: #f0ad4e;
            /* Warna kuning */
        }

        .status-label.success {
            background-color: #5cb85c;
            /* Warna hijau */
        }
    </style>
</head>

<body onload="window.print()">
    <div class="container">
        <!-- Kop Surat -->
        <div class="header">
            <img src="{{ asset('img/logo.png') }}" alt="Logo Perusahaan">
            <div class="info">
                <div style="font-size: 12px; font-weight: bold;">Laporan Patroli Keselamatan</div>
                <div>Unit Kerja: {{ $tim }}</div>
                <div>Tanggal Pemeriksaan: {{ $patrol->tanggal }}</div>
                <div>Tim Inspeksi: {{ $nama }}</div>
            </div>
        </div>

        <!-- Tabel Laporan -->
        <div class="table-wrapper">
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
                    @foreach ($perbaikan as $key => $row)
                        <tr>
                            <td>{{ $no[$key] }}</td>
                            <td>
                                <img src="{{ asset('storage/' . $row->temuan) }}" alt="Temuan">
                            </td>
                            <td>{{ $row->keterangan }}</td>
                            <td>{{ $row->perbaikan }}</td>
                            <td>
                                @foreach ($user as $i)
                                    @if ($i->user_id == $row->user_id)
                                        {{ $i->name }}
                                    @endif
                                @endforeach
                            </td>
                            <td>{{ $row->target }}</td>
                            <td>
                                <img src="{{ asset('storage/' . $row->dokumentasi) }}" alt="Dokumentasi">
                            </td>
                            <td>
                                @switch($row->status)
                                    @case('Belum Dicek')
                                        <div class="status-label danger">
                                            {{ $row->status }}
                                        </div>
                                    @break

                                    @case('Setuju Admin')
                                        <div class="status-label warning">
                                            {{ $row->status }}
                                        </div>
                                    @break

                                    @case('Lolos Admin')
                                        <div class="status-label warning">
                                            {{ $row->status }}
                                        </div>
                                    @break

                                    @case('Proses')
                                        <div class="status-label warning">
                                            {{ $row->status }}
                                        </div>
                                    @break

                                    @default
                                        <div class="status-label success">
                                            {{ $row->status }}
                                        </div>
                                @endswitch
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="footer">
            Dicetak oleh sistem pada: {{ now()->format('d-m-Y H:i:s') }}
        </div>
    </div>
</body>

</html>

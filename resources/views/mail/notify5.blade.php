<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi Persetujuan Admin</title>
</head>
<body>
    <h1>Notifikasi Persetujuan Admin</h1>
    <p>Data Perbaikan telah disetujui oleh Admin.</p>
    <ul>
        <li>Tanggal: {{ $details['tanggal'] }}</li>
        <li>Status: {{ $details['status'] }}</li>
    </ul>
    <p>Silakan cek sistem untuk proses persetujuan.</p>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi Persetujuan Semua</title>
</head>
<body>
    <h1>Notifikasi Persetujuan Semua</h1>
    <p>Data Perbaikan telah disetujui oleh Manager.</p>
    <ul>
        <li>Tanggal: {{ $details['tanggal'] }}</li>
        <li>Status: {{ $details['status'] }}</li>
    </ul>
    <p>Silakan cek sistem untuk melihatnya.</p>
</body>
</html>

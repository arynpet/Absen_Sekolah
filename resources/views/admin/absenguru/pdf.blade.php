<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Absensi Guru</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #333;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
        }
        h3 {
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h3>Data Absensi Guru</h3>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Guru</th>
                <th>Mata Pelajaran</th>
                <th>Status Kehadiran</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kehadiran as $k)
            <tr>
                <td>{{ \Carbon\Carbon::parse($k->tanggal)->format('d-m-Y') }}</td>
                <td>{{ $k->guru->nama_guru ?? '-' }}</td>
                <td>{{ $k->mataPelajaran->nama_mata_pelajaran ?? '-' }}</td>
                <td>{{ $k->status ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

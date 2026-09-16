<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
    <style>
        /* INI ADALAH KODE BACKGROUND & STYLE */
        body {
            background-color: #eef2f7; /* Warna background abu-abu kebiruan */
            font-family: sans-serif;
            margin: 0;
            padding: 20px;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            max-width: 900px;
            margin: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th { background-color: #f8f9fa; }
    </style>
</head>
<body>

<div class="container">
    <h2>Data Transaksi Masuk</h2>
    <table>
        <tr style="background: #f4f4f4;">
            <th>ID Transaksi</th>
            <th>Nominal</th>
            <th>Bukti</th>
            <th>Status</th>
            <th>Aksi</th>
            <th>Cetak</th> <!-- Tambahkan kolom baru -->
        </tr>
        @foreach($transaksis as $t)
        <tr>
            <td>{{ $t->trx_id }}</td>
            <td>{{ $t->nominal }}</td>
            <td>
                <a href="{{ asset('bukti/'.$t->bukti_transfer) }}" target="_blank">Lihat Gambar</a>
            </td>
            <td>{{ $t->status }}</td>
            <td>
                @if($t->status == 'pending')
                    <form action="/admin/konfirmasi/{{ $t->id }}" method="POST">
                        @csrf
                        <button type="submit">Konfirmasi</button>
                    </form>
                @else
                    <b>Selesai</b>
                @endif
            </td>
            <td>
                <!-- Tombol Cetak hanya muncul jika status sudah selesai -->
                @if($t->status != 'pending')
                    <a href="{{ url('/admin/cetak/'.$t->id) }}" target="_blank" style="text-decoration:none; color:blue;">Print Struk</a>
                @else
                    -
                @endif
            </td>
        </tr>
        @endforeach
    </table>
</div>
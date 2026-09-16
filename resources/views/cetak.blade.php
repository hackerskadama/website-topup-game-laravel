<!DOCTYPE html>
<html>
<head>
    <title>Struk - {{ $transaksi->trx_id }}</title>
    <style>
        /* Paksa printer untuk tidak memberikan margin bawaan */
        @page { size: 58mm auto; margin: 0; }
        
        body { 
            font-family: 'Courier New', Courier, monospace; 
            font-size: 12px; 
            margin: 0; 
            padding: 5px; 
            width: 100%;
        }
        
        .struk { 
            width: 100%; 
            max-width: 220px; 
        }
        
        .center { text-align: center; }
        
        .row { 
            display: flex; 
            justify-content: space-between; 
            margin-bottom: 3px; 
        }
        
        hr { border: 0; border-top: 1px dashed #000; margin: 5px 0; }
        
        h3 { margin: 0; font-size: 16px; }
        h4 { margin: 5px 0; font-size: 13px; }
    </style>
</head>
<body onload="window.print()">
    <div class="struk">
        <!-- Nama Toko Anda -->
        <h3 class="center">BAYY STORE</h3>
        <p class="center" style="font-size: 10px; margin: 0;">John Doe1234 Magnolia CourtHouston,texas, united states</p>
        
        <hr>
        <h4 class="center">BUKTI SUKSES</h4>
        <hr>
        
        <div class="row"><span>Status:</span> <b>BERHASIL</b></div>
        <div class="row"><span>ID:</span> {{ $transaksi->trx_id }}</div>
        <div class="row"><span>Waktu:</span> {{ \Carbon\Carbon::parse($transaksi->updated_at)->timezone('Asia/Jakarta')->format('d/m/y H:i') }}</div>
        <hr>
        
        <div class="row"><span>Produk:</span> {{ $transaksi->game_name }}</div>
        <div class="row"><span>Detail:</span> {{ $transaksi->nominal }}</div>
        <hr>
        
        <div class="row"><span>Total Bayar:</span> <b>Rp {{ number_format($transaksi->price, 0, ',', '.') }}</b></div>
        <hr>
        
        <p class="center">Terima Kasih</p>
    </div>
</body>
</html>
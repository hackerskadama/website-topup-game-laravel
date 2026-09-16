<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Top Up {{ $gameName }}</title>
    <style>
        /* Tampilan Utama - Dioptimalkan untuk HP & Desktop */
        body { 
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; 
            background: #0f172a; 
            color: white; 
            display: flex; 
            justify-content: center; 
            padding: 20px 12px; 
            margin: 0; 
        }
        .container { width: 100%; max-width: 480px; } /* Lebar diperpas untuk HP */
        
        .section-box { 
            background: #1e293b; 
            padding: 16px; 
            border-radius: 16px; 
            border: 1px solid #334155; 
            margin-bottom: 14px; 
        }
        
        .game-header { display: flex; align-items: center; gap: 12px; font-size: 1.3rem; font-weight: bold; color: #e4ebed; }
        .game-logo { width: 48px; height: 48px; object-fit: cover; border-radius: 10px; }
        
        label.input-label { font-size: 0.95rem; font-weight: bold; color: #cbd5e1; display: block; margin-bottom: 8px; }
        
        input[type="text"] { 
            width: 100%; 
            padding: 14px; 
            border-radius: 12px; 
            border: 1px solid #334155; 
            background: #0f172a; 
            color: white; 
            font-size: 1rem; 
            box-sizing: border-box; 
            outline: none; 
        }
        input[type="text"]:focus { border-color: #38bdf8; }

        /* Daftar Harga Memanjang ke Bawah */
        .price-list { display: flex; flex-direction: column; gap: 10px; margin-top: 10px; }
        
        .price-item { 
            background: #0f172a; 
            padding: 14px 16px; 
            border-radius: 12px; 
            border: 1px solid #334155; 
            cursor: pointer; 
            transition: 0.2s; 
            display: flex; 
            flex-direction: column; 
            gap: 2px; 
        }
        .price-item:hover { border-color: #38bdf8; background: #1e293b; }
        
        input[type="radio"]:checked + .price-item { 
            border-color: #22c55e; 
            background: #064e3b; 
        }
        
        .btn-submit { 
            width: 100%; 
            padding: 16px; 
            background: #22c55e; 
            border: none; 
            border-radius: 12px; 
            color: white; 
            font-weight: bold; 
            font-size: 1.05rem; 
            cursor: pointer; 
            margin-top: 10px; 
            transition: 0.2s; 
        }
        .btn-submit:hover { background: #16a34a; }
        
        .btn-back { display: inline-block; margin-bottom: 15px; color: #94a3b8; text-decoration: none; font-size: 0.95rem; cursor: pointer; }
        .btn-back:hover { color: white; }

        /* --- STYLING LOADING OVERLAY --- */
        #loader-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.92);
            backdrop-filter: blur(6px);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 99999;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease;
        }
        #loader-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        .loader-icon {
            width: 70px;
            height: 70px;
            margin-bottom: 16px;
            animation: pulseIcon 1s infinite alternate ease-in-out;
        }
        .loader-line-container {
            width: 180px;
            height: 4px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 12px;
            position: relative;
        }
        .loader-line-container::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, #38bdf8, transparent);
            animation: moveLine 1.1s infinite linear;
        }
        .loader-text {
            color: #cbd5e1;
            font-size: 1rem;
            font-weight: 500;
            letter-spacing: 1px;
        }

        @keyframes pulseIcon {
            0% { transform: scale(0.95); opacity: 0.8; }
            100% { transform: scale(1.08); opacity: 1; filter: drop-shadow(0 0 8px rgba(56, 189, 248, 0.6)); }
        }
        @keyframes moveLine {
            0% { left: -100%; }
            100% { left: 100%; }
        }
    </style>
</head>
<body>

<!-- ELEMEN LOADING OVERLAY -->
<div id="loader-overlay">
    <svg class="loader-icon" viewBox="0 0 24 24" fill="#38bdf8">
        <path d="M6 2L2 7L12 22L22 7L18 2H6Z" stroke="white" stroke-width="1.2" stroke-linejoin="round"/>
        <path d="M2 7H22" stroke="#0f172a" stroke-width="1"/>
        <path d="M6 2L12 22L18 2" stroke="#0f172a" stroke-width="0.8"/>
    </svg>
    <div class="loader-line-container"></div>
    <div class="loader-text" id="loaderText">MEMUAT...</div>
</div>

<div class="container">
    <!-- Tombol kembali menggunakan link normal -->
    <a href="{{ secure_url('/topup') }}" id="btnBack" class="btn-back">&larr; Kembali ke Pilih Game</a>

    <!-- Menggunakan secure_url agar aman dari warning Ngrok HTTPS -->
    <form id="formTopup" action="{{ secure_url('/topup/pilih-pembayaran') }}" method="POST">
        @csrf
        <input type="hidden" name="game_name" value="{{ $gameName }}">

        <!-- Info Game -->
        <div class="section-box">
            <div class="game-header">
                <img src="{{ asset($gameData['logo']) }}" class="game-logo" alt="{{ $gameName }}"> 
                <span>Top Up {{ $gameName }}</span>
            </div>
        </div>

        <!-- Input User ID -->
        <div class="section-box">
            <label class="input-label">Masukkan User ID Game</label>
            <input type="text" name="user_id" id="userId" placeholder="Contoh: 12345678 (1234)" required>
        </div>

        <!-- Daftar Harga -->
        <div class="section-box">
            <label class="input-label">Pilih Nominal Diamond</label>
            <div class="price-list">
                @foreach($gameData['items'] as $item)
                <label style="cursor: pointer;">
                    <input type="radio" name="nominal" value="{{ $item['label'] }}" required style="display:none">
                    <div class="price-item">
                        <span style="font-size: 1rem; font-weight: bold; color: white;">{{ $item['label'] }}</span>
                        <span style="font-size: 0.85rem; color: #94a3b8;">TPG Diamond {{ $gameName }}</span>
                        <b style="font-size: 1.1rem; color: #22c55e; margin-top: 2px;">Rp {{ number_format($item['price'], 0, ',', '.') }}</b>
                    </div>
                </label>
                @endforeach
            </div>
        </div>

        <button type="submit" id="btnSubmit" class="btn-submit">Lanjut ke Pembayaran</button>
    </form>
</div>

<script>
    const loader = document.getElementById('loader-overlay');
    const loaderText = document.getElementById('loaderText');

    // 1. Tombol Kembali
    document.getElementById('btnBack').addEventListener('click', function(e) {
        e.preventDefault();
        const url = this.getAttribute('href');
        
        loaderText.innerText = "KEMBALI...";
        loader.classList.add('active');

        setTimeout(() => {
            window.location.href = url;
        }, 300);
    });

    // 2. Submit Form Pembayaran
    document.getElementById('formTopup').addEventListener('submit', function() {
        const submitBtn = document.getElementById('btnSubmit');
        submitBtn.disabled = true;
        
        loaderText.innerText = "PROSES PEMBAYARAN...";
        loader.classList.add('active');
    });

    // 3. Menangkap event tombol Back dari browser
    window.addEventListener('pageshow', function(event) {
        if (event.persisted || (performance.getEntriesByType("navigation")[0] && performance.getEntriesByType("navigation")[0].type === "back_forward")) {
            loader.classList.remove('active');
        }
    });
</script>

</body>
</html>
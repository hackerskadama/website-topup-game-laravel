<!DOCTYPE html>
<html lang="id">
<head>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Pembayaran Top-Up</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background: #0f172a; color: #f8fafc; margin: 0; padding: 15px; }
        .container { max-width: 450px; margin: auto; position: relative; z-index: 10; }
        
        .btn-back-ajax { 
            background: transparent; 
            border: none; 
            color: #94a3b8; 
            text-decoration: none; 
            font-size: 1rem; 
            cursor: pointer; 
            padding: 10px 0; 
            margin-bottom: 10px; 
            display: inline-block; 
            font-weight: 500; 
            position: relative;
            z-index: 999999;
        }
        .btn-back-ajax:hover { color: white; }

        h2 { font-size: 1.25rem; margin-bottom: 15px; color: #f6f9fa; }
        .card { background: #1e293b; padding: 18px; border-radius: 16px; margin-bottom: 15px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .bank-item { background: #334155; padding: 12px; margin-bottom: 8px; border-radius: 10px; display: flex; justify-content: space-between; align-items: center; }
        .bank-name { font-weight: bold; color: #fff; }
        .bank-number { color: #94a3b8; font-family: monospace; font-size: 1.1rem; }
        
        #qris-container { display: none; text-align: center; margin-top: 15px; padding: 15px; background: white; border-radius: 10px; }
        #qris-container img { width: 100%; max-width: 200px; }

        label { display: block; margin-bottom: 8px; font-size: 0.9rem; color: #cbd5e1; }
        select, input[type="file"] { width: 100%; padding: 12px; background: #0f172a; border: 1px solid #475569; border-radius: 10px; color: white; box-sizing: border-box; }
        .btn-success { width: 100%; padding: 14px; background: #069644; border: none; border-radius: 12px; color: white; font-weight: bold; cursor: pointer; font-size: 1rem; margin-top: 15px; transition: 0.3s; }
        
        .wa-float { position: fixed; bottom: 20px; right: 20px; background: #25d366; color: white; padding: 12px 20px; border-radius: 50px; text-decoration: none; font-weight: bold; font-size: 0.9rem; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(37, 211, 102, 0.3); z-index: 1000; }

        /* --- STYLING LOADING OVERLAY --- */
        #loader-overlay { 
            position: fixed; 
            top: 0; 
            left: 0; 
            width: 100%; 
            height: 100%; 
            background: rgba(15, 23, 42, 0.92); 
            backdrop-filter: blur(8px); 
            display: flex; 
            flex-direction: column; 
            align-items: center; 
            justify-content: center; 
            z-index: 9999999; 
            opacity: 0; 
            visibility: hidden; 
            transition: opacity 0.4s ease-in-out; 
        }
        #loader-overlay.active { 
            opacity: 1; 
            visibility: visible; 
        }
        .loader-icon { 
            width: 75px; 
            height: 75px; 
            margin-bottom: 20px; 
            animation: pulseIcon 1s infinite alternate ease-in-out; 
        }
        .loader-line-container { 
            width: 220px; 
            height: 4px; 
            background: rgba(255, 255, 255, 0.15); 
            border-radius: 10px; 
            overflow: hidden; 
            margin-bottom: 15px; 
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
            font-size: 0.95rem; 
            font-weight: 600; 
            letter-spacing: 0.5px; 
            text-align: center; 
            max-width: 280px; 
            padding: 0 15px; 
            line-height: 1.4; 
        }

        @keyframes pulseIcon { 
            0% { transform: scale(0.95); opacity: 0.8; } 
            100% { transform: scale(1.08); opacity: 1; filter: drop-shadow(0 0 10px rgba(56, 189, 248, 0.8)); } 
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
    <div class="loader-text" id="loader-message">Memuat...</div>
</div>

<div class="container">
    <a href="<?php echo e(url('/topup/' . ($gameName ?? session('order_data.game_name', 'MLBB')))); ?>" id="btnBackToDetail" class="btn-back-ajax">&larr; Kembali ke Pilih Nominal</a>

    <h2>Instruksi Pembayaran</h2>
    
    <div class="card">
        <p style="margin-top:0; font-size: 0.9rem;">Transfer sesuai nominal ke rekening berikut:</p>
        <?php $__currentLoopData = $metode_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bank => $nomor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bank-item">
                <span class="bank-name"><?php echo e($bank); ?></span>
                <span class="bank-number"><?php echo e($nomor); ?></span>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <div class="bank-item">
            <span class="bank-name">QRIS</span>
            <span class="bank-number">Scan di bawah</span>
        </div>
    </div>

    <form id="formPembayaran" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <div class="card">
            <label>Metode yang Anda gunakan:</label>
            <select name="metode_dipilih" id="metode_select" onchange="toggleQRIS()" required>
                <option value="">-- Pilih Metode --</option>
                <?php $__currentLoopData = $metode_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bank => $nomor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($bank); ?>"><?php echo e($bank); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <option value="QRIS">QRIS</option>
            </select>

            <div id="qris-container">
                <p style="color: black; font-weight: bold; margin-bottom: 5px;">Scan QRIS ini:</p>
                <img src="<?php echo e(asset('QR.jpg')); ?>" alt="QRIS Code">
            </div>

            <div style="margin-top: 15px;">
                <label>Upload Bukti Transfer:</label>
                <input type="file" name="bukti" accept="image/*" required>
            </div>
            
            <button type="submit" class="btn-success">Konfirmasi Pembayaran</button>
        </div>
    </form>
</div>

<a href="https://wa.me/6289696300447" class="wa-float" target="_blank">
    💬 Tanya CS
</a>

<script>
    const loader = document.getElementById('loader-overlay');
    const loaderText = document.getElementById('loader-message');

    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            loader.classList.remove('active');
        }
    });

    function toggleQRIS() {
        const select = document.getElementById('metode_select');
        const qris = document.getElementById('qris-container');
        qris.style.display = (select.value === 'QRIS') ? 'block' : 'none';
    }

    document.getElementById('btnBackToDetail').addEventListener('click', function(e) {
        e.preventDefault();
        const targetUrl = this.getAttribute('href');
        
        loaderText.innerText = "Kembali ke Menu...";
        loader.classList.add('active');

        setTimeout(function() {
            window.location.href = targetUrl;
        }, 600);
    });

    document.getElementById('formPembayaran').addEventListener('submit', function(e) {
        e.preventDefault();
        
        let btn = document.querySelector('.btn-success');
        btn.innerText = "OTW PROSES...";
        btn.disabled = true;
        
        loaderText.innerText = "MENGHUBUNGKAN KE ADMIN PEMBAYARAN...";
        loader.classList.add('active');

        let formData = new FormData(this);

        fetch('/topup/kirim-bukti', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = data.url;
            } else {
                loader.classList.remove('active');
                alert(data.message || "Gagal memproses");
                btn.innerText = "Konfirmasi Pembayaran";
                btn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            loader.classList.remove('active');
            alert("Terjadi kesalahan koneksi!");
            btn.innerText = "Konfirmasi Pembayaran";
            btn.disabled = false;
        });
    });
</script>

</body>
</html><?php /**PATH C:\laragon\www\topup-game\resources\views/pembayaran.blade.php ENDPATH**/ ?>
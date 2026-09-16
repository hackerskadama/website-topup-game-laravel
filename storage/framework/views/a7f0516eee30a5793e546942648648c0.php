<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
    <title>Top Up Center - PILIH Game</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: #0f172a; 
            color: white; 
            margin: 0; 
            padding: 20px 15px; 
            display: flex; 
            justify-content: center; 
            touch-action: pan-x pan-y; 
            -webkit-text-size-adjust: 100%;
        }
        .container { 
            width: 100%; 
            max-width: 480px; 
        }

        h2.title { 
            text-align: center; 
            color: #fafcfd; 
            font-size: 1.8rem; 
            font-weight: 800; 
            margin-bottom: 20px; 
            letter-spacing: 1px;
        }
        
        .card { 
            background: #1e293b; 
            padding: 12px 16px; 
            border-radius: 14px; 
            border: 1px solid #334155; 
            margin-bottom: 12px; 
            cursor: pointer; 
            transition: 0.2s; 
            display: flex; 
            align-items: center; 
            justify-content: space-between; 
            text-decoration: none; 
            color: white; 
        }
        .card:hover { 
            border-color: #38bdf8; 
            background: #252f45; 
        }
        
        .game-header { 
            display: flex; 
            align-items: center; 
            gap: 15px; 
            font-size: 1.1rem; 
            font-weight: 600; 
        }
        .game-logo { 
            width: 45px; 
            height: 45px; 
            object-fit: cover; 
            border-radius: 10px; 
        }
        .arrow { 
            font-size: 1.2rem; 
            color: #38bdf8; 
            font-weight: bold; 
        }

        /* --- STYLING LOADING OVERLAY --- */
        #loader-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.88);
            backdrop-filter: blur(6px);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease;
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
            width: 200px;
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
            font-size: 1.1rem;
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
    <div class="loader-text" id="loaderText">DAFTAR LIST GAME...</div>
</div>

<div class="container">
    <h2 class="title">DAFTAR GAME</h2>
    
    <?php $__currentLoopData = $games; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="card game-card" data-url="<?php echo e(route('topup.game', $name)); ?>">
        <div class="game-header">
            <img src="<?php echo e(asset($data['logo'])); ?>" class="game-logo" alt="<?php echo e($name); ?>"> 
            <span><?php echo e($name); ?></span>
        </div>
        <div class="arrow">&rarr;</div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<script>
    const loader = document.getElementById('loader-overlay');
    const loaderText = document.getElementById('loaderText');

    document.querySelectorAll('.game-card').forEach(card => {
        card.addEventListener('click', function() {
            const url = this.getAttribute('data-url');
            const gameTitle = this.querySelector('.game-header span').innerText;
            
            loaderText.innerText = "DAFTSR LIST " + gameTitle.toUpperCase() + "...";
            loader.classList.add('active');

            setTimeout(() => {
                window.location.href = url;
            }, 300);
        });
    });

    window.addEventListener('pageshow', function(event) {
        if (event.persisted || (performance.getEntriesByType("navigation")[0] && performance.getEntriesByType("navigation")[0].type === "back_forward")) {
            loader.classList.remove('active');
        }
    });

    // Mencegah double-tap zoom
    let lastTouchEnd = 0;
    document.addEventListener('touchend', function (event) {
        let now = (new Date()).getTime();
        if (now - lastTouchEnd <= 300) {
            event.preventDefault();
        }
        lastTouchEnd = now;
    }, false);

    // Mencegah pinch-to-zoom dengan 2 jari
    document.addEventListener('touchmove', function (event) {
        if (event.touches.length > 1) {
            event.preventDefault();
        }
    }, { passive: false });
</script>

</body>
</html><?php /**PATH C:\laragon\www\topup-game\resources\views/topup.blade.php ENDPATH**/ ?>
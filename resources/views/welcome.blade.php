<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Up Center</title>
    <style>
        body { 
            font-family: 'Segoe UI', sans-serif; 
            background-color: #0f172a; 
            color: white; 
            margin: 0; 
            padding: 40px 20px;
        }

        .container { 
            width: 100%; 
            max-width: 800px; 
            margin: 0 auto;
        }

        h2 { text-align: center; color: #38bdf8; font-size: 2.5rem; margin-bottom: 30px; }

        .input-box { 
            width: 100%; 
            padding: 25px; 
            margin-bottom: 40px; 
            border-radius: 20px; 
            border: 2px solid #334155; 
            background: #1e293b; 
            color: white; 
            font-size: 1.5rem; 
            box-sizing: border-box;
        }

        /* Styling Harga */
        .game-section { margin-bottom: 40px; }
        .game-section h3 { font-size: 2rem; color: #f1f5f9; margin-bottom: 20px; }

        .price-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); 
            gap: 20px; 
        }

        .price-card { 
            background: #1e293b; 
            padding: 30px; 
            border-radius: 20px; 
            text-align: center; 
            border: 2px solid #334155; 
            cursor: pointer;
            font-size: 1.3rem;
            transition: 0.3s;
        }

        .price-card:hover { border-color: #38bdf8; background: #2d3e5a; }
    </style>
</head>
<body>

<div class="container">
    <h2>Top Up Center</h2>
    
    <input type="text" class="input-box" placeholder="Masukkan User ID Game...">

    <!-- MLBB -->
    <div class="game-section">
        <h3>Mobile Legends</h3>
        <div class="price-grid">
            <div class="price-card">86 Diamond - Rp15.000</div>
            <div class="price-card">172 Diamond - Rp30.000</div>
            <div class="price-card">257 Diamond - Rp45.000</div>
        </div>
    </div>

    <!-- Free Fire -->
    <div class="game-section">
        <h3>Free Fire</h3>
        <div class="price-grid">
            <div class="price-card">100 Diamond - Rp12.000</div>
            <div class="price-card">200 Diamond - Rp24.000</div>
            <div class="price-card">500 Diamond - Rp60.000</div>
        </div>
    </div>
</div>

</body>
</html>
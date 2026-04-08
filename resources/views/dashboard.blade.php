<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>サーバ監視</title>
    <style>
        body { background: #f0f2f5; font-family: 'Segoe UI', sans-serif; display: flex; gap: 20px; justify-content: center; padding: 40px; }
        
        /* カードの見た目を定義 */
        .panel { 
            background: white; 
            width: 180px; 
            height: 420px; 
            border-radius: 12px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.1); 
            display: flex; 
            flex-direction: column; 
            border: 1px solid #e0e0e0;
            overflow: hidden; /* カードからもはみ出さないように */
        }

        .info { padding: 20px 10px; text-align: center; border-bottom: 1px solid #f5f5f5; }
        .label { font-size: 0.75rem; color: #888; font-weight: bold; margin-bottom: 5px; }
        .val { font-size: 2rem; font-weight: bold; font-family: 'Consolas', monospace; }

        /* グラフエリア：白背景をカードの端まで広げる */
        .graph { 
            flex-grow: 1; 
            background: #ffffff; 
            position: relative; 
            margin: 0; 
            border-top: 1px solid #eee;
        }

        /* SVG：白背景の上で動くように設定 */
        svg { 
            width: 100%; 
            height: 100%; 
            display: block; 
            box-sizing: border-box;
            overflow: visible; /* 線が天井を超えても描画は続ける */
        }

        /* 折れ線の設定 */
        polyline { 
            fill: none; 
            stroke-width: 3; 
            stroke-linecap: round; 
            stroke-linejoin: round; 
            transition: all 0.3s ease; 
        }

        /* 文字と線の色設定 */
        .cpu-color { color: #3182ce; } .cpu-stroke { stroke: #3182ce; }
        .mem-color { color: #48bb78; } .mem-stroke { stroke: #48bb78; }
        .disk-color { color: #ed8936; } .disk-stroke { stroke: #ed8936; }
    </style>
</head>
<body>

    <div class="panel">
        <div class="info"><div class="label">CPU LOAD</div><div id="cpu-num" class="val cpu-color">0.0</div></div>
        <div class="graph">
            <svg viewBox="0 0 100 300" preserveAspectRatio="none">
                <polyline id="cpu-line" class="cpu-stroke" points="0,300"></polyline>
            </svg>
        </div>
    </div>

    <div class="panel">
        <div class="info"><div class="label">MEMORY</div><div id="mem-num" class="val mem-color">0.0</div></div>
        <div class="graph">
            <svg viewBox="0 0 100 300" preserveAspectRatio="none">
                <polyline id="mem-line" class="mem-stroke" points="0,300"></polyline>
            </svg>
        </div>
    </div>

    <div class="panel">
        <div class="info"><div class="label">DISK USAGE</div><div id="disk-num" class="val disk-color">0.0</div></div>
        <div class="graph">
            <svg viewBox="0 0 100 300" preserveAspectRatio="none">
                <polyline id="disk-line" class="disk-stroke" points="0,300"></polyline>
            </svg>
        </div>
    </div>

    <script>
        const limit = 40;
        const vMax = 150; 
        let history = { cpu: Array(limit).fill(0), memory: Array(limit).fill(0), disk: Array(limit).fill(0) };

        function update() {
            fetch('/api/stats')
                .then(res => res.json())
                .then(data => {
                    refresh('cpu', data.cpu, 'cpu-num', 'cpu-line');
                    refresh('memory', data.memory, 'mem-num', 'mem-line');
                    refresh('disk', data.disk, 'disk-num', 'disk-line');
                })
                .catch(e => console.error("データ取得エラー:", e));
        }

        function refresh(key, val, nId, lId) {
            const v = parseFloat(val) || 0;
            document.getElementById(nId).innerText = v.toFixed(1);

            history[key].push(v);
            history[key].shift();

            const pts = history[key].map((yVal, i) => {
                const x = (i / (limit - 1)) * 100;
                // 天井(0)から底(300)の間で正しく計算
                const y = 300 - (yVal / vMax * 300); 
                return `${x},${y}`;
            }).join(' ');

            document.getElementById(lId).setAttribute('points', pts);
        }

        setInterval(update, 1000);
        update();
    </script>
</body>
</html>

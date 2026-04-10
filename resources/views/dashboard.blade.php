<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>サーバ監視</title>
    <style>
        body { background: #f0f2f5; font-family: 'Segoe UI', sans-serif; display: flex; gap: 20px; justify-content: center; padding: 40px; }
        .panel { 
            background: white; width: 180px; height: 420px; border-radius: 12px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.1); display: flex; flex-direction: column; 
            border: 1px solid #e0e0e0; overflow: hidden; 
        }
        .info { padding: 20px 10px; text-align: center; border-bottom: 1px solid #f5f5f5; }
        .label { font-size: 0.75rem; color: #888; font-weight: bold; margin-bottom: 5px; }
        .val { font-size: 2rem; font-weight: bold; font-family: 'Consolas', monospace; }
        .graph { flex-grow: 1; background: #ffffff; position: relative; border-top: 1px solid #eee; }
        svg { width: 100%; height: 100%; display: block; }
        polyline { 
            fill: none; 
            stroke-width: 1.5; 
            stroke-linecap: round; 
            stroke-linejoin: round; 
            vector-effect: non-scaling-stroke; 
        }
        .cpu-color { color: #3182ce; }
        .mem-color { color: #48bb78; }
        .disk-color { color: #ed8936; }
    </style>
</head>
<body>

    <div class="panel">
        <div class="info"><div class="label">CPU LOAD</div><div id="cpu-num" class="val cpu-color">0.0</div></div>
        <div class="graph">
            <svg viewBox="0 0 100 100" preserveAspectRatio="none">
                <line x1="0" y1="100" x2="100" y2="100" stroke="#ddd" stroke-width="1" />
                <polyline id="cpu-line" stroke="#3182ce"></polyline>
            </svg>
        </div>
    </div>

    <div class="panel">
        <div class="info"><div class="label">MEMORY</div><div id="mem-num" class="val mem-color">0.0</div></div>
        <div class="graph">
            <svg viewBox="0 0 100 100" preserveAspectRatio="none">
                <line x1="0" y1="100" x2="100" y2="100" stroke="#ddd" stroke-width="1" />
                <polyline id="mem-line" stroke="#48bb78"></polyline>
            </svg>
        </div>
    </div>

    <div class="panel">
        <div class="info"><div class="label">DISK USAGE</div><div id="disk-num" class="val disk-color">0.0</div></div>
        <div class="graph">
            <svg viewBox="0 0 100 100" preserveAspectRatio="none">
                <line x1="0" y1="100" x2="100" y2="100" stroke="#ddd" stroke-width="1" />
                <polyline id="disk-line" stroke="#ed8936"></polyline>
            </svg>
        </div>
    </div>

    <script>
        const limit = 40;
        let history = { cpu: [], memory: [], disk: [] };

        function update() {
            fetch('/api/stats')
                .then(res => res.json())
                .then(data => {
                    refresh('cpu', data.cpu, 'cpu-num', 'cpu-line', 200);
                    refresh('memory', data.memory, 'mem-num', 'mem-line', 100);
                    refresh('disk', data.disk, 'disk-num', 'disk-line', 100);
                })
                .catch(e => console.error("データ取得エラー:", e));
        }

        function refresh(key, val, nId, lId, mVal) {
            const v = parseFloat(val) || 0;
            const numEl = document.getElementById(nId);
            if (numEl) numEl.innerText = v.toFixed(1);

            history[key].push(v);
            if (history[key].length > limit) history[key].shift();

            const len = history[key].length;
            const pts = history[key].map((yVal, i) => {
                const x = len > 1 ? (i / (len - 1)) * 100 : 0;
                const y = 100 - (Math.min(yVal, mVal) / mVal * 100);
                return x + "," + y;
            }).join(' ');

            const line = document.getElementById(lId);
            if (line) line.setAttribute('points', pts);
        }

        setInterval(update, 1000);
        update();
    </script>
</body>
</html>

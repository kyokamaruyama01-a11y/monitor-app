<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>サーバ監視ダッシュボード</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .dashboard { display: flex; gap: 20px; }
        .card { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); text-align: center; width: 180px; }
        .label { color: #666; font-size: 0.9rem; margin-bottom: 10px; }
        .value { font-size: 2rem; font-weight: bold; color: #3182ce; }
        .unit { font-size: 1rem; color: #888; }
        .status-ok { color: #48bb78; font-size: 0.8rem; margin-top: 5px; }
    </style>
    <!-- refreshのメタタグは削除しました（Ajaxを使うため） -->
</head>
<body>
    <div class="dashboard">
        <div class="card">
            <div class="label">💻 CPU負荷</div>
            <div class="value"><span id="cpu-value">{{ $cpu }}</span><span class="unit"> %</span></div>
            <div class="status-ok">● Running</div>
        </div>
        <div class="card">
            <div class="label">🧠 メモリ使用率</div>
            <div class="value"><span id="memory-value">{{ $memory }}</span><span class="unit"> %</span></div> <!-- MBから%に変更 -->
            <div class="status-ok">● Active</div>
        </div>
        <div class="card">
            <div class="label">💾 ディスク使用率</div>
            <div class="value"><span id="disk-value">{{ $disk }}</span><span class="unit"> %</span></div>
            <div class="status-ok">● Healthy</div>
        </div>
    </div>
    <script>
    function updateStats(){
        fetch('/api/stats') 
            .then(response => response.json())
            .then(data => {
                
                document.getElementById('cpu-value').innerText = data.cpu;
                document.getElementById('memory-value').innerText = data.memory;
                document.getElementById('disk-value').innerText = data.disk;
                console.log("Stats updated:", data); // 確認用
            })
            .catch(error => console.error('Error:', error));
    }
    // 3秒ごとに実行
    setInterval(updateStats, 3000);
    </script>
</body>
</html>

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
    <meta http-equiv="refresh" content="3"> <!-- 3秒ごとに更新 -->
</head>
<body>
    <div class="dashboard">
        <div class="card">
            <div class="label">💻 CPU負荷</div>
            <div class="value">{{ $cpu }}<span class="unit"> %</span></div>
            <div class="status-ok">● Running</div>
        </div>
        <div class="card">
            <div class="label">🧠 メモリ</div>
            <div class="value">{{ $memory }}<span class="unit"> MB</span></div>
            <div class="status-ok">● Active</div>
        </div>
        <div class="card">
            <div class="label">💾 ディスク使用率</div>
            <div class="value">{{ $disk }}<span class="unit"> %</span></div>
            <div class="status-ok">● Healthy</div>
        </div>
    </div>
</body>
</html>

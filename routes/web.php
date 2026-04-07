<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // 1. CPU負荷 (Linux用: 直近1分間の平均)
    $load = sys_getloadavg();
    $cpuLoad = isset($load[0]) ? ($load[0] * 100) : "N/A";

    // 2. メモリ使用量 (MB)
    $memUsage = round(memory_get_usage(true) / 1024 / 1024, 2);

    // 3. ディスク使用率 (Codespacesのルートディレクトリ)
    $total = disk_total_space("/");
    $free = disk_free_space("/");
    $diskPercent = round((($total - $free) / $total) * 100, 1);

    return view('dashboard', [
        'cpu' => $cpuLoad,
        'memory' => $memUsage,
        'disk' => $diskPercent,
    ]);
});

<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

class MonitorController extends Controller {
    public function index() {
        $load = sys_getloadavg();
        
        //メモリ（サーバ全体を計算）
        $memInfo = file_get_contents("/proc/meminfo");
        preg_match_all('/(?<name>\w+):\s+(?<value>\d+)\s/',$memInfo,$matches);
        $mem = array_combine($matches['name'], $matches['value']);
        $totalMemKb = (float)$mem['MemTotal'];
        $availMemKb = (float)$mem['MemAvailable'];
        $memoryPercent = (($totalMemKb - $availMemKb) / $totalMemKb) * 100;

        //ディスク
        $diskPercent = (1 - disk_free_space("/") / disk_total_space("/")) * 100;

        return view('dashboard', [
             'cpu' => number_format((float)$load * 100, 1),
            'memory' => number_format($memoryPercent, 1), 
            'disk' => number_format($diskPercent, 1)
        ]);
    }

    public function api(){
        //Linux用：CPU使用率（直近１分間のロードアベレージ）を取得
        $cpuLoad = sys_getloadavg()[0];

        //メモリ（サーバ全体）
        $memInfo = file_get_contents("/proc/meminfo");

        //正規表現で「項目名：数値」を抽出
        preg_match_all('/(?<name>\w+):\s+(?<value>\d+)\s/', $memInfo, $matches);
        $mem = array_combine($matches['name'], $matches['value']);

        //すべて「KB」単位で入っているのでfloatにキャスト
        $totalMemKb = (float)$mem['MemTotal'];
        $availMemKb = (float)$mem['MemAvailable'];

        //使用率を計算
        $usedMemKb = $totalMemKb - $availMemKb;
        $memoryPercent = ($usedMemKb / $totalMemKb) * 100;

        //ディスク
        $diskPercent = (1 - disk_free_space("/") / disk_total_space("/")) * 100;

        return response()->json([
            //number_format()で小数第一位までの文字に変換し、文字として送る
            'cpu' => number_format((float)$cpuLoad * 100, 1),
            'memory' => number_format($memoryPercent, 1),
            'disk' => number_format((1 - disk_free_space("/") / disk_total_space("/")) * 100,1)
        ]);
    }
}


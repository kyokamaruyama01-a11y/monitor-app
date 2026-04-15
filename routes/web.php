<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->get('/api/stats',function(){
    //CPU負荷（Linux系）
    $load = sys_getloadavg();
    $cpu = $load[0] * 100;

    //メモリ使用率
    $free = shell_exec('free');
    $free = (string)trim($free);
    $free_arr = explode("\n", $free);
    $mem = 0;
    if(isset($free_arr[1])){
        $mem_arr = preg_split('/\s+/', $free_arr[1]);
        $mem = ($mem_arr[2] / $mem_arr[1]) * 100;//使用率（％）
    }
    
    //ディスク使用率（ルートディレクトリ）
    $disk = disk_free_space("/") / disk_total_space("/") * 100;
    $disk = 100 - $disk;//空き容量から使用率に変換

    return response()->json([
        'cpu' => $cpu,
        'memory' => $mem,
        'disk' => $disk,
        'status' => 'success'
    ]);
});

require __DIR__.'/auth.php';

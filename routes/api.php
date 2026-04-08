<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MonitorController;

// これで /api/monitor-stats というURLでアクセス可能（認証不要）
Route::get('/monitor-stats', [MonitorController::class, 'api']);

?>
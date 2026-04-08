<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MonitorController;

Route::get('/', [MonitorController::class, 'index']);
Route::get('/api/stats', [MonitorController::class, 'api']);

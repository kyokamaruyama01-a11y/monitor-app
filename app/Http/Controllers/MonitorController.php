<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

class MonitorController extends Controller {
    public function index() {
        $load = sys_getloadavg();
        return view('dashboard', [
            'cpu' => isset($load[0]) ? ($load[0] * 100) : "N/A",
            'memory' => round(memory_get_usage(true) / 1024 / 1024, 2),
            'disk' => round((1 - disk_free_space("/") / disk_total_space("/")) * 100, 1)
        ]);
    }
}


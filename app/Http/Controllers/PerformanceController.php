<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PerformanceController extends Controller
{
    /**
     * 儲存性能監控數據
     */
    public function store(Request $request)
    {
        $data = $request->all();
        
        // 記錄到日誌（可根據需求改為儲存到資料庫）
        \Log::info('Performance Metrics', $data);
        
        // 可選：儲存到資料庫
        // PerformanceMetric::create($data);
        
        return response()->json(['status' => 'success']);
    }
}

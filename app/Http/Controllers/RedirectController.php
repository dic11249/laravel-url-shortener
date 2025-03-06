<?php

namespace App\Http\Controllers;

use App\Models\Url;
use App\Models\UrlVisit;
use App\Services\UrlService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class RedirectController extends Controller
{
    protected $urlService;

    public function __construct(UrlService $urlService)
    {
        $this->urlService = $urlService;
    }

    /**
     * 處理短網址重定向
     */
    public function redirect($code, Request $request)
    {
        try {
            // 獲取原始 URL
            $originalUrl = $this->urlService->getOriginalUrl($code);
            
            // 記錄訪問數據
            $this->recordVisit($code, $request);
            
            // 重定向到原始 URL
            return redirect($originalUrl);
        } catch (\Exception $e) {
            return response()->view('errors.404', ['error' => $e->getMessage()], 404);
        }
    }

    /**
     * 記錄訪問資訊
     */
    private function recordVisit($code, Request $request)
    {
        // 獲取 URL 資訊
        $url = Url::where('code', $code)->first();
        if (!$url) return;
        
        // 1. 記錄訪問記錄
        UrlVisit::create([
            'url_id' => $url->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referer' => $request->header('referer'),
        ]);
        
        // 2. 增加 Redis 中的點擊計數
        Redis::incr("clicks:{$code}");
        
        // 3. 更新 URL 的訪問次數
        $url->increment('visits_count');
    }
}

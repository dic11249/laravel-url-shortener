<?php

namespace App\Http\Controllers;

use App\Services\UrlService;
use Illuminate\Http\Request;

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
            // TODO
            
            // 重定向到原始 URL
            return redirect($originalUrl);
        } catch (\Exception $e) {
            return response()->view('errors.404', ['error' => $e->getMessage()], 404);
        }
    }
}

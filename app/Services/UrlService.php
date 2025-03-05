<?php

namespace App\Services;

use App\Models\Url;
use Illuminate\Support\Carbon;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Redis;

class UrlService
{
    /**
     * 創建短網址
     */
    public function create($originalUrl, $userId = null, $customCode = null, $expiresAt = null)
    {
        // 驗證 URL
        if (!filter_var($originalUrl, FILTER_VALIDATE_URL)) {
            throw new \Exception('無效的 URL 格式');
        }

        // 檢查是否有自訂短碼
        if ($customCode) {
            // 檢查短碼是否已存在
            if (Url::where('code', $customCode)->exists() ||
                Redis::exists("short:{$customCode}")) {
                throw new \Exception('此短碼已被使用');
            }
            $code = $customCode;
            $isCustom = true;
        } else {
            // 自動生成短碼
            $code = $this->generateUniqueCode();
            
            $isCustom = false;
        }

        // 創建 URL 記錄
        $url = Url::create([
            'user_id' => $userId,
            'code' => $code,
            'original_url' => $originalUrl,
            'expires_at' => $expiresAt ? Carbon::parse($expiresAt) : null,
            'is_custom' => $isCustom,
        ]);
        
        // 存入 Redis (用於快速重定向)
        $this->storeUrlInRedis($url);

        // 如果有使用者，更新他的短網址列表
        if ($userId) {
            Redis::zadd("user:{$userId}:urls", time(), $code);
        }

        return $url;
    }

    /**
     * 生成唯一短碼
     */
    private function generateUniqueCode()
    {
        do {
            // 使用 Redis 計數器生成基礎 ID
            $id = Redis::incr('url_counter');
            
            // 使用 Hashids 生成短碼 (限制在 8 碼以內)
            $code = Hashids::connection('main')->encode($id);
            
            // 檢查是否已存在
            $exists = Redis::exists("short:{$code}") || 
                      Url::where('code', $code)->exists();
                      
        } while ($exists);
        return $code;
    }

    /**
     * 將 URL 存入 Redis
     */
    private function storeUrlInRedis($url)
    {
        // 如果已過期，不存入 Redis
        if ($url->expires_at && $url->expires_at->isPast()) {
            return;
        }
        
        // 設置到 Redis 用於快速查找
        if ($url->expires_at) {
            // 有過期時間
            $ttl = $url->expires_at->diffInSeconds(now());
            Redis::setex("short:{$url->code}", $ttl, $url->original_url);
        } else {
            // 無過期時間
            Redis::set("short:{$url->code}", $url->original_url);
        }
    }

    /**
     * 獲取原始 URL
     */
    public function getOriginalUrl($code)
    {
        // 首先嘗試從 Redis 獲取
        $originalUrl = Redis::get("short:{$code}");

        // 如果 Redis 沒有，從資料庫獲取並快取
        if (!$originalUrl) {
            $url = Url::where('code', $code)
                      ->where('is_active', true)
                      ->where(function($query) {
                          $query->whereNull('expires_at')
                                ->orWhere('expires_at', '>', now());
                      })
                      ->first();

            if (!$url) {
                throw new \Exception('URL 不存在或已過期');
            }

            $originalUrl = $url->original_url;
            
            // 快取到 Redis
            $this->storeUrlInRedis($url);
        }

        return $originalUrl;
    }
}
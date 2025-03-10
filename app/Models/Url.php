<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Url extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'code',
        'original_url',
        'title',
        'expires_at',
        'is_custom',
        'is_active',
        'visits_count'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_custom' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 取得短網址的所有訪問記錄
     */
    public function visits()
    {
        return $this->hasMany(UrlVisit::class);
    }

    // public function stats()
    // {
    //     return $this->hasMany(UrlStat::class);
    // }

    /**
     * 作用域：只查詢有效的 URL
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }
}

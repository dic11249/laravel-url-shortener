<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UrlVisit extends Model
{
    use HasFactory;

    /**
     * 可批量賦值的屬性
     */
    protected $fillable = [
        'url_id', 
        'ip_address', 
        'user_agent', 
        'referer',
    ];

    /**
     * 取得關聯的短網址
     */
    public function url()
    {
        return $this->belongsTo(Url::class);
    }
}

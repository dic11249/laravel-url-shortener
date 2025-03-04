<?php

namespace App\Http\Controllers;

use App\Models\Url;
use App\Services\UrlService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UrlController extends Controller
{
    public $urlService;

    public function __construct(UrlService $urlService)
    {
        $this->urlService = $urlService;
    }

    public function store(Request $request)
    {
        
        Url::create([
            'user_id' => Auth::user()->id,
            'code' => $request->code,
            'original_url' => $request->originalUrl,
            'expires_at' => Carbon::today(),
            'is_custom' => !empty($request->code),
        ]);
    }
}

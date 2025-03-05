<?php

namespace App\Http\Controllers;

use App\Http\Resources\UrlResource;
use App\Models\Url;
use App\Services\UrlService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UrlController extends Controller
{
    public $urlService;

    public function __construct(UrlService $urlService)
    {
        $this->urlService = $urlService;
    }

    public function index()
    {
        $urls = Url::latest()->paginate(5);

        return Inertia::render('Url/Edit', [
            'urls' => UrlResource::collection($urls),
        ]);
    }

    public function create(Request $request)
    {
        $urls = Url::latest()->paginate(5);

        return Inertia::render('Url/Edit', [
            'urls' => UrlResource::collection($urls),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'originalUrl' => 'required|url',
            'code' => 'nullable|alpha_num|min:3|max:10',
        ]);
        
        Url::create([
            'user_id' => Auth::user()->id,
            'code' => $request->code,
            'original_url' => $request->originalUrl,
            'expires_at' => Carbon::today(),
            'is_custom' => !empty($request->code),
        ]);
    }
}

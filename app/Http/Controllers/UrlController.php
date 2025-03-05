<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Url;
use Inertia\Inertia;
use App\Services\UrlService;
use Illuminate\Http\Request;
use App\Http\Resources\UrlResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class UrlController extends Controller
{

    private $urlService;

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
        $userId = Auth::check() ? Auth::id() : null;

        $request->validate([
            'originalUrl' => 'required|url',
            'code' => 'nullable|alpha_num|min:3|max:10',
        ]);

        $this->urlService->create(
            $request->originalUrl,
            $userId,
            $request->code,
            $request->expiresAt ?? Carbon::today()->addDays(7)
        );

        return Redirect::route('dashboard');
    }
}

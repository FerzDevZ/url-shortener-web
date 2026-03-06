<?php

namespace App\Http\Middleware;

use App\Models\Link;
use Closure;
use Illuminate\Http\Request;

class CheckLinkValidity
{
    public function handle(Request $request, Closure $next)
    {
        $link = $request->route('link');

        if (!$link) {
            abort(404);
        }

        if (!$link->is_active) {
            abort(404, 'Link tidak aktif.');
        }

        if ($link->isExpired()) {
            return response()->view('public.expired', compact('link'), 410);
        }

        return $next($request);
    }
}

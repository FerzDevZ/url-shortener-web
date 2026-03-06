<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Services\LinkShortenerService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LinkController extends Controller
{
    public function __construct(protected LinkShortenerService $shortener) {}

    public function index()
    {
        $links = request()->user()->links()->withCount('clicks')->latest()->paginate(20);
        return response()->json($links);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'original_url' => 'required|url|max:2048',
                'custom_alias' => 'nullable|alpha_dash|max:50|unique:links,custom_alias|unique:links,short_code',
                'title'        => 'nullable|string|max:255',
                'password'     => 'nullable|string|max:50',
                'expires_at'   => 'nullable|date|after:now',
            ]);

            $link = $this->shortener->create($validated, $request->user()->id);

            return response()->json([
                'message' => 'Link created successfully',
                'data'    => $link,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    public function show(Link $link)
    {
        if ($link->user_id !== request()->user()->id) {
            abort(403);
        }
        $link->loadCount('clicks');
        return response()->json(['data' => $link]);
    }

    public function destroy(Link $link)
    {
        if ($link->user_id !== request()->user()->id) {
            abort(403);
        }
        
        $this->shortener->clearCache($link);
        $link->delete();

        return response()->json(['message' => 'Link deleted successfully']);
    }
}

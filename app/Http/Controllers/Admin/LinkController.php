<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\LinkClick;
use App\Services\QrCodeService;
use App\Services\LinkShortenerService;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    public function __construct(
        protected QrCodeService $qrCode,
        protected LinkShortenerService $shortener
    ) {}

    public function index(Request $request)
    {
        $query = Link::with('user')->withCount('clicks');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('original_url', 'like', "%{$request->search}%")
                  ->orWhere('short_code', 'like', "%{$request->search}%")
                  ->orWhere('custom_alias', 'like', "%{$request->search}%")
                  ->orWhere('title', 'like', "%{$request->search}%");
            });
        }

        if ($request->filter === 'active') {
            $query->where('is_active', true);
        } elseif ($request->filter === 'inactive') {
            $query->where('is_active', false);
        } elseif ($request->filter === 'expired') {
            $query->where('expires_at', '<', now());
        }

        $links = $query->latest()->paginate(15)->withQueryString();

        return view('admin.links.index', compact('links'));
    }

    public function show(Link $link)
    {
        $qrSvg = $this->qrCode->generateInline($link->short_url, 200);

        $clicksPerDay = $link->clicks()
            ->where('clicked_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(clicked_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $deviceStats  = $link->clicks()->selectRaw('device, COUNT(*) as count')->groupBy('device')->get();
        $browserStats = $link->clicks()->selectRaw('browser, COUNT(*) as count')->groupBy('browser')->get();

        return view('admin.links.show', compact('link', 'qrSvg', 'clicksPerDay', 'deviceStats', 'browserStats'));
    }

    public function toggleActive(Link $link)
    {
        $link->update(['is_active' => !$link->is_active]);
        $this->shortener->clearCache($link);

        return back()->with('success', 'Status link berhasil diubah.');
    }

    public function destroy(Link $link)
    {
        $this->shortener->clearCache($link);
        $link->delete();

        return redirect()->route('admin.links.index')
            ->with('success', 'Link berhasil dihapus.');
    }
}

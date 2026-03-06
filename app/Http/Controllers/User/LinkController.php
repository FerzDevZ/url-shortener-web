<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\LinkClick;
use App\Services\LinkShortenerService;
use App\Services\QrCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class LinkController extends Controller
{
    public function __construct(
        protected LinkShortenerService $shortener,
        protected QrCodeService $qrCode,
    ) {}

    public function index()
    {
        $activeWorkspaceId = session('active_workspace_id');

        if ($activeWorkspaceId) {
            $links = Link::where('workspace_id', $activeWorkspaceId)
                ->withCount('clicks')
                ->latest()
                ->paginate(10);
        } else {
            $links = auth()->user()->links()
                ->whereNull('workspace_id')
                ->withCount('clicks')
                ->latest()
                ->paginate(10);
        }

        return view('user.links.index', compact('links'));
    }

    public function create(Request $request)
    {
        $workspaces = auth()->user()->workspaces()->get();
        // Cek parameter query, jika tidak ada cek session
        $selectedWorkspaceId = $request->query('workspace_id', session('active_workspace_id'));

        return view('user.links.create', compact('workspaces', 'selectedWorkspaceId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'original_url' => 'required|url|max:2048',
            'custom_alias' => 'nullable|alpha_dash|max:50|unique:links,custom_alias|unique:links,short_code',
            'workspace_id' => 'nullable|exists:workspaces,id',
            'title'        => 'nullable|string|max:255',
            'password'     => 'nullable|string|max:50',
            'expires_at'   => 'nullable|date|after:now',
            'fb_pixel_id'  => 'nullable|string|max:255',
            'gtm_id'       => 'nullable|string|max:255',
            'qr_color'     => 'nullable|string|max:7',
            'qr_logo'      => 'nullable|image|mimes:jpeg,png,svg|max:512',
        ]);

        // Validasi keamanan ekstra: pastikan user benaran anggota dari tim ini
        if (!empty($validated['workspace_id'])) {
            $isMember = auth()->user()->workspaces()->where('workspace_id', $validated['workspace_id'])->exists();
            if (!$isMember) {
                abort(403, 'Akses ditolak. Anda mencoba memasukkan link ke dalam lingkungan kerja di mana Anda tidak bergabung.');
            }
        }

        if ($request->hasFile('qr_logo')) {
            $validated['qr_logo_path'] = $request->file('qr_logo')->store('qr_logos', 'public');
        }

        $link = $this->shortener->create($validated, auth()->id());

        return redirect()->route('user.links.show', $link->id)
            ->with('success', 'Link berhasil dibuat!');
    }

    public function show(Link $link)
    {
        Gate::authorize('view', $link);

        $qrSvg = $this->qrCode->generateInline($link, 200);

        // Statistik klik 30 hari terakhir
        $clicksPerDay = $link->clicks()
            ->where('clicked_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(clicked_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Breakdown
        $deviceStats  = $link->clicks()->selectRaw('device, COUNT(*) as count')->groupBy('device')->get();
        $browserStats = $link->clicks()->selectRaw('browser, COUNT(*) as count')->groupBy('browser')->get();
        $osStats      = $link->clicks()->selectRaw('os, COUNT(*) as count')->groupBy('os')->get();

        // Recent clicks
        $recentClicks = $link->clicks()->orderByDesc('clicked_at')->limit(10)->get();

        return view('user.links.show', compact(
            'link', 'qrSvg', 'clicksPerDay', 'deviceStats', 'browserStats', 'osStats', 'recentClicks'
        ));
    }

    public function edit(Link $link)
    {
        Gate::authorize('update', $link);
        return view('user.links.edit', compact('link'));
    }

    public function update(Request $request, Link $link)
    {
        Gate::authorize('update', $link);

        $validated = $request->validate([
            'title'        => 'nullable|string|max:255',
            'is_active'    => 'boolean',
            'expires_at'   => 'nullable|date',
            'fb_pixel_id'  => 'nullable|string|max:255',
            'gtm_id'       => 'nullable|string|max:255',
            'qr_color'     => 'nullable|string|max:7',
            'qr_logo'      => 'nullable|image|mimes:jpeg,png,svg|max:512',
            'remove_qr_logo' => 'nullable|boolean',
        ]);

        if ($request->filled('remove_qr_logo')) {
            if ($link->qr_logo_path) Storage::disk('public')->delete($link->qr_logo_path);
            $validated['qr_logo_path'] = null;
        } elseif ($request->hasFile('qr_logo')) {
            if ($link->qr_logo_path) Storage::disk('public')->delete($link->qr_logo_path);
            $validated['qr_logo_path'] = $request->file('qr_logo')->store('qr_logos', 'public');
        }

        $link->update($validated);
        $this->shortener->clearCache($link);

        return redirect()->route('user.links.show', $link->id)
            ->with('success', 'Link berhasil diupdate!');
    }

    public function destroy(Link $link)
    {
        Gate::authorize('delete', $link);
        
        $this->shortener->clearCache($link);
        if ($link->qr_logo_path) Storage::disk('public')->delete($link->qr_logo_path);
        
        $link->delete();

        return redirect()->route('user.links.index')
            ->with('success', 'Link berhasil dihapus!');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\LinkClick;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_links'  => Link::count(),
            'total_clicks' => LinkClick::count(),
            'total_users'  => User::count(),
            'active_links' => Link::where('is_active', true)->count(),
        ];

        // Klik 30 hari terakhir
        $clicksPerDay = LinkClick::where('clicked_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(clicked_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Link terpopuler
        $topLinks = Link::withCount('clicks')
            ->orderByDesc('click_count')
            ->limit(5)
            ->get();

        // User terbaru
        $recentUsers = User::latest()->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'clicksPerDay', 'topLinks', 'recentUsers'));
    }
}

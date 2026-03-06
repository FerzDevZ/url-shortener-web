<?php

namespace App\Http\Controllers;

use App\Models\BioPage;
use Illuminate\Http\Request;

class PublicBioController extends Controller
{
    public function show($slug)
    {
        $bio = BioPage::where('slug', $slug)->firstOrFail();
        
        // Ambil semua public & active links dari user ini
        $links = $bio->user->links()
                           ->where('is_active', true)
                           ->where(function($query) {
                               $query->whereNull('expires_at')
                                     ->orWhere('expires_at', '>', now());
                           })
                           ->latest()
                           ->get();

        return view('public.bio', compact('bio', 'links'));
    }
}

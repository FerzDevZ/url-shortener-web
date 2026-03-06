<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BioPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BioPageController extends Controller
{
    public function edit()
    {
        $bio = auth()->user()->bioPage;
        
        // Buat default bio page kalau belum ada
        if (!$bio) {
            $bio = BioPage::create([
                'user_id' => auth()->id(),
                'slug' => auth()->user()->name ? \Illuminate\Support\Str::slug(auth()->user()->name) : 'user-' . auth()->id(),
                'title' => auth()->user()->name,
                'theme_color' => 'dark'
            ]);
        }

        return view('user.bio.edit', compact('bio'));
    }

    public function update(Request $request)
    {
        $bio = auth()->user()->bioPage;

        $validated = $request->validate([
            'slug' => 'required|alpha_dash|max:50|unique:bio_pages,slug,' . $bio->id,
            'title' => 'nullable|string|max:100',
            'bio' => 'nullable|string|max:500',
            'theme_color' => 'required|in:light,dark,blue,green,purple',
            'photo' => 'nullable|image|max:2048' // max 2MB
        ]);

        if ($request->hasFile('photo')) {
            if ($bio->photo_path) {
                Storage::disk('public')->delete($bio->photo_path);
            }
            $path = $request->file('photo')->store('bio-photos', 'public');
            $validated['photo_path'] = $path;
        }

        unset($validated['photo']);
        $bio->update($validated);

        return back()->with('success', 'Halaman Link in Bio berhasil diperbarui!');
    }
}

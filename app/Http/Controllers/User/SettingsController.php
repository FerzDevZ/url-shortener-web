<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $tokens = auth()->user()->tokens;
        return view('user.settings', compact('tokens'));
    }

    public function generateToken(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $token = auth()->user()->createToken($request->name);
        
        return back()->with('token_plain', $token->plainTextToken)
            ->with('success', 'Token API berhasil dibuat. Salin dan simpan segera karena tidak akan ditampilkan lagi!');
    }

    public function revokeToken(Request $request, $id)
    {
        auth()->user()->tokens()->where('id', $id)->delete();
        return back()->with('success', 'Akses Token berhasil dicabut.');
    }
}

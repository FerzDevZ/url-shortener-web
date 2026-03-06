<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Services\LinkShortenerService;
use App\Services\QrCodeService;
use App\Services\ClickTrackerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class PublicController extends Controller
{
    public function __construct(
        protected LinkShortenerService $shortener,
        protected QrCodeService $qrCode,
        protected ClickTrackerService $tracker,
    ) {}

    public function index()
    {
        // Generate math captcha for guests
        if (!auth()->check()) {
            $num1 = rand(1, 9);
            $num2 = rand(1, 9);
            session(['captcha_answer' => $num1 + $num2]);
            session(['captcha_question' => "$num1 + $num2"]);
        }

        return view('public.home');
    }

    public function shorten(Request $request)
    {
        // Rate limit: 10 per menit per IP
        $key = 'shorten:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 10)) {
            return back()->withErrors(['url' => 'Terlalu banyak permintaan. Coba lagi nanti.']);
        }
        RateLimiter::hit($key, 60);

        $rules = [
            'original_url' => 'required|url|max:2048',
            'custom_alias' => 'nullable|alpha_dash|max:50|unique:links,custom_alias|unique:links,short_code',
            'password'     => 'nullable|string|max:50',
            'expires_at'   => 'nullable|date|after:now',
            'website'      => 'nullable|max:0', // Honeypot
        ];

        // Jika guest, wajib jawab captcha
        if (!auth()->check()) {
            $rules['captcha'] = 'required|numeric|in:' . session('captcha_answer');
        }

        $validated = $request->validate($rules, [
            'original_url.required' => 'URL panjang wajib diisi.',
            'original_url.url' => 'Format URL tidak valid.',
            'website.max' => 'Spam terdeteksi.',
            'captcha.required' => 'Jawaban keamanan wajib diisi.',
            'captcha.in' => 'Jawaban keamanan salah.',
        ]);

        // Regenerate captcha after successful attempt or just clear it
        if (!auth()->check()) {
            session()->forget(['captcha_answer', 'captcha_question']);
        }

        $link = $this->shortener->create([
            'original_url' => $validated['original_url'],
            'custom_alias' => $validated['custom_alias'] ?? null,
            'password'     => $validated['password'] ?? null,
            'expires_at'   => $validated['expires_at'] ?? null,
        ], auth()->id());

        $qrSvg = $this->qrCode->generateInline($link, 200);

        return back()->with([
            'success'   => true,
            'link'      => $link,
            'short_url' => $link->short_url,
            'qr_svg'    => $qrSvg,
        ]);
    }

    public function redirect(Request $request, string $code)
    {
        $link = $this->shortener->findByCode($code);

        if (!$link || !$link->is_active) {
            abort(404);
        }

        if ($link->isExpired()) {
            return view('public.expired', compact('link'));
        }

        // Cek password
        if ($link->isPasswordProtected()) {
            $enteredPassword = session("link_pass_{$link->id}");
            if (!$enteredPassword || !Hash::check($enteredPassword, $link->password_hash)) {
                return view('public.password', compact('link', 'code'));
            }
        }

        // Record klik
        $this->tracker->record($link, $request);

        if ($link->fb_pixel_id || $link->gtm_id) {
            return view('public.redirect_analytics', compact('link'));
        }

        return redirect()->away($link->original_url, 302);
    }

    public function checkPassword(Request $request, string $code)
    {
        $link = $this->shortener->findByCode($code);

        if (!$link || !$link->isPasswordProtected()) {
            abort(404);
        }

        $request->validate(['password' => 'required|string']);

        if (!Hash::check($request->password, $link->password_hash)) {
            return back()->withErrors(['password' => 'Password salah.']);
        }

        // Simpan di session
        session(["link_pass_{$link->id}" => $request->password]);

        // Record klik
        $this->tracker->record($link, $request);

        if ($link->fb_pixel_id || $link->gtm_id) {
            return view('public.redirect_analytics', compact('link'));
        }

        return redirect()->away($link->original_url, 302);
    }
}

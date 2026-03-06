<?php

namespace App\Jobs;

use App\Models\Link;
use App\Models\LinkClick;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

class RecordLinkClick implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Link $link,
        public string $ip,
        public ?string $userAgent,
        public ?string $referer
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // 1. Ambil data GeoIP dari IP
        $country = null;
        $city = null;
        try {
            $geo = geoip($this->ip);
            $country = $geo->country ?? null;
            $city = $geo->city ?? null;
        } catch (\Exception $e) {
            // Abaikan jika konfigurasi GeoIP tidak ada / gagal
        }

        // 2. Parsel User Agent secara manual (karena Agent::class butuh package tambahan, 
        // kita extract basic info pakai regex regex sederhana)
        $device = 'Desktop';
        $browser = 'Unknown';
        $os = 'Unknown';
        $isBot = false;

        if ($this->userAgent) {
            $ua = strtolower($this->userAgent);
            
            // Basic Bot Detection
            if (str_contains($ua, 'bot') || str_contains($ua, 'spider') || str_contains($ua, 'crawl')) {
                $isBot = true;
            }

            // Basic Device Detection
            if (str_contains($ua, 'mobile') || str_contains($ua, 'android') || str_contains($ua, 'iphone')) {
                $device = 'Mobile';
            } elseif (str_contains($ua, 'tablet') || str_contains($ua, 'ipad')) {
                $device = 'Tablet';
            }

            // Basic OS Detection
            if (str_contains($ua, 'windows')) $os = 'Windows';
            elseif (str_contains($ua, 'mac os')) $os = 'macOS';
            elseif (str_contains($ua, 'linux')) $os = 'Linux';
            elseif (str_contains($ua, 'android')) $os = 'Android';
            elseif (str_contains($ua, 'iphone') || str_contains($ua, 'ipad')) $os = 'iOS';

            // Basic Browser Detection
            if (str_contains($ua, 'chrome')) $browser = 'Chrome';
            elseif (str_contains($ua, 'firefox')) $browser = 'Firefox';
            elseif (str_contains($ua, 'safari') && !str_contains($ua, 'chrome')) $browser = 'Safari';
            elseif (str_contains($ua, 'edge')) $browser = 'Edge';
            elseif (str_contains($ua, 'opera') || str_contains($ua, 'opr')) $browser = 'Opera';
        }

        DB::transaction(function () use ($country, $city, $device, $browser, $os, $isBot) {
            // Catat klik
            LinkClick::create([
                'link_id'    => $this->link->id,
                'ip_address' => $this->ip,
                'country'    => $country,
                'city'       => $city,
                'device'     => $device,
                'browser'    => $browser,
                'os'         => $os,
                'referer'    => $this->referer,
                'user_agent' => $this->userAgent,
                'is_bot'     => $isBot,
                'clicked_at' => now(),
            ]);

            // Increment hitung
            $this->link->increment('click_count');
        });
    }
}

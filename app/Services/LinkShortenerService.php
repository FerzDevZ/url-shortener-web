<?php

namespace App\Services;

use App\Models\Link;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class LinkShortenerService
{
    public function create(array $data, ?int $userId = null): Link
    {
        // Generate unique short code jika tidak pakai custom alias
        $shortCode = $data['custom_alias'] ?? $this->generateUniqueCode();

        $link = Link::create([
            'user_id'       => $userId,
            'workspace_id'  => $data['workspace_id'] ?? null,
            'original_url'  => $data['original_url'],
            'short_code'    => $shortCode,
            'custom_alias'  => $data['custom_alias'] ?? null,
            'title'         => $data['title'] ?? null,
            'password_hash' => isset($data['password']) && $data['password']
                                ? bcrypt($data['password']) : null,
            'expires_at'    => $data['expires_at'] ?? null,
            'is_active'     => true,
            'fb_pixel_id'   => $data['fb_pixel_id'] ?? null,
            'gtm_id'        => $data['gtm_id'] ?? null,
            'qr_color'      => $data['qr_color'] ?? '#000000',
            'qr_logo_path'  => $data['qr_logo_path'] ?? null,
        ]);

        return $link;
    }

    public function generateUniqueCode(int $length = 6): string
    {
        do {
            $code = Str::random($length);
        } while (Link::where('short_code', $code)->exists());

        return $code;
    }

    public function findByCode(string $code): ?Link
    {
        // Cache object link selamanya (di Redis) untuk mempercepat redirect hingga 10x lipat
        // Cache key akan menggunakan custom_alias jika ada, jika tidak pakai short_code
        return Cache::rememberForever("link_data_{$code}", function () use ($code) {
            return Link::where('custom_alias', $code)
                       ->orWhere('short_code', $code)
                       ->first();
        });
    }

    /**
     * Clear cache for a specific link when updated/deleted
     */
    public function clearCache(Link $link): void
    {
        if ($link->custom_alias) {
            Cache::forget("link_data_{$link->custom_alias}");
        }
        Cache::forget("link_data_{$link->short_code}");
    }

    public function isAliasAvailable(string $alias, ?int $excludeId = null): bool
    {
        $query = Link::where('custom_alias', $alias)
            ->orWhere('short_code', $alias);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->doesntExist();
    }
}

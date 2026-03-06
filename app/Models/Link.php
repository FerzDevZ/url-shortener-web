<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Link extends Model
{
    protected $fillable = [
        'user_id', 'workspace_id', 'original_url', 'short_code', 'custom_alias',
        'title', 'password_hash', 'expires_at', 'is_active', 'click_count',
        'fb_pixel_id', 'gtm_id', 'qr_color', 'qr_logo_path'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'is_bot' => 'boolean',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function clicks(): HasMany
    {
        return $this->hasMany(LinkClick::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeNotExpired($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
        });
    }

    // Accessors
    public function getShortUrlAttribute(): string
    {
        $code = $this->custom_alias ?? $this->short_code;
        return url("/{$code}");
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isPasswordProtected(): bool
    {
        return !is_null($this->password_hash);
    }
}

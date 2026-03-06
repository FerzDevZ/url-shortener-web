<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LinkClick extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'link_id', 'ip_address', 'country', 'city',
        'device', 'browser', 'os', 'referer', 'user_agent',
        'is_bot', 'clicked_at',
    ];

    protected $casts = [
        'clicked_at' => 'datetime',
        'is_bot' => 'boolean',
    ];

    public function link(): BelongsTo
    {
        return $this->belongsTo(Link::class);
    }
}

<?php

namespace App\Services;

use App\Models\Link;
use Illuminate\Http\Request;
use App\Jobs\RecordLinkClick;

class ClickTrackerService
{
    /**
     * Dispatch the click tracking job to the queue.
     * We no longer block the request, it completes in 0 seconds.
     */
    public function record(Link $link, Request $request): void
    {
        RecordLinkClick::dispatch(
            $link,
            $request->ip(),
            $request->userAgent(),
            $request->headers->get('referer')
        );
    }
}

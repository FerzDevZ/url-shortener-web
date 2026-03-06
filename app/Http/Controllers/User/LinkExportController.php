<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Link;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LinkExportController extends Controller
{
    public function csv(Link $link)
    {
        Gate::authorize('view', $link);

        $clicks = $link->clicks()->orderBy('clicked_at', 'desc')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => sprintf('attachment; filename="link_%s_analytics.csv"', $link->short_code),
        ];

        return new StreamedResponse(function () use ($clicks) {
            $file = fopen('php://output', 'w');
            
            // Header baris CSV
            fputcsv($file, [
                'Waktu Klik', 'IP Address', 'Negara', 'Kota', 
                'Perangkat', 'Browser', 'OS', 'Referer', 'Is Bot'
            ]);

            foreach ($clicks as $click) {
                fputcsv($file, [
                    $click->clicked_at->format('Y-m-d H:i:s'),
                    $click->ip_address,
                    $click->country ?? '-',
                    $click->city ?? '-',
                    $click->device,
                    $click->browser,
                    $click->os,
                    $click->referer ?? '-',
                    $click->is_bot ? 'Ya' : 'Tidak'
                ]);
            }

            fclose($file);
        }, 200, $headers);
    }
}

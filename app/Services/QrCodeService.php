<?php

namespace App\Services;

use App\Models\Link;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class QrCodeService
{
    public function generateAndSave(Link $link): string
    {
        $url = $link->short_url;
        $filename = "qrcodes/{$link->short_code}.svg";

        $svg = QrCode::format('svg')
            ->size(300)
            ->margin(1)
            ->generate($url);

        Storage::disk('public')->put($filename, $svg);

        return $filename;
    }

    public function getQrCodePath(Link $link): string
    {
        $filename = "qrcodes/{$link->short_code}.svg";

        if (!Storage::disk('public')->exists($filename)) {
            return $this->generateAndSave($link);
        }

        return $filename;
    }

    public function getQrCodeUrl(Link $link): string
    {
        $path = $this->getQrCodePath($link);
        return Storage::disk('public')->url($path);
    }

    public function generateInline(Link $link, int $size = 200): string
    {
        $qr = QrCode::format('svg')
            ->size($size)
            ->margin(1)
            ->errorCorrection('H'); // High error correction for logo support

        if ($link->qr_color) {
            $hex = ltrim($link->qr_color, '#');
            if (strlen($hex) == 6) {
                list($r, $g, $b) = sscanf($hex, "%02x%02x%02x");
                $qr->color($r, $g, $b);
            }
        }

        if ($link->qr_logo_path && Storage::disk('public')->exists($link->qr_logo_path)) {
            $path = Storage::disk('public')->path($link->qr_logo_path);
            $qr->merge($path, .2, true);
        }

        return $qr->generate($link->short_url);
    }
}

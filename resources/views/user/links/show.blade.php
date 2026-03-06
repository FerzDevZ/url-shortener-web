@extends('user.layout')

@section('page-title', $link->title ?? ('/' . ($link->custom_alias ?? $link->short_code)))
@section('page-subtitle', 'Analitik URL: ' . route('redirect', $link->custom_alias ?? $link->short_code))

@section('topbar-actions')
    <div style="display: flex; gap: 0.75rem;">
        <a href="{{ route('user.links.export', $link) }}" class="btn btn-secondary">
            <svg style="width: 1rem; height: 1rem; margin-right: 0.25rem; display: inline-block; vertical-align: text-bottom;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Laporan CSV
        </a>
        <a href="{{ $link->short_url }}" target="_blank" class="btn btn-secondary">Test Tautan</a>
        <a href="{{ route('user.links.edit', $link) }}" class="btn btn-primary">Ubah Pengaturan</a>
    </div>
@endsection

@section('content')
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
    <div class="stat-card">
        <div class="stat-label">Total Klik</div>
        <div class="stat-value text-accent">{{ number_format($link->click_count) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Klik Asli (Human)</div>
        <div class="stat-value text-success">{{ number_format($link->clicks()->where('is_bot', false)->count()) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Klik Oleh Bot</div>
        <div class="stat-value text-danger">{{ number_format($link->clicks()->where('is_bot', true)->count()) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Status Link</div>
        <div class="stat-value {{ $link->is_active && !$link->isExpired() ? 'text-success' : 'text-danger' }}" style="font-size: 1.25rem; display: flex; align-items: center; height: 100%;">
            {{ $link->is_active && !$link->isExpired() ? 'Berjalan Aktif' : 'Tidak Aktif / Kadaluarsa' }}
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
    <!-- Chart -->
    <div class="card">
        <div class="card-header"><h3 class="card-title">Perkembangan Klik 30 Hari Terakhir</h3></div>
        <div class="card-body">
            <canvas id="clickChart" height="100"></canvas>
        </div>
    </div>

    <!-- QR Code Section -->
    <div>
        <div class="card" style="margin-bottom: 1rem;">
            <div class="card-header"><h3 class="card-title">Bagikan via QR Code</h3></div>
            <div class="card-body" style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 1rem;">
                <div style="background: white; padding: 1rem; border-radius: 0.5rem; border: 1px solid var(--border);">
                    {!! $qrSvg !!}
                </div>
                <div class="text-primary" style="font-size: 0.875rem; font-weight: 500;">{{ $link->short_url }}</div>
                <button onclick="copyToClipboard('{{ $link->short_url }}', this)" class="btn btn-secondary btn-block">Salin Tautan Pendek</button>
            </div>
        </div>

        <!-- Tracking Indicators & Branding info -->
        <div class="card p-3">
            <h4 class="text-primary mb-2" style="font-size: 0.85rem; font-weight: 600;">Status Analitik Tambahan:</h4>
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                @if($link->fb_pixel_id)
                    <span class="badge badge-blue">FB Pixel: {{ $link->fb_pixel_id }}</span>
                @endif
                @if($link->gtm_id)
                    <span class="badge badge-yellow">GTM: {{ $link->gtm_id }}</span>
                @endif
                @if($link->qr_logo_path)
                    <span class="badge text-secondary" style="border: 1px solid var(--border);">Custom QR Logo</span>
                @endif
                @if(!$link->fb_pixel_id && !$link->gtm_id && !$link->qr_logo_path && $link->qr_color == '#000000')
                    <span class="text-muted" style="font-size: 0.8rem;">Standar (Tidak ada pelacakan pixel aktif)</span>
                @endif
            </div>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 2rem;">
    @foreach([['label' => 'Tipe Perangkat', 'data' => $deviceStats, 'field' => 'device'], ['label' => 'Browser Digunakan', 'data' => $browserStats, 'field' => 'browser'], ['label' => 'Sistem Operasi', 'data' => $osStats, 'field' => 'os']] as $section)
    <div class="card">
        <div class="card-header" style="padding: 0.75rem 1rem;"><h3 class="card-title" style="font-size: 0.8rem;">{{ $section['label'] }}</h3></div>
        <div class="card-body" style="padding: 1rem;">
            @foreach($section['data'] as $item)
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.35rem 0; font-size: 0.875rem; border-bottom: 1px dashed var(--border); margin-bottom: 0.25rem;">
                <span class="text-primary">{{ $item->{$section['field']} ?? 'Lainnya/Unknown' }}</span>
                <span class="text-accent" style="font-weight: 600;">{{ $item->count }}</span>
            </div>
            @endforeach
            @if($section['data']->isEmpty()) <div class="text-muted" style="font-size: 0.8rem;">Belum ada data analitik</div> @endif
        </div>
    </div>
    @endforeach
</div>

<!-- Recent Clicks -->
@if($recentClicks->isNotEmpty())
<div class="card">
    <div class="card-header"><h3 class="card-title">Riwayat Klik Terbaru</h3></div>
    <div class="card-body" style="padding: 0;">
        <table class="data-table">
            <thead><tr><th>Waktu Akses</th><th>Device</th><th>Browser</th><th>OS</th><th>Sumber (Referer)</th></tr></thead>
            <tbody>
            @foreach($recentClicks as $click)
            <tr>
                <td style="font-size: 0.875rem;">{{ $click->clicked_at->format('d M Y, H:i') }}</td>
                <td><span class="badge {{ $click->device === 'Mobile' ? 'badge-blue' : 'badge-yellow' }}" style="font-size: 0.7rem;">{{ $click->device ?? '-' }}</span></td>
                <td class="text-secondary">{{ $click->browser ?? '-' }}</td>
                <td class="text-secondary">{{ $click->os ?? '-' }}</td>
                <td class="text-muted url-truncate" style="max-width: 200px;" title="{{ $click->referer ?? 'Direct/Bookmarks' }}">
                    {{ $click->referer ? parse_url($click->referer, PHP_URL_HOST) : 'Direct/Bookmarks' }}
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
function copyToClipboard(text, btn) {
    navigator.clipboard.writeText(text).then(() => {
        const originalText = btn.textContent;
        btn.textContent = 'Berhasil disalin!';
        btn.classList.add('bg-success', 'text-white');
        setTimeout(() => {
            btn.textContent = originalText;
            btn.classList.remove('bg-success', 'text-white');
        }, 2000);
    });
}

const style = getComputedStyle(document.body);
const accentColor = style.getPropertyValue('--accent').trim() || '#6366f1';
const textColor = style.getPropertyValue('--text-muted').trim() || '#94a3b8';
const gridColor = style.getPropertyValue('--border').trim() || '#e5e8ed';

new Chart(document.getElementById('clickChart'), {
    type: 'line',
    data: {
        labels: @json($clicksPerDay->pluck('date')),
        datasets: [{
            label: 'Total Klik Harian', 
            data: @json($clicksPerDay->pluck('count')),
            borderColor: accentColor, backgroundColor: `${accentColor}1A`,
            borderWidth: 2, fill: true, tension: 0.4, pointRadius: 3, pointBackgroundColor: accentColor
        }]
    },
    options: {
        responsive: true, plugins: { legend: { display: false } },
        scales: {
            x: { ticks: { color: textColor, font: { family: 'Inter', size: 10 } }, grid: { color: gridColor, drawBorder: false } },
            y: { ticks: { color: textColor, font: { family: 'Inter', size: 10 } }, grid: { color: gridColor, drawBorder: false }, beginAtZero: true }
        }
    }
});
</script>
@endpush

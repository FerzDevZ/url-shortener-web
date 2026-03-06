@extends('admin.layout')

@section('page-title', 'Detail Link')
@section('page-subtitle', 'Statistik dan informasi link: /' . ($link->custom_alias ?? $link->short_code))

@section('content')
<div style="display: flex; gap: 0.75rem; margin-bottom: 1.5rem;">
    <a href="{{ route('admin.links.index') }}" class="btn btn-secondary">Kembali</a>
    <a href="{{ $link->short_url }}" target="_blank" class="btn btn-primary">Buka Link URL</a>
    <form action="{{ route('admin.links.toggle', $link) }}" method="POST" style="display:inline;">
        @csrf @method('PATCH')
        <button type="submit" class="btn btn-secondary">{{ $link->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button>
    </form>
</div>

<div style="display: grid; grid-template-columns: 3fr 2fr; gap: 1.5rem; margin-bottom: 1.5rem;">
    <!-- Link Info -->
    <div class="card">
        <div class="card-header"><h3 class="card-title">Informasi Link</h3></div>
        <div class="card-body">
            <div style="display: flex; flex-direction: column; gap: 0.875rem;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 1px dashed var(--border); padding-bottom: 0.5rem;">
                    <span class="text-muted" style="font-size: 0.875rem;">Short URL</span>
                    <a href="{{ $link->short_url }}" target="_blank" class="text-accent" style="font-weight: 500;">{{ $link->short_url }}</a>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 1px dashed var(--border); padding-bottom: 0.5rem;">
                    <span class="text-muted" style="font-size: 0.875rem;">URL Asli</span>
                    <span class="text-primary url-truncate" style="font-weight: 500; text-align: right; max-width: 300px;" title="{{ $link->original_url }}">{{ $link->original_url }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 1px dashed var(--border); padding-bottom: 0.5rem;">
                    <span class="text-muted" style="font-size: 0.875rem;">Total Klik</span>
                    <span class="text-primary" style="font-weight: 600;">{{ number_format($link->click_count) }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 1px dashed var(--border); padding-bottom: 0.5rem;">
                    <span class="text-muted" style="font-size: 0.875rem;">Status</span>
                    <span>{!! $link->is_active ? '<span class="badge badge-green">Aktif</span>' : '<span class="badge badge-red">Nonaktif</span>' !!}</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 1px dashed var(--border); padding-bottom: 0.5rem;">
                    <span class="text-muted" style="font-size: 0.875rem;">Password Protected</span>
                    <span class="text-primary" style="font-weight: 500;">{{ $link->isPasswordProtected() ? 'Ya' : 'Tidak' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 1px dashed var(--border); padding-bottom: 0.5rem;">
                    <span class="text-muted" style="font-size: 0.875rem;">Tgl Kadaluarsa</span>
                    <span class="text-primary" style="font-weight: 500;">{{ $link->expires_at ? $link->expires_at->format('d M Y H:i') : 'Tidak Berlaku' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 1px dashed var(--border); padding-bottom: 0.5rem;">
                    <span class="text-muted" style="font-size: 0.875rem;">Pemilik Pembuat</span>
                    <span class="text-primary" style="font-weight: 500;">{{ $link->user?->name ?? 'Tamu / Umum' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <span class="text-muted" style="font-size: 0.875rem;">Waktu Dibuat</span>
                    <span class="text-primary" style="font-weight: 500;">{{ $link->created_at->format('d M Y, H:i') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- QR Code & Stats -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <div class="card">
            <div class="card-header"><h3 class="card-title">QR Code</h3></div>
            <div class="card-body" style="display: flex; align-items: center; justify-content: center; flex-direction: column;">
                <div style="background: white; padding: 1rem; border-radius: 0.5rem; border: 1px solid var(--border);">
                    {!! $qrSvg !!}
                </div>
            </div>
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            @foreach([['label' => 'Tipe Device', 'data' => $deviceStats], ['label' => 'Top Browser', 'data' => $browserStats]] as $section)
            <div class="card">
                <div class="card-header" style="padding: 0.75rem 1rem;"><span style="font-size: 0.8rem; font-weight: 600;" class="text-muted">{{ $section['label'] }}</span></div>
                <div class="card-body" style="padding: 1rem;">
                    @foreach($section['data'] as $item)
                    <div style="display: flex; justify-content: space-between; font-size: 0.8rem; padding: 0.2rem 0; border-bottom: 1px dashed var(--border); margin-bottom: 0.25rem;">
                        <span class="text-primary">{{ $item->{strtolower($section['label'] === 'Tipe Device' ? 'device' : 'browser')} ?? $item->device ?? $item->browser ?? 'Lainnya' }}</span>
                        <span class="text-accent" style="font-weight: 600;">{{ $item->count }}</span>
                    </div>
                    @endforeach
                    @if($section['data']->isEmpty()) <div class="text-muted" style="font-size: 0.8rem;">Belum ada data</div> @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Klik Chart -->
<div class="card">
    <div class="card-header"><h3 class="card-title">Klik 30 Hari Terakhir</h3></div>
    <div class="card-body">
        <canvas id="clickChart" height="80"></canvas>
    </div>
</div>
@endsection

@push('scripts')
<script>
const style = getComputedStyle(document.body);
const accentColor = style.getPropertyValue('--accent').trim() || '#6366f1';
const textColor = style.getPropertyValue('--text-muted').trim() || '#94a3b8';
const gridColor = style.getPropertyValue('--border').trim() || '#e5e8ed';

new Chart(document.getElementById('clickChart'), {
    type: 'bar',
    data: {
        labels: @json($clicksPerDay->pluck('date')),
        datasets: [{ 
            label: 'Klik', 
            data: @json($clicksPerDay->pluck('count')), 
            backgroundColor: `${accentColor}80`, 
            borderColor: accentColor, 
            borderWidth: 1, 
            borderRadius: 4 
        }]
    },
    options: { 
        responsive: true, 
        plugins: { legend: { display: false } }, 
        scales: { 
            x: { ticks: { color: textColor, font: { family: 'Inter', size: 10 } }, grid: { color: gridColor, drawBorder: false } }, 
            y: { ticks: { color: textColor, font: { family: 'Inter', size: 10 } }, grid: { color: gridColor, drawBorder: false }, beginAtZero: true } 
        } 
    }
});
</script>
@endpush

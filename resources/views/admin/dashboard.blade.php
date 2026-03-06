@extends('admin.layout')

@section('page-title', 'Dashboard')
@section('page-subtitle', 'Overview statistik global')

@section('content')
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
    @foreach([
        ['label' => 'Total Link', 'value' => number_format($stats['total_links'])],
        ['label' => 'Total Klik', 'value' => number_format($stats['total_clicks'])],
        ['label' => 'Link Aktif', 'value' => number_format($stats['active_links'])],
        ['label' => 'Total User', 'value' => number_format($stats['total_users'])],
    ] as $stat)
    <div class="stat-card">
        <div class="stat-label">{{ $stat['label'] }}</div>
        <div class="stat-value text-accent">{{ $stat['value'] }}</div>
    </div>
    @endforeach
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
    <!-- Chart Klik 30 Hari -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Klik 30 Hari Terakhir</h3>
        </div>
        <div class="card-body">
            <canvas id="clickChart" height="110"></canvas>
        </div>
    </div>

    <!-- Top Links -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Link Terpopuler</h3>
        </div>
        <div class="card-body" style="padding-top: 0.5rem; padding-bottom: 0.5rem;">
            @forelse($topLinks as $link)
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid var(--border);">
                <div style="overflow: hidden;">
                    <a href="{{ route('admin.links.show', $link) }}" class="text-primary" style="font-weight: 600; font-size: 0.875rem;">/{{ $link->custom_alias ?? $link->short_code }}</a>
                    <div class="text-muted url-truncate" style="font-size: 0.75rem; max-width: 150px;">{{ parse_url($link->original_url, PHP_URL_HOST) }}</div>
                </div>
                <div class="text-accent" style="font-weight: 700; font-size: 0.875rem;">{{ number_format($link->click_count) }}</div>
            </div>
            @empty
                <div class="text-muted" style="font-size: 0.875rem; text-align: center; padding: 1rem 0;">Belum ada data</div>
            @endforelse
            <div style="text-align: center; margin-top: 1rem; margin-bottom: 0.5rem;">
                <a href="{{ route('admin.links.index') }}" class="text-accent" style="font-size: 0.8rem; font-weight: 600;">Lihat Semua Link &rarr;</a>
            </div>
        </div>
    </div>
</div>

<!-- Recent Users -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">User Terbaru</h3>
        <a href="{{ route('admin.users.index') }}" class="text-accent" style="font-size: 0.8rem; font-weight: 600;">Lihat Semua &rarr;</a>
    </div>
    <div class="card-body" style="padding: 0;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Bergabung</th>
                </tr>
            </thead>
            <tbody>
            @forelse($recentUsers as $user)
            <tr>
                <td style="font-weight: 600;" class="text-primary">{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @if($user->is_admin)
                        <span class="badge badge-blue">Admin</span>
                    @else
                        <span class="badge" style="background: var(--bg-hover); color: var(--text-secondary);">User</span>
                    @endif
                </td>
                <td>{{ $user->created_at->format('d M Y') }}</td>
            </tr>
            @empty
                <tr><td colspan="4" style="text-align: center;" class="text-muted">Belum ada user</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
const labels = @json($clicksPerDay->pluck('date'));
const data   = @json($clicksPerDay->pluck('count'));

const style = getComputedStyle(document.body);
const accentColor = style.getPropertyValue('--accent').trim() || '#6366f1';
const textColor = style.getPropertyValue('--text-muted').trim() || '#94a3b8';
const gridColor = style.getPropertyValue('--border').trim() || '#e5e8ed';

new Chart(document.getElementById('clickChart'), {
    type: 'line',
    data: {
        labels: labels.length ? labels : ['Tidak ada data'],
        datasets: [{
            label: 'Klik',
            data: data.length ? data : [0],
            borderColor: accentColor,
            backgroundColor: `${accentColor}1A`, // 10% opacity
            borderWidth: 2,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: accentColor,
            pointRadius: 3,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { ticks: { color: textColor, font: { size: 10, family: 'Inter' } }, grid: { color: gridColor } },
            y: { ticks: { color: textColor, font: { size: 10, family: 'Inter' } }, grid: { color: gridColor }, beginAtZero: true }
        }
    }
});
</script>
@endpush

@extends('admin.layout')

@section('page-title', 'Kelola Link')
@section('page-subtitle', 'Semua link yang ada di sistem')

@section('content')
<!-- Search & Filter -->
<div style="display: flex; gap: 0.75rem; margin-bottom: 1.5rem; flex-wrap: wrap; align-items: center;">
    <form method="GET" action="{{ route('admin.links.index') }}" style="display: flex; gap: 0.75rem; flex: 1; min-width: 260px;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari URL, kode, alias..." class="form-control" style="max-width: 320px;">
        <select name="filter" class="form-control" style="max-width: 160px; appearance: auto;">
            <option value="">Semua Status</option>
            <option value="active" {{ request('filter') === 'active' ? 'selected' : '' }}>Aktif</option>
            <option value="inactive" {{ request('filter') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
            <option value="expired" {{ request('filter') === 'expired' ? 'selected' : '' }}>Kadaluarsa</option>
        </select>
        <button type="submit" class="btn btn-primary">Cari</button>
        @if(request('search') || request('filter'))
            <a href="{{ route('admin.links.index') }}" class="btn btn-secondary">Reset</a>
        @endif
    </form>
    <div class="text-muted" style="font-size: 0.875rem; font-weight: 500;">Total: {{ $links->total() }} link</div>
</div>

<div class="card" style="overflow: hidden;">
    <div style="overflow-x: auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Link Target</th>
                    <th>Kode Pendek</th>
                    <th>Total Klik</th>
                    <th>Status</th>
                    <th>Pemilik</th>
                    <th>Tgl Dibuat</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($links as $link)
            <tr>
                <td>
                    <div style="max-width: 240px;">
                        <div class="text-primary url-truncate" style="font-weight: 600; font-size: 0.875rem;" title="{{ $link->original_url }}">
                            {{ $link->title ?? parse_url($link->original_url, PHP_URL_HOST) }}
                        </div>
                        <div class="text-muted url-truncate" style="font-size: 0.75rem;">{{ Str::limit($link->original_url, 55) }}</div>
                    </div>
                </td>
                <td>
                    <div style="display: flex; align-items: center; gap: 0.375rem;">
                        <a href="{{ $link->short_url }}" target="_blank" class="text-accent" style="font-weight: 600; font-size: 0.875rem;">
                            /{{ $link->custom_alias ?? $link->short_code }}
                        </a>
                        @if($link->isPasswordProtected()) 
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="text-muted" title="Password Protected"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg> 
                        @endif
                    </div>
                </td>
                <td style="font-weight: 600;">{{ number_format($link->click_count) }}</td>
                <td>
                    @if(!$link->is_active)
                        <span class="badge badge-red">Nonaktif</span>
                    @elseif($link->isExpired())
                        <span class="badge badge-yellow">Kadaluarsa</span>
                    @else
                        <span class="badge badge-green">Aktif</span>
                    @endif
                </td>
                <td style="font-size: 0.875rem;">{{ $link->user?->name ?? 'Tamu' }}</td>
                <td style="font-size: 0.875rem;">{{ $link->created_at->format('d M Y') }}</td>
                <td>
                    <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                        <a href="{{ route('admin.links.show', $link) }}" class="btn btn-sm btn-secondary" title="Analytics">Detail</a>
                        <form action="{{ route('admin.links.toggle', $link) }}" method="POST" style="display:inline;">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-secondary">{{ $link->is_active ? 'Nonaktif' : 'Aktifkan' }}</button>
                        </form>
                        <form action="{{ route('admin.links.destroy', $link) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus link ini secara permanen?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
                <tr><td colspan="7" style="text-align: center; padding: 3rem;" class="text-muted">Tidak ada link ditemukan</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($links->hasPages())
<div style="margin-top: 1.5rem; display: flex; justify-content: center;">
    {{ $links->withQueryString()->links() }}
</div>
@endif
@endsection

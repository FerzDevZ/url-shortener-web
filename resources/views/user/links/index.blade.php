@extends('user.layout')

@section('page-title', 'Link Saya')
@section('page-subtitle', 'Manajemen URL pendek yang Anda buat')

@section('topbar-actions')
    <a href="{{ route('user.links.create') }}" class="btn btn-primary">Buat Link Baru</a>
@endsection

@section('content')
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
    <div class="stat-card">
        <div class="stat-label">Total Link</div>
        <div class="stat-value text-accent">{{ $links->total() }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Total Klik (Semua)</div>
        <div class="stat-value text-primary">{{ number_format(auth()->user()->links()->sum('click_count')) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Link Aktif</div>
        <div class="stat-value text-success">{{ auth()->user()->links()->where('is_active', true)->count() }}</div>
    </div>
</div>

<div class="card" style="overflow: hidden;">
    <div style="overflow-x: auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>URL Tujuan</th>
                    <th>Link Pendek</th>
                    <th>Total Klik</th>
                    <th>Status</th>
                    <th>Tgl Dibuat</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($links as $link)
            <tr>
                <td>
                    <div style="max-width: 240px;">
                        <div class="text-primary url-truncate" style="font-weight: 600; font-size: 0.875rem;">
                            {{ $link->title ?? parse_url($link->original_url, PHP_URL_HOST) }}
                        </div>
                        <div class="text-muted url-truncate" style="font-size: 0.75rem;">{{ Str::limit($link->original_url, 45) }}</div>
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
                <td style="font-size: 0.875rem;">{{ $link->created_at->format('d M Y') }}</td>
                <td>
                    <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                        <a href="{{ route('user.links.show', $link) }}" class="btn btn-sm btn-secondary">Detail</a>
                        <a href="{{ route('user.links.edit', $link) }}" class="btn btn-sm btn-secondary">Edit</a>
                        <form action="{{ route('user.links.destroy', $link) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus link ini secara permanen?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
                <tr><td colspan="6" style="text-align: center; padding: 3rem;" class="text-muted">
                    Belum ada link. <a href="{{ route('user.links.create') }}" class="text-accent" style="font-weight: 500;">Buat link pertama Anda &rarr;</a>
                </td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($links->hasPages())
<div style="margin-top: 1.5rem; display: flex; justify-content: center;">
    {{ $links->links() }}
</div>
@endif
@endsection

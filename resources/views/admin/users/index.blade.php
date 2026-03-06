@extends('admin.layout')

@section('page-title', 'Kelola User')
@section('page-subtitle', 'Daftar semua pengguna terdaftar')

@section('content')
<div style="display: flex; gap: 0.75rem; margin-bottom: 1.5rem; flex-wrap: wrap; align-items: center;">
    <form method="GET" action="{{ route('admin.users.index') }}" style="display: flex; gap: 0.75rem;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..." class="form-control" style="width: 320px;">
        <button type="submit" class="btn btn-primary">Cari</button>
        @if(request('search'))
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Reset</a>
        @endif
    </form>
    <div class="text-muted" style="font-size: 0.875rem; font-weight: 500;">Total: {{ $users->total() }} user</div>
</div>

<div class="card" style="overflow: hidden;">
    <div style="overflow-x: auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nama User</th>
                    <th>Alamat Email</th>
                    <th>Role</th>
                    <th>Link Dibuat</th>
                    <th>Tgl Bergabung</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($users as $user)
            <tr>
                <td style="font-weight: 600;" class="text-primary">{{ $user->name }}</td>
                <td class="text-secondary">{{ $user->email }}</td>
                <td>
                    @if($user->is_admin)
                        <span class="badge badge-blue">Admin</span>
                    @else
                        <span class="badge" style="background: var(--bg-hover); color: var(--text-secondary);">User</span>
                    @endif
                </td>
                <td style="font-weight: 600;" class="text-accent">{{ number_format($user->links_count) }}</td>
                <td style="font-size: 0.875rem;">{{ $user->created_at->format('d M Y') }}</td>
                <td>
                    <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                        <form action="{{ route('admin.users.toggle-admin', $user) }}" method="POST" style="display:inline;">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-secondary" title="{{ $user->is_admin ? 'Cabut Akses Admin' : 'Jadikan Admin' }}">
                                {{ $user->is_admin ? 'Cabut Admin' : 'Beri Admin' }}
                            </button>
                        </form>
                        @if($user->id !== auth()->id())
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus user {{ $user->name }}? PERHATIAN: Semua link yang dibuat user ini juga akan terhapus.')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
                <tr><td colspan="6" style="text-align: center; padding: 3rem;" class="text-muted">Tidak ada user ditemukan</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($users->hasPages())
<div style="margin-top: 1.5rem; display: flex; justify-content: center;">
    {{ $users->withQueryString()->links() }}
</div>
@endif
@endsection

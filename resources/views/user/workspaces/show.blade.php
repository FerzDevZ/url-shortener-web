@extends('user.layout')

@section('page-title', $workspace->name)
@section('page-subtitle', 'Manajemen Tim dan Tautan Khusus Lingkungan Kerja')

@section('topbar-actions')
    <a href="{{ route('user.workspaces.index') }}" class="btn btn-secondary">Kembali ke Daftar Tim</a>
@endsection

@section('content')
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
    <!-- Bagian Anggota Tim -->
    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h3 class="card-title">Anggota Tim ({{ $workspace->users->count() }})</h3>
            @if($myRole === 'admin')
            <button type="button" class="btn btn-primary btn-sm" onclick="document.getElementById('addMemberModal').style.display = 'block'">+ Undang Anggota</button>
            @endif
        </div>
        <div class="card-body" style="padding: 0;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nama Pengguna</th>
                        <th>Alamat Email</th>
                        <th>Peran (Role)</th>
                        <th>Bergabung Sejak</th>
                        @if($myRole === 'admin')
                        <th style="text-align: right;">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($workspace->users as $member)
                    <tr>
                        <td style="font-weight: 500;">
                            {{ $member->name }}
                            @if($member->id === auth()->id()) <span class="badge badge-accent" style="margin-left: 0.25rem;">Anda</span> @endif
                        </td>
                        <td class="text-secondary">{{ $member->email }}</td>
                        <td>
                            @if($myRole === 'admin' && $member->id !== auth()->id())
                            <form action="{{ route('user.workspaces.members.update', [$workspace, $member]) }}" method="POST" style="display: inline-flex; gap: 0.25rem; align-items: center;">
                                @csrf @method('PATCH')
                                <select name="role" onchange="this.form.submit()" class="form-control" style="font-size: 0.75rem; padding: 0.15rem 0.5rem; height: auto; width: auto; border-radius: 4px;">
                                    <option value="member" {{ $member->pivot->role === 'member' ? 'selected' : '' }}>Member</option>
                                    <option value="admin" {{ $member->pivot->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                            </form>
                            @else
                            <span class="badge {{ $member->pivot->role === 'admin' ? 'badge-blue' : 'badge-yellow' }}">
                                {{ ucfirst($member->pivot->role) }}
                            </span>
                            @endif
                        </td>
                        <td class="text-muted" style="font-size: 0.85rem;">{{ $member->pivot->created_at->format('d M Y') }}</td>
                        
                        @if($myRole === 'admin')
                        <td style="text-align: right;">
                            @if($member->id !== auth()->id())
                            <form action="{{ route('user.workspaces.members.remove', [$workspace, $member]) }}" method="POST" onsubmit="return confirm('Keluarkan anggota ini dari tim?');" style="display: inline-block;">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-danger" style="background: none; border: none; cursor: pointer; padding: 0.25rem; text-decoration: underline; font-size: 0.875rem;">Keluarkan</button>
                            </form>
                            @endif
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit Profil Tim & Hapus -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Informasi Workspace</h3></div>
            <div class="card-body">
                @if($myRole === 'admin')
                <form action="{{ route('user.workspaces.update', $workspace) }}" method="POST">
                    @csrf @method('PUT')
                    <div style="margin-bottom: 1rem;">
                        <label class="form-label" for="name">Nama Tim</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $workspace->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="description">Deskripsi</label>
                        <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $workspace->description) }}</textarea>
                    </div>
                    <div style="display: flex; justify-content: flex-end;">
                        <button type="submit" class="btn btn-primary btn-sm mt-3">Simpan Info Tim</button>
                    </div>
                </form>
                @else
                <div>
                    <div class="text-muted" style="font-size: 0.75rem; text-transform: uppercase; font-weight: 600; margin-bottom: 0.25rem;">Deskripsi Tim</div>
                    <p class="text-secondary">{{ $workspace->description ?? 'Tidak ada gambaran misi dari tim ini.' }}</p>
                </div>
                @endif
            </div>
        </div>

        @if($myRole === 'admin')
        <div class="card" style="border-color: #fee2e2;">
            <div class="card-body" style="background: #fef2f2; border-radius: inherit;">
                <h4 class="text-danger" style="margin-bottom: 0.5rem; font-weight: 600; font-size: 0.95rem;">Zona Berbahaya</h4>
                <p class="text-secondary" style="font-size: 0.85rem; margin-bottom: 1rem;">Membubarkan tim akan menghapus seluruh data afiliasi permanen. Tautan asli akan tetap ada di database tetapi kepemilikannya tercerabut.</p>
                <form action="{{ route('user.workspaces.destroy', $workspace) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin 100% ingin membubarkan Tim ini selamanya? Proses ini tidak dapat dibatalkan.');">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn bg-danger text-white btn-block" style="border: 1px solid #dc2626;">Hapus Tim Permanen</button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Semua Tautan Milik Tim ini -->
<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h3 class="card-title">Tautan Singkat Milik Tim ({{ $workspace->links->count() }})</h3>
        <a href="{{ route('user.links.create', ['workspace_id' => $workspace->id]) }}" class="btn btn-primary btn-sm">+ Buat Tautan Kolektif</a>
    </div>
    
    <div class="card-body" style="padding: 0;">
        @if($workspace->links->isEmpty())
        <div class="text-center" style="padding: 3rem 1rem;">
            <div class="text-muted" style="margin-bottom: 0.5rem;">Tim ini belum meluncurkan tautan apapun.</div>
            <a href="{{ route('user.links.create', ['workspace_id' => $workspace->id]) }}" class="text-primary font-weight-bold" style="text-decoration: none;">Klik di sini untuk memulai kampanye kolaboratif pertama tim.</a>
        </div>
        @else
        <table class="data-table">
            <thead>
                <tr>
                    <th>Tautan</th>
                    <th>Judul/Metriks</th>
                    <th>Dibuat Oleh</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($workspace->links as $link)
                <tr>
                    <td style="max-width: 200px;">
                        <a href="{{ $link->short_url }}" target="_blank" class="text-primary" style="text-decoration: none; font-weight: 500; word-break: break-all;">
                            {{ str_replace(['http://', 'https://'], '', $link->short_url) }}
                        </a>
                        <div class="text-secondary mt-1 url-truncate" style="font-size: 0.8rem;" title="{{ $link->original_url }}">{{ $link->original_url }}</div>
                    </td>
                    <td>
                        <div style="font-weight: 500; color: var(--text-primary);">{{ $link->title ?? 'Tanpa Judul' }}</div>
                        <div class="text-muted mt-1" style="font-size: 0.8rem;">
                            <span class="text-accent font-weight-bold">{{ number_format($link->click_count) }}</span> Klik Total 
                        </div>
                    </td>
                    <td>
                        <div class="text-secondary" style="font-size: 0.875rem;">
                            {{ $link->user->name ?? 'Unknown User' }}
                        </div>
                        <div class="text-muted" style="font-size: 0.75rem;">{{ $link->created_at->diffForHumans() }}</div>
                    </td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('user.links.show', $link) }}" class="btn btn-sm btn-secondary" style="padding: 0.25rem 0.5rem;">Statistik</a>
                            <a href="{{ route('user.links.edit', $link) }}" class="btn btn-sm" style="background: var(--bg-hover); color: var(--text-primary); border: 1px solid var(--border); padding: 0.25rem 0.5rem;">Edit</a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>

@if($myRole === 'admin')
<!-- Modal Tambah Anggota -->
<div id="addMemberModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 50; padding: 2rem;">
    <div class="card" style="max-width: 500px; margin: 2rem auto;">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h3 class="card-title">Undang Anggota Reguler</h3>
            <button onclick="document.getElementById('addMemberModal').style.display = 'none'" style="background: none; border: none; cursor: pointer; color: var(--text-muted);">
                <svg style="width: 24px; height: 24px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <div class="card-body" style="padding: 1.5rem;">
            <div class="alert alert-info text-sm mb-4" style="padding: 0.75rem;">
                <strong>Catatan:</strong> Undangan saat ini hanya mendukung pengguna yang sudah terdaftar di ekosistem platform kami.
            </div>
            <form action="{{ route('user.workspaces.members.add', $workspace) }}" method="POST">
                @csrf
                <div style="margin-bottom: 1rem;">
                    <label class="form-label" for="email">Alamat Email Pengguna <span class="text-danger">*</span></label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="akun@email.com" required>
                </div>
                <div style="margin-bottom: 2rem;">
                    <label class="form-label" for="role">Hak Akses</label>
                    <select name="role" id="role" class="form-control" required style="cursor: pointer;">
                        <option value="member" selected>Member (Hanya bisa baca info dan kelola Tautan)</option>
                        <option value="admin">Admin (Memiliki hak tambah anggota & rubah info Tim)</option>
                    </select>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                    <button type="button" onclick="document.getElementById('addMemberModal').style.display = 'none'" class="btn btn-secondary">Batal</button>
                    <button type="submit" class="btn btn-primary">Undang Sekarang</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

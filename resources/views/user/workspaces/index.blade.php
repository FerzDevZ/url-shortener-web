@extends('user.layout')

@section('page-title', 'Tim & Lingkungan Kerja')
@section('page-subtitle', 'Berkolaborasi dengan anggota lain di dalam proyek bersama.')

@section('topbar-actions')
    <button type="button" class="btn btn-primary" onclick="document.getElementById('createWorkspaceModal').style.display = 'block'">+ Buat Tim Baru</button>
@endsection

@section('content')
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
    @foreach($workspaces as $workspace)
    <div class="card" style="transition: transform 0.2s, box-shadow 0.2s; cursor: pointer;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='var(--shadow-lg)';" onmouseout="this.style.transform='none'; this.style.boxShadow='var(--shadow-md)';" onclick="window.location.href='{{ route('user.workspaces.show', $workspace) }}'">
        <div class="card-body" style="padding: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--text-primary); margin: 0;">{{ $workspace->name }}</h3>
                <span class="badge {{ $workspace->pivot->role === 'admin' ? 'badge-blue' : 'badge-yellow' }}">
                    {{ ucfirst($workspace->pivot->role) }}
                </span>
            </div>
            
            <p class="text-secondary" style="font-size: 0.875rem; margin-bottom: 1.5rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 40px; line-height: 1.4;">
                {{ $workspace->description ?? 'Tidak ada deskripsi tim.' }}
            </p>
            
            <div style="display: flex; justify-content: space-between; border-top: 1px solid var(--border); padding-top: 1rem;">
                <div style="text-align: center;">
                    <div style="font-size: 1.25rem; font-weight: 700; color: var(--accent);">{{ $workspace->users_count }}</div>
                    <div class="text-muted" style="font-size: 0.75rem;">Anggota</div>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 1.25rem; font-weight: 700; color: var(--text-primary);">{{ $workspace->links_count }}</div>
                    <div class="text-muted" style="font-size: 0.75rem;">Total Tautan</div>
                </div>
                <!-- Perataan Flex dummy dummy -->
                <div style="width: 2rem;"></div> 
            </div>
        </div>
    </div>
    @endforeach

    @if($workspaces->isEmpty())
    <div class="card" style="grid-column: 1 / -1; text-align: center; padding: 4rem 2rem;">
        <svg style="width: 64px; height: 64px; color: var(--text-muted); margin: 0 auto 1.5rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
        <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary); margin-bottom: 0.5rem;">Belum ada Tim</h3>
        <p class="text-secondary" style="max-width: 400px; margin: 0 auto 1.5rem;">Ciptakan Workspace untuk berkolaborasi menyingkat tautan bersama teman atau rekan kerja Anda.</p>
        <button type="button" class="btn btn-primary" onclick="document.getElementById('createWorkspaceModal').style.display = 'block'">Mulai Buat Tim</button>
    </div>
    @endif
</div>

<!-- Modal Buat Workspace -->
<div id="createWorkspaceModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 50; padding: 2rem;">
    <div class="card" style="max-width: 500px; margin: 2rem auto; position: relative;">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h3 class="card-title">Buat Workspace (Tim) Baru</h3>
            <button onclick="document.getElementById('createWorkspaceModal').style.display = 'none'" style="background: none; border: none; cursor: pointer; color: var(--text-muted);">
                <svg style="width: 24px; height: 24px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <div class="card-body" style="padding: 1.5rem;">
            <form action="{{ route('user.workspaces.store') }}" method="POST">
                @csrf
                <div style="margin-bottom: 1rem;">
                    <label class="form-label" for="name">Nama Tim <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Contoh: Tim Marketing Alpha" required>
                </div>
                <div style="margin-bottom: 1.5rem;">
                    <label class="form-label" for="description">Deskripsi (Opsional)</label>
                    <textarea name="description" id="description" class="form-control" rows="3" placeholder="Jelaskan apa tujuan dari tim ini..."></textarea>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                    <button type="button" onclick="document.getElementById('createWorkspaceModal').style.display = 'none'" class="btn btn-secondary">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

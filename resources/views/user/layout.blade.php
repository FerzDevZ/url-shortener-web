@extends('layouts.dark')

@section('sidebar')
<aside class="sidebar">
    <a href="{{ route('home') }}" class="sidebar-brand">
        <div class="sidebar-brand-icon">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="white">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
            </svg>
        </div>
        <span class="sidebar-brand-text">ShortLink</span>
    </a>

    <nav style="flex: 1; display: flex; flex-direction: column;">
        <!-- Workspace Switcher -->
        <div style="padding: 0 1rem 1rem 1rem; border-bottom: 1px solid var(--border); margin-bottom: 1rem;">
            <label class="nav-section-label" style="display: block; margin-bottom: 0.5rem;">Cakupan Dashboard</label>
            <form action="{{ route('user.workspaces.switch') }}" method="POST" id="workspaceSwitcherForm">
                @csrf
                <select name="workspace_id" onchange="document.getElementById('workspaceSwitcherForm').submit()" class="form-control" style="background: var(--bg-hover); border-color: var(--border); color: var(--text-primary); font-size: 0.8rem; height: auto; padding: 0.4rem;">
                    <option value="">Personal Workspace</option>
                    @foreach(auth()->user()->workspaces as $ws)
                        <option value="{{ $ws->id }}" {{ session('active_workspace_id') == $ws->id ? 'selected' : '' }}>
                            {{ $ws->name }}
                        </option>
                    @endforeach
                </select>
            </form>
            <div style="font-size: 0.7rem; color: var(--text-muted); margin-top: 0.35rem; line-height: 1.2;">
                @if(session('active_workspace_id'))
                    Menampilkan data Tim
                @else
                    Menampilkan data Pribadi
                @endif
            </div>
        </div>

        <div class="nav-section-label">Manajemen</div>
        <a href="{{ route('user.links.index') }}" class="nav-link {{ request()->routeIs('user.links.index') ? 'active' : '' }}">
            <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
            Link Saya
        </a>
        <a href="{{ route('user.links.create') }}" class="nav-link {{ request()->routeIs('user.links.create') ? 'active' : '' }}">
            <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Buat Link
        </a>
        <a href="{{ route('user.workspaces.index') }}" class="nav-link {{ request()->routeIs('user.workspaces.*') ? 'active' : '' }}">
            <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            Tim Kolaborasi
        </a>
        <a href="{{ route('user.bio.edit') }}" class="nav-link {{ request()->routeIs('user.bio.edit') ? 'active' : '' }}">
            <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
            Profil (Link in Bio)
        </a>
        <a href="{{ route('user.settings') }}" class="nav-link {{ request()->routeIs('user.settings') ? 'active' : '' }}">
            <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
            API & Pengaturan
        </a>

        <div class="nav-section-label">Aksi</div>
        <a href="{{ route('home') }}" class="nav-link" target="_blank">
            <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" /></svg>
            Halaman Utama
        </a>

        @if(auth()->user()->is_admin)
        <div class="divider"></div>
        <a href="{{ route('admin.dashboard') }}" class="nav-link text-accent" style="font-weight: 500;">
            <svg class="nav-icon text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
            Masuk Admin Panel
        </a>
        @endif
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
        <div class="sidebar-user-email">{{ auth()->user()->email }}</div>
        <form action="{{ route('logout') }}" method="POST" style="margin-top: 0.5rem;">
            @csrf
            <button type="submit" class="nav-link text-danger" style="width: 100%; border: none; background: transparent; cursor: pointer; padding: 0;">
                Keluar
            </button>
        </form>
    </div>
</aside>
@endsection

@section('topbar')
<header class="topbar">
    <div>
        <h2 style="font-size: 1.125rem; font-weight: 600; color: var(--text-primary); margin: 0;">@yield('page-title', 'Dashboard')</h2>
        <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.125rem;">@yield('page-subtitle', '')</div>
    </div>
    <div style="display: flex; align-items: center; gap: 0.75rem;">
        @yield('topbar-actions')
        <button id="theme-toggle" class="theme-toggle" aria-label="Toggle Theme"></button>
    </div>
</header>
@endsection

@extends('layouts.dark')

@section('sidebar')
<aside class="sidebar">
    <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
        <div class="sidebar-brand-icon">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="white">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
            </svg>
        </div>
        <span class="sidebar-brand-text">Admin Panel</span>
    </a>

    <nav style="flex: 1; display: flex; flex-direction: column;">
        <div class="nav-section-label">Menu Utama</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
            Dashboard
        </a>
        <a href="{{ route('admin.links.index') }}" class="nav-link {{ request()->routeIs('admin.links.*') ? 'active' : '' }}">
            <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>
            Kelola Link
        </a>
        <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
            Kelola User
        </a>

        <div class="nav-section-label">Aksi</div>
        <a href="{{ route('home') }}" class="nav-link" target="_blank">
            <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" /></svg>
            Lihat Website
        </a>
        <a href="{{ route('user.links.index') }}" class="nav-link">
            <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
            Panel User
        </a>
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
        <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.125rem;">@yield('page-subtitle', 'Overview Sistem')</div>
    </div>
    <div style="display: flex; align-items: center; gap: 0.75rem;">
        <span class="badge badge-blue">Admin Mode</span>
        <button id="theme-toggle" class="theme-toggle" aria-label="Toggle Theme"></button>
    </div>
</header>
@endsection

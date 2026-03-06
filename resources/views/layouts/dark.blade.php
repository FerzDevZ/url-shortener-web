<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'URL Shortener')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    
    <script>
        // Inline script untuk mencegah flash of wrong theme (FOUC)
        const theme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        if (theme === 'dark') document.documentElement.setAttribute('data-theme', 'dark');
    </script>
</head>
<body>
    <div class="app-shell">
        @hasSection('sidebar')
            @yield('sidebar')
        @endif

        <div class="main-area" style="margin-left: @hasSection('sidebar') 220px @else 0 @endif;">
            @hasSection('topbar')
                @yield('topbar')
            @endif

            <main class="page-content">
                @if(session('success'))
                    <div class="alert alert-success"><span>Bagian Sukses: </span>{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-error">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>

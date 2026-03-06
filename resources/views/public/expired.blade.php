<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Link Tidak Tersedia - ShortLink</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        const theme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        if (theme === 'dark') document.documentElement.setAttribute('data-theme', 'dark');
    </script>
    <style>
        body { display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 1.5rem; background: var(--bg-secondary); }
        .box { background: var(--bg-card); border: 1px solid var(--border); padding: 3rem 2rem; border-radius: var(--radius-lg); box-shadow: var(--shadow-md); text-align: center; max-width: 440px; width: 100%; }
        .icon { width: 64px; height: 64px; margin: 0 auto 1.5rem; color: var(--text-muted); }
    </style>
</head>
<body>
    <div class="box">
        <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h1 class="text-primary" style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.75rem;">Link Telah Kadaluarsa</h1>
        <p class="text-secondary" style="font-size: 0.95rem; margin-bottom: 2rem;">Tautan ini sudah tidak aktif karena telah melewati batas waktu yang ditentukan oleh pemiliknya.</p>
        
        @if($link->expires_at)
            <div style="background: var(--bg-hover); padding: 1rem; border-radius: var(--radius-sm); margin-bottom: 2rem; display: inline-block;">
                <div class="text-muted" style="font-size: 0.75rem; text-transform: uppercase; font-weight: 600; margin-bottom: 0.25rem;">Waktu Kadaluarsa</div>
                <div class="text-primary" style="font-weight: 500; font-size: 0.9rem;">{{ $link->expires_at->format('d M Y, H:i') }}</div>
            </div>
        @endif
        
        <br>
        <a href="{{ route('home') }}" class="btn btn-primary btn-lg" style="width: 100%; justify-content: center;">Kembali ke Beranda</a>
    </div>
</body>
</html>

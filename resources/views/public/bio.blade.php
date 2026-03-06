<!DOCTYPE html>
<html lang="id" data-theme="{{ $bio->theme_color === 'dark' ? 'dark' : 'light' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $bio->title ?? 'Link in Bio' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        body {
            /* Preset theme adjustments */
            @if($bio->theme_color === 'blue') --bg-secondary: #eff6ff; --bg-card: #ffffff; --text-primary: #1e3a8a; --accent: #3b82f6; --accent-hover: #2563eb; @endif
            @if($bio->theme_color === 'green') --bg-secondary: #f0fdf4; --bg-card: #ffffff; --text-primary: #14532d; --accent: #16a34a; --accent-hover: #15803d; @endif
            @if($bio->theme_color === 'purple')--bg-secondary: #faf5ff; --bg-card: #ffffff; --text-primary: #581c87; --accent: #9333ea; --accent-hover: #7e22ce; @endif

            display: flex; flex-direction: column; align-items: center; min-height: 100vh;
            padding: 3rem 1.5rem; background: var(--bg-secondary);
        }
        .bio-container {
            width: 100%; max-width: 480px; text-align: center;
        }
        .profile-pic {
            width: 110px; height: 110px; border-radius: 50%; object-fit: cover;
            margin: 0 auto 1.5rem; border: 3px solid var(--bg-card); box-shadow: var(--shadow-md);
        }
        .bio-title {
            font-size: 1.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem; letter-spacing: -0.01em;
        }
        .bio-desc {
            font-size: 1rem; color: var(--text-secondary); margin-bottom: 2.5rem; line-height: 1.6;
        }
        .bio-links {
            display: flex; flex-direction: column; gap: 1rem;
        }
        .bio-link-card {
            display: block; width: 100%; padding: 1.125rem;
            background: var(--bg-card); border: 1px solid var(--border);
            border-radius: var(--radius-lg); color: var(--text-primary);
            text-decoration: none; font-weight: 600; font-size: 1.05rem;
            transition: all 0.2s ease; box-shadow: var(--shadow-sm);
        }
        .bio-link-card:hover {
            border-color: var(--accent); transform: translateY(-2px); box-shadow: var(--shadow-md);
        }
        .footer-brand {
            margin-top: 4rem; font-size: 0.8rem; color: var(--text-muted); font-weight: 500;
        }
        .footer-brand a { color: var(--text-muted); text-decoration: none; font-weight: 600; }
        .footer-brand a:hover { color: var(--text-primary); }
    </style>
</head>
<body>
    <div class="bio-container">
        @if($bio->photo_path)
            <img src="{{ Storage::url($bio->photo_path) }}" alt="{{ $bio->title }}" class="profile-pic">
        @else
            <!-- Avatar placeholder -->
            <div style="width:110px; height:110px; border-radius:50%; background:var(--accent); color:#fff; display:flex; align-items:center; justify-content:center; font-size:2.5rem; font-weight:bold; margin: 0 auto 1.5rem; text-transform:uppercase;">
                {{ substr($bio->title ?? $bio->slug, 0, 1) }}
            </div>
        @endif

        @if($bio->title) <h1 class="bio-title">{{ $bio->title }}</h1> @endif
        @if($bio->bio) <p class="bio-desc">{{ $bio->bio }}</p> @endif

        <div class="bio-links">
            @forelse($links as $link)
                <a href="{{ $link->short_url }}" target="_blank" class="bio-link-card">
                    {{ $link->title ?? parse_url($link->original_url, PHP_URL_HOST) }}
                </a>
            @empty
                <div class="text-muted" style="padding: 2rem; border: 1px dashed var(--border); border-radius: var(--radius-lg); background: transparent;">Belum ada tautan aktif</div>
            @endforelse
        </div>

        <div class="footer-brand">
            Dibuat menggunakan <a href="{{ route('home') }}">ShortLink</a>
        </div>
    </div>
</body>
</html>

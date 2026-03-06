<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShortLink - Penyingkat URL Elegant</title>
    <meta name="description" content="Penyingkat URL gratis, aman, dan elegan dengan analitik lengkap dan QR Code.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        const theme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        if (theme === 'dark') document.documentElement.setAttribute('data-theme', 'dark');
    </script>
    <style>
        .hero-section {
            padding: 5rem 1.5rem;
            text-align: center;
            max-width: 800px;
            margin: 0 auto;
        }
        .hero-title {
            font-size: 3rem;
            font-weight: 800;
            color: var(--text-primary);
            line-height: 1.2;
            margin-bottom: 1rem;
            letter-spacing: -0.02em;
        }
        .hero-title span {
            color: var(--accent);
        }
        .hero-subtitle {
            font-size: 1.125rem;
            color: var(--text-secondary);
            margin-bottom: 2.5rem;
        }
        .shorten-box {
            background: var(--bg-card);
            border: 1px solid var(--border);
            padding: 1.5rem;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            text-align: left;
            margin-bottom: 2rem;
        }
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-top: 4rem;
            text-align: left;
        }
        .feature-card {
            padding: 1.5rem;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
        }
        .feature-icon {
            width: 40px; height: 40px; 
            background: var(--accent-subtle);
            color: var(--accent);
            border-radius: var(--radius-sm);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 1rem;
        }
        nav {
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border);
            background: var(--topbar-bg);
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .brand { font-size: 1.25rem; font-weight: 800; color: var(--text-primary); text-decoration: none; }
    </style>
</head>
<body>
    <nav>
        <a href="/" class="brand">ShortLink</a>
        <div style="display: flex; gap: 1rem; align-items: center;">
            <button id="theme-toggle" class="theme-toggle" aria-label="Toggle Theme"></button>
            @auth
                <a href="{{ auth()->user()->is_admin ? route('admin.dashboard') : route('user.links.index') }}" class="btn btn-secondary">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="text-secondary" style="font-size: 0.9rem; font-weight: 500;">Masuk</a>
                <a href="{{ route('register') }}" class="btn btn-primary">Daftar Gratis</a>
            @endauth
        </div>
    </nav>

    <div class="hero-section">
        <h1 class="hero-title">Pendekin Link Jadi <span>Lebih Mudah</span></h1>
        <p class="hero-subtitle">Bagikan URL panjang menjadi tautan pendek, lacak setiap klik, dan gunakan QR Code dengan platform yang elegan dan responsif.</p>

        <div class="shorten-box">
            @if(session('short_url'))
                <div style="background: var(--success-subtle); border: 1px solid rgba(34,197,94,0.3); padding: 1.5rem; border-radius: var(--radius-sm); margin-bottom: 1rem;">
                    <div style="font-size: 0.85rem; color: #15803d; font-weight: 600; margin-bottom: 0.5rem;">Link Berhasil Dibuat!</div>
                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
                        <a href="{{ session('short_url') }}" target="_blank" style="font-size: 1.25rem; font-weight: 700; color: var(--accent);">{{ session('short_url') }}</a>
                        <button onclick="navigator.clipboard.writeText('{{ session('short_url') }}'); this.textContent = 'Tersalin!'; setTimeout(() => this.textContent = 'Salin', 2000)" class="btn btn-primary">Salin</button>
                    </div>
                </div>
            @endif

            <form action="{{ route('shorten') }}" method="POST">
                @csrf
                <div style="display: flex; gap: 0.5rem; margin-bottom: 1rem; flex-wrap: wrap;">
                    <input type="url" name="original_url" placeholder="Paste link panjang di sini..." class="form-control" style="flex: 1; padding: 0.75rem 1rem; font-size: 1rem;" required>
                    <button type="submit" class="btn btn-primary btn-lg">Pendekin</button>
                </div>
                
                <!-- Honeypot untuk bot spammer -->
                <div style="display: none;" aria-hidden="true">
                    <label>Bila ini terlihat, jangan diisi</label>
                    <input type="text" name="website" tabindex="-1" autocomplete="off">
                </div>

                @guest
                    <div style="margin-bottom: 1rem;">
                        <label class="form-label">Verifikasi Keamanan: Berapa {{ session('captcha_question') }} ? *</label>
                        <input type="number" name="captcha" placeholder="Jawaban..." class="form-control" style="max-width: 150px;" required>
                    </div>
                @endguest
                
                <details style="cursor: pointer; margin-top: 0.5rem;">
                    <summary style="font-size: 0.85rem; color: var(--text-secondary); font-weight: 500; outline: none;">Pengaturan Lanjutan</summary>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 1rem;">
                        <div>
                            <label class="form-label">Custom Alias</label>
                            <input type="text" name="custom_alias" placeholder="contoh-nama" class="form-control" pattern="[a-zA-Z0-9_\-]+">
                        </div>
                        <div>
                            <label class="form-label">Password Link (Opsional)</label>
                            <input type="password" name="password" placeholder="Minta sandi" class="form-control">
                        </div>
                    </div>
                </details>
            </form>
            @if($errors->any())
                <div class="alert alert-error" style="margin-top: 1rem; margin-bottom: 0;">{{ $errors->first() }}</div>
            @endif
        </div>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon"><svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg></div>
                <h3 style="font-size: 1.05rem; font-weight: 600; margin-bottom: 0.5rem;">Alias Kustom</h3>
                <p style="font-size: 0.9rem; color: var(--text-secondary);">Buat URL Anda lebih mudah diingat dengan menambahkan teks kustom pada akhir link pilihan Anda.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg></div>
                <h3 style="font-size: 1.05rem; font-weight: 600; margin-bottom: 0.5rem;">Statistik Klik</h3>
                <p style="font-size: 0.9rem; color: var(--text-secondary);">Ketahui berapa banyak orang yang mengklik, dari perangkat apa, dan lokasi mereka secara real-time.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg></div>
                <h3 style="font-size: 1.05rem; font-weight: 600; margin-bottom: 0.5rem;">Amankan URL</h3>
                <p style="font-size: 0.9rem; color: var(--text-secondary);">Lindungi tautan sensitif Anda menggunakan kata sandi agar hanya dibuka oleh orang yang tepat.</p>
            </div>
        </div>
    </div>
</body>
</html>

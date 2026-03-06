<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tautan Terkunci - ShortLink</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        const theme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        if (theme === 'dark') document.documentElement.setAttribute('data-theme', 'dark');
    </script>
    @if($link->gtm_id)
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','{{ $link->gtm_id }}');</script>
    <!-- End Google Tag Manager -->
    @endif

    @if($link->fb_pixel_id)
    <!-- Facebook Pixel Code -->
    <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '{{ $link->fb_pixel_id }}');
    fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id={{ $link->fb_pixel_id }}&ev=PageView&noscript=1"
    /></noscript>
    <!-- End Facebook Pixel Code -->
    @endif

    <style>
        body { display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 1.5rem; background: var(--bg-secondary); }
        .box { background: var(--bg-card); border: 1px solid var(--border); padding: 2.5rem 2rem; border-radius: var(--radius-lg); box-shadow: var(--shadow-md); max-width: 400px; width: 100%; }
        .icon { width: 56px; height: 56px; margin: 0 auto 1.25rem; color: var(--accent); }
    </style>
</head>
<body>
    @if($link->gtm_id)
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $link->gtm_id }}"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    @endif

    <div class="box text-center" style="text-align: center;">
        <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
        </svg>
        <h1 class="text-primary" style="font-size: 1.25rem; font-weight: 700; margin-bottom: 0.5rem;">Tautan Terlindungi</h1>
        <p class="text-secondary" style="font-size: 0.9rem; margin-bottom: 2rem;">Masukkan kata sandi yang benar untuk melanjutkan ke halaman tujuan.</p>

        @if($errors->any())
            <div class="alert alert-error text-left" style="text-align: left; padding: 0.6rem 1rem;">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('link.password', $code) }}" method="POST" style="text-align: left;">
            @csrf
            <div style="margin-bottom: 1.5rem;">
                <label class="form-label" style="font-weight: 600;">Kata Sandi</label>
                <input type="password" name="password" class="form-control" style="padding: 0.75rem 1rem;" placeholder="Ketik sandi..." autofocus required>
            </div>
            <button type="submit" class="btn btn-primary btn-block btn-lg">Buka Tautan</button>
        </form>

        <div style="margin-top: 2rem;">
            <a href="{{ route('home') }}" class="text-muted" style="font-size: 0.85rem; font-weight: 500;">&larr; Kembali ke halaman awal</a>
        </div>
    </div>
</body>
</html>

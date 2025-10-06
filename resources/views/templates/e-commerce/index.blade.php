<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $content['seo_title'] ?? ($content['company_name'] ?? 'E-Commerce Preview') }}</title>
    <meta name="description" content="{{ $content['seo_description'] ?? ($content['hero_subtitle'] ?? '') }}">
    <style>
        :root {
            --primary: {{ $content['primary_color'] ?? '#2563eb' }};
            --secondary: {{ $content['secondary_color'] ?? '#0f172a' }};
            --accent: {{ $content['accent_color'] ?? '#f59e0b' }};
            --font-family: {{ $content['font_family'] ?? '"Inter", sans-serif' }};
            --radius: {{ $content['border_radius'] === 'rounded' ? '1rem' : ($content['border_radius'] === 'pill' ? '9999px' : '0.75rem') }};
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: var(--font-family);
            color: #0f172a;
            background: #f8fafc;
        }

        a { color: inherit; text-decoration: none; }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        header {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
        }

        header .inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 0;
            gap: 1rem;
        }

        header img {
            height: 50px;
            width: auto;
        }

        .hero {
            position: relative;
            min-height: 420px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: #fff;
            padding: 4rem 1.5rem;
        }

        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(15,23,42,0.75), rgba(30,64,175,0.75));
        }

        .hero-background {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            filter: brightness(0.8);
        }

        .hero .content {
            position: relative;
            max-width: 720px;
            z-index: 1;
        }

        .hero h1 {
            font-size: clamp(2.5rem, 5vw, 3.5rem);
            margin-bottom: 1rem;
        }

        .hero p {
            font-size: 1.125rem;
            margin-bottom: 2rem;
            color: rgba(255,255,255,0.88);
        }

        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.85rem 1.75rem;
            border-radius: var(--radius);
            font-weight: 600;
            border: none;
            cursor: pointer;
        }

        .button-primary {
            background: var(--primary);
            color: #fff;
            box-shadow: 0 10px 25px rgba(37,99,235,0.25);
        }

        section {
            padding: 4rem 0;
        }

        .section-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .section-header h2 {
            font-size: clamp(2rem, 4vw, 2.75rem);
            margin-bottom: 0.75rem;
            color: var(--secondary);
        }

        .section-header p {
            max-width: 640px;
            margin: 0 auto;
            color: #475569;
            font-size: 1.05rem;
        }

        .grid {
            display: grid;
            gap: 1.75rem;
        }

        .grid-3 {
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        }

        .card {
            background: #fff;
            border-radius: var(--radius);
            padding: 1.75rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 12px 24px rgba(15,23,42,0.07);
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 1.5rem;
        }

        .stat-card {
            padding: 1.5rem;
            border-radius: var(--radius);
            background: linear-gradient(135deg, rgba(37,99,235,0.12), rgba(2,132,199,0.12));
            border: 1px solid rgba(148,163,184,0.3);
            text-align: center;
        }

        .stat-card strong {
            display: block;
            font-size: 2.25rem;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .about {
            display: grid;
            gap: 2.5rem;
            align-items: center;
        }

        @media (min-width: 900px) {
            .about {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        .about img {
            width: 100%;
            border-radius: var(--radius);
            border: 1px solid rgba(148,163,184,0.25);
            box-shadow: 0 15px 30px rgba(15,23,42,0.12);
        }

        .gallery {
            display: grid;
            gap: 1rem;
        }

        @media (min-width: 768px) {
            .gallery {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        .gallery img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: calc(var(--radius) * 0.75);
            border: 1px solid rgba(148,163,184,0.2);
        }

        .testimonial {
            background: #fff;
            border-radius: var(--radius);
            border: 1px solid #e2e8f0;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(15,23,42,0.08);
        }

        .testimonial .author {
            margin-top: 1.5rem;
            font-weight: 600;
            color: var(--secondary);
        }

        .testimonial .role {
            color: #64748b;
            font-size: 0.95rem;
        }

        footer {
            background: var(--secondary);
            color: rgba(255,255,255,0.85);
            padding: 3rem 0;
        }

        .contact-card {
            background: #fff;
            padding: 2rem;
            border-radius: var(--radius);
            border: 1px solid #e2e8f0;
            box-shadow: 0 15px 35px rgba(15,23,42,0.12);
        }

        .social-links {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }

        .social-links a {
            padding: 0.65rem 1rem;
            border-radius: 999px;
            background: rgba(148,163,184,0.15);
            color: var(--secondary);
            font-weight: 500;
        }

        .whatsapp {
            position: fixed;
            right: 1.5rem;
            bottom: 1.5rem;
            padding: 0.9rem 1.4rem;
            border-radius: 999px;
            background: {{ $content['whatsapp_color'] ?? '#25D366' }};
            color: #fff;
            font-weight: 600;
            box-shadow: 0 15px 25px rgba(37,211,102,0.35);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .whatsapp svg {
            width: 20px;
            height: 20px;
        }
    </style>
</head>
<body>
    @php
        $resolveMedia = function ($path) use (&$resolveMedia) {
            if (!$path) {
                return null;
            }

            if (is_array($path)) {
                return array_map(function ($item) use (&$resolveMedia) { return $resolveMedia($item); }, $path);
            }

            if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, 'data:')) {
                return $path;
            }

            $normalized = ltrim($path, '/');
            if (str_starts_with($normalized, 'storage/')) {
                $normalized = substr($normalized, 8);
            }

            if (file_exists(public_path("storage/{$normalized}"))) {
                return asset("storage/{$normalized}");
            }

            if (file_exists(public_path($normalized))) {
                return asset($normalized);
            }

            return asset("storage/{$normalized}");
        };

        $heroBackground = $resolveMedia($content['hero_background'] ?? null);
        $logo = $resolveMedia($content['company_logo'] ?? null);
        $aboutImage = $resolveMedia($content['about_image'] ?? null);
        $gallery = array_filter(array_map($resolveMedia, $content['gallery_photos'] ?? []));
        $services = $content['services'] ?? [];
        $stats = $content['company_stats'] ?? [];
        $testimonials = $content['testimonials'] ?? [];
    @endphp

    <header>
        <div class="container inner">
            <div style="display:flex; align-items:center; gap:0.75rem;">
                @if($logo)
                    <img src="{{ $logo }}" alt="{{ $content['company_name'] ?? 'Brand Logo' }}">
                @endif
                <div>
                    <div style="font-weight:700; font-size:1.25rem; color:var(--secondary);">{{ $content['company_name'] ?? 'Nama Brand' }}</div>
                    @if(!empty($content['company_tagline']))
                        <div style="color:#64748b; font-size:0.95rem;">{{ $content['company_tagline'] }}</div>
                    @endif
                </div>
            </div>
            @if(!empty($content['hero_cta_link']) && !empty($content['hero_cta_text']))
                <a class="button button-primary" href="{{ $content['hero_cta_link'] }}">{{ $content['hero_cta_text'] }}</a>
            @endif
        </div>
    </header>

    <section class="hero">
        @if($heroBackground)
            <div class="hero-background" style="background-image:url('{{ $heroBackground }}');"></div>
        @endif
        <div class="content">
            <h1>{{ $content['hero_title'] ?? 'Tingkatkan Penjualan Online Anda' }}</h1>
            @if(!empty($content['hero_subtitle']))
                <p>{{ $content['hero_subtitle'] }}</p>
            @endif
            @if(!empty($content['hero_cta_link']) && !empty($content['hero_cta_text']))
                <a class="button button-primary" href="{{ $content['hero_cta_link'] }}">{{ $content['hero_cta_text'] }}</a>
            @endif
        </div>
    </section>

    @if(!empty($stats))
        <section>
            <div class="container">
                <div class="stats">
                    @foreach($stats as $stat)
                        <div class="stat-card">
                            <strong>{{ $stat['number'] ?? '100%' }}</strong>
                            <div style="font-size:0.95rem; color:#475569;">{{ $stat['label'] ?? 'Kepuasan Pelanggan' }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if(!empty($services))
        <section>
            <div class="container">
                <div class="section-header">
                    <h2>{{ $content['services_title'] ?? 'Produk Unggulan' }}</h2>
                    @if(!empty($content['services_subtitle']))
                        <p>{{ $content['services_subtitle'] }}</p>
                    @endif
                </div>
                <div class="grid grid-3">
                    @foreach($services as $service)
                        <div class="card">
                            <div style="font-size:0.95rem; color:var(--accent); text-transform:uppercase; letter-spacing:0.08em; font-weight:600;">
                                {{ $service['icon'] ?? 'Kategori' }}
                            </div>
                            <h3 style="font-size:1.35rem; margin:0.75rem 0; color:var(--secondary);">
                                {{ $service['title'] ?? 'Nama Produk' }}
                            </h3>
                            <p style="color:#475569; line-height:1.6;">{{ $service['description'] ?? 'Deskripsi produk atau layanan ecommerce Anda.' }}</p>
                            @if(!empty($service['link']))
                                <div style="margin-top:1rem;">
                                    <a href="{{ $service['link'] }}" style="color:var(--primary); font-weight:600;">Lihat Detail ?</a>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section>
        <div class="container about">
            @if($aboutImage)
                <div>
                    <img src="{{ $aboutImage }}" alt="Tentang Kami">
                </div>
            @endif
            <div>
                <div class="section-header" style="text-align:left; margin-bottom:1.5rem; padding:0;">
                    <h2>{{ $content['about_title'] ?? 'Tentang Brand Kami' }}</h2>
                    @if(!empty($content['about_content']))
                        <p style="margin:0;">{{ $content['about_content'] }}</p>
                    @endif
                </div>
                <div style="display:flex; flex-wrap:wrap; gap:0.75rem;">
                    @foreach($services as $service)
                        <span style="padding:0.5rem 1rem; border-radius:999px; background:rgba(37,99,235,0.08); color:var(--primary); font-weight:500;">
                            {{ $service['title'] ?? 'Kategori' }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    @if(!empty($gallery))
        <section>
            <div class="container">
                <div class="section-header">
                    <h2>{{ $content['gallery_title'] ?? 'Galeri Produk' }}</h2>
                    <p>Jelajahi koleksi produk unggulan kami.</p>
                </div>
                <div class="gallery">
                    @foreach($gallery as $photo)
                        <div>
                            <img src="{{ $photo }}" alt="Galeri Produk">
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if(!empty($testimonials))
        <section style="background:#f1f5f9;">
            <div class="container">
                <div class="section-header">
                    <h2>{{ $content['testimonials_title'] ?? 'Apa Kata Pelanggan' }}</h2>
                    <p>Testimoni dari pelanggan yang telah merasakan kualitas produk kami.</p>
                </div>
                <div class="grid grid-3">
                    @foreach($testimonials as $testimonial)
                        <div class="testimonial">
                            <div style="font-size:1.05rem; line-height:1.7; color:#1e293b;">
                                “{{ $testimonial['content'] ?? 'Pelayanan ramah dan produk berkualitas, sangat recommended!' }}”
                            </div>
                            <div class="author">{{ $testimonial['name'] ?? 'Nama Pelanggan' }}</div>
                            @if(!empty($testimonial['position']))
                                <div class="role">{{ $testimonial['position'] }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section>
        <div class="container" style="display:grid; gap:2rem;">
            <div class="contact-card">
                <h2 style="margin-top:0; color:var(--secondary);">{{ $content['contact_title'] ?? 'Hubungi Kami' }}</h2>
                <p style="color:#475569; margin-bottom:1.5rem;">Kami siap membantu kebutuhan toko online Anda kapan pun dibutuhkan.</p>
                <div style="display:grid; gap:0.75rem; color:#1e293b;">
                    @if(!empty($content['contact_email']))
                        <div>Email: <strong>{{ $content['contact_email'] }}</strong></div>
                    @endif
                    @if(!empty($content['contact_phone']))
                        <div>Telepon: <strong>{{ $content['contact_phone'] }}</strong></div>
                    @endif
                    @if(!empty($content['contact_address']))
                        <div>Alamat: <strong>{{ $content['contact_address'] }}</strong></div>
                    @endif
                </div>
                @if(!empty($content['social_links']))
                    <div class="social-links">
                        @foreach($content['social_links'] as $link)
                            <a href="{{ $link['url'] ?? '#' }}" target="_blank" rel="noopener">
                                {{ $link['label'] ?? ucfirst($link['platform'] ?? 'Social') }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>

    <footer>
        <div class="container" style="text-align:center;">
            <div style="font-size:1.1rem; font-weight:600; margin-bottom:0.25rem;">{{ $content['company_name'] ?? 'Nama Brand' }}</div>
            <div>{{ $content['company_tagline'] ?? 'Solusi e-commerce andalan untuk bisnis Anda.' }}</div>
        </div>
    </footer>

    @if(!empty($content['whatsapp_enabled']) && !empty($content['whatsapp_number']))
        <a class="whatsapp" href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $content['whatsapp_number']) }}" target="_blank" rel="noopener">
            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12.04 2.004c-5.51 0-9.98 4.468-9.98 9.975 0 1.76.46 3.47 1.33 4.99L2 22l5.15-1.34c1.45.79 3.08 1.21 4.74 1.21h.01c5.5 0 9.98-4.47 9.98-9.975.01-2.67-1.03-5.18-2.91-7.06a9.94 9.94 0 00-7.93-2.84zm5.84 14.257c-.25.7-1.47 1.34-2.04 1.42-.52.07-1.18.1-1.91-.12-.44-.14-1-.33-1.73-.64-3.05-1.32-5.04-4.4-5.19-4.61-.15-.21-1.24-1.66-1.24-3.17 0-1.5.79-2.24 1.07-2.54.25-.27.66-.39 1.07-.39h.23c.35.01.52.02.75.58.29.7.99 2.4 1.07 2.57.09.18.15.38.03.59-.11.21-.17.33-.31.52-.14.18-.29.41-.41.55-.14.14-.28.3-.12.58.17.28.76 1.25 1.63 2.02 1.12.99 2.07 1.3 2.38 1.45.31.16.49.13.67-.08.18-.21.77-.9.98-1.21.21-.31.4-.26.67-.16.26.09 1.66.78 1.94.92.29.14.48.21.55.33.07.13.07.75-.18 1.45z"/></svg>
            {{ $content['whatsapp_greeting_text'] ?? 'Chat via WhatsApp' }}
        </a>
    @endif
</body>
</html>

